<?php

namespace Swiftly\Template;

/**
 * A null template
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Null Implements TemplateInterface
{

    /**
     * Returns an empty string
     *
     * @param string $template  Path to template
     * @param array $data       Template data
     * @return string           Empty string
     */
    public function render( string $template, array $data = [] ) : string
    {
        return '';
    }

}