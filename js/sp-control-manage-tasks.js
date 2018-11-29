/**
 * @file
 * Provides task operations.
 */
(function ($, Drupal) {

  'use strict';

  Drupal.spControl = Drupal.spControl || {};

  /**
   * Init click listener.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches spControlTasks behaviors.
   */
  Drupal.behaviors.spControlTasks = {
    attach: function (context, settings) {
      $('.sp_control_tasks_wrapper .sp-control-tasks-action', context).on({'click': manageTaskAction});
    }
  };

  /**
   * Clear form and prepared for a new task.
   */
  var setTaskNew = function () {
    var $labelOperations = $('.add_task_id');

    var $form = $('.sp-control-edit-task-form');
    var $selects = $form.find('select');
    var $textareas = $form.find('textarea');
    // To defaults
    $selects.each(function (i) {
      $(this).find('option:eq(0)').prop('selected', true);
    });
    $textareas.val('');

    $labelOperations.html(Drupal.t('Adding a new task'));
    //Hide edit button and show add button
    $('input[data-drupal-selector="edit-edit-save-submit"]').hide();
    $('input[data-drupal-selector="edit-add-save-submit"]').show();

    Drupal.spControl.bodyChangeClass('action-edit-task', 'action-add-task');
  };

  /**
   * Executes action for a specific link.
   *
   * @param {object} event
   */
  var manageTaskAction = function (event) {
    var $deleteTaskAskButton = $('input[name=delete_task_ask]');
    var $editTaskAskButton = $('input[name=edit_task_ask]');
    var $deleteHidden = $('input[name=delete_task_ask_id]');
    var $editHidden = $('input[name=edit_task_ask_id]');
    var $el = $(this);
    // Extract data
    var $dataHref = manageParseActionData($el.attr('href'));
    switch ($dataHref.type) {
      case 'add_task' :
        setTaskNew();
        // Move to top
        document.location.hash = "not-exists";
        document.location.hash = "page-wrapper";
        break;
      case 'edit_task' :
        $editHidden.val($dataHref.params[0]);
        $editTaskAskButton.trigger('mousedown');
        break;
      case 'delete_task' :
        var confirmAnswer = confirm(Drupal.t('Are you sure that you want to delete this task?'));
        if (confirmAnswer) {
          $deleteHidden.val($dataHref.params[0]);
          $deleteTaskAskButton.trigger('click');
        }
        break;
      default:

        break;
    }
    event.preventDefault();
  };

})(jQuery, Drupal);


