// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * AJAX helper for the tag management page.
 *
 * @module     core/tag
 * @package    core_tag
 * @copyright  2015 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      3.0
 */
define(['jquery', 'core/ajax', 'core/templates', 'core/notification', 'core/str', 'core/yui', 'core/pending'],
        function($, ajax, templates, notification, str, Y, Pending) {
    return /** @alias module:core/tag */ {

        /**
         * Initialises tag index page.
         *
         * @method initTagindexPage
         */
        initTagindexPage: function() {
            // Click handler for changing tag type.
            $('body').delegate('.tagarea[data-ta] a[data-quickload=1]', 'click', function(e) {
                var pendingPromise = new Pending('core/tag:initTagindexPage');

                e.preventDefault();
                var target = $(this);
                var query = target[0].search.replace(/^\?/, '');
                var tagarea = target.closest('.tagarea[data-ta]');
                var args = query.split('&').reduce(function(s, c) {
                      var t = c.split('=');
                      s[t[0]] = decodeURIComponent(t[1]);
                      return s;
                    }, {});

                ajax.call([{
                    methodname: 'core_tag_get_tagindex',
                    args: {tagindex: args}
                }])[0]
                .then(function(data) {
                    return templates.render('core_tag/index', data);
                })
                .then(function(html, js) {
                    templates.replaceNode(tagarea, html, js);
                    return;
                })
                .always(pendingPromise.resolve)
                .catch(notification.exception);
            });
        },

        /**
         * Initialises tag management page.
         *
         * @method initManagePage
         */
        initManagePage: function() {
            // Set cell 'time modified' to 'now' when any of the element is updated in this row.
            $('body').on('updated', '[data-inplaceeditable]', function(e) {
                var pendingPromise = new Pending('core/tag:initManagePage');

                str.get_strings([
                    {
                        key: 'selecttag',
                        component: 'core_tag',
                    },
                    {
                        key: 'now',
                        component: 'core',
                    },
                ])
                .then(function(result) {
                    $('label[for="tagselect' + e.ajaxreturn.itemid + '"]').html(result[0]);
                    $(e.target).closest('tr').find('td.col-timemodified').html(result[1]);

                    return;
                })
                .always(pendingPromise.resolve)
                .catch(notification.exception);

                if (e.ajaxreturn.itemtype === 'tagflag') {
                    var row = $(e.target).closest('tr');
                    if (e.ajaxreturn.value === '0') {
                        row.removeClass('flagged-tag');
                    } else {
                        row.addClass('flagged-tag');
                    }
                }
            });

            // Confirmation for single tag delete link.
            $('.tag-management-table').delegate('a.tagdelete', 'click', function(e) {
                var pendingPromise = new Pending('core/tag:tagdelete');

                e.preventDefault();
                var href = $(this).attr('href');
                str.get_strings([
                    {key: 'delete', component: 'core'},
                    {key: 'confirmdeletetag', component: 'tag'},
                    {key: 'yes', component: 'core'},
                    {key: 'no', component: 'core'},
                ])
                .then(function(s) {
                    return notification.confirm(s[0], s[1], s[2], s[3], function() {
                        window.location.href = href;
                    });
                })
                .always(pendingPromise.resolve)
                .catch(notification.exception);
            });

            // Confirmation for bulk tag delete button.
            $("#tag-management-delete").click(function(e) {
                var form = $(this).closest('form').get(0);

                var cnt = $(form).find("input[type=checkbox]:checked").length;
                if (!cnt) {
                    return;
                }

                var pendingPromise = new Pending('core/tag:tag-management-delete');
                var tempElement = $("<input type='hidden'/>").attr('name', this.name);
                e.preventDefault();
                str.get_strings([
                    {key: 'delete', component: 'core'},
                    {key: 'confirmdeletetags', component: 'tag'},
                    {key: 'yes', component: 'core'},
                    {key: 'no', component: 'core'},
                ])
                .then(function(s) {
                    return notification.confirm(s[0], s[1], s[2], s[3], function() {
                        tempElement.appendTo(form);
                        form.submit();
                    });
                })
                .always(pendingPromise.resolve)
                .catch(notification.exception);
            });

            // Confirmation for bulk tag combine button.
            $("#tag-management-combine").click(function(e) {
                var pendingPromise = new Pending('core/tag:tag-management-combine');

                e.preventDefault();
                var form = $(this).closest('form').get(0);
                var tags = $(form).find("input[type=checkbox]:checked");

                if (tags.length <= 1) {
                    str.get_strings([
                        {key: 'combineselected', component: 'tag'},
                        {key: 'selectmultipletags', component: 'tag'},
                        {key: 'ok'},
                    ])
                    .then(function(s) {
                        return notification.alert(s[0], s[1], s[2]);
                    })
                    .always(pendingPromise.resolve)
                    .catch(notification.exception);

                    return;
                }

                var tempElement = $("<input type='hidden'/>").attr('name', this.name);
                str.get_strings([
                    {key: 'combineselected', component: 'tag'},
                    {key: 'selectmaintag', component: 'tag'},
                    {key: 'continue'},
                    {key: 'cancel'},
                ])
                .then(function(s) {
                    var el = $('<div><form id="combinetags_form">' +
                        '<div class="description"></div><div class="form-group options"></div>' +
                        '<div class="form-group">' +
                        '   <input type="submit" class="btn btn-primary" id="combinetags_submit"/>' +
                        '   <input type="button" class="btn btn-secondary" id="combinetags_cancel"/>' +
                        '</div>' +
                        '</form></div>');
                    el.find('.description').html(s[1]);
                    el.find('#combinetags_submit').attr('value', s[2]);
                    el.find('#combinetags_cancel').attr('value', s[3]);
                    var fldset = el.find('.options');
                    tags.each(function() {
                        var tagid = $(this).val(),
                            tagname = $('.inplaceeditable[data-itemtype=tagname][data-itemid=' + tagid + ']').attr('data-value');
                        var option = '<div class="form-check">' +
                            '   <input type="radio" class="form-check-input" name="maintag" ' +
                            '       id="combinetags_maintag_' + tagid + '" value="' + tagid + '"/>' +
                            '   <label class="form-check-label" for="combinetags_maintag_' + tagid + '">' + tagname + '</label>' +
                            '</div>';
                        fldset.append($(option));
                    });
                    // TODO: MDL-57778 Convert to core/modal.
                    Y.use('moodle-core-notification-dialogue', function() {
                        var panel = new M.core.dialogue({
                            draggable: true,
                            modal: true,
                            closeButton: true,
                            headerContent: s[0],
                            bodyContent: el.html()
                        });
                        panel.show();
                        $('#combinetags_form input[type=radio]').first().focus().prop('checked', true);
                        $('#combinetags_form #combinetags_cancel').on('click', function() {
                            panel.destroy();
                        });
                        $('#combinetags_form').on('submit', function() {
                            tempElement.appendTo(form);
                            var maintag = $('input[name=maintag]:checked', '#combinetags_form').val();
                            $("<input type='hidden'/>").attr('name', 'maintag').attr('value', maintag).appendTo(form);
                            form.submit();
                            return false;
                        });
                    });

                    return;
                })
                .always(pendingPromise.resolve)
                .catch(notification.exception);
            });

            // When user changes tag name to some name that already exists suggest to combine the tags.
            $('body').on('updatefailed', '[data-inplaceeditable][data-itemtype=tagname]', function(e) {
                var exception = e.exception; // The exception object returned by the callback.
                var newvalue = e.newvalue; // The value that user tried to udpated the element to.
                var tagid = $(e.target).attr('data-itemid');
                if (exception.errorcode === 'namesalreadybeeingused') {
                    var pendingPromise = new Pending('core/tag:tag-management-combine-exists');

                    e.preventDefault(); // This will prevent default error dialogue.
                    str.get_strings([
                        {key: 'confirm', component: 'core'},
                        {key: 'nameuseddocombine', component: 'tag'},
                        {key: 'yes', component: 'core'},
                        {key: 'cancel', component: 'core'},
                    ])
                    .then(function(s) {
                        return notification.confirm(s[0], s[1], s[2], s[3], function() {
                            window.location.href = window.location.href + "&newname=" + encodeURIComponent(newvalue) +
                                "&tagid=" + encodeURIComponent(tagid) +
                                '&action=renamecombine&sesskey=' + M.cfg.sesskey;
                        });
                    })
                    .always(pendingPromise.resolve)
                    .catch(notification.exception);
                }
            });

            // Form for adding standard tags.
            $('body').on('click', 'a[data-action=addstandardtag]', function(e) {
                var pendingPromise = new Pending('core/tag:addstandardtag');
                e.preventDefault();

                str.get_strings([
                    {key: 'addotags', component: 'tag'},
                    {key: 'inputstandardtags', component: 'tag'},
                    {key: 'continue', component: 'core'},
                    {key: 'cancel', component: 'core'},
                ])
                .then(function(s) {
                    var el = $('<div><form id="addtags_form" method="POST">' +
                        '<input type="hidden" name="action" value="addstandardtag"/>' +
                        '<input type="hidden" name="sesskey" value="' + M.cfg.sesskey + '"/>' +
                        '<div class="form-group">' +
                        '   <label for="id_tagslist">' + s[1] + '</label>' +
                        '   <input type="text" id="id_tagslist" class="form-control" name="tagslist"/>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '   <input type="submit" class="btn btn-primary" id="addtags_submit"/>' +
                        '   <input type="button" class="btn btn-secondary" id="addtags_cancel"/>' +
                        '</div>' +
                        '</form></div>');
                    el.find('#addtags_form').attr('action', window.location.href);
                    el.find('#addtags_submit').attr('value', s[2]);
                    el.find('#addtags_cancel').attr('value', s[3]);
                    // TODO: MDL-57778 Convert to core/modal.
                    Y.use('moodle-core-notification-dialogue', function() {
                        var panel = new M.core.dialogue({
                            draggable: true,
                            modal: true,
                            closeButton: true,
                            headerContent: s[0],
                            bodyContent: el.html()
                        });
                        panel.show();
                        $('#addtags_form input[type=text]').focus();
                        $('#addtags_form #addtags_cancel').on('click', function() {
                            panel.destroy();
                        });
                        pendingPromise.resolve();
                    });
                })
                .catch(notification.exception);
            });
        },

        /**
         * Initialises tag collection management page.
         *
         * @method initManageCollectionsPage
         */
        initManageCollectionsPage: function() {
            $('body').on('updated', '[data-inplaceeditable]', function(e) {
                var pendingPromise = new Pending('core/tag:initManageCollectionsPage-updated');

                var ajaxreturn = e.ajaxreturn,
                    areaid, collid, isenabled;
                if (ajaxreturn.component === 'core_tag' && ajaxreturn.itemtype === 'tagareaenable') {
                    areaid = $(this).attr('data-itemid');
                    $(".tag-collections-table ul[data-collectionid] li[data-areaid=" + areaid + "]").hide();
                    isenabled = ajaxreturn.value;
                    if (isenabled === '1') {
                        $(this).closest('tr').removeClass('dimmed_text');
                        collid = $(this).closest('tr').find('[data-itemtype="tagareacollection"]').attr("data-value");
                        $(".tag-collections-table ul[data-collectionid=" + collid + "] li[data-areaid=" + areaid + "]").show();
                    } else {
                        $(this).closest('tr').addClass('dimmed_text');
                    }
                }
                if (ajaxreturn.component === 'core_tag' && ajaxreturn.itemtype === 'tagareacollection') {
                    areaid = $(this).attr('data-itemid');
                    $(".tag-collections-table ul[data-collectionid] li[data-areaid=" + areaid + "]").hide();
                    collid = $(this).attr('data-value');
                    isenabled = $(this).closest('tr').find('[data-itemtype="tagareaenable"]').attr("data-value");
                    if (isenabled === "1") {
                        $(".tag-collections-table ul[data-collectionid=" + collid + "] li[data-areaid=" + areaid + "]").show();
                    }
                }

                pendingPromise.resolve();
            });

            $('body').on('click', '.addtagcoll > a', function(e) {
                var pendingPromise = new Pending('core/tag:initManageCollectionsPage-addtagcoll');

                e.preventDefault();
                var href = $(this).attr('data-url') + '&sesskey=' + M.cfg.sesskey;
                str.get_strings([
                    {key: 'addtagcoll', component: 'tag'},
                    {key: 'name'},
                    {key: 'searchable', component: 'tag'},
                    {key: 'create'},
                    {key: 'cancel'},
                ])
                .then(function(s) {
                    var el = $('<div><form id="addtagcoll_form">' +
                        '<div class="form-group">' +
                        '   <label for="addtagcoll_name"></label> ' +
                        '   <input id="addtagcoll_name" type="text" class="form-control"/>  ' +
                        '</div>' +
                        '<div class="form-group form-check">' +
                        '   <input id="addtagcoll_searchable" type="checkbox" value="1" checked class="form-check-input"/>' +
                        '   <label for="addtagcoll_searchable" class="form-check-label"></label>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '   <input type="submit" class="btn btn-primary" id="addtagcoll_submit"/>' +
                        '   <input type="button" class="btn btn-secondary" id="addtagcoll_cancel"/>' +
                        '</div>' +
                        '</form></div>');
                    el.find('label[for="addtagcoll_name"]').html(s[1]);
                    el.find('label[for="addtagcoll_searchable"]').html(s[2]);
                    el.find('#addtagcoll_submit').attr('value', s[3]);
                    el.find('#addtagcoll_cancel').attr('value', s[4]);
                    // TODO: MDL-57778 Convert to core/modal.
                    Y.use('moodle-core-notification-dialogue', function() {
                        var panel = new M.core.dialogue({
                            draggable: true,
                            modal: true,
                            closeButton: true,
                            headerContent: s[0],
                            bodyContent: el.html()
                        });
                        panel.show();
                        $('#addtagcoll_form #addtagcoll_name').focus();
                        $('#addtagcoll_form #addtagcoll_cancel').on('click', function() {
                            panel.destroy();
                        });
                        $('#addtagcoll_form').on('submit', function() {
                            var name = $('#addtagcoll_form #addtagcoll_name').val();
                            var searchable = $('#addtagcoll_form #addtagcoll_searchable').prop('checked') ? 1 : 0;
                            if (String(name).length > 0) {
                                window.location.href = href + "&name=" + encodeURIComponent(name) + "&searchable=" + searchable;
                            }
                            return false;
                        });
                        pendingPromise.resolve();
                    });
                })
                .catch(notification.exception);
            });

            $('body').on('click', '.tag-collections-table .action_delete', function(e) {
                var pendingPromise = new Pending('core/tag:initManageCollectionsPage-action_delete');

                e.preventDefault();
                var href = $(this).attr('data-url') + '&sesskey=' + M.cfg.sesskey;
                str.get_strings([
                    {key: 'delete'},
                    {key: 'suredeletecoll', component: 'tag', param: $(this).attr('data-collname')},
                    {key: 'yes'},
                    {key: 'no'},
                ])
                .then(function(s) {
                    return notification.confirm(s[0], s[1], s[2], s[3], function() {
                        window.location.href = href;
                    });
                })
                .always(pendingPromise.resolve)
                .catch(notification.exception);
            });
        }
    };
});
