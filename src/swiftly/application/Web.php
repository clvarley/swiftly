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

        // Create the database object
        if ( $config->hasValue( 'database' ) && !empty(( $db_opts = $config->getValue( 'database' ) )) ) {

            // Get the correct adapter
            switch ( mb_strtolower( $db_opts['adapter'] ?? 'mysqli' ) ) {
                case 'sqlite':
                    $adapter = new Sqlite( $db_opts );
                break;

                case 'postgres':
                case 'postgresql':
                    $adapter = new Postgres( $db_opts );
                break;

                case 'mysql':
                case 'mysqli':
                default:
                    $adapter = new Mysql( $db_opts );
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

        // Get the router
        if ( is_file( APP_CONFIG . 'routes.json' ) ) {
            $router = Router::fromJson( APP_CONFIG . 'routes.json' );
        } else {
            $router = new Router();
        }

        $request = $this->services->getService( 'request' );

        $action = $router->get( $route = $request->query->asString( '_route_' ) );

        // Get the response object
        $response = $this->services->getService( 'response' );

        // Did we return a callable route?
        if ( is_null( $action ) ) {

            $response->setStatus( 404 );

        } else {

            list( $controller, $method ) = $action;

            $controller = new $controller( $this->services );
            $controller->setRenderer( new Php() );

            // Call the method!
            $controller->{$method}();

            if ( !empty($body = $controller->getOutput() ) ) {
                $response->setBody( $body );
            }
        }

        // Send the response and end!
        $response->send();

        return;
    }
}
