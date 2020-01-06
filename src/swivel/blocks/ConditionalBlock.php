<?php

namespace Swivel\Blocks;

use \Swivel\Blocks\BlockInterface;

/**
 * A conditional block
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class ConditionalBlock Implements BlockInterface
{

    /**
     * The contents of this conditional block
     *
     * @var string $contents Conditional block contents
     */
    private $contents = '';

    /**
     * Creates a new conditional block from the content provided
     *
     * @param string $content (Optional) Conditional content
     */
    public function __construct( string $content = '' )
    {
        $this->contents = $content;
    }

    /**
     * Converts this block into a string
     *
     * @return string String representation
     */
    public function toString() : string
    {
        return '';
    }

    /**
     * Is this conditional block valid?
     *
     * @return bool Block valid?
     */
    public function isValid() : bool
    {
        return true;
    }

}
