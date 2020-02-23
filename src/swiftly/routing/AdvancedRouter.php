<?php

namespace Swiftly\Routing;

/**
 * Advanced router that compiles given routes to regex
 *
 * @author C Varley <clvarley>
 */
Class AdvancedRouter Implements RouterAdapterInterface
{

    /**
     * The routes for this router
     *
     * @var array $routes Routes
     */
    private $routes = [];

    /**
     * Routes compiled into regex
     *
     * @var string $compiled Regular expression
     */
    private $compiled = '';

    /**
     * Adds multiple routes to this router
     *
     * @param array $routes Array of routes
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
     * Add a single route to this router
     *
     * @param array $route  Route definition
     * @return void         N/a
     */
    public function addRoute( array $route ) : void
    {
        if ( empty( $route['path'] ) || empty( $route['handler'] ) ) {
            return;
        }

        $this->routes[] = $route;

        return;
    }

    /**
     * Gets the route that matches the given request
     *
     * @param string $request Client request
     * @param string $method  (Optional) method
     * @return array|null     Matching route
     */
    public function dispatch( string $request, string $method = '' ) : ?array
    {
        if ( empty( $this->compiled ) && !empty( $this->routes ) ) {
            $this->compile();
        }

        \preg_match_all( $this->compiled, $request, $matches, PREG_SET_ORDER );

        $route = null;

        if ( !empty( $matches ) && !empty( $matches[0] ) ) {
            $route = $this->routes[$matches[0]['MARK']];

            $params = [];

            foreach ( $route['params'] as $param ) {
                if ( isset( $matches[0][$param] ) ) {
                    $params[$param] = $matches[0][$param];
                }
            }

            // Get the params
            $route['params'] = $params;
        }

        return $route;
    }

    /**
     * Compiles all the routes in this router into a regular expression
     *
     * @return void N/a
     */
    private function compile() : void
    {
        $regex = [];

        foreach ( $this->routes as $index => $route ) {
            $regex[] = '(?:^' . $route['path'] . '$(*MARK:' . $index . '))';
        }

        $this->compiled = '(' . implode( '|', $regex ) . ')';

        return;
    }
}
