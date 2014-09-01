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
use Symfony\Component\HttpFoundation\Response;

use PaymentSuite\PaypalWebCheckoutBundle\Exception\PaymentException;
use PaymentSuite\PaypalWebCheckoutBundle\Exception\ParameterNotReceivedException;

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
     */
    public function executeAction(Request $request)
    {
        $formView = $this->get('paypal_web_checkout.manager')->processPayment();

        return $this->render('PaypalWebCheckoutBundle:Paypal:process.html.twig', array(
            'paypal_form' => $formView,
        ));
    }

    /**
     * Payment success action
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function okAction(Request $request)
    {
        $orderId = $request->query->get('order_id', false);

        return $this->render('PaypalWebCheckoutBundle:Frontend:success.html.twig', array(
            'orderId' => $orderId,
        ));
    }

    /**
     * Payment fail action
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function koAction(Request $request)
    {
        $orderId = $request->query->get('order_id', false);

        return $this->render('PaypalWebCheckoutBundle:Frontend:fail.html.twig', array(
            'orderId' => $orderId,
        ));
    }

    /**
     * Process Paypal response
     *
     * @param Request $request Request element
     *
     * @return Response
     */
    public function processAction(Request $request)
    {
        $logger = $this->get('logger');
        $orderId = $request->query->get('order_id');

        try {
            $this
                ->get('paypal_web_checkout.manager')
                ->processResult($orderId, $request->query->all());
        } catch (ParameterNotReceivedException $pex) {
            $logger->err(
                sprintf(
                    '[PAYMENT] Paypal payment error. Parameter %s not received. Order number #%s',
                    $pex->getMessage(),
                    $orderId
                )
            );

            return new Response('FAIL', 200);
        } catch (PaymentException $pe) {
            $logger->err(
                sprintf(
                    '[PAYMENT] Paypal payment error. Order number #%s',
                    $orderId
                )
            );
        }

        $logger->info('[PAYMENT] Paypal payment success. Order number #' . $orderId);

        return new Response('OK', 200);
    }
}
