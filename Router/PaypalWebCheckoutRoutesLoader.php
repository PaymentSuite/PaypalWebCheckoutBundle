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

namespace PaymentSuite\PaypalWebCheckoutBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Paypal router
 */
class PaypalWebCheckoutRoutesLoader implements LoaderInterface
{
    /**
     * @var string
     *
     * Execution route name
     */
    private $controllerRouteName;

    /**
     * @var string
     *
     * Execution controller route
     */
    private $controllerRoute;

    /**
     * @var boolean
     *
     * Route is loaded
     */
    private $loaded = false;

    /**
     * Construct method
     *
     * @param string $controllerRouteName Controller route name
     * @param string $controllerRoute     Controller route
     */
    public function __construct(
        $controllerRouteName,
        $controllerRoute,
        $controllerSuccessRouteName,
        $controllerSuccessRoute,
        $controllerFailRouteName,
        $controllerFailRoute,
        $controllerNotifyRouteName,
        $controllerNotifyRoute
    ) {
        $this->controllerRouteName = $controllerRouteName;
        $this->controllerRoute = $controllerRoute;
        $this->controllerSuccessRouteName = $controllerSuccessRouteName;
        $this->controllerSuccessRoute = $controllerSuccessRoute;
        $this->controllerFailRouteName = $controllerFailRouteName;
        $this->controllerFailRoute = $controllerFailRoute;
        $this->controllerNotifyRouteName = $controllerNotifyRouteName;
        $this->controllerNotifyRoute = $controllerNotifyRoute;
    }

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     *
     * @return RouteCollection
     *
     * @throws RuntimeException Loader is added twice
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {

            throw new \RuntimeException('Do not add this loader twice');
        }

        $routes = new RouteCollection();

        $routes->add($this->controllerRouteName, new Route($this->controllerRoute, array(
            '_controller' => 'PaypalWebCheckoutBundle:PaypalWebCheckout:execute',
        )));

        $routes->add($this->controllerSuccessRouteName, new Route($this->controllerSuccessRoute, array(
            '_controller' => 'PaypalWebCheckoutBundle:PaypalWebCheckout:ok',
        )));

        $routes->add($this->controllerFailRouteName, new Route($this->controllerFailRoute, array(
            '_controller' => 'PaypalWebCheckoutBundle:PaypalWebCheckout:ko',
        )));

        $routes->add($this->controllerNotifyRouteName, new Route($this->controllerNotifyRoute, array(
            '_controller' => 'PaypalWebCheckoutBundle:PaypalWebCheckout:process',
        )));

        $this->loaded = true;

        return $routes;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'paypal_web_checkout' === $type;
    }

    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface A LoaderResolverInterface instance
     */
    public function getResolver()
    {
    }

    /**
     * Sets the loader resolver.
     *
     * @param LoaderResolverInterface $resolver A LoaderResolverInterface instance
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}
