<?php

namespace Swiftly\Console;

/**
 * Provides utility methods for dealing with console output
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Output
{

    /**
     * Outputs to the console
     *
     * @param string $output Output
     */
    public static function write( string $output ) : void
    {
        echo $out;
    }

    /**
     * Outputs to the console with a newline
     *
     * @param string $output Output
     */
    public static function writeLine( string $output ) : void
    {
        echo $output . PHP_EOL;
    }

    /**
     * Clears the display
     */
    public static function clear() : void
    {
        readline_redisplay();
    }

}