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
     *
     */
    public function bindSingleton( string $name, $implementation ) : void
    {

    }

    /**
     *
     */
    public function bindInstance( string $name, $implementation ) : void
    {

    }

    /**
     * Resolve the given dependency
     *
     * @param string $name
     * @return
     */
    public function resolve( string $name ) /* : ?object */
    {

    }

}
