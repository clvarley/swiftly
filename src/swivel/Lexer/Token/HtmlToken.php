<?php

namespace Lexer\Token;

/**
 * Token representing HTML markup
 */
Class HtmlToken Extends AbstractToken
{

    static $token = 'T_HTML';

    static $regex = '';

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