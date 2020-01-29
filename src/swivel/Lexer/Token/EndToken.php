<?php

namespace Swivel\Lexer\Token;

/**
 * Token identifying a closing tag
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class EndToken Extends AbstractToken
{

    static $token = 'T_END';

    static $regex = '\]\]';

    private $content = '';

    public function __construct( string $content )
    {
        $this->content = $content;
    }

    public function getContent() : string
    {
        return $this->content;
    }

}