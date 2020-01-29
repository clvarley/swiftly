<?php

namespace Swivel\Lexer;

use \Swivel\Lexer\Token\AbstractToken;

/**
 * The interface all Lexers should implement
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Interface LexerInterface
{

    /**
     * Compiles this lexer in preperation for parsing
     *
     * @return void N/a
     */
    public function compile() : void;

    /**
     * Consumes and lexes the given source code
     *
     * @param string $input Source code
     * @return void         N/a
     */
    public function consume( string $input ) : void;

    /**
     * Increments the internal pointer and checks if there is another token
     *
     * @return bool Token available
     */
    public function next() : bool;

    /**
     * Gets the token at the current position
     *
     * @return AbstractToken Current token
     */
    public function current() : AbstractToken;

}