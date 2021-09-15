<?php

namespace Drupal\cff_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CFF helps you' block.
 *
 * @Block(
 *   id = "cff_helps_you_block",
 *   admin_label = @Translation("CFF Helps you"),
 *   category = @Translation("Custom")
 * )
 */
class HelpsYouBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cff_helps_you_form';
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    \Drupal::service('page_cache_kill_switch')->trigger();
    return [
      '#theme' => "cff_helps_you_block",
      '#source' => views_embed_view('products', 'page_1'),
    ];
  }
}
