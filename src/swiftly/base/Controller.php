<?php

namespace Swiftly\Base;

use \Swiftly\Services\Manager;

/**
 * The abstract class all controllers should inherit
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Abstract Class Controller
{

  /**
   * @var Manager $services Service manager
   */
  private $services = null;

  /**
   * Load the services into the base controller
   *
   * @param Manager $services Service manager
   */
  public function __construct( Manager $services = null )
  {
    $this->services = $services;
  }

  /**
   * Provide access to services directly
   *
   * @param string $name Service name
   */
  public function __get( string $name )
  {
    return ( $this->services !== null ? $this->services->getService( $name ) : null );
  }

}
