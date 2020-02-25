<?php

namespace Swiftly\Routing;

/**
 * Performs very rudimentary routing
 *
 * @author C Varley <clvarley>
 */
Class SimpleRouter Implements RouterAdapterInterface
{

    /**
     * The routes available
     *
     * @var array $routes Routes
     */
    private $routes = [];

    /**
     * Adds a collection of simple routes
     *
     * @param array $routes Array of simple routes
     * @return void         N/a
     */
    public function addRoutes( array $routes ) : void
    {
        foreach ( $routes as $route ) {
            $this->addRoute( $route );
        }

        return;
    }

    /**
     * Adds a single simple route
     *
     * @param array $route  Route definition
     * @return void         N/a
     */
    public function addRoute( array $route ) : void
    {
        if ( empty( $route['path'] ) || empty( $route['handler'] ) ) {
            return;
        }

        $path = \trim( \mb_strtolower( $route['path'] ) );

        if ( $path !== '/' ) {
            $path = \rtrim( $path, '\\/' );
        }

        if ( !empty( $path ) ) {
            $this->routes[$path] = $route;
        }

        return;
    }

    /**
     * Finds the simple route that satisfies this request
     *
     * Simple router ignores the `$method` param
     *
     * @param string $request Client request
     * @param string $method  (Ignored) Method
     * @return array|null     Matching route (Or null)
     */
    public function dispatch( string $request, string $method = '' ) : ?array
    {
        $request = \trim( \mb_strtolower( $request ) );

        if ( $request !== '/' ) {
            $request = \rtrim( $request, '\\/' );
        }

        $handler = null;

        if ( !empty( $request ) && isset( $this->routes[$request] ) ) {
            $handler = $this->routes[$request];
        } elseif ( isset( $this->routes['*'] ) ) {
            $handler = $this->routes['*'];
        }

        return $handler;
    }

}
