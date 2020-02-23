<?php

namespace Swiftly\Routing;

use \Swiftly\Routing\{ RouteLoaderInterface, RouterAdapterInterface, Action };

/**
 * Attempts to route the given request
 *
 * @author C Varley <clvarley>
 */
Final Class Router
{

    /**
     * Loader responsible for parsing the routes file
     *
     * @var RouteLoaderInterface $loader Route loader
     */
    private $loader = null;

    /**
     * Router responsible for routing request
     *
     * @var RouterAdapterInterface $adapter Router implementation
     */
    private $adapter = null;

    /**
     * Constructs the router with the adapters provided
     *
     * @param RouteLoaderInterface $loader    Route loader
     * @param RouterAdapterInterface $adapter Router implementation
     */
    public function __construct( RouteLoaderInterface $loader, RouterAdapterInterface $adapter )
    {
        $this->loader = $loader;
        $this->adapter = $adapter;
    }

    /**
     * Load the given routes file
     *
     * @param string $file  Path to routes file
     * @return bool         Loaded successfully?
     */
    public function load( string $file ) : bool
    {
        if ( !\is_file( $file ) || !\is_readable( $file ) ) {
            return false;
        }

        // Check if the loader supports this file
        if ( !$this->loader->supports( $file ) ) {
            return false;
        }

        $routes = $this->loader->parse( $file );

        $this->adapter->addRoutes( $routes );

        return !empty( $routes );
    }

    /**
     * Attempt to match the given request to a route
     *
     * @param string $request User request
     * @param string $method  (Optional) Method
     * @return Action         Action (Or null)
     */
    public function dispatch( string $request, string $method = '' ) : ?Action
    {
        if ( empty( $request ) ) {
            $request = '/';
        }

        // Get the route definition
        $def = $this->adapter->dispatch( $request, $method );

        // Checks for null or empty
        if ( empty( $def ) ) {
            return null;
        }

        list( 'class' => $controller, 'method' => $method ) = $def['handler'];

        $action = new Action( $controller, $method );

        return new Action( $controller, $method );
    }

    /**
     * Creates a new router for the simple routing format
     *
     * @return self Simple router
     */
    public static function newSimple() : self
    {
        return new Router(
            new SimpleRouteLoader,
            new SimpleRouter
        );
    }

    /**
     * Creates a new router for the advanced routing format
     */
    public static function newAdvanced() : self
    {
        return new Router(
            new AdvancedRouteLoader,
            new AdvancedRouter
        );
    }


}
