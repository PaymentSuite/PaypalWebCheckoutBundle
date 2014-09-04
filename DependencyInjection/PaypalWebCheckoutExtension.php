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

namespace PaymentSuite\PaypalWebCheckoutBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PaypalWebCheckoutExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('paypal_web_checkout.business', $config['business']);
        $container->setParameter('paypal_web_checkout.debug', $config['debug']);
        $container->setParameter('paypal_web_checkout.controller.route', $config['controller_route']);

        $container->setParameter('paypal_web_checkout.success.route.name', $config['payment_success']['route']);
        $container->setParameter('paypal_web_checkout.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('paypal_web_checkout.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('paypal_web_checkout.fail.route.name', $config['payment_fail']['route']);
        $container->setParameter('paypal_web_checkout.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('paypal_web_checkout.fail.order.field', $config['payment_fail']['order_append_field']);

        $container->setParameter('paypal_web_checkout.process.route.name', $config['payment_process']['route']);
        $container->setParameter('paypal_web_checkout.process.route', $config['payment_process']['path']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
