<?php

namespace Swiftly\Filesystem;

use \Swiftly\Filesystem\AbstractPathable;

/**
 * Usefull class used to represent a generic file
 *
 * @author C Varley <conor@highorbit.co.uk>
 */
Class File Extends AbstractPathable
{

    /**
     * Construct a File wrapper around the given file
     *
     * @param string $filepath Path to file
     */
    public function __construct( string $filepath = '' )
    {
        if ( is_file($filepath) && is_readable($filepath) ) {

            $this->path = $filepath;

        }
    }

    /**
     * Gets the size of this file
     *
     * As filesize can throw an E_WARNING, we swallow the error with @
     * and simply return 0.
     *
     * @return int In bytes
     */
    public function getSize() : int
    {
        return @filesize( $this->path ) ?: 0;
    }

    /**
     * Creates a new copy of the file at the given location
     *
     * @param string $path  Desintation path
     * @return bool         Successful?
     */
    public function copy( string $path ) : bool
    {
        return ( !empty($this->path) && is_file($this->path) && copy($this->path, $path) );
    }

    /**
     * Rename the file within it's current folder
     *
     * If you're looking for similar functionality to PHP's inbuilt `rename`
     * function, use the `File->move()` method instead.
     *
     * @param string $name  New file name
     * @return bool         Successful?
     */
    public function rename( string $name ) : bool
    {
        return ( !empty($this->path) && is_file($this->path) && rename($this->path, dirname($this->path) . '/' . $name ) );
    }

    /**
     * Moves the file to new location
     *
     * This method is functionally equivalent to PHP's standard `rename`. If
     * you just need to change the name of this file within a directory, use
     * the `File->rename()` method instead.
     *
     * @param string $path  New file path
     * @return bool         Successful?
     */
    public function move( string $path ) : bool
    {
        return ( !empty($this->path) && is_file($this->path) && rename($this->path, $path) );
    }

}