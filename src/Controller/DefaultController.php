<?php

namespace Drupal\sp_control\Controller;

use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Controller\ControllerBase;
use Drupal\views\Views;

/**
 * Default controller.
 */
class DefaultController extends ControllerBase {

  /**
   * Route callback for users and tasks operation.
   *
   * @param string $route_param
   *   Route parameter.
   *
   * @return array
   */
  public function getOperationTab($route_param) {
    switch ($route_param) {

      /* Manage users case */
      case 'manage-users';
        $lines['#attached']['library'][] = 'sp_control/manage_users';
        // Wrapper for user
        $lines += [
          '#prefix' => '<div class="sw-manage-users-wrapper">',
          '#suffix' => '</div>',
        ];

        $form = \Drupal::formBuilder()
          ->getForm('\Drupal\sp_control\Form\EditUserForm');
        $lines[] = $form;

        $view = $this->getView('sp_control_users', 'block_1');
        $lines[] = $view;

        return $lines;

      /* Manage task case */
      case 'manage-tasks' :
        $lines['#attached']['library'][] = 'sp_control/manage_tasks';
        // Wrapper for tasks
        $lines += [
          '#prefix' => '<div class="sp_control_tasks_wrapper" id="sp_control_tasks_wrapper">',
          '#suffix' => '</div>',
        ];

        $form = \Drupal::formBuilder()
          ->getForm('\Drupal\sp_control\Form\EditTaskForm');
        $lines[] = $form;

        $view = $this->getView('sp_control_tasks', 'block_1');
        $lines[] = $view;

        return $lines;

      /* Default case */
      default :
        return ['#markup' => $this->t('This route not exists!'),];
    }
  }

  /**
   * Returns view.
   *
   * @param string $view_name
   *   View name.
   * @param string $display_name
   *   Display name.
   *
   * @return array|NULL
   */
  public function getView($view_name, $display_name) {
    $view = Views::getView($view_name);
    if (is_object($view)) {
      $view->setDisplay($display_name);
      $view->preExecute();
      $view->execute();
      return $view->buildRenderable();
    }
    return NULL;
  }

}
