<?php

namespace Swiftly\Dependencies;

use Swiftly\Dependencies\LoaderInterface;

/**
 * Dependency management
 *
 * @author C Varley <clvarley>
 */
Class Container
{

    /**
     * The service implementations
     *
     * @var array $services Array of implementations
     */
    private $services = [];

    /**
     * Binds a service as a singleton
     *
     * @param string $name    Service name
     * @param callable|object Service implementation
     * @return void           N/a
     */
    public function bindSingleton( string $name, $implementation ) : void
    {
        $this->services[$name] = new Dependency( $implementation, true );
    }

    /**
     * Binds a service
     *
     * @param string $name    Service name
     * @param callable|object Service implementation
     * @return void           N/a
     */
    public function bindInstance( string $name, $implementation ) : void
    {
        $this->services[$name] = new Dependency( $implementation, false );
    }

    /**
     * Load services from the given dependency loader
     *
     * @param Swiftly\Dependencies\LoaderInterface $loader Dependency loader
     */
    public function loadDependencies( LoaderInterface $loader ) : void
    {
        $loader->load( $this );
    }

    /**
     * Resolve the given dependency
     *
     * @param string $name Service name
     * @return object|null Service implementation
     */
    public function resolve( string $name ) /* : ?object */
    {
        $result = null;

        if ( isset( $this->services[$name] ) ) {
            $result = $this->services[$name]->resolve( $this );
        }

        return $result;
    }

    /**
     * Check to see if a service exists
     *
     * @param string $name  Service name
     * @return bool         Service exists
     */
    public function has( string $name ) : bool
    {
        return isset( $this->services[$name] );
    }
}
