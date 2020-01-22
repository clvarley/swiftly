<?php

namespace Lexer\Token;

/**
 * Token identifying a variable
 */
Class IdentifierToken Extends AbstractToken
{

    static $token = 'T_IDENTIFIER';

    static $regex = '[a-zA-Z_]+';

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