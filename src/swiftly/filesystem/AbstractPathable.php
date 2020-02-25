<?php

namespace Swiftly\Filesystem;

/**
 * Represents a file or directory path
 *
 * @author C Varley <clvarley>
 */
Abstract Class AbstractPathable
{

    /**
     * @var string $path Path to file or directory
     */
    protected $path = '';

    /**
     * Is this a valid directory or file descriptor
     *
     * @return bool Is this path a valid file or dir?
     */
    public function isValid() : bool
    {
        return \file_exists( $this->path );
    }

    /**
     * Get the path of this directory or file
     *
     * @return string File or dir path
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get the basename for this directory or file
     *
     * @return string Basename
     */
    public function getName() : string
    {
        return \basename( $this->path );
    }

    /**
     * Is this descriptor a valid file
     *
     * @return bool Is a file
     */
    public function isFile() : bool
    {
        return ( \is_file( $this->path ) && \is_readable( $this->path ) );
    }

    /**
     * Is this descriptor a valid directory
     *
     * @return bool Is a dir
     */
    public function isDir() : bool
    {
        return \is_dir( $this->path );
    }

}
