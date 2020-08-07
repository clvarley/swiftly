<?php

namespace Swiftly\Dependencies;

use \Swiftly\Dependencies\Container;

/**
 * Interface for classes that can load dependencies from a file
 *
 * @author C Varley <clvarley>
 */
Interface LoaderInterface
{

    /**
     * Construct a dependency loader for the given file
     *
     * @param string $filepath  Path to file
     */
    public function __construct( string $filepath ) : void;

    /**
     * Load the services defined in the file into the given container
     *
     * @param Swiftly\Dependencies\Container $container Dependency container
     * @return void                                     N/a
     */
    public function load( Container $container ) : void;

}
