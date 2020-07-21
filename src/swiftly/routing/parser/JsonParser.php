<?php

namespace Swiftly\Routing\Parser;

/**
 * Class responsible for loading routes from .json files
 *
 * @author C Varley <clvarley>
 */
Class JsonParser Implements ParserInterface
{

    /**
     * Allowed HTTP methods
     *
     * @var array HTTP_METHODS HTTP verbs
     */
    private const HTTP_METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'UPDATE'
    ];

    /**
     * The regex used to strip out URL args
     *
     * @var string ROUTE_REGEX Regular expression
     */
    private const ROUTE_REGEX = '~\[(?:(?P<type>i|s):)?(?P<name>\w+)\]|(?:[^\[]+)~ix';

    /**
     * Parse the given json routes file
     *
     * @param  string $filename Routes file
     * @return array            Parsed routes
     */
    public function parseFile( string $filename ) : array
    {
        $content = \file_get_contents( $filename ) ?: '';

        // No content, bail early
        if ( empty( $content ) ) {
            return [];
        }

        $content = \json_decode( $content, true, 4 );

        if ( $content === null || \json_last_error() !== \JSON_ERROR_NONE ) {
            return [];
        }

        $parsed_routes = [];

        // Build the routes
        foreach ( $content as $name => $route ) {

            // If the name is already in use
            if ( empty( $name ) || isset( $parsed_routes[$name] ) ) {
                $name = \uniqid( $name ?: '_' );
            }

            // Invalid, skip this one!
            if ( !\is_array( $route ) || empty( $route ) ) {
                continue;
            }

            $parsed_routes[$name] = $this->convert( $route );
        }

        return $parsed_routes;
    }

    /**
     * Converts the file route definition into a standardised format
     *
     * @param  array $route Route file definition
     * @return array        Standardised definition
     */
    private function convert( array $route ) : array
    {
        $standard = [];

        // Get the method
        if ( !empty( $route['methods'] ) ) {

            if ( \is_array( $route['methods'] ) ) {
                $methods = $route['methods'];
            } else {
                $methods = [ $route['methods'] ];
            }

            $methods = \array_map( '\strtoupper', $methods );

            // Strip out invalid methods
            $methods = \array_intersect( self::HTTP_METHODS, $methods );

            $standard['methods'] = ( $methods ?: [ 'GET' ] );
        } else {
            $standard['methods'] = [ 'GET' ];
        }

        // Get the path
        if ( empty( $route['path'] ) || !\is_string( $route['path'] ) ) {
            // TODO: Logic to handle this
        } else {
            $standard['path'] = $this->parseRoute( $route['path'], $standard['args'] );
        }

        // Get the controller
        if ( empty( $route['handler'] ) || !\is_string( $route['handler'] ) ) {
            // TODO: Logic to handle this
        }

        $handler = \explode( '::', $route['handler'] );

        // If no method, default to `index()`
        if ( empty( $handler[1] ) ) {
            $handler[1] = 'index';
        }

        $standard['handler'] = [
            'class'   => $handler[0],
            'method'  => $handler[1]
        ];

        return $standard;
    }

    /**
     * Parse the given route and build the neccessary regex
     *
     * The args parameter will be filled with the details of any arguments
     * this route takes
     *
     * @param  string $route  Route path
     * @param  array  $args   Route arguments
     * @return string         Regular expression
     */
    private function parseRoute( string $route, &$args = [] ) : string
    {
        $route = \rtrim( $route, " \n\r\t\0\x0B\\/" );

        // No route, assume root
        if ( empty( $route ) ) {
            return '/';
        }

        // Route placeholders?
        if ( !\preg_match_all( self::ROUTE_REGEX, $route, $matches, \PREG_SET_ORDER | \PREG_OFFSET_CAPTURE ) ) {
            return $route;
        }

        $route = '';

        /**
         * Reference:
         *
         * $match[0]      - The entire match, ie: '[i:name]'
         * $match['name'] - The name of the placeholder
         * $match['type'] - The type of the placeholder
         */
        foreach ( $matches as $match ) {

            if ( empty( $match['name'] ) ) {
                $route .= \preg_quote( $match[0][0] );
                continue;
            }

            // Atomic groups were messing with names :(
            $regex = '(';

            // Use appropriate regex
            switch ( $match['type'][0] ) {
                case 'i':
                    $regex .= '\d+';
                break;

                case 's':
                default:
                    $regex .= '[a-zA-Z0-9-_]+';
                break;
            }

            $regex .= ')';

            // Register the param
            $args[] = [
                'name'    => $match['name'][0],
                'offset'  => \strlen( $route ),
                'length'  => \strlen( $regex ),
                'type'    => $match['type'][0] ?: 's'
            ];

            $route .= $regex;
        }

        return $route;
    }
}
