<?php

namespace Swivel\Lexer\Token;

/**
 * Token representing any whitespace
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class WhitespaceToken Extends AbstractToken
{

    static $token = 'T_WHITESPACE';

    static $regex = '\s';

}