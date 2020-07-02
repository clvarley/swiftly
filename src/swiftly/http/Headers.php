<?php

namespace Swiftly\Http;

/**
 * Represents a collection of HTTP headers
 *
 * @author C Varley <clvarley>
 */
Class Headers
{

    /**
     * @var array $headers HTTP headers
     */
    private $headers = [];

    /**
     * Create a new HTTP headers collection
     *
     * @param array $headers Array of HTTP headers
     */
    public function __construct( array $headers = [] )
    {
        foreach ( $headers as $header => $value ) {
            $this->addHeader( $header, $value );
        }
    }

    /**
     * Get the first value of the given header
     *
     * @param string $name  Header name
     * @return string|null  Header value [Or null]
     */
    public function getHeader( string $name ) : ?string
    {
        return ( $this->headers[mb_strtolower($name)][0] ?? null );
    }

    /**
     * Get all values of the given header
     *
     * @param string $name  Header name
     * @return array|null   Header values [Or null]
     */
    public function getHeaders( string $name ) : ?array
    {
        return ( $this->headers[mb_strtolower( $name )] ?? null );
    }

    /**
     * Get all headers
     *
     * @return array Headers
     */
    public function getAll() : array
    {
        return $this->headers;
    }

    /**
     * Checks to see if a given header exists
     *
     * @param string $name  Header name
     * @return bool         Header exists
     */
    public function hasHeader( string $name ) : bool
    {
        return ( \array_key_exists( \mb_strtolower( $name ), $this->headers ) );
    }

    /**
     * Sets the given header
     *
     * Explicitly overwrites any existing values, call {@see Headers::addHeader()}
     * to append a new value.
     *
     * @param string $name  Header name
     * @param string $value Header value
     */
    public function setHeader( string $name, string $value ) : void
    {
        $this->headers[\mb_strtolower( $name )] = [$value];
    }

    /**
     * Adds a value to the given header (Or creates it if it doesn't exist)
     *
     * @param string $name  Header name
     * @param string $value Header value
     */
    public function addHeader( string $name, string $value ) : void
    {
        $name = \mb_strtolower( $name );

        if ( \array_key_exists( $name, $this->headers ) ) {
            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = [ $value ];
        }

        return;
    }

}
