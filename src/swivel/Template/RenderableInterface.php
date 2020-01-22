<?php

namespace Template;

/**
 * Class all renderers should implement
 */
Interface RenderableInterface
{

    /**
     * Sets the file system root for this renderer
     *
     * @param string $root File path
     */
    public function setRoot( string $root ) : void;

    /**
     * Renders the given template with the data provided
     *
     * @param string $file  Template file
     * @param array  $data  (Optional) Template data
     */
    public function render( string $file, array $data = [] ) : void;

}