<?php

/**
 * @file
 * Creates a block which displays the block
 */

namespace Drupal\mydbexample\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;


/**
 * Provides the MyDBExample main block.
 *
 * @Block(
 *   id = "mydbexample_block",
 *   admin_label = @Translation("The MyDBExample Block")
 * )
 */
class MyDBExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
      '#type' => 'markup',
      '#markup' => $this->t('MyDBExample Block'),
    ];

  }


  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    // If viewing a node, get the fully loaded node object.
    $node = \Drupal::routeMatch()->getParameter('node');

    if ( !(is_null($node)) ) {
      return AccessResult::allowedIfHasPermission($account, 'view mydbexample');
    }

    return AccessResult::forbidden();
  }
}
