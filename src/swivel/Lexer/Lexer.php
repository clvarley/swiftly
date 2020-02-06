<?php

namespace Swivel\Lexer;

use \Swivel\Lexer\Token\AbstractToken;
use \Swivel\Lexer\LexerInterface;

/**
 * Simple lexer class
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Lexer Implements LexerInterface
{

    /**
     * The input to lex
     *
     * @var string $input Input
     */
    private $input = '';

    /**
     * Stores the regex used to lex the content
     *
     * Only populated when the `compile()` method is run
     *
     * @var string $regex Regular expression
     */
    private $regex = '';

    /**
     * Stores the tokens that have been registered
     *
     * @var \Swivel\Lexer\Token\AbstractToken[] $tokens Registered tokens
     */
    private $tokens = [];

    /**
     * Stores the matches found by the regex
     *
     * @var array $matches Regex matches
     */
    private $matches = [];

    /**
     * Count of matches made
     *
     * @var int $count Total matches
     */
    private $count = 0;

    /**
     * Current position of the reader in the input
     *
     * @var int $index Reader index
     */
    private $index = -1;

    /**
     * Register a new token with the lexer
     *
     * @param string $token Name of class implementing AbstractToken
     * @return void         N/a
     */
    public function register( string $token ) : void
    {
        if ( !$this->validToken( $token ) ) {
            throw new \Exception( "Given class: $token is not a valid token!" );
        }

        $this->tokens[ $token::$token ] = $token;
    }

    /**
     * Compiles the regex required for this lexer
     *
     * @return void N/a
     */
    public function compile() : void
    {
        if ( !empty( $this->regex ) || empty( $this->tokens ) ) {
            return;
        }

        $regex = [];

        foreach ( $this->tokens as $token ) {
            $regex[] = '(?|' . $token::$regex . '(*MARK:' . $token::$token . '))';
        }

        $this->regex = '#' . implode( '|', $regex ) . '#i';

        return;
    }

    /**
     * Consumes the input and prepares for parsing
     *
     * @param string $input [description]
     */
    public function consume( string $input ) : void
    {
        if ( empty( $this->regex ) ) {
            throw new \Exception( 'Lexer cannot consume without first being compiled!' );
        }

        $this->input = $input;

        preg_match_all( $this->regex, $this->input, $this->matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE );

        $this->count = count( $this->matches );

        return;
    }

    /**
     * Tokenizes all the input and returns an array of tokens
     *
     * @return \Swivel\Lexer\Token\AbstractToken[] Token stream
     */
    public function all() : array
    {
        $return = [];

        for ( $i = 0, $length = count( $this->matches ); $i < $length; $i++ ) {
            $return[] = $this->matchIdentifier( $this->matches[$i] );
        }

        $this->index = count( $this->matches );

        return $return;
    }

    /**
     * Move the lexer to the next position
     *
     * @return bool Not EOF?
     */
    public function next() : bool
    {
        $this->index++;

        return $this->index < $this->count;
    }

    /**
     * Returns the token for the current position
     *
     * @return \Swivel\Lexer\Token\AbstractToken Current token
     */
    public function current() : AbstractToken
    {
        return $this->matchIdentifier( $this->matches[$this->index] );
    }

    /**
     * Checks to see if the given class name is a valid token
     *
     * @param  string $classname  Name of a class
     * @return bool               Valid token
     */
    private function validToken( string $classname ) : bool
    {
        return is_subclass_of( $classname, AbstractToken::class, true );
    }

    /**
     * Matches the given regex match to it's token
     *
     * @param  array         $match REGEX matches
     * @return AbstractToken        Token
     */
    private function matchIdentifier( array $match ) : AbstractToken
    {
        return ( new $this->tokens[$match['MARK']]( $match[0][0] ) );
    }

    /**
     *  Creates a new lexer with the default tokens already registered
     *
     * @return self Default lexer
     */
    public static function fromDefault() : self
    {
        $lexer = new Lexer();

        $lexer->tokens = [
            'T_END_IF'      => \Swivel\Lexer\Token\EndIfToken::class,
            'T_START_IF'    => \Swivel\Lexer\Token\StartIfToken::class,
            'T_START'       => \Swivel\Lexer\Token\StartToken::class,
            'T_IDENTIFIER'  => \Swivel\Lexer\Token\IdentifierToken::class,
            'T_END'         => \Swivel\Lexer\Token\EndToken::class,
            'T_WHITESPACE'  => \Swivel\Lexer\Token\WhitespaceToken::class,
            'T_UNKNOWN'     => \Swivel\Lexer\Token\UnknownToken::class
        ];

        return $lexer;
    }

}