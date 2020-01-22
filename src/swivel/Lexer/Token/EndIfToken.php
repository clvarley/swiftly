<?php

namespace Lexer\Token;

/**
 * Token identifying the end of a conditional
 */
Class EndIfToken Extends AbstractToken
{

    static $token = 'T_END_IF';

    static $regex = '\[%\s{0,}endif\s{0,}%\]';

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