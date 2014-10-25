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

namespace PaymentSuite\PaypalWebCheckoutBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class PaypalWebCheckoutMethod
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 * @author Mickaël Andrieu <andrieu.travail@gmail.com>
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
     * PaypalExpressCheckout orderId
     */
    private $orderId;

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
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return PaypalWebCheckoutMethod self Object
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

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
