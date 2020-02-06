<?php

namespace Swivel\Lexer\Token;

/**
 * Token identifying an opening tag
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class StartToken Extends AbstractToken
{

    static $token = 'T_START';

    static $regex = '\[\[';

}