<?php

namespace Swiftly\Config;

/**
 * Represents a collection of config values
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Config
{

  /**
   * @var array $settings Config settings
   */
  private $settings = [];

  /**
   * Construct config object
   *
   * @param array $settings Settings
   */
  public function __construct( array $settings = [] )
  {
    $this->settings = $settings;
  }

  /**
   * Check to see if a config value exists
   *
   * @param string $setting Setting name
   * @return bool           Setting exists?
   */
  public function hasValue( string $setting ) : bool
  {
    return array_key_exists(mb_strtolower($setting), $this->settings);
  }

  /**
   * Get a config value
   *
   * @param string $setting Setting name
   * @return mixed          Setting value
   */
  public function getValue( string $setting ) // : mixed
  {
    return ( isset($this->settings[mb_strtolower($setting)]) ? $this->settings[mb_strtolower($setting)] : null );
  }

  /**
   * Create a config object from a JSON file
   *
   * @static
   *
   * @param string $filepath  Path to JSON file
   * @return Config           Config object
   */
  public static function fromJson( string $filepath ) : Config
  {
    $values = [];

    if ( is_file($filepath) && ( $values = file_get_contents($filepath) ) !== false ) {

      $values = json_decode($values, true);

      if ( json_last_error() !== JSON_ERROR_NONE ) {
        $values = [];
      }
    }

    return ( new Config($values) );
  }

}
