<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 20.03.2018
 * Time: 11:51
 */

namespace Drupal\sp_control\Constant;

/**
 * Class TaskStatusConstant.
 */
class TaskStatusConstant {


  /**
   * New task is created.
   */
  CONST TASK_STATUS_NEW = 1;

  /**
   * Task is processed.
   */
  CONST TASK_STATUS_PROCESS = 2;

  /**
   * Task is ended.
   */
  CONST TASK_STATUS_END = 4;

  /**
   * Task is closed.
   */
  CONST TASK_STATUS_CLOSED = 8;

  /**
   * Return human readable names.
   *
   * @param integer $task_status
   *   Level precise selected
   *
   * @return mixed
   */
  static function map($task_status = NULL) {
    $NAME_MAP_LIST = [
      self::TASK_STATUS_NEW => t("New"),
      self::TASK_STATUS_PROCESS => t("In progress"),
      self::TASK_STATUS_END => t("Ended"),
      self::TASK_STATUS_CLOSED => t("Closed")
    ];

    if (isset($task_status)) {
      if( isset($NAME_MAP_LIST[$task_status])) {
        return $NAME_MAP_LIST[$task_status];
      }else {
        return reset($NAME_MAP_LIST);
      }
    }
    return $NAME_MAP_LIST;
  }
}