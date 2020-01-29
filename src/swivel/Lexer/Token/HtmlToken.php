<?php

namespace Swivel\Lexer\Token;

/**
 * Token representing HTML markup
 *
 * @author C Varley <cvarley@highorbit.co.uk>
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