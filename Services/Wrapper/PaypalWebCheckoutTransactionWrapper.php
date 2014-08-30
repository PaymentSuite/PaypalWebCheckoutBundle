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

namespace PaymentSuite\PaypalWebCheckoutBundle\Services\Wrapper;

class PaypalWebCheckoutTransactionWrapper
{
    /**
     * Merchant identifier
     * @var string $business
     */
    private $business;

    /**
     * Paypal web endpoint
     * @var string $endpoint
     */
    private $endpoint;

    /**
     * Debug integration
     * @var booelan $debug
     */
    private $debug;

    /**
     * Payment errors
     * @var array $errors
     */
    private $errors = [];

    /**
     * Paypal response
     * @var array $response
     */
    private $response = null;

    /**
     * 
     */
    public function __construct($business, $endpoint, $debug = false)
    {
        $this->user = $business;
        $this->endpoint = $endpoint;

        if (true === $debug) {
            $this->endpoint = str_replace('sandbox.','', $this->endpoint);
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
