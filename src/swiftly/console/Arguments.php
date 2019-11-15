<?php

namespace Swiftly\Console;

/**
 * Provides utility methods for dealing with console arguments
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Arguments
{

    /**
     * Create a new arguments object
     */
    public function __construct()
    {
        // TODO
    }

    /**
     * Create a new console arguments object
     *
     * @static
     *
     * @return Arguments CLI arguments
     */
    public static function fromGlobals() : Arguments
    {
        // TODO

        return ( new Arguments() );
    }

}