<?php

namespace Drupal\sp_control\Constant;

/**
 * Class TaskPriorityConstant.
 */
class TaskPriorityConstant {

  /**
   * Low priority.
   */
  CONST TASK_PRIORITY_LOW = 1;

  /**
   * Middle priority.
   */
  CONST TASK_PRIORITY_MIDDLE = 2;

  /**
   * Hight priority.
   */
  CONST TASK_PRIORITY_HIGHT = 4;

  /**
   * Returns human readable names
   *
   * @param integer $task_level
   *   Importance level of the task
   *
   * @return mixed
   */
  static function map($task_level = NULL) {
    $NAME_MAP_LIST = [
      self::TASK_PRIORITY_LOW => t("Low"),
      self::TASK_PRIORITY_MIDDLE => t("Middle"),
      self::TASK_PRIORITY_HIGHT => t("Hight"),
    ];

    if (isset($task_level)) {
      if (isset($NAME_MAP_LIST[$task_level])) {
        return $NAME_MAP_LIST[$task_level];
      }
      else {
        return reset($NAME_MAP_LIST);
      }
    }
    return $NAME_MAP_LIST;
  }

}