<?php

namespace Drupal\emergya_products\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'emergya products' block.
 *
 * @Block(
 *   id = "emergya_products",
 *   admin_label = @Translation("Emergya products"),
 *   category = @Translation("Custom")
 * )
 */
class EmergyaProducts extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    \Drupal::service('page_cache_kill_switch')->trigger();
    return [
      '#theme' => "emergya_products",
      '#source' => views_embed_view('products', 'page_1'),
    ];
  }
}
