<?php

namespace Lexer;

use \Lexer\Token\AbstractToken;

/**
 * Simple lexer class
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
     * @var AbstractToken[] $tokens Registered tokens
     */
    private $tokens = [];

    /**
     * Stores the matches found by the regex
     *
     * @var array $matches Regex matches
     */
    private $matches = [];

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
            $regex[] = '(?P<' . $token::$token . '>' . $token::$regex . ')';
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

        return;
    }

    /**
     * Tokenizes all the input and returns an array of tokens
     *
     * @return AbstractToken[] Token stream
     */
    public function all() : array
    {
        $return = [];

        foreach ( $this->matches as $match ) {
            $return[] = $this->matchIdentifier( $match );
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

        return $this->index < count( $this->matches );
    }

    /**
     * Returns the token for the current position
     *
     * @return AbstractToken Current token
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
        $id = array_keys( $match )[ count( $match ) - 2 ];

        return ( new $this->tokens[$id]( $match[$id][0] ) );
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
            'T_END_IF'      => \Lexer\Token\EndIfToken::class,
            'T_START_IF'    => \Lexer\Token\StartIfToken::class,
            'T_START'       => \Lexer\Token\StartToken::class,
            'T_IDENTIFIER'  => \Lexer\Token\IdentifierToken::class,
            'T_END'         => \Lexer\Token\EndToken::class,
            'T_WHITESPACE'  => \Lexer\Token\WhitespaceToken::class,
            'T_UNKNOWN'     => \Lexer\Token\UnknownToken::class
        ];

        return $lexer;
    }

}