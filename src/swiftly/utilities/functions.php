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
