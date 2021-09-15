<?php

namespace Drupal\cff_blocks\Plugin\Block;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\ecedi_basics\abstracts\Plugin\Block\EcediAbstractBlock;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Sign up section' block.
 *
 * @Block(
 *   id = "cff_signup_block",
 *   admin_label = @Translation("Sign up section"),
 *   category = @Translation("Custom")
 * )
 */
class SignUpSectionBlock extends EcediAbstractBlock implements ContainerFactoryPluginInterface {

  /**
   * Current route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    // Automatically add url cache context.
    $this->routeMatch = $route_match;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];
    $storage = $this->entityTypeManager->getStorage('block');
    $block_ids = ['login_block', 'hp_right_column'];
    foreach ($block_ids as $block_id) {
      $block = $storage->load($block_id);
      if (!empty($block)) {
        $output[$block_id] = $this->entityTypeManager->getViewBuilder('block')->view($block);
      }
    }
    return $output;
  }

}
