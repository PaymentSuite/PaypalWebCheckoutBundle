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

    public function getResponse()
    {
        return $this->response;
    }
}
