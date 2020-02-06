<?php

namespace Swiftly\Routing;

/**
 * Loads and parses simple syntax route files
 *
 * @author C Varley <clvarley>
 */
Class SimpleRouteLoader Implements RouteLoaderInterface
{

    /**
     * Currently supported route file types
     *
     * @var array SUPPORTED_TYPES Supported file types
     */
    private const SUPPORTED_TYPES = [ 'json' ];

    /**
     * Checks to see is this is a simple route file
     *
     * TODO: This check needs a complete overhaul at some point!
     *
     * @param string $file  File path
     * @return bool         File supported
     */
    public function supports( string $file ) : bool
    {
        return ( \is_file( $file ) && \in_array( \pathinfo( $file, PATHINFO_EXTENSION ), self::SUPPORTED_TYPES ) );
    }

    /**
     * Parses the given simple routes file
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
     * Builds a route array from the JSON_DECODED content
     *
     * TODO: Rebuild to use the new *Route* class
     *
     * @param array $content  Json decoded array
     * @return array          Array of routes
     */
    private function build( array $content ) : array
    {
        $routes = [];

        foreach ( $content as $path => $handler ) {

            $handler = explode( '::', $handler, 2 );

            // No class given?
            if ( !isset( $handler[0] ) || empty( $handler[0] ) ) {
                continue;
            }

            // Check for missing method
            if ( !isset( $handler[1] ) || empty( $handler[1] ) ) {
                $handler[1] = 'index';
            }

            // Build the route array!
            $routes[] = [
                'path'    => $path,
                'handler' => [
                    'object'  => $handler[0],
                    'method'  => $handler[1]
                ],
                'params'  => []
            ];
        }

        return $routes;
    }

}
