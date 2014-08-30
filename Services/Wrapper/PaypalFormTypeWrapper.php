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

namespace PaymentSuite\PaypalWebCheckoutBundle\Services\Wrapper;

use Symfony\Component\Form\FormFactory;

use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use PaymentSuite\PaypalWebCheckoutBundle\Exception\CurrencyNotSupportedException;

class PaypalFormTypeWrapper
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;

    /**
     * @var PaymentBridge
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var string $business
     *
     * Merchant identifier
     */
    private $business;

    /**
     * @var string $paypalUrl
     *
     * Paypal web url
     */
    private $paypalUrl;

    /**
     * @var booelan $debug
     *
     * Debug integration
     */
    private $debug;

    /**
     * @var string $returnUrl
     *
     * Route for success payment
     */
    private $returnUrl;

    /**
     * @var string $cancelReturnUrl
     *
     * Route for fail payment
     */
    private $cancelReturnUrl;

    /**
     * @var string $notifyUrl
     *
     * Route for process payment
     */
    private $notifyUrl;

    /**
     * Formtype construct method
     *
     * @param FormFactory            $formFactory             Form factory
     * @param PaymentBridgeInterface $paymentBridge           Payment bridge
     * @param string                 $bussines                merchant code
     * @param string                 $paypalUrl               gateway url
     * @param string                 $returnUrl               merchant url ok
     * @param string                 $cancelReturnUrl         merchant url ko
     * @param string                 $notifyUrl               merchant payment proccess url
     */
    public function __construct(
        FormFactory $formFactory,
        PaymentBridgeInterface $paymentBridge,
        $business,
        $paypalUrl,
        $returnUrl,
        $cancelReturnUrl,
        $notifyUrl
    ) {
        $this->formFactory     = $formFactory;
        $this->paymentBridge   = $paymentBridge;
        $this->business        = $business;
        $this->paypalUrl       = $paypalUrl;
        $this->returnUrl       = $returnUrl;
        $this->cancelReturnUrl = $cancelReturnUrl;
        $this->notifyUrl       = $notifyUrl;
    }

    /**
     * Builds form given return, success and fail urls
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function buildForm()
    {
        $extraData = $this->paymentBridge->getExtraData();
        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null);

        $amount     = $this->paymentBridge->getAmount()->getAmount()/100;
        $itemNumber = $this->paymentBridge->getOrderNumber();
        $currency   = $this->checkCurrency($this->paymentBridge->getCurrency());

        $formBuilder
            ->setAction($this->paypalUrl)
            ->setMethod('POST')

            ->add('amount', 'hidden', array(
                'data' => $amount,
            ))
            ->add('business', 'hidden', array(
                'data' => $this->business,
            ))
            ->add('return', 'hidden', array(
                'data' => $this->returnUrl,
            ))
            ->add('cancel_return', 'hidden', array(
                'data' => $this->cancelReturnUrl,
            ))
            ->add('notify_url', 'hidden', array(
                'data' => $this->notifyUrl,
            ))
            ->add('item_number', 'hidden', array(
                'data' => $itemNumber,
            ))
            ->add('currency_code', 'hidden', array(
                'data' => $currency,
            ))
        ;

        return $formBuilder->getForm()->createView();
    }

    public function checkCurrency($currency)
    {
        $allowedCurrencies = [
            'AUD', 'BRL', 'CAD', 'CZK', 'DKK',
            'EUR', 'HKD', 'HUF', 'ILS', 'JPY',
            'MYR', 'MXN', 'NOK', 'NZD', 'PHP',
            'PLN', 'GBP', 'RUB', 'SGD', 'SEK',
            'CHF', 'TWD', 'THB', 'TRY', 'USD'
        ];

        if (!in_array($currency, $allowedCurrencies)) {
            throw new CurrencyNotSupportedException();
        }

        return $currency;
    }
}
