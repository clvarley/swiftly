<?php

/**
 * Provides simple wrapper around autoload
 *
 * @author C Varley <clvarley>
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
        \spl_autoload_register([ $this, 'find' ]);
    }

    /**
     * Adds a new namespace - folder mapping to the autoloader
     *
     * @param string $prefix  Namespace prefix
     * @param string $path    Folder path
     */
    public function addPrefix( string $prefix, string $path ) : void
    {
        if ( '' !== $prefix && \is_dir( $path ) ) {
            $this->prefixes[\mb_strtolower( $prefix )] = $path;
        }
    }

    /**
     * Find the file for a class
     *
     * Used by spl_autoload_register
     *
     * @param string $class_name  Class name
     * @return void               N/a
     */
    public function find( string $class_name ) : void
    {
        $class_name = \mb_strtolower( \trim( $class_name, '\\ ' ) );

        if ( \mb_strpos( $class_name, '\\') !== false ) {
            $namespace_parts = \explode( '\\', $class_name );
            $class_name = \array_pop( $namespace_parts );
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
            $route_dir = \get_include_path();
        }

        if ( !\is_dir( $route_dir ) ) {
            return;
        }

        foreach ( $namespace_parts as $part ) {
            $route_dir .= $part . \DIRECTORY_SEPARATOR;
            if ( !\is_dir( $route_dir ) ) return; // Missing directory!
        }

        $files = \scandir( $route_dir, \SCANDIR_SORT_NONE );

        foreach ( $files as $file ) {
            if ( \mb_strtolower( \mb_substr( $file, 0, -4 ) ) === $class_name ) {
                require $route_dir . $file;
                return; // Class file found!
            }
        }

        return; // Not found! :(
    }
}
