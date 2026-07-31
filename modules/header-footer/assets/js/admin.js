(function ($) {
    'use strict';

    var data      = window.bdeaHFData || {};
    var grouped   = data.conditions || {};

    // Open create modal
    $(document).on('click', '.bdea-hf-create-btn', function () {
        var type = $(this).data('type');
        if (!type) return;
        $('#bdea-hf-create-modal').find('.bdea-hf-modal-type-label').text(type.charAt(0).toUpperCase() + type.slice(1));
        $('#bdea-hf-create-modal input[name="type"]').val(type);
        if (type === 'footer') {
            $('#bdea-hf-create-modal input[name="sticky"]').closest('.bdea-hf-field').hide();
            $('#bdea-hf-create-modal input[name="scroll_animation"]').closest('.bdea-hf-field').hide();
        } else {
            $('#bdea-hf-create-modal input[name="sticky"]').closest('.bdea-hf-field').show();
            $('#bdea-hf-create-modal input[name="scroll_animation"]').closest('.bdea-hf-field').show();
        }
        $('#bdea-hf-create-modal').show();
    });

    // Close modals
    $(document).on('click', '.bdea-hf-modal-close, .bdea-hf-modal-overlay, [data-close-modal]', function (e) {
        if ($(e.target).hasClass('bdea-hf-modal-overlay') || $(e.target).hasClass('bdea-hf-modal-close') || $(e.target).attr('data-close-modal') !== undefined) {
            $('.bdea-hf-modal-overlay').hide();
        }
    });

    // Submit create template
    $(document).on('click', '.bdea-hf-create-submit', function () {
        var $btn  = $(this);
        var $form = $('#bdea-hf-create-form');
        var data  = $form.serializeArray();

        data.push({ name: 'nonce', value: $btn.data('nonce') });
        data.push({ name: 'action', value: 'bdea_hf_create_template' });

        $btn.prop('disabled', true).text('Creating...');

        $.post(ajaxurl, data, function (res) {
            if (res.success && res.data.edit_url) {
                window.location.href = res.data.edit_url;
            } else {
                alert(res.data.message || 'Error creating template.');
                $btn.prop('disabled', false).text('Create & Edit with Elementor');
            }
        }).fail(function () {
            alert('Request failed.');
            $btn.prop('disabled', false).text('Create & Edit with Elementor');
        });
    });

    // Edit conditions
    $(document).on('click', '.bdea-hf-edit-cond', function () {
        var postId = $(this).data('id');
        var $modal = $('#bdea-hf-conditions-modal');
        var $list  = $modal.find('.bdea-hf-conditions-list');

        $modal.find('input[name="template_id"]').val(postId);
        $list.html('<p style="text-align:center;color:#8c8f94;padding:20px;">Loading...</p>');
        $modal.show();

        $.post(ajaxurl, {
            action:   'bdea_hf_get_template_conditions',
            post_id:  postId,
            nonce:    $('.bdea-hf-conditions-save').data('nonce')
        }, function (res) {
            if (res.success && res.data.conditions) {
                $list.empty();
                var conds = res.data.conditions;
                if (conds.length === 0) {
                    addConditionRow($list, { type: 'include', condition: '' }, 0);
                } else {
                    $.each(conds, function (i, cond) {
                        addConditionRow($list, cond, i);
                    });
                }
                $modal.find('input[name="disable_theme"]').prop('checked', res.data.disable_theme === 'yes');
                $modal.find('input[name="sticky"]').prop('checked', res.data.sticky === 'yes');
                $modal.find('input[name="scroll_animation"]').prop('checked', res.data.scroll_animation === 'yes');

                var dev = res.data.device_visibility || {};
                $modal.find('input[name="device_desktop"]').prop('checked', dev.desktop !== '');
                $modal.find('input[name="device_tablet"]').prop('checked', dev.tablet !== '');
                $modal.find('input[name="device_mobile"]').prop('checked', dev.mobile !== '');

                // Show/hide header-only options
                if (res.data.type === 'footer') {
                    $modal.find('input[name="sticky"]').closest('.bdea-hf-field-inline').hide();
                    $modal.find('input[name="scroll_animation"]').closest('.bdea-hf-field-inline').hide();
                } else {
                    $modal.find('input[name="sticky"]').closest('.bdea-hf-field-inline').show();
                    $modal.find('input[name="scroll_animation"]').closest('.bdea-hf-field-inline').show();
                }
            } else {
                $list.html('<p style="text-align:center;color:#8c8f94;padding:20px;">No conditions loaded.</p>');
            }
        });
    });

    // Add condition row
    $(document).on('click', '.bdea-hf-add-condition-row', function () {
        var $list = $(this).closest('.bdea-hf-modal-body').find('.bdea-hf-conditions-list');
        var idx   = $list.find('.bdea-hf-condition-row').length;
        addConditionRow($list, { type: 'include', condition: '' }, idx);
    });

    function addConditionRow($list, cond, idx) {
        var optionsHtml = buildOptions(cond.condition || '');
        var row = $(
            '<div class="bdea-hf-condition-row">' +
            '<select name="conditions[' + idx + '][type]">' +
            '<option value="include"' + (cond.type === 'include' ? ' selected' : '') + '>Include</option>' +
            '<option value="exclude"' + (cond.type === 'exclude' ? ' selected' : '') + '>Exclude</option>' +
            '</select> ' +
            '<select name="conditions[' + idx + '][condition]">' +
            optionsHtml +
            '</select> ' +
            '<button type="button" class="bdea-hf-remove-condition">\u2715</button>' +
            '</div>'
        );
        $list.append(row);
    }

    $(document).on('click', '.bdea-hf-remove-condition', function () {
        $(this).closest('.bdea-hf-condition-row').remove();
    });

    // Save conditions
    $(document).on('click', '.bdea-hf-conditions-save', function () {
        var $btn   = $(this);
        var $modal = $('#bdea-hf-conditions-modal');
        var postId = $modal.find('input[name="template_id"]').val();
        var conds  = [];

        $modal.find('.bdea-hf-condition-row').each(function () {
            var type = $(this).find('select[name^="conditions["][name$="[type]"]').val();
            var cond = $(this).find('select[name^="conditions["][name$="[condition]"]').val();
            if (cond) {
                conds.push({ type: type, condition: cond });
            }
        });

        $btn.prop('disabled', true).text('Saving...');

        var disableTheme    = $modal.find('input[name="disable_theme"]').is(':checked') ? 'yes' : '';
        var sticky          = $modal.find('input[name="sticky"]').is(':checked') ? 'yes' : '';
        var scrollAnimation = $modal.find('input[name="scroll_animation"]').is(':checked') ? 'yes' : '';
        var devDesktop      = $modal.find('input[name="device_desktop"]').is(':checked') ? 'yes' : '';
        var devTablet       = $modal.find('input[name="device_tablet"]').is(':checked') ? 'yes' : '';
        var devMobile       = $modal.find('input[name="device_mobile"]').is(':checked') ? 'yes' : '';

        $.post(ajaxurl, {
            action:          'bdea_hf_update_conditions',
            post_id:         postId,
            conditions:      conds,
            disable_theme:   disableTheme,
            sticky:          sticky,
            scroll_animation: scrollAnimation,
            device_desktop:  devDesktop,
            device_tablet:   devTablet,
            device_mobile:   devMobile,
            nonce:           $btn.data('nonce')
        }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert(res.data.message || 'Error saving conditions.');
                $btn.prop('disabled', false).text('Save Conditions');
            }
        }).fail(function () {
            alert('Request failed.');
            $btn.prop('disabled', false).text('Save Conditions');
        });
    });

    // Clear multi-select
    $(document).on('click', '.bdea-hf-clear-select', function () {
        var name = $(this).data('target');
        $(this).closest('.bdea-hf-field').find('select[name="' + name + '[]"] option').prop('selected', false);
    });

    // Duplicate template
    $(document).on('click', '.bdea-hf-duplicate-btn', function () {
        var $btn   = $(this);
        var postId = $btn.data('id');

        $btn.prop('disabled', true).text('...');

        $.post(ajaxurl, {
            action: 'bdea_hf_duplicate_template',
            post_id: postId,
            nonce:  $btn.data('nonce')
        }, function (res) {
            if (res.success && res.data.edit_url) {
                window.location.href = res.data.edit_url;
            } else {
                alert(res.data.message || 'Error duplicating template.');
                $btn.prop('disabled', false).text('Duplicate');
            }
        }).fail(function () {
            alert('Request failed.');
            $btn.prop('disabled', false).text('Duplicate');
        });
    });

    // Export template
    $(document).on('click', '.bdea-hf-export-btn', function () {
        var $btn   = $(this);
        var postId = $btn.data('id');

        $btn.prop('disabled', true).text('...');

        $.post(ajaxurl, {
            action: 'bdea_hf_export_template',
            post_id: postId,
            nonce:  $btn.data('nonce')
        }, function (res) {
            if (res.success && res.data.export) {
                var blob  = new Blob([JSON.stringify(res.data.export, null, 2)], { type: 'application/json' });
                var url   = URL.createObjectURL(blob);
                var a     = document.createElement('a');
                a.href    = url;
                a.download = (res.data.export.title || 'template') + '.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                $btn.prop('disabled', false).text('Export');
            } else {
                alert(res.data.message || 'Export failed.');
                $btn.prop('disabled', false).text('Export');
            }
        }).fail(function () {
            alert('Request failed.');
            $btn.prop('disabled', false).text('Export');
        });
    });

    // Open import modal
    $(document).on('click', '.bdea-hf-import-btn', function () {
        $('#bdea-hf-import-modal').show();
    });

    // Submit import
    $(document).on('click', '.bdea-hf-import-submit', function () {
        var $btn   = $(this);
        var $form  = $('#bdea-hf-import-form');
        var file   = $form.find('input[type="file"]')[0].files[0];

        if (!file) { alert('Select a .json file.'); return; }

        var fd = new FormData();
        fd.append('action', 'bdea_hf_import_template');
        fd.append('nonce',  $btn.data('nonce'));
        fd.append('import_file', file);

        $btn.prop('disabled', true).text('Importing...');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success && res.data.edit_url) {
                    window.location.href = res.data.edit_url;
                } else {
                    alert(res.data.message || 'Import failed.');
                    $btn.prop('disabled', false).text('Import Template');
                }
            },
            error: function () {
                alert('Request failed.');
                $btn.prop('disabled', false).text('Import Template');
            }
        });
    });

    // Select all checkboxes
    $(document).on('change', '#bdea-hf-select-all', function () {
        $('.bdea-hf-cb').prop('checked', $(this).is(':checked'));
    });

    // Bulk apply
    $(document).on('click', '#bdea-hf-bulk-apply', function () {
        var action = $('#bdea-hf-bulk-action').val();
        if (!action) { alert('Select an action.'); return; }

        var ids = [];
        $('.bdea-hf-cb:checked').each(function () { ids.push($(this).val()); });
        if (ids.length === 0) { alert('Select templates.'); return; }

        var $btn = $(this);
        $btn.prop('disabled', true).text('Processing...');

        $.post(ajaxurl, {
            action:   'bdea_hf_bulk_action',
            doaction: action,
            post_ids: ids,
            nonce:    $btn.data('nonce')
        }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert(res.data.message || 'Error.');
                $btn.prop('disabled', false).text('Apply');
            }
        }).fail(function () {
            alert('Request failed.');
            $btn.prop('disabled', false).text('Apply');
        });
    });

    function buildOptions(selected) {
        var html = '';
        $.each(grouped, function (group, conditions) {
            html += '<optgroup label="' + $('<span>').text(group).html() + '">';
            $.each(conditions, function (id, label) {
                var sel = String(id) === String(selected) ? ' selected' : '';
                html += '<option value="' + id + '"' + sel + '>' + $('<span>').text(label).html() + '</option>';
            });
            html += '</optgroup>';
        });
        return html;
    }

    // Drag-drop reorder
    var $tableBody = $('.bdea-hf-table tbody');
    if ($tableBody.length) {
        $tableBody.sortable({
            handle: 'td:first',
            helper: function (e, ui) {
                ui.children().each(function () { $(this).width($(this).width()); });
                return ui;
            },
            stop: function () {
                var order = [];
                $tableBody.find('tr[data-id]').each(function () {
                    order.push($(this).data('id'));
                });
                $.post(ajaxurl, {
                    action: 'bdea_hf_reorder_templates',
                    order:  order,
                    nonce:  data.reorder_nonce || ''
                }, function (res) {
                    // silently update
                });
            }
        });
    }

})(jQuery);
