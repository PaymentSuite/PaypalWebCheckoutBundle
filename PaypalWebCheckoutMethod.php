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
     * @var string
     */
    protected $paymentStatus;

    /**
     * @var string
     */
    protected $notifyVersion;

    /**
     * @var string
     */
    protected $payerStatus;

    /**
     * @var string
     */
    protected $business;

    /**
     * @var integer
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $paymentType;

    /**
     * @var string
     */
    protected $receiverEmail;

    /**
     * @var string
     */
    protected $pendingReason;

    /**
     * @var string
     */
    protected $txnType;

    /**
     * @var string
     */
    protected $itemName;

    /**
     * @var string
     */
    protected $mcCurrency;

    /**
     * @var integer
     */
    protected $testIpn;

    /**
     * @var float
     */
    protected $paymentGross;

    /**
     * @var float
     *
     * PaypalExpressCheckout amount
     */
    private $mcGross;

    /**
     * @var string
     *
     * PaypalExpressCheckout orderId
     */
    private $itemNumber;

    /**
     * Paypal verify string check
     *
     * @var string
     */
    private $verifySign;

    /**
     * Paypal payer Email address
     *
     * @var string
     */
    private $payerEmail;

    /**
     * Paypal transaction id
     *
     * @var string
     */
    private $txnId;

    /**
     * Paypal currency code
     *
     * @var string
     */
    private $currency;

    /**
     * Paypal IPN track id
     *
     * @var string
     */
    private $ipnTrackId;

    /**
     * Initialize Paypal Method using an array which represents
     * the parameters coming from the IPN message as shown in
     *
     * https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/#id091EAB0105Z
     * @param float  $mcGross
     * @param string $paymentStatus
     * @param string $notifyVersion
     * @param string $payerStatus
     * @param string $business
     * @param string $quantity
     * @param string $verifySign
     * @param string $payerEmail
     * @param string $txnId
     * @param string $paymentType
     * @param string $receiverEmail
     * @param strrig $pendingReason
     * @param string $txnType
     * @param string $itemName
     * @param string $mcCurrency
     * @param string $itemNumber
     * @param string $testIpn
     * @param float  $paymentGross
     * @param string $ipnTrackId
     */
    public function __construct(
        $mcGross = null, $paymentStatus = null, $notifyVersion = null,
        $payerStatus = null, $business = null, $quantity = null, $verifySign = null,
        $payerEmail = null, $txnId = null, $paymentType = null, $receiverEmail = null,
        $pendingReason = null, $txnType = null, $itemName = null,
        $mcCurrency = null, $itemNumber = null, $testIpn = null,
        $paymentGross = null, $ipnTrackId = null
    )
    {
        $this->mcGross = $mcGross;
        $this->paymentStatus = $paymentStatus;
        $this->notifyVersion = $notifyVersion;
        $this->payerStatus = $payerStatus;
        $this->business = $business;
        $this->quantity = $quantity;
        $this->verifySign = $verifySign;
        $this->payerEmail = $payerEmail;
        $this->txnId = $txnId;
        $this->paymentType = $paymentType;
        $this->receiverEmail = $receiverEmail;
        $this->pendingReason = $pendingReason;
        $this->txnType = $txnType;
        $this->itemName = $itemName;
        $this->mcCurrency = $mcCurrency;
        $this->itemNumber = $itemNumber;
        $this->testIpn = $testIpn;
        $this->paymentGross = $paymentGross;
        $this->ipnTrackId = $ipnTrackId;
    }

    /**
     * Returns Paypal currency code
     *
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets Paypal currencyt code
     *
     * @param mixed $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Returns Paypal IPN Track ID
     *
     * @return mixed
     */
    public function getIpnTrackId()
    {
        return $this->ipnTrackId;
    }

    /**
     * Sets Paypal IPN track ID
     *
     * @param mixed $ipnTrackId
     *
     * @return $this
     */
    public function setIpnTrackId($ipnTrackId)
    {
        $this->ipnTrackId = $ipnTrackId;

        return $this;
    }

    /**
     * Returns Paypal payer Email address
     *
     * @return mixed
     */
    public function getPayerEmail()
    {
        return $this->payerEmail;
    }

    /**
     * Sets Paypal payer Email address
     *
     * @param mixed $payerEmail
     *
     * @return $this
     */
    public function setPayerEmail($payerEmail)
    {
        $this->payerEmail = $payerEmail;

        return $this;
    }

    /**
     * Returns Paypal transaction id
     *
     * @return string
     */
    public function getTxnId()
    {
        return $this->txnId;
    }

    /**
     * Sets Paypal transaction id
     *
     * @param string $transactionId
     *
     * @return $this
     */
    public function setTxnId($transactionId)
    {
        $this->txnId = $transactionId;

        return $this;
    }

    /**
     * Returns Paypal verify check code
     *
     * @return string
     */
    public function getVerifySign()
    {
        return $this->verifySign;
    }

    /**
     * Sets Paypal verify check code
     *
     * @param mixed $verifySign
     *
     * @return $this
     */
    public function setVerifySign($verifySign)
    {
        $this->verifySign = $verifySign;

        return $this;
    }

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
     * Returns Paypal total amount
     *
     * @return float
     */
    public function getMcGross()
    {
        return $this->mcGross;
    }

    /**
     * Sets Paypal total amount
     *
     * @param float $amount
     *
     * @return $this self Object
     */
    public function setMcGross($amount)
    {
        $this->mcGross = $amount;

        return $this;
    }

    /**
     * Returns order id
     *
     * @return string
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
    }

    /**
     * Sets order id
     *
     * @param string $orderId
     *
     * @return $this self Object
     */
    public function setItemNumber($orderId)
    {
        $this->itemNumber = $orderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @param string $business
     */
    public function setBusiness($business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @param string $itemName
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMcCurrency()
    {
        return $this->mcCurrency;
    }

    /**
     * @param string $mcCurrency
     */
    public function setMcCurrency($mcCurrency)
    {
        $this->mcCurrency = $mcCurrency;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotifyVersion()
    {
        return $this->notifyVersion;
    }

    /**
     * @param string $notifyVersion
     */
    public function setNotifyVersion($notifyVersion)
    {
        $this->notifyVersion = $notifyVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getPayerStatus()
    {
        return $this->payerStatus;
    }

    /**
     * @param string $payerStatus
     */
    public function setPayerStatus($payerStatus)
    {
        $this->payerStatus = $payerStatus;

        return $this;
    }

    /**
     * @return float
     */
    public function getPaymentGross()
    {
        return $this->paymentGross;
    }

    /**
     * @param float $paymentGross
     */
    public function setPaymentGross($paymentGross)
    {
        $this->paymentGross = $paymentGross;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param string $paymentStatus
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * @return string
     */
    public function getPendingReason()
    {
        return $this->pendingReason;
    }

    /**
     * @param string $pendingReason
     */
    public function setPendingReason($pendingReason)
    {
        $this->pendingReason = $pendingReason;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getReceiverEmail()
    {
        return $this->receiverEmail;
    }

    /**
     * @param string $receiverEmail
     */
    public function setReceiverEmail($receiverEmail)
    {
        $this->receiverEmail = $receiverEmail;

        return $this;
    }

    /**
     * @return int
     */
    public function getTestIpn()
    {
        return $this->testIpn;
    }

    /**
     * @param int $testIpn
     */
    public function setTestIpn($testIpn)
    {
        $this->testIpn = $testIpn;

        return $this;
    }

    /**
     * @return string
     */
    public function getTxnType()
    {
        return $this->txnType;
    }

    /**
     * @param string $txnType
     */
    public function setTxnType($txnType)
    {
        $this->txnType = $txnType;

        return $this;
    }

}
