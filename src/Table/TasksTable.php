<?php

namespace Drupal\sp_control\Table;

/**
 * Helps to work with tasks.
 */
class TasksTable {

  /**
   * Table name.
   */
  const TABLE_NAME = 'sp_control_tasks';

  /**
   * List available fields.
   */
  const LIST_FIELDS = [
    'task_id',
    'description',
    'creator',
    'implementer',
    'priority',
    'status',
    'created',
    'updated',
  ];


  /**
   * Add new task to the table.
   *
   * @param array $data_task
   *   Data.
   *
   * @return bool|array
   *
   * @throws
   */
  static function addTask(array $data_task) {

    foreach (self::LIST_FIELDS AS $lf_key => $lf_value) {
      if (isset($data_task[$lf_value])) {
        $assoc_fields[$lf_value] = $data_task[$lf_value];
      }
    }
    // Exit because data dont exist
    if (!isset($assoc_fields)) {
      return NULL;
    }

    $time_now = time();
    if (!isset($assoc_fields['created'])) {
      $assoc_fields['created'] = $time_now;
    }
    if (!isset($assoc_fields['updated'])) {
      $assoc_fields['updated'] = $time_now;
    }

    \Drupal::database()->insert(self::TABLE_NAME)
      ->fields($assoc_fields)
      ->execute();
    return TRUE;

  }

  /**
   * Edits selected row in tasks table .
   *
   * @param int $id_task
   *   ID task to edit
   * @param array $data_task
   *   New data task
   *
   * @return bool|array
   */
  static function updateTask($id_task, array $data_task) {

    foreach (self::LIST_FIELDS AS $lf_key => $lf_value) {
      if (isset($data_task[$lf_value])) {
        $assoc_fields[$lf_value] = $data_task[$lf_value];
      }
    }

    // Exit because data dont exist
    if (!isset($assoc_fields)) {
      return NULL;
    }

    $time_now = time();
    if (!isset($assoc_fields['updated'])) {
      $assoc_fields['updated'] = $time_now;
    }

    \Drupal::database()->update(self::TABLE_NAME)
      ->fields($assoc_fields)
      ->condition('task_id', $id_task)
      ->execute();

    return TRUE;
  }

  /**
   * Delete tasks by id.
   *
   * @param integer $id_task
   *   ID task to delete.
   *
   * @return bool|array
   */
  static function deleteTask($id_task) {
    $query = \Drupal::database()->delete(self::TABLE_NAME);
    $query->condition('task_id', $id_task);
    $query->execute();
    return TRUE;
  }

  /**
   * Select specific task by id .
   *
   * @param integer $id_task
   *   ID task to select.
   *
   * @return bool|array
   */
  static function selectTask($id_task) {
    $database = \Drupal::database();
    return $database->select(self::TABLE_NAME, 'ts')
      ->fields('ts')
      ->condition('task_id', $id_task)
      ->execute()
      ->fetchAll(\PDO::FETCH_ASSOC);
  }

}