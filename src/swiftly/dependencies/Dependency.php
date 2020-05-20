<?php

namespace Swiftly\Dependencies;

/**
 * Wraps a dependency in the dependency container
 *
 * @author C Varley <clvarley>
 */
Class Dependency
{

    /**
     * Is this dependency a singleton?
     *
     * @var bool $is_singleton Is singleton?
     */
    private $is_singleton = false;

    /**
     * Actual implementation of this dependency
     *
     * @var callable|object $implementation Dependency implementation
     */
    private $implementation = null;

    /**
     * Creates a new dependency
     *
     * @param callable|object $implementation Dependency implementation
     * @param bool $is_singleton              (Optional) Is singleton
     */
    public function __construct( $implementation, bool $is_singleton = false )
    {
        $this->implementation = $implementation;
        $this->is_singleton = $is_singleton;
    }

    /**
     * Resolve this dependency
     *
     * @param Swiftly\Dependencies\Container $container Dependency container
     * @return object                                   Resolved dependency
     */
    public function resolve( Container $container ) /* : object */
    {
        return;
    }

}
