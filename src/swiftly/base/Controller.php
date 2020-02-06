<?php

namespace Swiftly\Base;

use \Swiftly\Services\Manager;
use \Swiftly\Template\TemplateInterface As Renderer;
use \Swiftly\Base\Model;

/**
 * The abstract class all controllers should inherit
 *
 * @author C Varley <clvarley>
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
     * @var Model[] $models DB Models
     */
    private $models = [];

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
     * Attempts to get a DB model
     *
     * @param string $name  Model name
     * @return Model|null   DB model (Or null)
     */
    public function getModel( string $name ) : ?Model
    {
        if ( !isset( $this->models[$name] ) ) {
            $this->models[$name] = $this->createModel( $name );
        }

        return $this->models[$name];
    }

    /**
     * Tries to create a model of the given type
     *
     * @param string $name  Model name
     * @return Model|null   Db model (Or null)
     */
    private function createModel( string $name ) : ?Model
    {
        $result = null;

        // TODO: This needs a lot of work!
        if ( is_file( APP_MODEL . $name . '.php' ) ) {

            include APP_MODEL . $name . '.php';

            if ( class_exists( $name ) ) {
                $result = new $name( $this->getService( 'db' ) );
            }
        }

        return $result;
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
        return $this->renderer->render( APP_VIEW . $template, $data );
    }

}
