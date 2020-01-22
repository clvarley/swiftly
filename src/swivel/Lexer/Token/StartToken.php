<?php

namespace Lexer\Token;

/**
 * Token identifying an opening tag
 */
Class StartToken Extends AbstractToken
{

    static $token = 'T_START';

    static $regex = '\[\[';

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