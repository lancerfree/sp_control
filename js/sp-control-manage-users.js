/**
 * @file
 * Provides user operations.
 */
(function ($, Drupal) {

  'use strict';

  Drupal.spControl = Drupal.spControl || {};

  /**
   * Inits click listener.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches spControlTasks behaviors.
   */
  Drupal.behaviors.spControlUsers = {
    attach: function (context, settings) {
      $('.sw-manage-users-wrapper .sp-control-users-action', context).on({'click': manageUserAction});
    }
  };

  /**
   * Executes action for specific link.
   *
   * @param {object} event
   */
  var manageUserAction = function (event) {
    var $userNameElement = $('.manage-users-form-wrapper [name="name"]');
    var $labelElement = $('.form-item-user-id-visible span');
    var $userIdElement = $('.manage-users-form-wrapper [name="user_id"]');
    var $userDeleteAskElement = $('.manage-users-form-wrapper [name="delete_user_ask"]');
    var $userDeleteHiddenElement = $('.manage-users-form-wrapper [name="delete_user_ask_id"]');
    var $userEditAskElement = $('.manage-users-form-wrapper [name="edit_user_ask"]');
    var $userEditHiddenElement = $('.manage-users-form-wrapper [name="edit_user_ask_id"]');
    var $eventElement = $(this);
    var href = $eventElement.attr('href');
    var nameSelectedUser;
    var matches;
    // Add case
    if (matches = href.match(/#add/i)) {
      $userNameElement.val('');
      $labelElement.text(Drupal.t('Adding a new user'));
      $userIdElement.val('');
      Drupal.spControl.bodyChangeClass('action-edit-user', 'action-add-user');
    }
    // Edit case
    else if (matches = href.match(/#edit-(\d+)/i)) {
      // Rebuild form use ajax submit buttons
      $userEditHiddenElement.val(matches[1]);
      $userEditAskElement.trigger('mousedown');
      document.location.hash = "not-exists";
      document.location.hash = "page-wrapper"
    }
    // Delete case
    else if (matches = href.match(/#delete-(\d+)/i)) {
      nameSelectedUser = $eventElement.closest('tr').find('td.views-field-name').text().trim();
      if (confirm(Drupal.t('Delete user @user_name with @id ', {
            '@user_name': nameSelectedUser,
            '@id': matches[1]
          }))) {
        $userDeleteHiddenElement.val(matches[1]);
        $userDeleteAskElement.trigger('click');
      }
    }
    else {
      console.log("Case does not exits!");
    }
    event.preventDefault();
  };
})(jQuery, Drupal);


