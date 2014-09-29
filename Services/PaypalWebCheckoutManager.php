<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaypalWebCheckoutBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaypalWebCheckoutBundle\Exception\ParameterNotReceivedException;
use PaymentSuite\PaypalWebCheckoutBundle\PaypalWebCheckoutMethod;
use PaymentSuite\PaypalWebCheckoutBundle\Services\Wrapper\PaypalFormTypeWrapper;

/**
 * Class PaypalWebCheckoutManager
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 * @author Mickaël Andrieu <andrieu.travail@gmail.com>
 */
class PaypalWebCheckoutManager
{
    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;

    /**
     * @var Array config
     *
     * Paypal Web Checkout configuration
     */
    protected $config;

    /**
     * @var UrlFactory
     *
     * URL factory
     */
    protected $urlFactory;

    /**
     * Construct method for paypal manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param PaypalFormTypeWrapper  $paypalFormTypeWrapper  Paypal Wrapper
     * @param UrlFactory             $urlFactory
     */
    public function __construct(
        PaymentEventDispatcher $paymentEventDispatcher,
        PaymentBridgeInterface $paymentBridge,
        PaypalFormTypeWrapper $paypalFormTypeWrapper,
        UrlFactory $urlFactory
    ) {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->paypalFormTypeWrapper = $paypalFormTypeWrapper;
        $this->urlFactory = $urlFactory;
    }

    /**
     * Dispatches order load event and prepares paypal form for submission
     *
     * This is a synchronous action that takes place on the implementor
     * side, i.e. right after click the "pay with checkout" button it the
     * final stage of a checkout process.
     *
     * See documentation for PaypalWebCheckout Api Integration at
     * https://developer.paypal.com/docs/integration/web/web-checkout/
     *
     * @throws PaymentOrderNotFoundException
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function generatePaypalForm()
    {
        $paypalMethod = new PaypalWebCheckoutMethod([]);

        /**
         * We expect listeners for the payment.order.load event
         * to store the Order into the bridge
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $paypalMethod
            );

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException;
        }

        /**
         * We expect the Order to be created and physically flushed
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $paypalMethod
            );

        $formView = $this
            ->paypalFormTypeWrapper
            ->buildForm();

        return $formView;
    }

    /**
     * Process Paypal IPN response to payment
     *
     * When the IPN mesage is validated, a payment success event
     * should be dispatched.
     *
     * @param integer $orderId    Order Id
     * @param array   $parameters parameter array coming from Paypal IPN notification
     *
     * @throws ParameterNotReceivedException
     * @throws PaymentException
     */
    public function processPaypalIPNMessage($orderId, array $parameters)
    {
        /**
         * Retrieving the order object.
         */
        $order = $this->paymentBridge->findOrder($orderId);

        if (!$order) {
            throw new PaymentOrderNotFoundException(sprintf(
                'Order #%s not found', $orderId)
            );
        }
        $this->paymentBridge->setOrder($order);

        /*
         * Check that we receive the mandatory parameters
         */
        $this->checkResultParameters($parameters);

        /*
         * Initializing PaypalWebCheckoutMethod, which is
         * an object representation of the payment information
         * coming from the payment processor
         */
        $paypalMethod = new PaypalWebCheckoutMethod(
            $parameters['mc_gross'],
            $parameters['payment_status'],
            $parameters['notify_version'],
            $parameters['payer_status'],
            $parameters['business'],
            $parameters['quantity'],
            $parameters['verify_sign'],
            $parameters['payer_email'],
            $parameters['txn_id'],
            $parameters['payment_type'],
            $parameters['receiver_email'],
            null,
            $parameters['txn_type'],
            $parameters['item_name'],
            $parameters['mc_currency'],
            $parameters['item_number'],
            $parameters['test_ipn'],
            $parameters['payment_gross'],
            $parameters['ipn_track_id']
        );

        /*
         * Notifying payment.done, which means that the
         * payment has been received, although we still
         * do not know if it is succesful or not.
         * Listening fot this event is useful when one
         * wants to record transaction informations
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $paypalMethod
            );

        /*
         * Check if the transaction is successful
         */
        if (!$this->transactionSuccessful($parameters)) {

            $this
                ->paymentEventDispatcher
                ->notifyPaymentOrderFail(
                    $this->paymentBridge,
                    $paypalMethod
                );

            throw new PaymentException();
        }

        /**
         * Payment paid successfully
         *
         * Paid process has ended successfully
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderSuccess(
                $this->paymentBridge,
                $paypalMethod
            );
    }

    /**
     * Checks that all the required parameters are received
     *
     * @param array $parameters Parameters
     *
     * @throws ParameterNotReceivedException
     */
    protected function checkResultParameters(array $parameters)
    {
        $requiredParameters = array(
            'item_number',
            'payment_status'
        );

        foreach ($requiredParameters as $requiredParameter) {

            if (!isset($parameters[$requiredParameter])) {

               throw new ParameterNotReceivedException($requiredParameter);
            }
        }
    }

    /**
     * Check if transaction is complete
     *
     * When we receive an IPN response, we should
     * check that the price paid corresponds to the
     * amount stored in the PaymentMethod. This double
     * check is essential since the web checkout form
     * could be mangled.
     *
     * See https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNIntro/
     *
     * @param array $ipnParameters Paypal IPN parameters
     *
     * @return boolean
     */
    protected function transactionSuccessful($ipnParameters)
    {
        /*
         * First of all we have to check the validity of the IPN
         * message. We need to send back the contents of the query
         * string coming from Paypal's IPN message.
         */
        $ipnNotifyValidateUrl = $this->urlFactory->getPaypalBaseUrl()
            . '?'
            . http_build_query(
                array_merge(
                    $this->urlFactory->getPaypalNotifyValidateQueryParam(),
                    $ipnParameters)
            );
        $ipnValidated = (file_get_contents($ipnNotifyValidateUrl) == 'VERIFIED');

        /*
         * Matching paid amount with the originating order amount,
         * this is a security check to prevent frauds by manually
         * changing the papal form
         */
        $amountMatches = $this->paymentBridge->getAmount() / 100 == $ipnParameters['mc_gross'];
        $amountMatches = $amountMatches && $this->paymentBridge->getCurrency() == ($ipnParameters['mc_currency']);

        /**
         * When a transaction is successful, payment_status has a 'Completed' value
         */

        return ($amountMatches && $ipnValidated && (strcmp($ipnParameters['payment_status'], 'Completed') === 0));
    }
}
