<?php

namespace Drupal\sp_control\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * For reuse code.
 */
trait ManageFormTrait {

  /**
   * Clears user input.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function clearFormInput(array $form, FormStateInterface $form_state) {
    // Clear user input.
    $inputs = $form_state->getUserInput();
    $values = $form_state->getValues();
    // We should not clear the system items from the user input.
    $clean_keys = $form_state->getCleanValueKeys();
    foreach ($inputs as $key => $item) {
      if (!in_array($key, $clean_keys) && substr($key, 0, 1) !== '_') {
        unset($inputs[$key],$values[$key]);
      }
    }
    $form_state->setUserInput($inputs);
    $form_state->setValues($values);
  }

  /**
   * Sends data to  sp_control_preprocess_html.
   *
   *  @param string $class_name
   */
  public function setBodyClass($class_name) {
    $GLOBALS['SP_CONTROL_BODY_CLASSES'][] = $class_name;
  }

}
