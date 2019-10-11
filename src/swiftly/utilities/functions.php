<?php

/**
 * Utility functions
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */

/**
 * Uppercases the first letter of the string
 *
 * @param string $subject Subject string
 * @return string Result
 */
function mb_ucfirst( string $subject ) : string
{
  if ( mb_strlen($subject) ) {
    $subject = ( mb_strtoupper(mb_substr($subject, 0, 1)) . mb_substr($subject, 1) );
  }

  return $subject;
}

/**
 * Lowercases the first letter of the string
 *
 * @param string $subject Subject string
 * @return string Result
 */
function mb_lcfirst( string $subject ) : string
{
  if ( mb_strlen($subject) ) {
    $subject = ( mb_strtolower(mb_substr($subject, 0, 1)) . mb_substr($subject, 1) );
  }

  return $subject;
}

/**
 * Formats a human readable string for this number of bytes
 *
 * @param int $bytes Number of bytes
 * @return string    Formatted bytes
 */
function format_bytes( int $bytes ) : string
{
    $iterations = 4;

    while( $bytes > 1024 && $iterations !== 0 ) {
        $bytes = $bytes / 1024;
        $iterations--;
    }

    switch ( $iterations ) {
        case 4:
            $formatted = sprintf('%.2f %s', $bytes, 'tb');
        break;

        case 3:
            $formatted = sprintf('%.2f %s', $bytes, 'gb');
        break;

        case 2:
            $formatted = sprintf('%.2f %s', $bytes, 'mb');
        break;

        case 1:
            $formatted = sprintf('%.2f %s', $bytes, 'kb');
        break;

        default:
            $formatted = sprintf('%d %s', $bytes, 'bytes');
        break;
    }

    return $formatted;
}
