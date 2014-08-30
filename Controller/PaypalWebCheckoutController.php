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
 
namespace PaymentSuite\PaypalWebCheckoutBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use PaymentSuite\PaypalWebCheckoutBundle\Exception\PaymentException;

/**
 * PaypalWebCheckoutController
 */
class PaypalWebCheckoutController extends Controller
{
    /**
     * Payment execution
     *
     * @param Request $request Request element
     *
     * @return Response
     *
     * @Method("GET")
     */
    public function executeAction(Request $request)
    {
        $formView = $this->get('paypal_web_checkout.manager')->processPayment();

        return $this->render('PaypalWebCheckoutBundle:Paypal:process.html.twig',array(
            'paypal_form' => $formView,
        ));
    }
}
