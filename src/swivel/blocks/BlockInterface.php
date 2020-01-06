<?php

namespace Swivel\Blocks;

/**
 * The interface for all blocks in the Swivel engine
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Interface BlockInterface
{

    /**
     * Provide the block with it's content
     *
     * @param string $content   (Optional) Block content
     */
    public function __construct( string $content = '' );

    /**
     * Convert the block to a string
     *
     * @return string String representation
     */
    public function toString() : string;

    /**
     * Is this block valid?
     *
     * @return bool Valid block?
     */
    public function isValid() : bool;

}
