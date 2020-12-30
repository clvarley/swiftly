<?php

namespace Swiftly\Application;

use \Swiftly\Config\Config;
use \Swiftly\Dependencies\Container;
use \Swiftly\Dependencies\Loaders\PhpLoader;
use \Swiftly\Http\Server\{ Request, Response };
use \Swiftly\Template\TemplateInterface;
use \Swiftly\Routing\{ Dispatcher, ParserInterface };
use \Swiftly\Database\{ Database, AdapterInterface };
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

        $services = new PhpLoader( APP_SWIFTLY . 'services.php'  );

        // Register default services
        $this->dependencies = new Container;
        $this->dependencies->load( $services );

        // Bind this config
        $this->dependencies->bind( Config::class, $config )->singleton( true );


        // Register the appropriate database adapter
        if ( $config->hasValue( 'database' ) ) {
            $this->bindDatabase( $this->dependencies, $config->getValue( 'database' ) );
        }

        // TODO:
    }

    /**
     * Start our app
     */
    public function start() : void
    {

        // Create a new router
        $parser = $this->dependencies->resolve( ParserInterface::class );
        $router = new Dispatcher( $parser );


        // Get the global request object
        $request = $this->dependencies->resolve( Request::class );


        // Load route.json and dispatch
        if ( \is_file( APP_CONFIG . 'routes.json' ) ) {
            $router->load( APP_CONFIG . 'routes.json' );
        }

        $action = $router->dispatch( $request );

        // Did we return a callable route?
        if ( \is_null( $action ) || !$action->prepare( $this->dependencies ) ) {

            $response = new Response( '', 404 );

        } else {

            $action->getController()->setRenderer( $this->dependencies->resolve( TemplateInterface::class ) );

            // Execute the request
            $response = $action->execute( $this->dependencies );

            if ( $response === null ) {
                $response = new Response( '', 404 );
            }
        }

        // Send the response and end!
        $response->send();

        return;
    }

    /**
     * Binds the database adapter
     *
     * @param \Swiftly\Dependencies\Container $services Dependency manager
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
        $services->bind( AdapterInterface::class, function () use (&$config, $adapter) {
            return new $adapter( $config );
        });

        // Bind the database as a singleton
        $services->bind( Database::class, function ( $service ) {
            $database = new Database( $service->resolve( AdapterInterface::class ) );
            $database->open();
            return $database;
        })->singleton( true );

        return;
    }
}
