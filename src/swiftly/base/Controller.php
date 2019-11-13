<?php

namespace Swiftly\Base;

use \Swiftly\Services\Manager;
use \Swiftly\Template\TemplateInterface As Renderer;

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
     * @var Renderer $renderer Internal renderer
     */
    private $renderer = null;

    /**
     * @var string $output Controller output
     */
    private $output = '';

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
     * Provide access to services
     *
     * @param string $name Service name
     */
    public function getService( string $name )
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

    /**
     * Sets the object that will be used for rendering
     *
     * @param
     */
    public function setRenderer( Renderer $renderer ) : void
    {
        $this->renderer = $renderer;
    }

    /**
     * Renders the given template with the data provided
     *
     * @param  string $template Template to render
     * @param  array  $data     Template data
     * @return string           The result
     */
    public function render( string $template, array $data = [] ) : string
    {
        return $this->renderer->render( $template, $data );
    }

}
