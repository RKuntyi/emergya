<?php

namespace Drupal\ecedi_basics\abstracts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EcediAbstractBlock.
 *
 * Automatically take into account:
 * - If the block is used in a context.
 * - If the block is used in the block layout.
 * - Cache context "url" if the block use a routeMatch service.
 *
 * @property \Drupal\Core\Routing\RouteMatchInterface routeMatch
 */
abstract class EcediAbstractBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Store block ID in configuration.
    $this->configuration['block_id'] = $form['id']['#value'];
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
