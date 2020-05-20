<?php

namespace Swiftly\Dependencies;

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
     * Resolve the given dependency
     *
     * @param string $name
     * @return
     */
    public function resolve( string $name ) /* : ?object */
    {
        $result = null;

        if ( isset( $this->services[$name] ) ) {
            $result = $this->services[$name]->resolve( $this );
        }

        return $result;
    }
}
