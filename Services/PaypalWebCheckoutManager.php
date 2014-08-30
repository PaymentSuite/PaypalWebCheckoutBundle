<?php

/**
 * PaypalWebCheckout for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 *
 * Arkaitz Garro 2014
 */

namespace PaymentSuite\PaypalWebCheckoutBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\PaypalWebCheckoutBundle\PaypalWebCheckoutMethod;
use PaymentSuite\PaypalWebCheckoutBundle\Form\Type\PaypalFormTypeWrapper;
use PaymentSuite\PaypalWebCheckoutBundle\Services\Wrapper\PaypalWebCheckoutTransactionWrapper;

/**
 * Paypal Web Checkout manager
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
     * @var PaypalWebCheckoutTransactionWrapper $paypalWrapper
     *
     * Paypal Web Checkout wrapper
     */
    protected $paypalWrapper;

    /**
     * @var Array config
     *
     * Paypal Web Checkout configuration
     */
    protected $config;

    /**
     * Construct method for paypal manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param PaypalWebCheckoutTransactionWrapper Paypal Wrapper
     */
    public function __construct(
        PaymentEventDispatcher $paymentEventDispatcher,
        PaymentBridgeInterface $paymentBridge,
        PaypalWebCheckoutTransactionWrapper $paypalWrapper
    ) {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->paypalWrapper = $paypalWrapper;
    }

    /**
     * See also PaypalWebCheckout Api Integration : https://developer.paypal.com/docs/integration/web/web-checkout/
     */
    public function processPayment()
    {
        $paypalMethod = new PaypalWebCheckoutMethod();
        
        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
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
         * Order exists right here
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
     *
     */
    public function getPaymentStatus()
    {

    }
}
