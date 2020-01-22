<?php

namespace Lexer\Token;

/**
 * Token identifying a closing tag
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