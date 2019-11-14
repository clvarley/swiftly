<?php

/**
 * Provides simple wrapper around autoload
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Final Class Autoloader
{

    /**
     * @var array $prefixes Namespace mappings
     */
    private $prefixes = [];

    /**
     * Registers this autoloader with spl
     */
    public function __construct()
    {
      spl_autoload_register([ $this, 'find' ]);
    }

    /**
     * Adds a new namespace - folder mapping to the autoloader
     *
     * @param string $prefix  Namespace prefix
     * @param string $path    Folder path
     */
    public function addPrefix( string $prefix, string $path ) : void
    {
      if ( '' !== $prefix && is_dir( $path ) ) {
        $this->prefixes[mb_strtolower( $prefix )] = $path;
      }
    }

    /**
     * Find the file for a class
     *
     * Used by spl_autoload_register
     *
     * @param string $class_name  Class name
     * @return bool               File found?
     */
    public function find( string $class_name ) : bool
    {
        $class_name = mb_strtolower( trim( $class_name, '\\ ' ) );

        if ( mb_strpos( $class_name, '\\') !== false ) {
            $namespace_parts = explode( '\\', $class_name );
            $class_name = array_pop( $namespace_parts );
        } else {
            $namespace_parts = [];
            $class_name = $class_name;
        }

        if ( isset( $namespace_parts[0] ) && isset( $this->prefixes[$namespace_parts[0]] ) ) {
            $route_dir = $this->prefixes[$namespace_parts[0]];
            unset( $namespace_parts[0] );
        } elseif ( isset( $this->prefixes['*'] ) ) {
            $route_dir = $this->prefixes['*'];
        } else {
            return false; // Error!
        }

        if ( is_dir( $route_dir ) ) {
            foreach ( $namespace_parts as $part ) {
                $route_dir .= $part . DIRECTORY_SEPARATOR;
                if ( !is_dir( $route_dir ) ) return false; // Missing directory!
            }

            $files = glob( $route_dir . '*.php' );

            foreach ( $files as $file ) {
                if ( mb_strtolower( basename( $file, '.php' ) ) === $class_name ) {
                    include_once $file;
                    return true; // Class file found!
                }
            }
        }

        return false; // Not found!
    }
}
