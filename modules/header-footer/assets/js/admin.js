(function ($) {
    'use strict';

    var data      = window.bdeaHFData || {};
    var grouped   = data.conditions || {};

    var typeLabels = {
        header: 'Header',
        footer: 'Footer',
        single: 'Single Post',
        archive: 'Archive (Category / Tag / Loop)',
        '404': '404 Page',
        announcement: 'Announcement',
        bottom_bar: 'Bottom Bar',
        'loop': 'Loop Item'
    };

    function typeLabel(type) {
        return typeLabels[type] || type.charAt(0).toUpperCase() + type.slice(1);
    }

    // Open create modal
    $(document).on('click', '.bdea-hf-create-btn', function (e) {
        e.preventDefault();
        var type = $(this).data('type');
        if (!type) return;
        $('#bdea-hf-create-modal').find('.bdea-hf-modal-type-label').text(typeLabel(type));
        $('#bdea-hf-create-modal input[name="type"]').val(type);
        $('#bdea-hf-create-modal select[name="template_type"]').val(type);
        updateCreateModalForType(type);
        $('#bdea-hf-create-modal').show();
    });

    // Type select inside create modal
    $(document).on('change', '#bdea-hf-create-modal select[name="template_type"]', function () {
        var type = $(this).val();
        $('#bdea-hf-create-modal input[name="type"]').val(type);
        $('#bdea-hf-create-modal').find('.bdea-hf-modal-type-label').text(typeLabel(type));
        updateCreateModalForType(type);
    });

    function updateCreateModalForType(type) {
        var showDisable = (type === 'header' || type === 'footer');
        var showConditions = (type !== 'loop');
        $('#bdea-hf-create-modal input[name="disable_theme"]').closest('.bdea-hf-field').toggle(showDisable);
        $('#bdea-hf-create-modal .bdea-hf-field-row').toggle(showConditions);
        var condMap = { single: 'singular:post_type:post', archive: 'archive', '404': '404' };
        if (condMap[type]) {
            $('#bdea-hf-create-modal select[name="condition"]').val(condMap[type]);
        }
    }

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
    $(document).on('click', '.bdea-hf-edit-cond', function (e) {
        e.preventDefault();
        var postId = $(this).data('id');
        var $modal = $('#bdea-hf-conditions-modal');
        var $list  = $modal.find('.bdea-hf-conditions-list');

        $modal.find('input[name="template_id"]').val(postId);
        $('#bdea-hf-cond-search').val('');
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

                var dev = res.data.device_visibility || {};
                $modal.find('input[name="device_desktop"]').prop('checked', dev.desktop !== '');
                $modal.find('input[name="device_tablet"]').prop('checked', dev.tablet !== '');
                $modal.find('input[name="device_mobile"]').prop('checked', dev.mobile !== '');

                updateConditionsModalForType(res.data.type || 'header');
            } else {
                $list.html('<p style="text-align:center;color:#8c8f94;padding:20px;">No conditions loaded.</p>');
            }
        });
    });

    // Add condition row
    $(document).on('click', '.bdea-hf-add-condition-row', function () {
        var $list = $(this).closest('.bdea-hf-modal-body').find('.bdea-hf-conditions-list');
        addConditionRow($list, { type: 'include', condition: '' });
    });

    function addConditionRow($list, cond, idx) {
        var optionsHtml = buildOptions(cond.condition || '');
        var type        = cond.type === 'exclude' ? 'exclude' : 'include';
        var index       = typeof idx === 'number' ? idx : $list.find('.bdea-hf-condition-row').length;

        var row = $(
            '<div class="bdea-hf-condition-row bdea-hf-cond-' + type + '">' +
            '<span class="bdea-hf-drag-handle dashicons dashicons-menu" title="Drag to reorder"></span>' +
            '<select name="conditions[' + index + '][type]" class="bdea-hf-cond-type">' +
            '<option value="include"' + (type === 'include' ? ' selected' : '') + '>Include</option>' +
            '<option value="exclude"' + (type === 'exclude' ? ' selected' : '') + '>Exclude</option>' +
            '</select> ' +
            '<select name="conditions[' + index + '][condition]" class="bdea-hf-cond-select">' +
            optionsHtml +
            '</select> ' +
            '<button type="button" class="bdea-hf-row-btn bdea-hf-duplicate-condition" title="Duplicate condition">&#10697;</button>' +
            '<button type="button" class="bdea-hf-row-btn bdea-hf-remove-condition" title="Remove condition">\u2715</button>' +
            '<div class="bdea-hf-specific-wrap"></div>' +
            '</div>'
        );

        $list.append(row);
        applySearch($('#bdea-hf-cond-search').val() || '');
        if ($list.hasClass('ui-sortable')) { $list.sortable('refresh'); }
        return row;
    }

    function reindexConditionRows($list) {
        $list.find('.bdea-hf-condition-row').each(function (i) {
            $(this).find('select[name$="[type]"]').attr('name', 'conditions[' + i + '][type]');
            $(this).find('select[name$="[condition]"]').attr('name', 'conditions[' + i + '][condition]');
        });
    }

    // Row type change -> update tint
    $(document).on('change', '.bdea-hf-cond-type', function () {
        var $row = $(this).closest('.bdea-hf-condition-row');
        $row.removeClass('bdea-hf-cond-include bdea-hf-cond-exclude')
            .addClass($(this).val() === 'exclude' ? 'bdea-hf-cond-exclude' : 'bdea-hf-cond-include');
    });

    // Duplicate condition row
    $(document).on('click', '.bdea-hf-duplicate-condition', function () {
        var $row  = $(this).closest('.bdea-hf-condition-row');
        var $list = $row.closest('.bdea-hf-conditions-list');
        addConditionRow($list, {
            type:      $row.find('.bdea-hf-cond-type').val(),
            condition: $row.find('.bdea-hf-cond-select').val()
        });
    });

    $(document).on('click', '.bdea-hf-remove-condition', function () {
        $(this).closest('.bdea-hf-condition-row').remove();
    });

    // Search filter
    $(document).on('input', '#bdea-hf-cond-search', function () {
        applySearch($(this).val());
    });

    $(document).on('click', '.bdea-hf-cond-search-clear', function () {
        $('#bdea-hf-cond-search').val('');
        applySearch('');
    });

    function applySearch(q) {
        q = (q || '').toLowerCase();
        $('.bdea-hf-cond-select').each(function () {
            $(this).find('optgroup').each(function () {
                var hasVisible = false;
                $(this).find('option').each(function () {
                    var match = !q || $(this).text().toLowerCase().indexOf(q) !== -1;
                    $(this).toggle(match);
                    if (match) { hasVisible = true; }
                });
                $(this).toggle(hasVisible);
            });
        });
    }

    // Specific items (pages/posts) for singular post type conditions
    $(document).on('change', '.bdea-hf-cond-select', function () {
        var $row  = $(this).closest('.bdea-hf-condition-row');
        var $wrap = $row.find('.bdea-hf-specific-wrap');
        var val   = $(this).val();

        $wrap.empty().removeClass('bdea-hf-specific-open');

        var parts = val ? val.split(':') : [];
        if (parts.length === 3 && parts[0] === 'singular' && parts[1] === 'post_type') {
            loadSpecificItems($wrap, parts[2]);
        }
    });

    function loadSpecificItems($wrap, postType) {
        $wrap.addClass('bdea-hf-specific-open')
            .html('<span class="bdea-hf-specific-loading">Loading ' + $('<span>').text(postType).html() + ' items...</span>');

        $.post(ajaxurl, {
            action:   'bdea_hf_get_posts',
            post_type: postType,
            nonce:    $('.bdea-hf-conditions-save').data('nonce')
        }, function (res) {
            if (!res.success || !res.data.items || !res.data.items.length) {
                $wrap.html('<span class="bdea-hf-specific-empty">No published items found.</span>');
                return;
            }

            var html = '<div class="bdea-hf-specific-head">' +
                '<span>Add specific ' + $('<span>').text(postType).html() + ' items:</span>' +
                '<div class="bdea-hf-specific-actions">' +
                '<button type="button" class="button button-small bdea-hf-specific-add" data-type="include">+ Include</button>' +
                '<button type="button" class="button button-small bdea-hf-specific-add" data-type="exclude">- Exclude</button>' +
                '</div></div>' +
                '<div class="bdea-hf-specific-list">';

            $.each(res.data.items, function (i, item) {
                html += '<label class="bdea-hf-specific-item">' +
                    '<input type="checkbox" value="' + item.id + '" />' +
                    '<span>' + $('<span>').text(item.title).html() + '</span>' +
                    '</label>';
            });

            html += '</div>';
            $wrap.html(html);
        });
    }

    $(document).on('click', '.bdea-hf-specific-add', function () {
        var $wrap = $(this).closest('.bdea-hf-specific-wrap');
        var $list = $wrap.closest('.bdea-hf-conditions-list');
        var type  = $(this).data('type');

        $wrap.find('.bdea-hf-specific-item input:checked').each(function () {
            addConditionRow($list, { type: type, condition: 'singular:post_id:' + $(this).val() });
        });

        $wrap.find('.bdea-hf-specific-item input').prop('checked', false);
    });

    // Device visibility toggles
    function syncDeviceToggles($modal) {
        $modal.find('.bdea-hf-device-toggle').each(function () {
            var on = $(this).find('input').is(':checked');
            $(this).toggleClass('bdea-hf-device-off', !on);
        });
    }

    $(document).on('change', '.bdea-hf-device-toggle input', function () {
        syncDeviceToggles($(this).closest('#bdea-hf-conditions-modal'));
    });

    function updateConditionsModalForType(type) {
        var $modal = $('#bdea-hf-conditions-modal');
        var isHeaderFooter = (type === 'header' || type === 'footer');
        $modal.find('input[name="disable_theme"]').closest('.bdea-hf-field-inline').toggle(isHeaderFooter);
        syncDeviceToggles($modal);
    }

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
        var devDesktop      = $modal.find('input[name="device_desktop"]').is(':checked') ? 'yes' : '';
        var devTablet       = $modal.find('input[name="device_tablet"]').is(':checked') ? 'yes' : '';
        var devMobile       = $modal.find('input[name="device_mobile"]').is(':checked') ? 'yes' : '';

        $.post(ajaxurl, {
            action:          'bdea_hf_update_conditions',
            post_id:         postId,
            conditions:      conds,
            disable_theme:   disableTheme,
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
    $(document).on('click', '.bdea-hf-duplicate-btn', function (e) {
        e.preventDefault();
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
    $(document).on('click', '.bdea-hf-export-btn', function (e) {
        e.preventDefault();
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
    $(document).on('click', '.bdea-hf-import-btn', function (e) {
        e.preventDefault();
        $('#bdea-hf-import-modal').show();
    });

    // Import dropzone
    $(document).on('click', '#bdea-hf-dropzone', function () {
        $(this).find('input[type="file"]').trigger('click');
    });

    $(document).on('change', '#bdea-hf-dropzone input[type="file"]', function () {
        var file = this.files && this.files[0] ? this.files[0] : null;
        $('#bdea-hf-dropzone').toggleClass('bdea-hf-dropzone-has-file', !!file);
        $('#bdea-hf-dropzone .bdea-hf-dropzone-file').text(file ? 'Selected: ' + file.name : '');
    });

    $(document).on('dragover dragenter', '#bdea-hf-dropzone', function (e) {
        e.preventDefault();
        $('#bdea-hf-dropzone').addClass('bdea-hf-dropzone-dragover');
    });

    $(document).on('dragleave dragend', '#bdea-hf-dropzone', function (e) {
        e.preventDefault();
        $('#bdea-hf-dropzone').removeClass('bdea-hf-dropzone-dragover');
    });

    $(document).on('drop', '#bdea-hf-dropzone', function (e) {
        e.preventDefault();
        $('#bdea-hf-dropzone').removeClass('bdea-hf-dropzone-dragover');
        var files = e.originalEvent.dataTransfer.files;
        if (files && files.length) {
            var input = $('#bdea-hf-dropzone input[type="file"]')[0];
            input.files = files;
            $(input).trigger('change');
        }
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
        var found = false;
        var html  = '';
        $.each(grouped, function (group, conditions) {
            html += '<optgroup label="' + $('<span>').text(group).html() + '">';
            $.each(conditions, function (id, label) {
                var sel = String(id) === String(selected);
                if (sel) { found = true; }
                html += '<option value="' + id + '"' + (sel ? ' selected' : '') + '>' + $('<span>').text(label).html() + '</option>';
            });
            html += '</optgroup>';
        });
        if (selected && !found) {
            html = '<option value="' + $('<span>').text(selected).html() + '" selected>Specific Item (' + selected + ')</option>' + html;
        }
        return html;
    }

    // Conditions list drag-drop reorder
    var $condList = $('.bdea-hf-conditions-list');
    if ($condList.length) {
        $condList.sortable({
            handle:   '.bdea-hf-drag-handle',
            items:    '.bdea-hf-condition-row',
            axis:     'y',
            tolerance: 'pointer',
            stop:     function () {
                reindexConditionRows($(this));
            }
        });
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
