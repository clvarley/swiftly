<?php

namespace Swiftly\Routing;

/**
 * Performs routing
 *
 * @author C Varley <cvarley@highorbit.co.uk>
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

    if ( is_file($filepath) && ( $values = file_get_contents($filepath) ) !== false ) {

      $values = json_decode($filepath, true);

      if ( json_last_error() !== JSON_ERROR_NONE ) {
        $values = [];
      }
    }

    return ( new Router($values) );
  }

  /**
   * Gets the callable for the route specified
   *
   * @param string $route The route
   * @return callable|null
   */
  public function get() : ?callable
  {
    // TODO:
    return [$this, 'get'];
  }

}
