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

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;

/**
 * Class PaypalWebCheckoutControllerTest
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 */
class PaypalWebCheckoutControllerTest extends WebTestCase
{
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
