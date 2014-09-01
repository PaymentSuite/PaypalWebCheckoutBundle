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

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;

/**
 * Class PaypalWebCheckoutControllerTest
 */
class PaypalWebCheckoutControllerTest extends WebTestCase
{
    /**
     * Test execute controller
     */
    public function testExecute()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('form')->count() === 1);

        $this->assertTrue($crawler->filter('input[type="hidden"]')->count() === 10);
    }
}
