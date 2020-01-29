<?php

namespace Swivel\Parser;

use \Swivel\Lexer\Token\AbstractToken;
use \Swivel\Lexer\LexerInterface;

/**
 * The parser for the markup language
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Parser Implements ParserInterface
{

    /**
     * The lexer being used by this parser
     *
     * @var \Lexer\Lexer $lexer Lexer
     */
    private $lexer = null;

    /**
     * Current statement depth
     *
     * @var int $depth Statement depth
     */
    private $depth = 0;

    /**
     * Stack of statements we are in
     *
     * @var \Lexer\Token\AbstractToken[] $stack Token stack
     */
    private $stack = [];

    /**
     * Any data the parser needs
     *
     * @var array $data Misc data
     */
    private $data = [];

    /**
     * Set the lexer to be used by this parser
     *
     * @param LexerInterface $lexer Lexer
     */
    public function setLexer( LexerInterface $lexer ) : void
    {
        $this->lexer = $lexer;
    }

    /**
     * Get the lexer being used by this parser
     *
     * @return LexerInterface|null Lever
     */
    public function getLexer() : ?LexerInterface
    {
        return $this->lexer;
    }

    /**
     * Set data for this parser
     *
     * @param array $data Parser data
     */
    public function setData( array $data ) : void
    {
        $this->data = $data;
    }

    /**
     * Gets the data from this parser
     *
     * @return array Parser data
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Loads a source file and attempts to parse it
     *
     * @param  string $file File path
     * @return void         N/a
     */
    public function parseFile( string $file )
    {
        if ( !is_file( $file ) ) {
            throw new \Exception( "Cannot parse file: $file as it does not exist!" );
        }

        return $this->parse( file_get_contents( $file ) );
    }

    /**
     * Attempts to parse the given string
     *
     * @param  string $input  Source code
     * @return void           N/a
     */
    public function parse( string $input )
    {
        if ( is_null( $this->lexer ) ) {
            throw new \Exception( "No lexer has been set for this parser!" );
        }

        $this->lexer->consume( $input );

        while( $this->lexer->next() ) {
            $token = $this->lexer->current();

            switch( $token::$token ) {
                case 'T_START_IF':
                    $this->tokenStartIf( $token );
                break;

                case 'T_START':
                    $this->tokenStart( $token );
                break;

                case 'T_END_IF':
                    $this->tokenEndIf( $token );
                break;

                case 'T_END':
                    $this->tokenEnd( $token );
                break;

                case 'T_IDENTIFIER':
                    $this->tokenIdentifier( $token );
                break;

                case 'T_WHITESPACE':
                case 'T_UNKNOWN':
                default:
                    $this->tokenUnknown( $token );
                break;
            }
        }

        return;
    }

    /**
     * Handle T_START_IF tokens
     *
     * @param AbstractToken $token The token
     */
    private function tokenStartIf( AbstractToken $token ) : void
    {
        if ( $this->depth !== 0 && $this->stack[$this->depth - 1]::$token === 'T_START' ) {
            throw new \Exception( "Cannot start 'IF' statement inside block scope", 1 );
        }

        preg_match( '#(?:\s{1,}if)?\s{0,}(\w+)\s{0,}#', $token->content, $matches );

        if ( empty( $matches ) ) {
            throw new \Exception( "Cannot have an empty 'IF' statement", 1 );
        }

        $match = trim( $matches[1] );

        // Skip ahead if the IF fails
        if ( !$this->hasVariable( $match ) || !(bool)$this->getVariable( $match ) ) {
            while ( $this->lexer->next() && $this->lexer->current()::$token !== 'T_END_IF' );
        } else {
            $this->stack[] = $token;
        }

        return;
    }

    /**
     * Handle T_END_IF tokens
     *
     * @param AbstractToken $token The token
     */
    private function tokenEndIf( AbstractToken $token ) : void
    {
        if ( $this->depth !== 0 && $this->stack[$this->depth - 1]::$token !== 'T_START_IF' ) {
            throw new \Exception( "Cannot 'ENDIF' when not in IF statement", 1 );
        }

        return;
    }

    /**
     * Handle T_UNKNOWN and T_WHITESPACE tokens
     *
     * @param AbstractToken $token The token
     */
    private function tokenUnknown( AbstractToken $token ) : void
    {
        if ( $this->depth !== 0 ) return;

        echo $token->content;

        return;
    }

    /**
     * Handle T_IDENTIFIER tokens
     *
     * @param AbstractToken $token The token
     */
    private function tokenIdentifier( AbstractToken $token ) : void
    {
        $content = $token->content;

        if ( $this->depth === 0 ) {
            echo $content;
        } elseif ( $this->hasVariable( $content ) ) {
            echo $this->getVariable( $content );
        } else {
            throw new \Exception( "Template cannot access variable '$content' as it doesn't exist!" );
        }

        return;
    }


    /**
     * Handle T_START tokens
     *
     * @param AbstractToken $token The token
     */
    private function tokenStart( AbstractToken $token ) : void
    {
        $this->depth++;
        $this->stack[] = $token;

        return;
    }

    /**
     * Handle T_END tokens
     *
     * @param AbstractToken $token The token
     */
    private function tokenEnd( AbstractToken $token ) : void
    {
        $this->depth--;
        array_pop( $this->stack );

        return;
    }

    /**
     * Tries to get a variable as string from the data store
     *
     * @param  string $name Variable name
     * @return string|null  Variable value
     */
    private function getVariable( string $name ) : ?string
    {
        return ( isset( $this->data[$name] ) ? $this->data[$name] : null );
    }

    /**
     * Checks if the given variable exists in the store
     *
     * @param  string $name Variable name
     * @return bool         Exists
     */
    private function hasVariable( string $name ) : bool
    {
        return isset( $this->data[$name] );
    }

}