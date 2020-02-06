<?php

namespace Swiftly\Routing;

/**
 * Responsible for loading and parsing route files
 *
 * @author C Varley <clvarley>
 */
Interface RouteLoaderInterface
{

    /**
     * Check if this loader supports the given routes file
     *
     * @param string $file  File path
     * @return bool         Supports format
     */
    public function supports( string $file ) : bool;

    /**
     * Attempt to parse the given routes file
     *
     * @param string $file  File path
     * @return array        Routes array
     */
    public function parse( string $file ) : array;

}
