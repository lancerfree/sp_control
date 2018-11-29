<?php

namespace Drupal\sp_control\Table;

/**
 * Helps to work with users.
 */
class UsersTable {

  /**
   * Table name.
   */
  const TABLE_NAME = 'sp_control_users';

  /**
   * List available fields.
   */
  const LIST_FIELDS = ['user_id', 'name', 'created', 'updated'];

  /**
   * Adds new user to the table.
   *
   * @param string $user_name
   *   User name.
   *
   * @return bool
   *
   * @throws
   */
  static function addUser($user_name) {
    $time_now = time();

    \Drupal::database()->insert(self::TABLE_NAME)
      ->fields([
        'name',
        'created',
        'updated',
      ])
      ->values([
        $user_name,
        $time_now,
        $time_now,
      ])
      ->execute();

    return TRUE;
  }

  /**
   * Select specific user by id.
   *
   * @param integer $id_user
   *   ID user to select.
   *
   * @return array|bool
   */
  static function selectUser($id_user) {
    $database = \Drupal::database();
    return $database->select(self::TABLE_NAME, 'u')
      ->fields('u')
      ->condition('user_id', $id_user)
      ->execute()
      ->fetchAll(\PDO::FETCH_ASSOC);
  }

  /**
   * Edit username.
   *
   * @param int $user_id
   *   User id.
   * @param string $user_name
   *   Name of user.
   *
   * @return bool
   */
  static function editUser($user_id, $user_name) {
    \Drupal::database()->update(self::TABLE_NAME)
      ->fields([
        'name' => $user_name,
        'updated' => REQUEST_TIME,
      ])
      ->condition('user_id', $user_id)
      ->execute();

    return TRUE;
  }


  /**
   * Delete user with selected id from db.
   *
   * @param int $user_id
   *   User id to delete.
   *
   * @return bool
   */
  static function deleteUser($user_id) {
    $query = \Drupal::database()->delete(self::TABLE_NAME);
    $query->condition('user_id', $user_id);
    $query->execute();

    return TRUE;
  }

  /**
   * Select all Users from db.
   *
   * @return array
   */
  static function listUsers() {
    $database = \Drupal::database();
    $result = $database->select(self::TABLE_NAME, 'u')
      ->fields('u')
      ->execute()
      ->fetchAll();
    return $result;
  }

  /**
   * Return array keyed by id.
   *
   * @param array $array_users
   *   Array of objects.
   *
   * @return array
   */
  private static function convertUserList($array_users) {
    $array_users_return = [];
    foreach ($array_users AS $au_value) {
      $array_users_return[$au_value->user_id] = $au_value->name;
    }
    return $array_users_return;
  }

  /**
   * Get Normalized array of user.
   *
   * @return array
   */
  static function normalizedList() {
    $users = self::convertUserList(self::listUsers());
    return $users;
  }
}
