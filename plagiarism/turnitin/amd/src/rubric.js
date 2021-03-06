/**
 * Javascript controller for handling rubrics.
 *
 * @package   turnitin
 * @copyright Turnitin
 * @author 2019 David Winn <dwinn@turnitin.com>
 * @module plagiarism_turnitin/rubric
 */

define(['jquery',
        'core/templates',
        'core/modal_factory',
        'core/modal_events',
        'plagiarism_turnitin/modal_rubric_manager_launch',
        'plagiarism_turnitin/modal_rubric_view_launch'
    ],
    function($, Templates, ModalFactory, ModalEvents, ModalRubricManagerLaunch, ModalRubricViewLaunch) {
        return {
            rubric: function() {
                var that = this;
                $('.rubric_manager_launch').on('click', function() {
                    that.rubricCreateModal(ModalRubricManagerLaunch.TYPE);
                });

                $(document).on('click', '.rubric_view', function() {
                    that.rubricCreateModal(ModalRubricViewLaunch.TYPE);
                });

                // Show warning when changing the rubric linked to an assignment.
                $('#id_plagiarism_rubric').mousedown(function () {
                    if ($('input[name="instance"]').val() != '' && $('input[name="rubric_warning_seen"]').val() != 'Y') {
                        if (confirm(M.str.plagiarism_turnitin.changerubricwarning)) {
                            $('input[name="rubric_warning_seen"]').val('Y');
                        }
                    }
                });
            },
            rubricCreateModal: function(modalType) {
                if ($('input[name="coursemodule"]').val()) {
                    var cmid = $('input[name="coursemodule"]').val();
                } else {
                    var urlParams = new URLSearchParams(window.location.search);
                    var cmid = urlParams.get('id');
                }

                ModalFactory.create({
                    type: modalType,
                    templateContext: {
                        cmid: cmid,
                        wwwroot: M.cfg.wwwroot
                    },
                    large: true
                })
                    .then(function (modal) {
                        modal.show();
                        modal.getRoot().find('.modal').addClass('tii_pp_modal_rubric');
                        modal.getRoot().find('.modal-content').addClass('tii_pp_modal_rubric_content');
                    });
            }
        };
    });