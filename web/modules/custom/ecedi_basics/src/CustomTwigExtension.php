<?php

namespace Drupal\ecedi_basics;

/**
 * Class DefaultService.
 *
 * @package Drupal\ecedi_basics
 *
 * extend Drupal's Twig_Extension class
 */
class CustomTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'ecedi_basics.customtwigextension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('staticPathDebug', [$this, 'staticPathDebug']),
    ];
  }

  /**
   * Returns Nothing.
   *
   * The purpose of this function is to prevent errors (Drupal side)
   *
   * @return string
   *   Nothing
   */
  public function staticPathDebug() {
    return '';
  }

}
