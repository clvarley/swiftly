<?php

namespace Swiftly\Template;

/**
 * Interface for renderable templates
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Interface TemplateInterface
{

  /**
   * Renders the given template optionally using the data provided
   *
   * @param string $template  Path to template
   * @param array $data       Template data
   * @return string           Rendered output
   */
  public function render( string $template, array $data = [] ) : string;

}
