<?php

/**
 * {@inheritdoc}
 */
namespace Drupal\cff_blocks\Plugin\Block;

use Drupal\ecedi_basics\abstracts\Plugin\Block\EcediAbstractBlock;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;


/**
 * Class CffNewsHighlight.
 *
 * @Block(
 *   id = "cff_news_highligh_block",
 *   admin_label = @Translation("Dernière actualité promue en home page")
 * )
 */
class CffNewsHighlight extends EcediAbstractBlock implements ContainerFactoryPluginInterface {

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
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
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
      ->condition('promote', 1, '=')
      ->sort('created', 'DESC')
      ->range(0, 3);
    $nids = $query->execute();

    if ($nids) {
      $news =  $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
      foreach ($news as $news_entity) {
        $results[] = [
          $this->entityTypeManager->getViewBuilder('node')->view($news_entity, 'news_hp_highlight'),
          $news_entity->toLink($this->t('Read more'), $rel = 'canonical',  $options = [])->toRenderable()
        ];
      }
    }
    return [$results];
  }
}
