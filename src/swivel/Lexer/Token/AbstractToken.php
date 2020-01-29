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
     * @var string TOKEN Token ID
     */
    static $token = '';

    /**
     * The regex used by this token
     *
     * @static
     * @var string REGEX Regular expression
     */
    static $regex = '';

    /**
     * Creates the token from the content provided
     *
     * @abstract
     * @param string $content Content
     */
    abstract public function __construct( string $content );

    /**
     * Gets the content of this token
     *
     * @abstract
     * @return string Content
     */
    abstract public function getContent() : string;

}