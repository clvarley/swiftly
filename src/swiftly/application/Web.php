<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Services\Manager;
use \Swiftly\Http\Server\{ Request, Response };
use \Swiftly\Template\Php;
use \Swiftly\Routing\Router;
use \Swiftly\Database\Database;
use \Swiftly\Database\Adapters\{ Mysql, Postgres, Sqlite };

/**
 * The front controller for our web app
 *
 * @author C Varley <clvarley>
 */
Class Web Implements ApplicationInterface
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
        $this->services->registerService( 'request', Request::fromGlobals() );
        $this->services->registerService( 'response', new Response() );

        $database = $config->getValue( 'database' );

        // Create the database object
        if ( !empty( $database ) && !empty( $database['adapter'] ) ) {

            // Get the correct adapter
            switch ( \mb_strtolower( $database['adapter'] ?? 'mysqli' ) ) {
                case 'sqlite':
                    $adapter = new Sqlite( $database );
                break;

                case 'postgres':
                case 'postgresql':
                    $adapter = new Postgres( $database );
                break;

                case 'mysql':
                case 'mysqli':
                default:
                    $adapter = new Mysql( $database );
                break;
            }

            // Create the DB abstraction
            $database = new Database( $adapter );
            $database->open();

            $this->services->registerService( 'db', $database );
        }

        // TODO: MORE!
    }

    /**
     * Start our app
     */
    public function start() : void
    {

        // Create a router
        if ( $this->config->getValue( 'router' ) === 'advanced' ) {
            $router = Router::newAdvanced();
        } else {
            $router = Router::newSimple();
        }

        // Get the global request object
        $http = $this->services->getService( 'request' );


        // Load route.json and dispatch
        if ( \is_file( APP_CONFIG . 'routes.json' ) ) {
            $router->load( APP_CONFIG . 'routes.json' );
        }

        $action = $router->dispatch( $http );

        // Get the global response object
        $response = $this->services->getService( 'response' );

        // Did we return a callable route?
        if ( \is_null( $action ) || !$action->prepare( $this->services ) ) {

            $response->setStatus( 404 );

        } else {

            $action->getController()->setRenderer( new Php() );

            // Execute the request
            $result = $action->execute();

            if ( !empty( $body = $result->getOutput() ) ) {
                $response->setBody( $body );
            }
        }

        // Send the response and end!
        $response->send();

        return;
    }
}
