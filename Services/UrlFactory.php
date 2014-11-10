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

use Symfony\Component\Routing\RouterInterface;

class UrlFactory
{
    /**
     * @var RouterInterface
     *
     * Router instance
     */
    private $router;

    /**
     * @var string
     *
     * Business name, aka Paypal account email
     */
    private $business;

    /**
     * @var string
     *
     * Paypal Base Paypal cgi-bin URL
     */
    private $paypalUrl;

    /**
     * @var string
     *
     * Paypal Base Paypal cgi-bin URL, Sandbox mode
     */
    private $paypalSandboxUrl;

    /**
     * @var array
     *
     * Query param to be added to the Base cgi-bin Url for validating IPN
     */
    private $paypalNotifyValidateQueryParam;

    /**
     * @var string
     */
    private $returnRouteName;

    /**
     * @var string
     */
    private $cancelReturnRouteName;

    /**
     * @var string
     */
    private $processRouteName;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param RouterInterface $router                         Router instance
     * @param string          $business                       Business name, aka Paypal account email
     * @param string          $paypalUrl                      Paypal Base Paypal cgi-bin URL
     * @param string          $paypalSandboxUrl               Paypal Base Paypal cgi-bin URL, Sandbox mode
     * @param array           $paypalNotifyValidateQueryParam Query param to be added to the Base cgi-bin Url for validating IPN
     * @param string          $returnRouteName                Route name for a succesful payment return from Paypal
     * @param string          $cancelReturnRouteName          Route name for a cancelled payment from Paypal
     * @param string          $processRouteName               Route name for the IPN listener (Paypal callback)
     * @param boolean         $debug                          Debug mode
     */
    public function __construct(
        RouterInterface $router,
        $business,
        $paypalUrl,
        $paypalSandboxUrl,
        $paypalNotifyValidateQueryParam,
        $returnRouteName,
        $cancelReturnRouteName,
        $processRouteName,
        $debug)
    {

        $this->router = $router;
        $this->business = $business;
        $this->paypalUrl = $paypalUrl;
        $this->paypalSandboxUrl = $paypalSandboxUrl;
        $this->paypalNotifyValidateQueryParam = $paypalNotifyValidateQueryParam;
        $this->returnRouteName = $returnRouteName;
        $this->cancelReturnRouteName = $cancelReturnRouteName;
        $this->processRouteName = $processRouteName;
        $this->debug = $debug;
    }

    public function getReturnUrlForOrderId($orderId)
    {
        return $this->router->generate(
            $this->returnRouteName,
            ['id' => $orderId],
            true
        );
    }

    public function getCancelReturnUrlForOrderId($orderId)
    {
        return $this->router->generate(
            $this->cancelReturnRouteName,
            ['id' => $orderId],
            true
        );
    }

    /**
     * Creates the IPN payment notification route,
     * which is triggered after PayPal processes the
     * payment and returns the validity of the transaction
     *
     * For further information
     *
     * @link https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/
     * @link https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNIntro/
     */
    public function getProcessUrlForOrderId($orderId)
    {
        return $this->router->generate(
            $this->processRouteName,
            [ 'order_id' => $orderId ],
            true
        );
    }

    /**
     * Returns the base Paypal API cgi-bin URL
     *
     * If $this->debug is se to TRUE, the Sandbox URL is returned
     *
     * @return string
     */
    public function getPaypalBaseUrl()
    {
        return $this->debug ? $this->paypalSandboxUrl : $this->paypalUrl;
    }

    /**
     * Returns the param/value query string for triggering the
     * validation of the paypal IPN message.
     *
     * @link https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNIntro/
     *
     * @return array
     */
    public function getPaypalNotifyValidateQueryParam()
    {
        return $this->paypalNotifyValidateQueryParam;
    }

}
