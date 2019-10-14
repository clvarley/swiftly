<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Services\Manager;
use \Swiftly\Http\Server\{ Request, Response };
use \Swiftly\Template\Php;
use \Swiftly\Routing\Router;

/**
 * The front controller for our web app
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Web Implements ApplicationInterface
{

  /**
   * @var Config $config Configuration values
   */
  private $config = null;

  /**
   * @var Manager $services Service manager
   */
  private $services = null;

  /**
   * Create our application
   *
   * @param Config $config Configuration values
   */
  public function __construct( Config $config )
  {
    $this->config = $config;

    $this->services = Manager::getInstance();
    $this->services->registerService( 'request', Request::fromGlobals() );
    $this->services->registerService( 'response', new Response() );
  }

  /**
   * Start our app
   */
  public function start() : void
  {

    // Get the router
    if ( is_file(APP_CONFIG . 'routes.json') ) {
      $router = Router::fromJson( APP_CONFIG . 'routes.json' );
    } else {
      $router = new Router();
    }

    $request = $this->services->getService('request');

    $action = $router->get( $route = $request->query->asString('_route_') );

    // Get the response object
    $response = $this->services->getService('response');

    // Did we return a callable route?
    if ( is_null($action) ) {

      $response->setStatus( 404 );

    } else {

      list( $controller, $method ) = $action;

      $controller = new $controller( $this->services );

      // Set the renderer to use
      $controller->setRenderer( new Php() );

      // Call the method!
      $controller->{$method}();

      if ( !empty($body = $controller->getOutput() ) ) {
        $response->setBody( $body );
      }
    }

    // Send the response and end!
    $response->send();

    return;
  }

}
