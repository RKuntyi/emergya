/**
 * @file
 * CFF blocks Sticky content menu JS behavior.
 */

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.cff_blocks_scrollnav = {
    attach: function (context, settings) {
      $('.node__content', context).once('scroolnav-processed').each(function () {
        const content = document.querySelector('.node__content');
        const insertTarget = document.querySelector('#sticky-menu-block');
        scrollnav.init(content, {
          debug: false,
          insertTarget: insertTarget,
          insertLocation: 'append'
        });
      });
    }
  }

})(jQuery, Drupal);
