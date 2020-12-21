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
        if ( \is_dir( $path ) ) {
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

        $parts = \explode( '\\', $class_name );
        $class = \array_pop( $parts );

        // Get class vendor
        if ( !empty( $parts ) ) {
            $vendor = \strtolower( \array_shift( $parts ) );
        } else {
            $vendor = '*';
        }

        // Vendor directory override?
        if ( !empty( $this->prefixes[$vendor] ) ) {
            $dir_path = $this->prefixes[\strtolower( $vendor )];
        } else {
            $dir_path = \get_include_path();
        }

        // Attempt 1. Simple file include?
        if ( \is_file( $file = $dir_path . \implode( \DIRECTORY_SEPARATOR, $parts ) . "/$class.php" ) ) {
            require $file;
            return;
        }

        // Atempt 2. Traverse directories
        foreach ( $parts as $part ) {
            $dir_path .= $part . \DIRECTORY_SEPARATOR;
            if ( !\is_dir( $dir_path ) ) return; // Missing directory!
        }

        $files = \scandir( $dir_path, \SCANDIR_SORT_NONE );

        foreach ( $files as $file ) {
            if ( \mb_strtolower( \mb_substr( $file, 0, -4 ) ) === $class ) {
                require $dir_path . $file;
                return;
            }
        }

        return; // Not found! :(
    }
}
