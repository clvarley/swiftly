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

    static $regex = '\[%(?:\s{1,}if)?\s{0,}\w+\s{0,}%\]';

}