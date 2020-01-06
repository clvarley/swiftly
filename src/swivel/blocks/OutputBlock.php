<?php

namespace Swivel\Blocks;

use \Swivel\Blocks\BlockInterface;

/**
 * A simple output block
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class OutputBlock Implements BlockInterface
{

    /**
     * The content to output
     *
     * @var string $content The content
     */
    private $content = '';

    /**
     * Pass in the value(s) to be outputed
     *
     * @param string $content Content
     */
    public function __construct( string $content = '' )
    {
        $this->content = $content;
    }

    /**
     *
     */
    public function toString() : string
    {
        return '';
    }

    /**
     * 
     */
    public function isValid() : bool
    {
        return true;
    }

}
