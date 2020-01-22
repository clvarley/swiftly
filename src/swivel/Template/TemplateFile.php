<?php

namespace Template;

Class TemplateFile
{

    /**
     * Path to template file
     *
     * @var string $file File path
     */
    private $file = '';

    /**
     * Creates a wrapper for a template file
     *
     * @param string $filer File path
     */
    public function __construct( string $file )
    {
        $this->file = $file;
    }

    /**
     * Gets the name of this template file
     *
     * @return string File name
     */
    public function getName() : string
    {
        return basename( $this->file );
    }

    /**
     * Gets the path of this template file
     *
     * @return string File path
     */
    public function getPath() : string
    {
        return $this->file;
    }

    /**
     * Checks if the file exists and is readable
     *
     * @return bool Is valid?
     */
    public function isValid() : bool
    {
        return ( is_file( $this->file ) && is_readable( $this->file ) );
    }

    /**
     * Gets the contents of this template file
     *
     * @throws \Exception On invalid template file
     * @return string     File contents
     */
    public function load() : string
    {
        if ( !$this->isValid() ) {
            throw new \Exception( "Tried to load template: $this->file but file is unreadable!" );
        }

        return file_get_contents( $this->file );
    }
}