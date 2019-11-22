<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Services\Manager;
use \Swiftly\Console\{ Input, Output, Command };

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
     * @var Manager $services Service manager
     */
    private $services = null;

    /**
     * Create our application
     *
     * @param Config $config Configuration values
     */
    public function __construct( Config $config )
    {
        $this->config = $config;

        $this->services = Manager::getInstance();
        $this->services->registerService( 'output', new Output() );
        $this->services->registerService( 'input', new Input() );
        $this->services->registerService( 'command', new Command() );
    }

    /**
     * Start our app
     */
    public function start() : void
    {

        // Get the router
        if ( is_file( APP_CONFIG . 'routes.json' ) ) {
            $router = Router::fromJson( APP_CONFIG . 'routes.json' );
        } else {
            $router = new Router();
        }

        return;
    }

}
