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
 * Mirrors Paypal IPN message issued when confirming a payment.
 * This class is used to wrap the message in a class
 *
 * @link https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/#id091EB04C0HS
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 * @author Mickaël Andrieu <andrieu.travail@gmail.com>
 */
class PaypalWebCheckoutMethod implements PaymentMethodInterface
{
    /**
     * @var string
     *
     * The status of the payment. Possible values are:
     *
     * Canceled_Reversal:
     *      A reversal has been canceled. For example, you won a dispute with the customer,
     *      and the funds for the transaction that was reversed have been returned to you.
     *
     * Completed:
     *      The payment has been completed, and the funds have been added successfully to your account balance.
     *
     * Created:
     *      A German ELV payment is made using Express Checkout.
     *
     * Denied:
     *      The payment was denied. This happens only if the payment was previously pending because
     *      of one of the reasons listed for the pending_reason variable or the Fraud_Management_Filters_x variable.
     *
     * Expired:
     *      This authorization has expired and cannot be captured.
     *
     * Failed:
     *      The payment has failed. This happens only if the payment was made from your customer's bank account.
     *
     * Pending:
     *      The payment is pending. See pending_reason for more information.
     *
     * Refunded:
     *      You refunded the payment.
     *
     * Reversed:
     *      A payment was reversed due to a chargeback or other type of reversal. The funds have been
     *      removed from your account balance and returned to the buyer. The reason for the reversal
     *      is specified in the ReasonCode element.
     * Processed:
     *      A payment has been accepted.
     *
     * Voided:
     *      This authorization has been voided.
     */
    protected $paymentStatus;

    /**
     * @var string
     *
     * Message's version number
     */
    protected $notifyVersion;

    /**
     * @var string
     *
     * Whether the customer has a verified PayPal account:
     *
     * verified:
     *      Customer has a verified PayPal account.
     *
     * unverified:
     *      Customer has an unverified PayPal account.
     */
    protected $payerStatus;

    /**
     * @var string
     *
     * Email address or account ID of the payment recipient (that is, the merchant).
     * Equivalent to the values of receiver_email (if payment is sent to primary account)
     * and business set in the Website Payment HTML.
     *
     * Note: The value of this variable is normalized to lowercase characters.
     * Length: 127 characters
     */
    protected $business;

    /**
     * @var integer
     *
     * Quantity as entered by your customer or as passed by you, the merchant.
     * If this is a shopping cart transaction, PayPal appends the number of the item (e.g. quantity1, quantity2).
     */
    protected $quantity;

    /**
     * @var string
     *
     * Payment type. Possible values:
     *
     * echeck:
     *      This payment was funded with an eCheck.
     *
     * instant:
     *      This payment was funded with PayPal balance, credit card, or Instant Transfer.
     */
    protected $paymentType;

    /**
     * @var string
     *
     * Primary email address of the payment recipient (that is, the merchant).
     * If the payment is sent to a non-primary email address on your PayPal account,
     * the receiver_email is still your primary email.
     *
     * Note: The value of this variable is normalized to lowercase characters.
     * Length: 127 characters
     */
    protected $receiverEmail;

    /**
     * @var string
     *
     * This variable is set only if payment_status is Pending.
     *
     * address:
     *      The payment is pending because your customer did not include a confirmed shipping address
     *      and your Payment Receiving Preferences is set yo allow you to manually accept or deny
     *      each of these payments. To change your preference, go to the Preferences section of your Profile.
     *
     * authorization:
     *      You set the payment action to Authorization and have not yet captured funds.
     *
     * echeck:
     *      The payment is pending because it was made by an eCheck that has not yet cleared.
     *
     * intl:
     *      The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism.
     *      You must manually accept or deny this payment from your Account Overview.
     *
     * multi-currency:
     *      You do not have a balance in the currency sent, and you do not have your profiles's
     *      Payment Receiving Preferences option set to automatically convert and accept this payment.
     *      As a result, you must manually accept or deny this payment.
     *
     * order:
     *      You set the payment action to Order and have not yet captured funds.
     *
     * paymentreview:
     *      The payment is pending while it is reviewed by PayPal for risk.
     *
     * regulatory_review:
     *      The payment is pending because PayPal is reviewing it for compliance with
     *      government regulations. PayPal will complete this review within 72 hours.
     *      When the review is complete, you will receive a second IPN message whose
     *      payment_status/reason code variables indicate the result.
     *
     * unilateral:
     *      The payment is pending because it was made to an email address that is
     *      not yet registered or confirmed.
     *
     * upgrade:
     *      The payment is pending because it was made via credit card and you must upgrade your account
     *      to Business or Premier status before you can receive the funds. upgrade can also mean that
     *      you have reached the monthly limit for transactions on your account.
     *
     * verify:
     *      The payment is pending because you are not yet verified. You must verify your account before
     *      you can accept this payment.
     *
     * other:
     *      The payment is pending for a reason other than those listed above.
     *      For more information, contact PayPal Customer Service.
     */
    protected $pendingReason;

    /**
     * @var string
     *
     * IPN Transaction Type
     *
     * Typically, your back-end or administrative processes will perform specific actions based on the kind
     * of IPN message received. You can use the txn_type variable in the message to trigger the kind of
     * processing you want to perform.
     *
     * Possible statuses:
     *
     * adjustment:
     *      A dispute has been resolved and closed
     *
     * cart:
     *      Payment received for multiple items; source is Express Checkout or the PayPal Shopping Cart.
     *
     * express_checkout:
     *      Payment received for a single item; source is Express Checkout
     *
     * masspay:
     *      Payment sent using Mass Pay
     *
     * merch_pmt:
     *      Monthly subscription paid for Website Payments Pro
     *
     * mp_cancel:
     *      Billing agreement cancelled
     *
     * mp_signup:
     *      Created a billing agreement
     *
     * new_case:
     *      A new dispute was filed
     *
     * payout:
     *      A payout related to a global shipping transaction was completed.
     *
     * pro_hosted:
     *      Payment received; source is Website Payments Pro Hosted Solution.
     *
     * recurring_payment:
     *      Recurring payment received
     *
     * recurring_payment_expired:
     *      Recurring payment expired
     *
     *recurring_payment_failed:
     *      Recurring payment failed. This transaction type is sent if:
     *      * The attempt to collect a recurring payment fails
     *      * The "max failed payments" setting in the customer's recurring payment profile is 0
     *      * In this case, PayPal tries to collect the recurring payment an unlimited number of
     *        times without ever suspending the customer's recurring payments profile.
     *
     * recurring_payment_profile_cancel:
     *      Recurring payment profile canceled
     *
     * recurring_payment_profile_created:
     *      Recurring payment profile created
     *
     * recurring_payment_skipped:
     *      Recurring payment skipped; it will be retried up to 3 times, 5 days apart
     *
     * recurring_payment_suspended:
     *      Recurring payment suspended. This transaction type is sent if PayPal tried to
     *      collect a recurring payment, but the related recurring payments profile has been suspended.
     *
     * recurring_payment_suspended_due_to_max_failed_payment:
     *      Recurring payment failed and the related recurring payment profile has been suspended.
     *      This transaction type is sent if:
     *      * PayPal's attempt to collect a recurring payment failed
     *      * The "max failed payments" setting in the customer's recurring payment profile is 1 or greater
     *      * the number of attempts to collect payment has exceeded the value specified for "max failed payments"
     *        In this case, PayPal suspends the customer's recurring payment profile.
     *
     * send_money:
     *      Payment received; source is the Send Money tab on the PayPal website
     *
     * subscr_cancel:
     *      Subscription canceled
     *
     * subscr_eot:
     *      Subscription expired
     *
     * subscr_failed:
     *      Subscription payment failed
     *
     * subscr_modify:
     *      Subscription modified
     *
     * subscr_payment:
     *      Subscription payment received
     *
     * subscr_signup:
     *      Subscription started
     *
     * virtual_terminal:
     *      Payment received; source is Virtual Terminal
     *
     * web_accept:
     *      Payment received; source is any of the following:
     *      * A Direct Credit Card (Pro) transaction
     *      * A Buy Now, Donation or Smart Logo for eBay auctions button
     */
    protected $txnType;

    /**
     * @var string
     *
     * Item name as passed by you, the merchant. Or, if not passed by you, as entered by your customer.
     * If this is a shopping cart transaction, PayPal will append the number of the item
     * (e.g., item_name1, item_name2, and so forth).
     *
     * Length: 127 characters
     */
    protected $itemName;

    /**
     * @var string
     *
     * For payment IPN notifications, this is the currency of the payment.
     *
     * For non-payment subscription IPN notifications (i.e., txn_type= signup, cancel, failed, eot, or modify),
     * this is the currency of the subscription.
     *
     * For payment subscription IPN notifications, it is the currency of the payment
     * (i.e., txn_type = subscr_payment)
     */
    protected $mcCurrency;

    /**
     * @var integer
     *
     * Whether the message is a test message. It is one of the following values:
     *
     * 1:
     *      the message is directed to the Sandbox
     */
    protected $testIpn;

    /**
     * @var float
     *
     * Full amount of the customer's payment, before transaction fee is subtracted.
     *
     * Equivalent to payment_gross for USD payments. If this amount is negative, it signifies a
     * refund or reversal, and either of those payment statuses can be for the full or partial
     * amount of the original transaction.
     */
    protected $mcGross;

    /**
     * @var string
     *
     * Pass-through variable for you to track purchases. It will get passed back to you at the completion
     * of the payment. If omitted, no variable will be passed back to you. If this is a shopping cart transaction,
     * PayPal will append the number of the item (e.g., item_number1, item_number2, and so forth)
     *
     * Length: 127 characters
     */
    protected $itemNumber;

    /**
     * @var string
     *
     * Encrypted string used to validate the authenticity of the transaction
     */
    protected $verifySign;

    /**
     * @var string
     *
     * Customer's primary email address. Use this email to provide any credits.
     * Length: 127 characters
     */
    protected $payerEmail;

    /**
     * @var string
     *
     * The merchant's original transaction identification number for the payment from the buyer,
     * against which the case was registered.
     */
    protected $txnId;

    /**
     * @var string
     *
     * Internal; only for use by MTS and DTS
     */
    protected $ipnTrackId;

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
        $mcCurrency = null, $itemNumber = null, $testIpn = null, $ipnTrackId = null
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
        $this->ipnTrackId = $ipnTrackId;
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
     * Returns business name
     *
     * @return string
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Sets business name
     *
     * @param string $business
     */
    public function setBusiness($business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Returns order id
     *
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * Sets item name
     *
     * @param string $itemName
     *
     * @return $this;
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;

        return $this;
    }

    /**
     * Return transaction currency
     *
     * @return string
     */
    public function getMcCurrency()
    {
        return $this->mcCurrency;
    }

    /**
     * Sets transaction currency
     *
     * @param string $mcCurrency
     *
     * @return @this
     */
    public function setMcCurrency($mcCurrency)
    {
        $this->mcCurrency = $mcCurrency;

        return $this;
    }

    /**
     * Returns ipn notify message version
     *
     * @return string
     */
    public function getNotifyVersion()
    {
        return $this->notifyVersion;
    }

    /**
     * Sets ipn notify message version
     *
     * @param string $notifyVersion
     */
    public function setNotifyVersion($notifyVersion)
    {
        $this->notifyVersion = $notifyVersion;

        return $this;
    }

    /**
     * Returns paypal payer status
     *
     * @return string
     */
    public function getPayerStatus()
    {
        return $this->payerStatus;
    }

    /**
     * Sets paypal payer status
     *
     * @param string $payerStatus
     *
     * @return $this
     */
    public function setPayerStatus($payerStatus)
    {
        $this->payerStatus = $payerStatus;

        return $this;
    }

    /**
     * Returns payment status
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Sets payment status
     *
     * @param string $paymentStatus
     *
     * @return $this
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * Returns payment type
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Sets payment type
     *
     * @param string $paymentType
     *
     * @return $this
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Returns pending payment reason
     *
     * @return string
     */
    public function getPendingReason()
    {
        return $this->pendingReason;
    }

    /**
     * Sets pending payment reason
     *
     * @param string $pendingReason
     *
     * @return $this
     */
    public function setPendingReason($pendingReason)
    {
        $this->pendingReason = $pendingReason;

        return $this;
    }

    /**
     * Returns order item quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Sets order item quantity
     *
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Returns payment receiver email
     *
     * @return string
     */
    public function getReceiverEmail()
    {
        return $this->receiverEmail;
    }

    /**
     * Sets payment receiver email
     *
     * @param string $receiverEmail
     *
     * @return $this
     */
    public function setReceiverEmail($receiverEmail)
    {
        $this->receiverEmail = $receiverEmail;

        return $this;
    }

    /**
     * Returns if this is a test ipn message
     *
     * @return int
     */
    public function getTestIpn()
    {
        return $this->testIpn;
    }

    /**
     * Sets if this is a test ipn message
     *
     * @param int $testIpn
     *
     * @return $this
     */
    public function setTestIpn($testIpn)
    {
        $this->testIpn = $testIpn;

        return $this;
    }

    /**
     * Returns ipn transaction type
     *
     * @return string
     */
    public function getTxnType()
    {
        return $this->txnType;
    }

    /**
     * Sets ipn transaction type
     *
     * @param string $txnType
     *
     * @return $this
     */
    public function setTxnType($txnType)
    {
        $this->txnType = $txnType;

        return $this;
    }

}
