<?php

namespace Drupal\cff_blocks\Plugin\Block;

use Drupal\ecedi_basics\abstracts\Plugin\Block\EcediAbstractBlock;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;

/**
 * Class EcediStickyMenuBlock.
 *
 * @Block(
 *   id = "cff_sticky_menu_block",
 *   admin_label = @Translation("Sticky menu block")
 * )
 */
class CFFStickyMenu extends EcediAbstractBlock implements ContainerFactoryPluginInterface {

  /**
   * Current route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    // Automatically add url cache context.
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface) {
      if (in_array($node->bundle(), ['article', 'news'])) {
        return [
          '#theme' => "cff_sticky_menu_block",
          '#attached' => [
            'library' => 'cff_blocks/scrollnav'
          ]
        ];
      }
    }
  }

}
