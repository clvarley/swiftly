<?php

namespace Swiftly\Http;

/**
 * Contains HTTP parameters
 *
 * @author C Varley <clvarley>
 */
Class Parameters
{

    /**
     * @var array $data Parameters
     */
    private $data = [];

    /**
     * Create a new parameter group
     *
     * @param array $data Parameters
     */
    public function __construct( array $data = [] )
    {
        $this->data = $data;
    }

    /**
     * Check to see if the given parameter exists
     *
     * @param string $name  Parameter name
     * @return bool         Parameter exists
     */
    public function has( string $name ) : bool
    {
        return ( isset( $this->data[$name] ) );
    }

    /**
     * Get the value of a given parameter
     *
     * @param string $name  Parameter name
     * @return mixed        Parameter value
     */
    public function get( string $name ) // : mixed
    {
        return ( isset( $this->data[$name] ) ? $this->data[$name] : '' );
    }

    /**
     * Gets all the parameters
     *
     * @return array Parameters
     */
    public function getAll() : array
    {
        return $this->data;
    }

    /**
     * Set a paramater
     *
     * @param string $name  Parameter name
     * @param mixed $value  Parameter value
     */
    public function set( string $name, /* mixed */ $value ) : void
    {
        $this->data[$name] = $value;
    }

    /**
     * Is the given parameter a string?
     *
     * @param string $name  Parameter name
     * @return bool         Is string?
     */
    public function isString( string $name ) : bool
    {
        return ( isset( $this->data[$name] ) && is_scalar( $this->data[$name] ) );
    }

    /**
     * Is the given parameter an integer?
     *
     * @param string $name  Parameter name
     * @return bool         Is numeric?
     */
    public function isNumeric( string $name ) : bool
    {
        return ( isset( $this->data[$name] ) && is_numeric( $this->data[$name] ) );
    }

    /**
     * Is the given parameter an array?
     *
     * @param string $name  Parameter name
     * @return bool         Is array?
     */
    public function isArray( string $name ) : bool
    {
        return ( isset( $this->data[$name] ) && is_array( $this->data[$name] ) );
    }

    /**
     * Get the given parameter value as a string
     *
     * @param string $name  Parameter name
     * @return string       Paramater value as string
     */
    public function asString( string $name ) : string
    {
        return ( isset( $this->data[$name] ) && is_scalar( $this->data[$name] ) ? (string)$this->data[$name] : '' );
    }

    /**
     * Get the given parameter value as an integer
     *
     * @param string $name  Parameter name
     * @return int          Paramater value as int
     */
    public function asInteger( string $name ) : int
    {
        return ( isset( $this->data[$name] ) && is_numeric( $this->data[$name] ) ? (int)$this->data[$name] : 0 );
    }

    /**
     * Get the given parameter value as an array
     *
     * @param string $name  Parameter name
     * @return array        Paramater value as array
     */
    public function asArray( string $name ) : array
    {
        return ( isset( $this->data[$name] ) && is_array( $this->data[$name] ) ? $this->data[$name] : [] );
    }
}
