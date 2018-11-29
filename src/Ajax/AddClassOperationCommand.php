<?php

namespace Drupal\sp_control\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * Adds to body attribute class.
 */
class AddClassOperationCommand implements CommandInterface {

  /**
   * Name class
   *
   * @var string
   */
  protected $class_to_add;

  /**
   * {@inheritdoc}
   */
  public function __construct($class_to_add) {
    $this->class_to_add = $class_to_add;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    return [
      'command' => 'addClassOperationCommand',
      'class_add' => $this->class_to_add,
    ];
  }
}