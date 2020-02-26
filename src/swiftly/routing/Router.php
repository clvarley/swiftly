<?php

namespace Swiftly\Routing;

use \Swiftly\Http\Server\Request;
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
     * @param Request $request  User request
     * @return Action           Action (Or null)
     */
    public function dispatch( Request $request ) : ?Action
    {
        $path = $request->query->asString( '_route_' );

        if ( empty( $path ) ) {
            $path = '/';
        } else {
            $path = \rtrim( $path, " \t\n\r\0\x0B\\/" );
        }

        // Get the route definition
        $def = $this->adapter->dispatch( $path, $request->getMethod() ?: 'GET' );

        // Checks for null or empty
        if ( empty( $def ) ) {
            return null;
        }

        list( 'class' => $controller, 'method' => $method ) = $def['handler'];

        $context = $request->query->getAll();

        if ( !empty( $def['params'] ) ) {
            $context = \array_merge( $context, $def['params'] );
        }

        return new Action( $controller, $method, $context );
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
     *
     * @return self Advanced router
     */
    public static function newAdvanced() : self
    {
        return new Router(
            new AdvancedRouteLoader,
            new AdvancedRouter
        );
    }


}
