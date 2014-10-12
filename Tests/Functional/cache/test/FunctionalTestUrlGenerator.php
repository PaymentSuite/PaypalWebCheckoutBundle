<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;

/**
 * FunctionalTestUrlGenerator
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class FunctionalTestUrlGenerator extends Symfony\Component\Routing\Generator\UrlGenerator
{
    private static $declaredRoutes = array(
        'paypal_web_checkout_execute' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::executeAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/payment/paypal_web_checkout/execute',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'payment_process' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::processAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/payment/paypal_web_checkout/process',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'payment_cancel' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::koAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/payment/paypal_web_checkout/ko',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'payment_success' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'PaymentSuite\\PaypalWebCheckoutBundle\\Controller\\PaypalWebCheckoutController::okAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/payment/paypal_web_checkout/ok',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
    );

    /**
     * Constructor.
     */
    public function __construct(RequestContext $context, LoggerInterface $logger = null)
    {
        $this->context = $context;
        $this->logger = $logger;
    }

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        if (!isset(self::$declaredRoutes[$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $name));
        }

        list($variables, $defaults, $requirements, $tokens, $hostTokens, $requiredSchemes) = self::$declaredRoutes[$name];

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, $requiredSchemes);
    }
}
