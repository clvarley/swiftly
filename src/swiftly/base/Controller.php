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
   * @var string $output Controller output
   */
  protected $output = '';

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

  /**
   * Set the output for this controller
   *
   * @param string $output The output
   */
  protected function setOutput( string $output = '' )
  {
    $this->output = $output;
  }

  /**
   * Get the output for this controller
   *
   * @return string Controller output
   */
  public function getOutput() : string
  {
    return $this->output;
  }

}
