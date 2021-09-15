<?php

namespace Drupal\cff_blocks\Plugin\Block;

use Drupal\ecedi_basics\abstracts\Plugin\Block\EcediAbstractBlock;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Class CFFDiscoveryBlock.
 *
 * @Block(
 *   id = "cff_discovery_block",
 *   admin_label = @Translation("Discovery")
 * )
 */
class CFFDiscoveryBlock extends EcediAbstractBlock implements ContainerFactoryPluginInterface {

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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    // Automatically add url cache context.
    $this->routeMatch = $route_match;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $results = [];
    $vid = 'discovery';
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadByProperties(['vid' => $vid]);
    if ($terms) {
      $results = $this->entityTypeManager->getViewBuilder('taxonomy_term')->viewMultiple($terms, 'teaser');
    }

    return [$results];
  }

}
