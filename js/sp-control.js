/**
 * @file
 * Provides common operations.
 */
(function ($, Drupal) {

  'use strict';

  /**
   * Namespace for module sp_control.
   *
   * @type {Object}
   */
  Drupal.spControl = Drupal.spControl || {};

  if (Drupal.AjaxCommands) {
    //Adds Custom Ajax command
    Drupal.AjaxCommands.prototype.addClassOperationCommand = function (ajax, response, status) {
      Drupal.spControl.bodyChangeClass('action-add-task', response.class_add);
    }
  }

  /**
   * Changes class attribute body tag.
   *
   * @param {string} removeClass
   *   Name class to remove.
   * @param {string} addClass
   *   Name class to add.
   */
  Drupal.spControl.bodyChangeClass = function (removeClass, addClass) {
    var $body = $('body');
    if (typeof removeClass !== 'undefined') {
      $body.removeClass(removeClass);
    }
    if (typeof addClass !== 'undefined') {
      $body.addClass(addClass);
    }
  };

})(jQuery, Drupal);
