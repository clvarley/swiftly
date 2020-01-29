<?php

namespace Swivel\Lexer\Token;

/**
 * Token identifying the end of a conditional
 *
 * @author C Varley <cvarley@highorbit.co.uk>
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