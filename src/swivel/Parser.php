<?php

namespace Swivel;

use \Swivel\Blocks\{ CommentBlock, ConditionalBlock, OutputBlock };

/**
 * The parser for the Swivel engine
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Parser
{

    /**
     * The content being parsed
     *
     * @var string $content Content
     */
    private $content = '';

    /**
     * Current index
     *
     * @var int $currentIndex Index
     */
    private $currentIndex = 0;

    /**
     * Block class mappings
     *
     * @var array BLOCKS Block interfaces
     */
    private const BLOCKS = [
        'comment'     => CommentBlock::__CLASS__,
        'conditional' => ConditionalBlock::__CLASS__,
        'output'      => OutputBlock::__CLASS__
    ];

    /**
     * Simple regex to capture the delimiter
     *
     * @var string DELIMITER
     */
    private const DELIMITER = '/\[\[([^\[]{0,})\]\]/i';

    /**
     * Parses the given content
     *
     * @return void N/a
     */
    public function parse( string $content ) : void
    {
        $this->content = $content;
        $this->currentIndex = 0;

        $matches = [];

        if ( !empty( $this->content ) && preg_match_all( self::DELIMITER, $this->content, $matches ) !== false ) {

        }

        return;
    }

    /**
     * Gets the supported blocks types of this parser
     *
     * @return array Block types
     */
    private function getBlockTypes() : array
    {
        return array_keys( self::BLOCKS );
    }

    /**
     * Gets the supported block classes of this parser
     *
     * @return array Block classes
     */
    private function getBlockClasses() : array
    {
        return array_values( self::BLOCKS );
    }

    /**
     * Creates a block of the given type
     *
     * @param string $type    Block type
     * @param string $content Block content
     * @return BlockInterface Block
     */
    private function getBlock( string $type, string $content = '' ) : BlockInterface
    {
        if ( array_key_exists( $type, self::BLOCKS ) ) {
            return ( new $this->blocks[$type]( $content ) );
        }

        throw new \Exception( "No block with given type: $type, exists!", 1 );
    }

}
