<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Services\Manager;
use \Swiftly\Http\{ Request, Response };

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

  }

}
