<?php

/**
 * @file
 * Definition of Drupal\d8views\Plugin\views\field\NodeTypeFlagger
 */

namespace Drupal\sp_control\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\NumericField;
use Drupal\views\ResultRow;
use Drupal\sp_control\Constant\TaskPriorityConstant;

/**
 * Field handler implements status of task.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("sp_control_verbose_priority")
 */
class VerbosePriorityFieldView extends NumericField {


  /**
   * @{inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['type_render'] = ['default' => 0];

    return $options;
  }

  /**
   * @{inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    $opts = $this->viewTypeMap();

    $form['type_render'] = [
      '#title' => $this->t('Type of render'),
      '#type' => 'select',
      '#default_value' => $this->options['type_render'],
      '#options' => $opts,
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $value_priority = $this->getValue($values);
    if (isset($this->options['type_render']) AND $this->options['type_render'] == 1) {
      return TaskPriorityConstant::map($value_priority);
    }
    else {
      return parent::render($values);
    }
  }

  /**
   * Returns mode list to view field.
   *
   * @return array
   */
  protected function viewTypeMap() {
    return [
      0 => $this->t('Default '),
      1 => $this->t('Verbose view of Priority'),
    ];
  }

}