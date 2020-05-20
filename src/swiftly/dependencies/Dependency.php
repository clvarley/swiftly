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
        $result = null;

        if ( is_callable( $this->implementation ) ) {
            $callback = $this->implementation;
            $result = $callback( $container );
        } elseif ( is_object( $this->implementation ) ) {
            $result = $this->implementation;
        } elseif ( is_string( $this->implementation ) && class_exists( $this->implementation ) ) {
            $result = new $this->implementation;
        }

        if ( $this->is_singleton ) {
            $this->implementation = $result;
            $this->is_singleton = false;
        }

        return $result;
    }
}
