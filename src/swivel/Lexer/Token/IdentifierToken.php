<?php

namespace Swivel\Lexer\Token;

/**
 * Token identifying a variable
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class IdentifierToken Extends AbstractToken
{

    static $token = 'T_IDENTIFIER';

    static $regex = '[a-zA-Z_]+';

}