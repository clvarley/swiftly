<?php

namespace Swiftly\Filesystem;

use \Swiftly\Filesystem\AbstractPathable;

/**
 * Abstraction used to represent a filesystem directory
 *
 * @author C Varley <conor@highorbit.co.uk>
 */
Class Directory Extends AbstractPathable
{

    /**
     * @var array $contents Abstracted directory contents
     */
    private $contents = [];

    /**
     * Construct a Directory wrapper around the given directory
     *
     * @param string $dirpath [description]
     */
    public function __construct( string $dirpath = '' )
    {
        $dirpath = rtrim( $dirpath, "\t\n\r\0\x0B\\/ " );

        if ( is_dir( $dirpath ) ) {

            $this->path = $dirpath;
            $this->contents = array_flip(array_diff(scandir($this->path, null) ?: [], ['.', '..']));

        }
    }

    /**
     * Gets the raw names of all the files in this directory
     *
     * By default only returns relative names, use `$full_path` to get
     * absolute filesystem paths.
     *
     * @param bool $full_path   Return the absolute path
     * @return array            Directory contents
     */
    public function getRawContents( bool $full_path = false ) : array
    {
        if ( $full_path ) {
            return array_map( function( $path ) {
                return $this->path . '/' . $path;
            }, array_keys($this->contents) );
        } else {
            return array_keys($this->contents);
        }
    }

    /**
     * Returns an array of the contents of this directory
     *
     * @return AbstractPathable[] Array of files and directories
     */
    public function getContents() : array
    {
        foreach ( $this->contents as $index => $value ) {
            if ( !is_object($value) ) {
                $this->contents[$index] = $this->getType( $index );
            }
        }

        return $this->contents;
    }

    /**
     * Does this directory contain the named child
     *
     * @param string $name Child name
     * @return boolean     Child exists?
     */
    public function hasChild( string $name )
    {
        return array_key_exists($name, $this->contents) ;
    }

    /**
     * Gets a file or child directory by name
     *
     * @param  string $name         Directory or file name
     * @return Directory|File|null  File, directory or null
     */
    public function getChild( string $name ) : ?AbstractPathable
    {
        if ( array_key_exists($name, $this->contents) ) {
            return ( is_object($this->contents[$name]) ? $this->contents[$name] : $this->getType($name) );
        } else {
            return null;
        }
    }

    /**
     * Gets the parent directory
     *
     * @return Directory|null   Parent directory or null
     */
    public function getParent() : ?Directory
    {
        return ( is_dir($parent = dirname($this->path)) ? new Directory( $parent ) : null );
    }

    /**
     * Refreshes the contents of this directory
     *
     * @return void N/a
     */
    public function update() : void
    {
        $this->rawcontents = array_flip(array_diff(scandir($this->path, null) ?: [], ['.', '..']));

        return;
    }

    /**
     * Recursively gets all the files from this directory and child directories
     *
     * @static
     * @param Directory|string $directory   Parent directory
     * @return array                        Array of files
     */
    public static function getFilesRecursive( $directory ) : array
    {
        $files = [];

        if ( is_string($directory) ) {
            $directory = new Directory( $directory );
        } elseif ( !is_a( $directory, 'Swiftly\Filesystem\Directory' ) ) {
            throw new \InvalidArgumentException("getFilesRecursive() only accepts a string or Filesystem\Directory object", 1);
        }

        foreach( $directory->getContents() as $file_or_dir ) {
            if ( $file_or_dir->isDir() ) {
                $files = array_merge( $files, self::getFilesRecursive( $file_or_dir ) );
            } else {
                $files[] = $file_or_dir;
            }
        }

        return $files;
    }

    /**
     * Works out if the given path is a file or directory
     *
     * @param string $relative_path     Path to file or dir
     * @return AbstractPathable         File or directory object
     */
    private function getType( string $relative_path ) : AbstractPathable
    {
        if ( is_dir($real_path = ($this->path . '/' . $relative_path)) ) {
            return ( new Directory($real_path) );
        } else {
            return ( new File($real_path) );
        }
    }
}