<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Services\Manager;
use \Swiftly\Http\{ Request, Response };
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

    if ( empty($route = $request->query->asString('_route_')) ) {
      $route = $request->getUrl();
    }

    $action = $router->get( $route );

    // No callable route returned?
    if ( is_null($action) ) exit;

    list( $controller, $method ) = $action;

    // TODO: Controller initialization and method call

    // Wrap up and send response (if necessary)
    $response = $this->services->getService('response');

    if ( !empty($body = $controller->getOutput() ) ) {
      $response->setBody( $body );
    }

    // Send the response and end!
    $response->send();

    return;
  }

}
