<?php

namespace Swiftly\Routing\Parser;

/**
 * Interface for classes that can parse the Swiftly route format
 *
 * @author C Varley <clvarley>
 */
Interface ParserInterface
{

    /**
     * Parse the routes file and return the routes
     *
     * @param string $filename  Routes file
     * @return array            Parsed routes
     */
    public function parseFile( string $filename ) : array;

}
