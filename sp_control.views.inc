<?php

/**
 * Implements hook_views_data().
 */
function sp_control_views_data() {
  $data = [];
  // Tasks table.
  $data['sp_control_tasks']['table']['group'] = t('SP Control Tables');
  $data['sp_control_tasks']['table']['base'] = [
    'title' => t('Task Table'),
    'help' => t('Provide list of tasks'),
    #'query_id' => 'sp_control_tasks',
  ];

  $data['sp_control_tasks']['task_id'] = [
    'title' => t('Task id'),
    'help' => t('This help for tas id'),
    'field' => [
      'id' => 'numeric',
    ],
  ];
  $data['sp_control_tasks']['description'] = [
    'title' => t('Task description'),
    'help' => t('Main data about task'),
    'field' => [
      'id' => 'standard',
    ],
  ];
  $data['sp_control_tasks']['creator'] = [
    'title' => t('Who create id'),
    'help' => t('ID User who create task.'),
    'field' => [
      'id' => 'numeric',
    ],
    'relationship' => [
      'base' => 'sp_control_users',
      'base field' => 'user_id',
      'id' => 'standard',
      'label' => t('Binds the table of users uses creator field'),
    ],
  ];
  $data['sp_control_tasks']['implementer'] = [
    'title' => t('Implementor'),
    'help' => t('User id who must implement this task'),
    'field' => [
      'id' => 'numeric',
    ],
    'relationship' => [
      'base' => 'sp_control_users',
      'base field' => 'user_id',
      'id' => 'standard',
      'label' => t('Binds the table of users uses implementer field'),
    ],
  ];
  $data['sp_control_tasks']['priority'] = [
    'title' => t('Priority'),
    'help' => t('ID Level priority of this task.This is set defined in the class'),
    'field' => [
      'id' => 'sp_control_verbose_priority',
    ],
  ];
  $data['sp_control_tasks']['status'] = [
    'title' => t('Status'),
    'help' => t('ID Status.This set defined in class status'),
    'field' => [
      'id' => 'sp_control_verbose_status',
    ],
  ];
  $data['sp_control_tasks']['created'] = [
    'title' => t('Create time'),
    'help' => t('Timestamp when task was created.'),
    'field' => [
      'id' => 'date',
    ],
  ];
  $data['sp_control_tasks']['updated'] = [
    'title' => t('Update time'),
    'help' => t('Timestamp when task was updated.'),
    'field' => [
      'id' => 'date',
    ],
  ];

  // Users table.
  $data['sp_control_users']['table']['group'] = t('SP Control Tables');
  $data['sp_control_users']['table']['base'] = [
    'title' => t('Users table'),
    'help' => t('Stores users data who creates or implements task.'),
  ];
  $data['sp_control_users']['user_id'] = [
    'title' => t('User ID'),
    'help' => t('Numeric value of user id.'),
    'field' => [
      'id' => 'numeric',
    ],
  ];
  $data['sp_control_users']['name'] = [
    'title' => t('User name'),
    'help' => t('Username.'),
    'field' => [
      'id' => 'standard',
    ],
  ];
  $data['sp_control_users']['created'] = [
    'title' => t('Create time'),
    'help' => t('Timestamp when user was created.'),
    'field' => [
      'id' => 'date',
    ],
  ];
  $data['sp_control_users']['updated'] = [
    'title' => t('Update time'),
    'help' => t('Timestamp when user was updated.'),
    'field' => [
      'id' => 'date',
    ],
  ];

  return $data;
}