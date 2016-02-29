<?php

/**
 * @file
 * Contains \Drupal\Tests\uuid_condition\Unit\UnitTestableUuidCondition.
 */

namespace Drupal\Tests\uuid_condition\Unit;

use Drupal\uuid_condition\Plugin\Condition\UuidCondition;

class UnitTestableUuidCondition extends UuidCondition
{

  /**
   * Mocked node.
   *
   * @var \Drupal\node\Entity\Node|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $mockNode;

  /**
   * Overwrite the constructor as we are not testing Drupal's plugin system.
   */
  public function __construct() {
    $this->mockNode = NULL;
  }

  /**
   * Setter for $mockNode
   *
   * @param \Drupal\node\Entity\Node $mockNode
   */
  public function setMockContextNode($mockNode) {
    $this->mockNode = $mockNode;
  }

  /**
   * Overrides ContextAware getContextValue() to return various mocked contexts.
   *
   * @return anything
   */
  public function getContextValue($context) {
    switch ($context) {
      case 'node':
        if ($this->mockNode) {
          return $this->mockNode;
        }
      default:
        return NULL;
    }
  }

}
