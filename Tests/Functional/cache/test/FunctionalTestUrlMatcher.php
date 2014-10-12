<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * FunctionalTestUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class FunctionalTestUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/payment/paypal_web_checkout')) {
            // paypal_web_checkout_execute
            if ($pathinfo === '/payment/paypal_web_checkout/execute') {
                return array (  '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::executeAction',  '_route' => 'paypal_web_checkout_execute',);
            }

            // payment_process
            if ($pathinfo === '/payment/paypal_web_checkout/process') {
                return array (  '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::processAction',  '_route' => 'payment_process',);
            }

            // payment_cancel
            if ($pathinfo === '/payment/paypal_web_checkout/ko') {
                return array (  '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::koAction',  '_route' => 'payment_cancel',);
            }

            // payment_success
            if ($pathinfo === '/payment/paypal_web_checkout/ok') {
                return array (  '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::okAction',  '_route' => 'payment_success',);
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
