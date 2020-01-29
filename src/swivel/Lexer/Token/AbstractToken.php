<?php

namespace Swivel\Lexer\Token;

/**
 * Abstract class all tokens should inherit from
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Abstract Class AbstractToken
{

    /**
     * Name of your token
     *
     * @static
     * @var string $token Token ID
     */
    static $token = '';

    /**
     * The regex used by this token
     *
     * @static
     * @var string $regex Regular expression
     */
    static $regex = '';

    /**
     * The content of this token
     *
     * @var string $content Token content
     */
    public $content = '';

    /**
     * Sets the content for this token
     *
     * @param string $content Token content
     */
    public function __construct( string $content )
    {
        $this->content = $content;
    }

}