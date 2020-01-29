<?php

namespace Swivel\Template;

/**
 * Class all renderers should implement
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Interface RenderableInterface
{

    /**
     * Renders the given template with the data provided
     *
     * @param string $file  Template file
     * @param array  $data  (Optional) Template data
     * @return mixed
     */
    public function render( string $file, array $data = [] );

}