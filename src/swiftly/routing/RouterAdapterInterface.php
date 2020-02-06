<?php

namespace Swiftly\Routing;

/**
 * Interface routing adapters need to implement
 *
 * @author C Varley <clvarley>
 */
Interface RouterAdapterInterface
{

    /**
     * Adds multiple routes to this router
     *
     * @param array $routes Array of routes
     * @return void         N/a
     */
    public function addRoutes( array $routes ) : void;

    /**
     * Add a single route to this router
     *
     * @param array $route  Route definition
     * @return void         N/a
     */
    public function addRoute( array $route ) : void;

    /**
     * Gets the route that matches the given request
     *
     * @param string $request Client request
     * @param string $method  (Optional) method
     * @return array|null     Matching route
     */
    public function dispatch( string $request, string $method = '' ) : ?array;

}
