<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;

/**
 * Interface that all applications must implement
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Interface ApplicationInterface
{

    /**
     * Setup our app using the configuration provided
     *
     * @param Config $config Configuration object
     */
    public function __construct( Config $config );

    /**
     * Start the application
     */
    public function start() : void;

}
