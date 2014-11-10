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

namespace PaymentSuite\PaypalWebCheckoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentException;
use PaymentSuite\PaypalWebCheckoutBundle\Exception\ParameterNotReceivedException;

/**
 * Class PaypalWebCheckoutController
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
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
        /*
         * The execute action will generate the Paypal web
         * checkout form before redirecting
         */
        $formView = $this->get('paypal_web_checkout.manager')->generatePaypalForm();

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
     * Process Paypal IPN notification
     *
     * This controller handles the IPN notification.
     * The notification is sent using POST method. However,
     * we expect our internal order_id to be passed as a
     * query parameter 'order_id'. The resulting URL for
     * IPN callback notification will have the following form:
     *
     * http://my-domain.com/payment/paypal_web_checkout/process?order_id=1001
     *
     * No matter what happens here, this controller will
     * always return a 200 status HTTP response, otherwise
     * Paypal notification engine will keep on sending the
     * message.
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
                ->processPaypalIPNMessage($orderId, $request->request->all());

            $logger->info('[PAYMENT] Paypal payment success. Order number #' . $orderId);

        } catch (ParameterNotReceivedException $pex) {

            $logger->error(
                sprintf(
                    '[PAYMENT] Paypal payment error. Parameter %s not received. Order number #%s',
                    $pex->getMessage(),
                    $orderId
                )
            );

        } catch (PaymentException $pe) {

            $logger->error(
                sprintf(
                    '[PAYMENT] Paypal payment error (%s). Order number #%s',
                    $orderId,
                    $pe->getMessage()
                )
            );

        }

        return new Response('OK', 200);
    }
}
