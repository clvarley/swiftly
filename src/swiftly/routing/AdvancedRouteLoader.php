<?php

namespace Swiftly\Routing;

/**
 * Responsible for loading routes from files that use the advanced syntax
 *
 * @author C Varley <clvarley>
 */
Class AdvancedRouteLoader Implements RouteLoaderInterface
{

    /**
     * The regex used to convert the Swiftly route format to regex
     *
     * @var string REGEX Regular expression
     */
    private const REGEX = '/\[(?P<type>i|s):(?P<name>\w+)\]/i';

    /**
     * Check if this loader supports the given routes file
     *
     * NOTE: This needs an overhaul
     *
     * @param string $file  File path
     * @return bool         Supports format
     */
    public function supports( string $file ) : bool
    {
        return ( \is_file( $file ) && \pathinfo( $file, PATHINFO_EXTENSION  ) === 'json' );
    }

    /**
     * Parses the given advanced routes file
     *
     * @param string $file  File path
     * @return array        Array of routes
     */
    public function parse( string $file ) : array
    {
        $content = \file_get_contents( $file ) ?: '';

        if ( empty( $content ) ) {
            return [];
        }

        $content = \json_decode( $content, true );

        if ( \json_last_error() === JSON_ERROR_NONE && $content !== null ) {
            $content = $this->build( $content );
        } else {
            $content = [];
        }

        return $content;
    }

    /**
     * Builds the route definitions from the JSON file contents
     *
     * @param array $content  JSON decoded array
     * @return array          Route definitions
     */
    private function build( $content ) : array
    {
        $routes = [];

        foreach ( $content as $name => $route ) {
            if ( empty( $route['handler'] ) || empty( $route['path'] ) ) {
                continue;
            }

            $handler = explode( '::', $route['handler'] );

            // Missing method
            if ( empty( $handler[1] ) ) {
                $handler[1] = 'index';
            }

            $params = [];

            // Convert from Swiftly route markup to regex
            $path = \preg_replace_callback( self::REGEX, function ( $matches ) use ( &$params ) {
                $regex = '(?P<' . $matches['name'] . '>';

                $params[] = $matches['name'];

                switch( $matches['type'] ) {
                    // Match integer
                    case 'i':
                        $regex .= '\d+';
                    break;

                    // Match string
                    case 's':
                        $regex .= '[A-Za-z-_0-9]+';
                    break;
                }

                return $regex . ')';
            }, $route['path'] );

            // Add the route
            $routes[] = [
                'path'    => $path,
                'handler' => [
                    'class'   => $handler[0],
                    'method'  => $handler[1]
                ],
                'params'  => $params
            ];
        }

        return $routes;
    }
}
