<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;

/**
 * The front controller for our console app
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Console Implements ApplicationInterface
{

    /**
     * @var Config $config Configuration values
     */
    private $config = null;

    /**
     * Create our application
     *
     * @param Config $config Configuration values
     */
    public function __construct( Config $config )
    {
        $this->config = $config;
    }

    /**
     * Start our app
     *
     * TODO: Implement console bootstrap
     */
    public function start() : void
    {
        return;
    }

}
