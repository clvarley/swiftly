<?php

namespace Swivel\Parser;

use \Lexer\Token\AbstractToken;
use \Lexer\LexerInterface;

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
            echo '<pre>';
            var_dump( $this->lexer->current() );
            echo '</pre>';
            // $this->handleToken( $this->lexer->current() );
        }

        return;
    }

    /**
     * Handle an individual token
     *
     * @param  AbstractToken $token Token
     * @return void                 N/a
     */
    private function handleToken( AbstractToken $token )
    {
        switch( $token::$token ) {
            case 'T_START_IF':
            case 'T_START':
                array_push( $this->stack, $token );
                $this->depth++;
            break;

            case 'T_END_IF':
            case 'T_END':
                array_pop( $this->stack );
                $this->depth--;
            break;

            case 'T_IDENTIFIER':
                $var = $token->getContent();

                if ( $this->depth === 0 ) {
                    echo $var;
                } else if (( $val = $this->getVariable( $var )) !== null ) {
                    echo $val;
                } else {
                    throw new \Exception( "Template cannot access variable '$var' as it doesn't exist!" );
                }
            break;

            case 'T_WHITESPACE':
            case 'T_UNKNOWN':
            default:
                if ( $this->depth !== 0 ) break;
                echo $token->getContent();
            break;
        }

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
        return ( isset( $this->data[$name] ) && is_scalar( $this->data[$name] ) ? (string)$this->data[$name] : null );
    }

}