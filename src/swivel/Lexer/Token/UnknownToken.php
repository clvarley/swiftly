<?php

namespace Lexer\Token;

/**
 * Represents tokens that have no meaning or are unknown
 */
Class UnknownToken Extends AbstractToken
{

    static $token = 'T_UNKNOWN';

    static $regex = '[^A-Za-z_]';

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