<?php

namespace Swiftly\Routing;

use \Swiftly\Base\Controller;

/**
 * Represents an action that can be called
 *
 * @author C Varley <clvarley>
 */
Class Action
{

    /**
     * The classname of the controller
     *
     * @var string $class Class name
     */
    private $class = '';

    /**
     * The controller method to call
     *
     * @var string $method Method name
     */
    private $method = '';

    /**
     * The controller used to handle the request
     *
     * @var \Swiftly\Base\Controller $controller Controller class
     */
    private $controller = null;

    /**
     * Scoped context variables
     *
     * @var array $context The context
     */
    private $context = [];

    /**
     * Create a new action using the controller and method provided
     *
     * @param string $class   Controller name
     * @param string $method  Controller method
     * @param array $context  (Optional) The context
     */
    public function __construct( string $class, string $method, array $context = [] )
    {
        $this->class = $class;
        $this->method = $method;
        $this->context = $context;
    }

    /**
     * Get the controller for this action
     *
     * @return \Swiftly\Base\Controller|null Controller
     */
    public function getController() : ?Controller
    {
        return $this->controller;
    }

    /**
     * Get the method for this action
     *
     * @return string Method name
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Prepares the controller and method for execution
     *
     * @param array $args Constructor arguments
     * @return bool       Prepared successfully?
     */
    public function prepare( ...$args ) : bool
    {
        // Already prepared!
        if ( !\is_null( $this->controller ) ) {
            return true;
        }

        // No classname
        if ( empty( $this->class ) ) {
            return false;
        }

        $this->controller = new $this->class( ...$args );
        $this->method = $this->method ?: 'index';

        return \method_exists( $this->controller, $this->method );
    }

    /**
     * Execute the method and return the controller
     *
     * Calling code should have already called the {@see Action::prepare} method
     *
     * @param array $params             Parameters
     * @return \Swiftly\Base\Controller The controller
     */
    public function execute( array $params = [] ) : Controller
    {
        $args = [];

        // Merge the context with any passed params
        $params = \array_merge( $this->context, $params );

        $method_info = new \ReflectionMethod( $this->controller, $this->method );

        $method_params = $method_info->getParameters();

        // Handle the parameters
        if ( !empty( $method_params ) && !empty( $params ) ) {
            $args = $this->handleParams( $method_params, $params );
        }

        // Execute the method
        $this->controller->{$this->method}( ...$args );

        return $this->controller;
    }

    /**
     * Handles the parameters for the method
     *
     * @param  array $method_params Method Parameters
     * @param  array $context       Context variables
     * @return array                Method arguments
     */
    private function handleParams( array $method_params, array $context ) : array
    {
        $args = [];

        foreach ( $method_params as $param ) {
            $value = null;

            $name = $param->getName();
            $type = $param->getType();

            if ( isset( $context[$name] ) && $this->isType( $type, $context[$name] ) ) {
                $value = $context[$name];
            } elseif ( $param->isOptional() ) {
                $value = $param->getDefaultValue();
            } elseif ( $param->allowsNull() ) {
                $value = null;
            } else {
                // TODO: Handle error cases
            }

            $args[$param->getPosition()] = $value;
        }

        return $args;
    }

    /**
     * Checks to see if a variable is of the given type
     *
     * @param  \ReflectionType $type  Variable type
     * @param  mixed $variable        The variable
     * @return bool                   Is of type
     */
    private function isType( \ReflectionType $type, $variable ) : bool
    {
        $result = false;

        $name = $type->getName();

        // No type specified
        if ( empty( $name ) ) {
            return true;
        }

        if ( $type->isBuiltin() ) {

            // Use the appropriate check
            switch ( $name ) {
                case 'string':
                    $result = \is_scalar( $variable );
                break;

                case 'int':
                case 'integer':
                case 'float':
                case 'double':
                    $result = \is_numeric( $variable );
                break;

                case 'bool':
                case 'boolean':
                    $result = \is_bool( $variable );
                break;
            }

        } elseif ( is_a( $variable, $name ) ) {
            $result = true;
        }

        return $result;
    }

}