<?php

namespace Swivel\Lexer\Token;

/**
 * Token identifying the start of a conditional
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class StartIfToken Extends AbstractToken
{

    static $token = 'T_START_IF';

    static $regex = '\[%\s{0,}\w+\s{0,}%\]';

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