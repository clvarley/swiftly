<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Dependencies\Container;
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
     * @var Container $services Dependency manager
     */
    private $dependencies = null;

    /**
     * Create our application
     *
     * @param Config $config Configuration values
     */
    public function __construct( Config $config )
    {
        $this->config = $config;

        $this->dependencies = new Container;

        // Bind app singletons
        $this->dependencies->bindSingleton( Config::class,   $config );
        $this->dependencies->bindSingleton( Request::class,  Request::fromGlobals() );
        $this->dependencies->bindSingleton( Response::class, new Response() );

        // Register the database object
        if ( $config->hasValue( 'adapter' ) ) {
            $this->bindDatabase( $this->dependencies, $config->getValue( 'database' ) );
        }

        // Register the template engine
        if ( $config->hasValue( 'template' ) ) {
            // TODO: Tidy this whole section up
        } else {
            $this->dependencies->bindInstance( Swiftly\Template\TemplateInterface::class, Php::class );
        }

        // TODO:
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
        $http = $this->dependencies->resolve( Request::class );


        // Load route.json and dispatch
        if ( \is_file( APP_CONFIG . 'routes.json' ) ) {
            $router->load( APP_CONFIG . 'routes.json' );
        }

        $action = $router->dispatch( $http );

        // Get the global response object
        $response = $this->dependencies->resolve( Response::class );

        // Did we return a callable route?
        if ( \is_null( $action ) || !$action->prepare( $this->dependencies ) ) {

            $response->setStatus( 404 );

        } else {

            $action->getController()->setRenderer( $this->dependencies->resolve( Swiftly\Template\TemplateInterface::class ) );

            // Execute the request
            $result = $action->execute( $this->dependencies );

            if ( !empty( $body = $result->getOutput() ) ) {
                $response->setBody( $body );
            }
        }

        // Send the response and end!
        $response->send();

        return;
    }

    /**
     * Binds the database adapter
     *
     * @param Swiftly\Dependencies\Container $services  Dependency manager
     * @param array $config                             Database config
     * @return void                                     N/a
     */
    private function bindDatabase( Container $services, array $config ) : void
    {
        // Get the correct adapter
        switch( \mb_strtolower( $config['adapter'] ) ) {
            case 'sqlite':
                $adapter = Sqlite::class;
            break;

            case 'postgres':
            case 'postgresql':
                $adapter = Postgres::class;
            break;

            case 'mysql':
            case 'mysqli':
            default:
                $adapter = Mysql::class;
            break;
        }

        // Bind the adapter
        $services->bindInstance( Swiftly\Database\AdapterInterface::class, function() use (&$config) {
            return new $adapter( $config );
        });

        // Bind the database wrapper
        $services->bindSingleton( Swiftly\Database\Database::class, Swiftly\Database\Database::class );

        return;
    }
}
