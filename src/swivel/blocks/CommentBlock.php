<?php

namespace Swivel\Blocks;

use \Swivel\Blocks\BlockInterface;

/**
 * A Swivel comment block, does nothing!
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class CommentBlock Implements BlockInterface
{

    /**
     * Comments require no processing!
     */
    public function __construct( string $content = '' ) {}

    /**
     * @inheritdoc
     */
    public function toString() : string
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function isValid() : bool
    {
        return true;
    }

}
