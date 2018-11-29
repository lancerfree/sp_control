<?php

namespace Drupal\sp_control\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\sp_control\Table\TasksTable;
use Drupal\sp_control\Table\UsersTable;
use Drupal\sp_control\Constant\TaskPriorityConstant;
use Drupal\sp_control\Constant\TaskStatusConstant;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InsertCommand;
use Drupal\sp_control\Ajax\AddClassOperationCommand;


/**
 * Implements the Manage Task Form.
 */
class EditTaskForm extends FormBase {

  use ManageFormTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $edit_id = NULL) {
    // Get data for select element in the form.
    $list_user = UsersTable::normalizedList();
    $list_priority = TaskPriorityConstant::map();
    $list_status = TaskStatusConstant::map();
    // Wrapper for Ajax
    $form['#prefix'] = '<div id="manage-tasks-form-wrapper" class="manage-tasks-form-wrapper">';
    $form['#suffix'] = '</div>';

    $df_values = $form_state->get('df_values');
    $task_id_value = $form_state->getValue('task_id');
    // Clear
    $form_state->set('df_values', NULL);

    if (!empty($df_values['task_id'])) {
      $current_id = $df_values['task_id'];
    }
    elseif (!empty($task_id_value)) {
      $current_id = $task_id_value;
    }

    // Simple Text for label.
    $selected_task_text = $current_id ? $this->t('Editing a task with id: @id', ['@id' => $current_id]) : $this->t('Adding a new task');

    $form['task_id'] = [
      '#type' => 'hidden',
    ];

    $form['task_id_visible'] = [
      '#type' => 'item',
      '#title' => $this->t('Operations'),
      //'#description' => $this->t('ID of this user.'),
      "#markup" => '<span class="add_task_id">' . $selected_task_text . '</span>',
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t('Please enter description of task.'),
    ];

    $form['creator'] = [
      '#type' => 'select',
      '#title' => $this->t('Creator of task'),
      '#options' => $list_user,
      '#description' => $this->t('Please enter who create this task.'),
      #  '#default_value' =>
    ];

    $form['implementer'] = [
      '#type' => 'select',
      '#title' => $this->t('Implementer of task'),
      '#description' => $this->t('Please enter who will implement this task.'),
      '#options' => $list_user,
    ];

    $form['priority'] = [
      '#type' => 'select',
      '#title' => $this->t('Priority of task'),
      '#description' => $this->t('Please enter priority ot the task.'),
      '#options' => $list_priority,
    ];

    $form['status'] = [
      '#type' => 'select',
      '#title' => $this->t('Status of task'),
      '#description' => $this->t('Please enter status ot the task.'),
      '#options' => $list_status,
    ];

    // Because form always rebuild need to change default value.
    if ($df_values) {
      foreach ($df_values AS $el_name => $el_dev_value) {
        $form[$el_name]['#default_value'] = $el_dev_value;
      }
    }
    // Auxiliary elements.
    $form['delete_task_ask_id'] = [
      '#type' => 'hidden',
    ];

    $form['edit_task_ask_id'] = [
      '#type' => 'hidden',
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['edit_save_submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save task'),
      '#submit' => ['::taskAllSubmit', '::editTaskSaveSubmit'],
    ];

    $form['actions']['add_save_submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save new task'),
      '#submit' => ['::taskAllSubmit', '::addTaskSaveSubmit'],
    ];

    if ($current_id) {
      $form['actions']['add_save_submit']['#attributes']['style'] = 'display:none';
      // This is for styling.
      $this->setBodyClass('action-edit-task');
    }
    else {
      $form['actions']['edit_save_submit']['#attributes']['style'] = 'display:none';
      $this->setBodyClass('action-add-task');
    }

    $default_submit_hidden = [
      '#type' => 'submit',
      '#attributes' => [
        'style' => 'display:none',
      ],
      '#submit' => ['::taskAllSubmit'],
    ];

    $form['actions']['add_task_ask'] = $default_submit_hidden + ['#name' => 'add_task_ask'];
    $form['actions']['add_task_ask']['#submit'][] = '::addTaskAskSubmit';

    $form['actions']['edit_task_ask'] = $default_submit_hidden + ['#name' => 'edit_task_ask'];
    //$form['actions']['edit_task_ask']['#submit'][] = '::editTaskAskSubmit';
    $form['actions']['edit_task_ask']['#submit'][] = '::editTaskAskSubmit';
    // Ajax element
    $form['actions']['edit_task_ask']['#ajax'] = [
      'callback' => '::editTaskAskAjaxSubmit',
      'wrapper' => 'manage-tasks-form-wrapper',
      'effect' => 'fade',
    ];

    $form['actions']['delete_task_ask'] = $default_submit_hidden + ['#name' => 'delete_task_ask'];
    $form['actions']['delete_task_ask']['#submit'][] = '::deleteTaskAskSubmit';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sp_control_edit_task_form';
  }

  /**
   * Common submit handler for all submit button.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function taskAllSubmit(array &$form, FormStateInterface $form_state) {
    // Explicitly set to rebuild form
    $form_state->setRebuild(TRUE);
  }

  /**
   * Implements a form submit handler.
   *
   * Return part of the form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   *
   * @return AjaxResponse
   */
  public function editTaskAskAjaxSubmit(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new InsertCommand('#manage-tasks-form-wrapper', $form));
    // Add class to body element.
    $response->addCommand(new AddClassOperationCommand('action-edit-task'));

    return $response;
  }

  /**
   * Implements a form submit handler.
   *
   * Rebuild form for add task.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function addTaskAskSubmit(array &$form, FormStateInterface $form_state) {
    $this->clearFormInput($form, $form_state);
    // $form_state->set('df_values',array_fill_keys(SWDBTasks::LIST_FIELDS,""));
  }

  /**
   * Implements a form submit handler.
   *
   * Saves a new task and clears form
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function addTaskSaveSubmit(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $intersect = array_intersect_key($values, array_flip(array_slice(TasksTable::LIST_FIELDS, 1)));
    TasksTable::addTask($intersect);
    \Drupal::messenger()
      ->addMessage($this->t('New task "@description" has been added!', ['@description' => $intersect['description']]));
    $this->clearFormInput($form, $form_state);
    // $form_state->set('df_values',array_fill_keys(SWDBTasks::LIST_FIELDS,""));
  }

  /**
   * Implements a form submit handler.
   *
   * Rebuilds form for an edit task.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function editTaskAskSubmit(array &$form, FormStateInterface $form_state) {
    if ($current_id = $form_state->getValue('edit_task_ask_id')) {
      $selected_task_values = TasksTable::selectTask($current_id);
      if ($selected_task_values && $el = reset($selected_task_values)) {
        $this->clearFormInput($form, $form_state);

        $df_values = array_intersect_key($el, array_flip(TasksTable::LIST_FIELDS));

        $form_state->set('df_values', $df_values);
      }
    }
  }

  /**
   * Implements a form submit handler.
   *
   * Updates edited task.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function editTaskSaveSubmit(array &$form, FormStateInterface $form_state) {
    if ($task_id = $form_state->getValue('task_id')) {
      $values = $form_state->getValues();
      $fields = TasksTable::LIST_FIELDS;
      array_shift($fields);
      $intersect = array_intersect_key($values, array_flip(array_slice(TasksTable::LIST_FIELDS, 1)));
      TasksTable::updateTask($task_id, $intersect);
      \Drupal::messenger()
        ->addMessage($this->t('Task with id:@id! edited!', ['@id' => $task_id]));
    }
  }

  /**
   * Implements a form submit handler.
   *
   * Deletes task by id and clears form if need.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function deleteTaskAskSubmit(array &$form, FormStateInterface $form_state) {
    if ($delete_id = $form_state->getValue('delete_task_ask_id')) {
      TasksTable::deleteTask($delete_id);
      $task_id = $form_state->getValue('task_id');
      if ($delete_id == $task_id) {
        \Drupal::messenger()
          ->addMessage($this->t('Task with id:@id! deleted!', ['@id' => $delete_id]), 'error');
        $this->clearFormInput($form, $form_state);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // validation not needed for this project
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
