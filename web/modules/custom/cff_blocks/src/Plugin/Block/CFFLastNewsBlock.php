<?php

namespace Drupal\cff_blocks\Plugin\Block;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\ecedi_basics\abstracts\Plugin\Block\EcediAbstractBlock;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EcediLastNewsBlock.
 *
 * @Block(
 *   id = "cff_last_news_block",
 *   admin_label = @Translation("CFF Last News")
 * )
 */
class CFFLastNewsBlock extends EcediAbstractBlock implements ContainerFactoryPluginInterface {

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
    $query = $this->entityTypeManager
      ->getStorage('node')
      ->getQuery()
      ->condition('type', 'news', '=')
      ->condition('status', 1, '=')
      ->condition('sticky', 1, '=')
      ->sort('created', 'DESC')
      ->range(0, 3);
    $nids = $query->execute();

    if ($nids) {
      $nodes =  $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
      $results = $this->entityTypeManager->getViewBuilder('node')->viewMultiple($nodes, 'teaser');
    }

    return [$results];
  }

}
