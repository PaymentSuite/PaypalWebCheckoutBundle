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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('paypal_web_checkout');

        $rootNode
            ->children()
                ->scalarNode('business')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('debug')
                    ->defaultTrue()
                ->end()
                ->scalarNode('controller_route')
                    ->defaultValue('/payment/paypal_web_checkout/execute')
                ->end()

                ->arrayNode('payment_success')
                    ->children()
                        ->scalarNode('route')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('order_append')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('order_append_field')
                            ->defaultValue('order_id')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('payment_fail')
                    ->children()
                        ->scalarNode('route')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('order_append')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('order_append_field')
                            ->defaultValue('card_id')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('payment_process')
                    ->children()
                        ->scalarNode('route')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
