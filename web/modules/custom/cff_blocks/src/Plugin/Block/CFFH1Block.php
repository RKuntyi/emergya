<?php

namespace Drupal\cff_blocks\Plugin\Block;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ecedi_basics\abstracts\Plugin\Block\EcediAbstractBlock;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;

/**
 * Class EcediLastNewsBlock.
 *
 * @Block(
 *   id = "cff_h1_block",
 *   admin_label = @Translation("H1 (Rendered content)")
 * )
 */
class CFFH1Block extends EcediAbstractBlock implements ContainerFactoryPluginInterface {

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
    $results = [
      '#type' => 'html_tag',
      '#tag' => 'h1',
      '#value' => $this->routeMatch->getRouteObject()->getDefault('_title'),
      '#attributes' => [
        'class' => ['h1-title'],
      ],
    ];

    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface) {
      $view_mode = 'page_header';
      switch ($node->bundle()) {
        case'article':
        case'news':
          $results = $this->entityTypeManager->getViewBuilder('node')->view($node, $view_mode);
          break;
      }
    }
    return [$results];
  }

}
