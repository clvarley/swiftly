<?php

namespace Swiftly\Routing;

/**
 * Performs routing
 *
 * @author C Varley <clvarley>
 */
Class Router
{

    /**
     * @var array $routes Array of routes
     */
    private $routes = [];

    /**
     * Builds a router for the routes provided
     *
     * @param array $routes [Optional] Route definitions
     */
    public function __construct( array $routes = [] )
    {
        $this->routes = $routes;
    }

    /**
     * Create a router from a JSON route file
     *
     * @param string $filepath  Path to JSON file
     * @return Router           Router object
     */
    public static function fromJson( string $filepath ) : Router
    {
        $values = [];

        if ( is_file( $filepath ) && ( $values = file_get_contents( $filepath ) ) !== false ) {

            $values = json_decode( $values, true );

            if ( json_last_error() !== JSON_ERROR_NONE ) {
                $values = [];
            }
        }

        return ( new Router( $values ) );
    }

    /**
     * Gets the callable for the route specified
     *
     * @param string $route The route
     * @return callable|null
     */
    public function get( string $route ) : ?callable
    {

        $route = mb_strtolower( trim( $route ) );

        $route = rtrim( $route, "/\\" );

        $controller = '';

        // Get the controller name
        if ( isset( $this->routes[$route] ) ) {
            $controller = $this->routes[$route];
        } elseif ( empty( $route ) && isset( $this->routes['/'] ) ) {
            $controller = $this->routes['/'];
        }

        $action = null;

        // If there's a controller name
        if ( !empty( $controller ) ) {

            $parts = explode( '::', $controller );

            // If no method is specified, assume `index()`
            if ( !isset( $parts[1] ) ) {
                $parts[1] = 'index';
            }

            if ( is_file( APP_APP . $parts[0] . '.php' ) ) {

                include( APP_APP . $parts[0] . '.php' );

                if ( class_exists( $parts[0] ) && method_exists( $parts[0], $parts[1] ) ) {
                    $action = [ $parts[0], $parts[1] ];
                }
            }
        }

        return $action;
    }
}
