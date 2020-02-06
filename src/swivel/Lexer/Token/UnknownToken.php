<?php

namespace Swivel\Lexer\Token;

/**
 * Represents tokens that have no meaning or are unknown
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class UnknownToken Extends AbstractToken
{

    static $token = 'T_UNKNOWN';

    static $regex = '[^A-Za-z_]';

}