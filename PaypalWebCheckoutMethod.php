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

namespace PaymentSuite\PaypalWebCheckoutBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * PaypalWebCheckoutMethod class
 */
class PaypalWebCheckoutMethod implements PaymentMethodInterface
{
    /**
     * @var float
     *
     * PaypalExpressCheckout amount
     */
    private $amount;

    /**
     * @var string
     *
     * PaypalExpressCheckout orderNumber
     */
    private $orderNumber;

    /**
     * @var SomeExtraData
     *
     * Some extra data given by payment response
     */
    private $someExtraData;
    
    /**
     * Get PaypalWebCheckout method name
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'paypal_web_checkout';
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return PaypalWebCheckoutMethod self Object
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     *
     * @return PaypalWebCheckoutMethod self Object
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Set some extra data
     *
     * @param string $someExtraData Some extra data
     *
     * @return PaypalWebCheckoutMethod self Object
     */
    public function setSomeExtraData($someExtraData)
    {
        $this->someExtraData = $someExtraData;

        return $this;
    }

    /**
     * Get some extra data
     *
     * @return array Some extra data
     */
    public function getSomeExtraData()
    {
        return $someExtraData;
    }
}
