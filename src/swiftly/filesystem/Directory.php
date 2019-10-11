<?php

namespace Swiftly\Filesystem;

use Swiftly\Filesystem\AbstractPathable;

/**
 * Abstraction used to represent a filesystem directory
 *
 * @author C Varley <conor@highorbit.co.uk>
 */
Class Directory Extends AbstractPathable
{

    /**
     * @var array $rawcontents Raw directory contents
     */
    private $rawcontents = [];

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
            $this->rawcontents = array_diff(scandir($this->path, null) ?: [], ['.', '..']);

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
            }, $this->rawcontents );
        } else {
            return $this->rawcontents;
        }
    }

    /**
     * Returns an array of the contents of this directory
     *
     * @return AbstractPathable[] Array of files and directories
     */
    public function getContents() : array
    {
        // REFACTOR: into private function so can be called from `getChild`

        if ( empty($this->contents) || ( count($this->contents) !== count($this->rawcontents))) {
            foreach( $this->rawcontents as $item ) {
                $this->contents[$item] = ( is_dir($this->path . '/' . $item) ? new Directory($this->path . '/' . $item) : new File($this->path . '/' . $item) );
            }
        }

        return $this->contents;
    }

    /**
     *
     *
     * @param  string $name [description]
     * @return [type]       [description]
     */
    public function getChild( string $name ) : ?Path
    {
        // TODO
    }

    /**
     * Refreshes the contents of this directory
     *
     * @return void N/a
     */
    public function update() : void
    {
        $this->rawcontents = array_diff(scandir($this->path, null) ?: [], ['.', '..']);
        $this->contents = [];

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
        } elseif ( !is_a( $directory, 'Directory' ) ) {
            throw new \InvalidArgumentException("getFilesRecursive() only accepts a string or Filesystem\Directory object", 1);
        }

        foreach( $directory->getContents() as $file_or_dir ) {
            if ( $file_or_dir->isDir() ) {
                $files = array_merge( $files, self::getRecursive( $file_or_dir ) );
            } else {
                $files[] = $file_or_dir;
            }
        }

        return $files;
    }
}