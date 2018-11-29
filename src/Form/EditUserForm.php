<?php

namespace Drupal\sp_control\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\sp_control\Table\UsersTable;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InsertCommand;
use Drupal\sp_control\Ajax\AddClassOperationCommand;


/**
 * Implements  EditUserForm form controller.
 */
class EditUserForm extends FormBase {

  use ManageFormTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // form wrapper
    $form['#prefix'] = '<div class="manage-users-form-wrapper" id="manage-users-form-wrapper">';
    $form['#suffix'] = '</div>';

    $df_values = $form_state->get('df_values');
    $user_id_value = $form_state->getValue('user_id');
    // Clear
    $form_state->set('df_values',NULL);

    if(!empty($df_values['user_id'])){
      $current_id = $df_values['user_id'];
    }elseif(!empty($user_id_value)){
      $current_id = $user_id_value;
    }

    // Simple Text for label.
    $selected_task_text = $current_id?$this->t('Editing a user with id: @id',['@id'=>$current_id]):$this->t('Adding a new user');

    // Show current operation
    $form['user_id_visible'] = [
      '#type' => 'item',
      '#title' => '',
      //'#description' => $this->t('ID of this user.'),
      "#markup" => '<span>' . $selected_task_text . '</span>',
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('The username must be at least 5 characters in length.'),
    ];

    $form['user_id'] = [
      '#type' => 'hidden',

    ];
    // Auxiliary elements.
    $form['delete_user_ask_id'] = [
      '#type' => 'hidden',
    ];

    $form['edit_user_ask_id'] = [
      '#type' => 'hidden',
    ];

    // Because form always rebuild need to change default value.
    if($df_values){
      foreach($df_values AS $el_name => $el_dev_value){
        $form[$el_name]['#default_value']=$el_dev_value;
      }
    }

    $form['actions'] = [
      '#type' => 'actions',
    ];
    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#submit' => ['::userSaveSubmit'],
    ];
    $form['actions']['edit_user_ask'] = [
      '#type' => 'submit',
      '#name' => 'edit_user_ask',
      '#submit' => ['::editUserAskSubmit'],
      '#attributes' => [
        'style' => 'display:none',
      ],
      '#ajax' => [
        'callback' => '::editUserAskAjaxSubmit',
        'wrapper' => 'manage-users-form-wrapper',
        'effect' => 'fade',
      ]
    ];

    $form['actions']['delete_user_ask'] = [
      '#type' => 'submit',
      '#name' => 'delete_user_ask',
      '#submit' => ['::deleteUserAskSubmit'],
      '#attributes' => [
        'style' => 'display:none',
      ],
    ];

    // Add common callback for submit
    foreach (\Drupal\Core\Render\Element::children($form['actions']) AS $name_element) {
      $form['actions'][$name_element]['#submit'][] = '::userAllSubmit';
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sp_control_edit_user_form';
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Not needed there
  }

  /**
   * User save button handler.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function userSaveSubmit(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('name');
    $userid = $form_state->getValue('user_id');
    if ($userid) {
      // Edit case.
      if ($username) {
        UsersTable::editUser($userid, $username);
        \Drupal::messenger()
          ->addMessage($this->t('Name of user with id:@id_user has been changed to name:@user_name!', [
            '@id_user' => $userid,
            '@user_name' => $username,
          ]));
        $this->setBodyClass('action-edit-user');
      }
      // Add case.
    } elseif ($username) {
      UsersTable::addUser($username);
      \Drupal::messenger()
        ->addMessage($this->t('New user @user_name has been added!', ['@user_name' => $username]));
      // Set a new form
      $this->clearFormInput($form, $form_state);
      $this->setBodyClass('action-add-user');
    }
    else {
      \Drupal::messenger()
        ->addMessage($this->t("Sorry, some unexpected error occurred."), 'error');
    }
  }

  /**
   * Ajax submit button handler.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   *
   * @return AjaxResponse
   */
  public function editUserAskAjaxSubmit(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new InsertCommand('.manage-users-form-wrapper', $form));
    // Add class to body element.
    $response->addCommand(new AddClassOperationCommand('action-edit-user'));

    return $response;
  }

  /**
   * Handler for hidden edit button.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function editUserAskSubmit(array &$form, FormStateInterface $form_state) {
    if($user_id = $form_state->getValue('edit_user_ask_id')){
      $selected_user_values= UsersTable::selectUser($user_id);
      if($selected_user_values && $el = reset($selected_user_values)){
        $this->clearFormInput($form,$form_state);

        $df_values = array_intersect_key($el,array_flip(UsersTable::LIST_FIELDS));

        $form_state->set('df_values',$df_values);
      }
    }
  }

  /**
   * Common handler for all submit buttons.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function userAllSubmit(array &$form, FormStateInterface $form_state) {
    // Explicitly set to rebuild form
    $form_state->setRebuild(TRUE);
  }

  /**
   * Delete button handler.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function deleteUserAskSubmit(array &$form, FormStateInterface $form_state) {
    if ($delete_id = $form_state->getValue('delete_user_ask_id')) {
      UsersTable::deleteUser($delete_id);
      \Drupal::messenger()
        ->addMessage($this->t('User with id:@id_user has been deleted!', ['@id_user' => $delete_id]));
      $user_id = $form_state->getValue('user_id');
      if ($delete_id == $user_id) {
        $this->clearFormInput($form, $form_state);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
