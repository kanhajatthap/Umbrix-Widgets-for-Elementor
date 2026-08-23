(function ($) {
    'use strict';

    var data      = window.elementskeyHFData || {};
    var grouped   = data.conditions || {};

    var typeLabels = {
        header: 'Header',
        footer: 'Footer',
        single: 'Single Post',
        archive: 'Archive (Category / Tag / Loop)',
        '404': '404 Page',
        announcement: 'Announcement',
        bottom_bar: 'Bottom Bar',
        'loop': 'Loop Item',
        section: 'Section'
    };

    function typeLabel(type) {
        return typeLabels[type] || type.charAt(0).toUpperCase() + type.slice(1);
    }

    // Open create modal
    $(document).on('click', '.elementskey-hf-create-btn', function (e) {
        e.preventDefault();
        var type = $(this).data('type');
        if (!type) return;
        $('#elementskey-hf-create-modal').find('.elementskey-hf-modal-type-label').text(typeLabel(type));
        $('#elementskey-hf-create-modal input[name="type"]').val(type);
        $('#elementskey-hf-create-modal select[name="template_type"]').val(type);
        updateCreateModalForType(type);
        $('#elementskey-hf-create-modal').show();
    });

    // Type select inside create modal
    $(document).on('change', '#elementskey-hf-create-modal select[name="template_type"]', function () {
        var type = $(this).val();
        $('#elementskey-hf-create-modal input[name="type"]').val(type);
        $('#elementskey-hf-create-modal').find('.elementskey-hf-modal-type-label').text(typeLabel(type));
        updateCreateModalForType(type);
    });

    function updateCreateModalForType(type) {
        var showDisable = (type === 'header' || type === 'footer');
        var showConditions = (type !== 'loop' && type !== 'section');
        $('#elementskey-hf-create-modal input[name="disable_theme"]').closest('.elementskey-hf-field').toggle(showDisable);
        $('#elementskey-hf-create-modal .elementskey-hf-field-row').toggle(showConditions);
        var condMap = { single: 'singular:post_type:post', archive: 'archive', '404': '404' };
        if (condMap[type]) {
            $('#elementskey-hf-create-modal select[name="condition"]').val(condMap[type]);
        }
    }

    // Close modals
    $(document).on('click', '.elementskey-hf-modal-close, .elementskey-hf-modal-overlay, [data-close-modal]', function (e) {
        if ($(e.target).hasClass('elementskey-hf-modal-overlay') || $(e.target).hasClass('elementskey-hf-modal-close') || $(e.target).attr('data-close-modal') !== undefined) {
            $('.elementskey-hf-modal-overlay').hide();
        }
    });

    // Submit create template
    $(document).on('click', '.elementskey-hf-create-submit', function () {
        var $btn  = $(this);
        var $form = $('#elementskey-hf-create-form');
        var data  = $form.serializeArray();

        data.push({ name: 'nonce', value: $btn.data('nonce') });
        data.push({ name: 'action', value: 'elementskey_hf_create_template' });

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
    $(document).on('click', '.elementskey-hf-edit-cond', function (e) {
        e.preventDefault();
        var postId = $(this).data('id');
        var $modal = $('#elementskey-hf-conditions-modal');
        var $list  = $modal.find('.elementskey-hf-conditions-list');

        $modal.find('input[name="template_id"]').val(postId);
        $('#elementskey-hf-cond-search').val('');
        $list.html('<p style="text-align:center;color:#8c8f94;padding:20px;">Loading...</p>');
        $modal.show();

        $.post(ajaxurl, {
            action:   'elementskey_hf_get_template_conditions',
            post_id:  postId,
            nonce:    $('.elementskey-hf-conditions-save').data('nonce')
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
    $(document).on('click', '.elementskey-hf-add-condition-row', function () {
        var $list = $(this).closest('.elementskey-hf-modal-body').find('.elementskey-hf-conditions-list');
        addConditionRow($list, { type: 'include', condition: '' });
    });

    function addConditionRow($list, cond, idx) {
        var optionsHtml = buildOptions(cond.condition || '');
        var type        = cond.type === 'exclude' ? 'exclude' : 'include';
        var index       = typeof idx === 'number' ? idx : $list.find('.elementskey-hf-condition-row').length;

        var row = $(
            '<div class="elementskey-hf-condition-row elementskey-hf-cond-' + type + '">' +
            '<span class="elementskey-hf-drag-handle dashicons dashicons-menu" title="Drag to reorder"></span>' +
            '<select name="conditions[' + index + '][type]" class="elementskey-hf-cond-type">' +
            '<option value="include"' + (type === 'include' ? ' selected' : '') + '>Include</option>' +
            '<option value="exclude"' + (type === 'exclude' ? ' selected' : '') + '>Exclude</option>' +
            '</select> ' +
            '<select name="conditions[' + index + '][condition]" class="elementskey-hf-cond-select">' +
            optionsHtml +
            '</select> ' +
            '<button type="button" class="elementskey-hf-row-btn elementskey-hf-duplicate-condition" title="Duplicate condition">&#10697;</button>' +
            '<button type="button" class="elementskey-hf-row-btn elementskey-hf-remove-condition" title="Remove condition">\u2715</button>' +
            '<div class="elementskey-hf-specific-wrap"></div>' +
            '</div>'
        );

        $list.append(row);
        applySearch($('#elementskey-hf-cond-search').val() || '');
        if ($list.hasClass('ui-sortable')) { $list.sortable('refresh'); }
        return row;
    }

    function reindexConditionRows($list) {
        $list.find('.elementskey-hf-condition-row').each(function (i) {
            $(this).find('select[name$="[type]"]').attr('name', 'conditions[' + i + '][type]');
            $(this).find('select[name$="[condition]"]').attr('name', 'conditions[' + i + '][condition]');
        });
    }

    // Row type change -> update tint
    $(document).on('change', '.elementskey-hf-cond-type', function () {
        var $row = $(this).closest('.elementskey-hf-condition-row');
        $row.removeClass('elementskey-hf-cond-include elementskey-hf-cond-exclude')
            .addClass($(this).val() === 'exclude' ? 'elementskey-hf-cond-exclude' : 'elementskey-hf-cond-include');
    });

    // Duplicate condition row
    $(document).on('click', '.elementskey-hf-duplicate-condition', function () {
        var $row  = $(this).closest('.elementskey-hf-condition-row');
        var $list = $row.closest('.elementskey-hf-conditions-list');
        addConditionRow($list, {
            type:      $row.find('.elementskey-hf-cond-type').val(),
            condition: $row.find('.elementskey-hf-cond-select').val()
        });
    });

    $(document).on('click', '.elementskey-hf-remove-condition', function () {
        $(this).closest('.elementskey-hf-condition-row').remove();
    });

    // Search filter
    $(document).on('input', '#elementskey-hf-cond-search', function () {
        applySearch($(this).val());
    });

    $(document).on('click', '.elementskey-hf-cond-search-clear', function () {
        $('#elementskey-hf-cond-search').val('');
        applySearch('');
    });

    function applySearch(q) {
        q = (q || '').toLowerCase();
        $('.elementskey-hf-cond-select').each(function () {
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
    $(document).on('change', '.elementskey-hf-cond-select', function () {
        var $row  = $(this).closest('.elementskey-hf-condition-row');
        var $wrap = $row.find('.elementskey-hf-specific-wrap');
        var val   = $(this).val();

        $wrap.empty().removeClass('elementskey-hf-specific-open');

        var parts = val ? val.split(':') : [];
        if (parts.length === 3 && parts[0] === 'singular' && parts[1] === 'post_type') {
            loadSpecificItems($wrap, parts[2]);
        }
    });

    function loadSpecificItems($wrap, postType) {
        $wrap.addClass('elementskey-hf-specific-open')
            .html('<span class="elementskey-hf-specific-loading">Loading ' + $('<span>').text(postType).html() + ' items...</span>');

        $.post(ajaxurl, {
            action:   'elementskey_hf_get_posts',
            post_type: postType,
            nonce:    $('.elementskey-hf-conditions-save').data('nonce')
        }, function (res) {
            if (!res.success || !res.data.items || !res.data.items.length) {
                $wrap.html('<span class="elementskey-hf-specific-empty">No published items found.</span>');
                return;
            }

            var html = '<div class="elementskey-hf-specific-head">' +
                '<span>Add specific ' + $('<span>').text(postType).html() + ' items:</span>' +
                '<div class="elementskey-hf-specific-actions">' +
                '<button type="button" class="button button-small elementskey-hf-specific-add" data-type="include">+ Include</button>' +
                '<button type="button" class="button button-small elementskey-hf-specific-add" data-type="exclude">- Exclude</button>' +
                '</div></div>' +
                '<div class="elementskey-hf-specific-list">';

            $.each(res.data.items, function (i, item) {
                html += '<label class="elementskey-hf-specific-item">' +
                    '<input type="checkbox" value="' + item.id + '" />' +
                    '<span>' + $('<span>').text(item.title).html() + '</span>' +
                    '</label>';
            });

            html += '</div>';
            $wrap.html(html);
        });
    }

    $(document).on('click', '.elementskey-hf-specific-add', function () {
        var $wrap = $(this).closest('.elementskey-hf-specific-wrap');
        var $list = $wrap.closest('.elementskey-hf-conditions-list');
        var type  = $(this).data('type');

        $wrap.find('.elementskey-hf-specific-item input:checked').each(function () {
            addConditionRow($list, { type: type, condition: 'singular:post_id:' + $(this).val() });
        });

        $wrap.find('.elementskey-hf-specific-item input').prop('checked', false);
    });

    // Device visibility toggles
    function syncDeviceToggles($modal) {
        $modal.find('.elementskey-hf-device-toggle').each(function () {
            var on = $(this).find('input').is(':checked');
            $(this).toggleClass('elementskey-hf-device-off', !on);
        });
    }

    $(document).on('change', '.elementskey-hf-device-toggle input', function () {
        syncDeviceToggles($(this).closest('#elementskey-hf-conditions-modal'));
    });

    function updateConditionsModalForType(type) {
        var $modal = $('#elementskey-hf-conditions-modal');
        var isHeaderFooter = (type === 'header' || type === 'footer');
        $modal.find('input[name="disable_theme"]').closest('.elementskey-hf-field-inline').toggle(isHeaderFooter);
        syncDeviceToggles($modal);
    }

    // Save conditions
    $(document).on('click', '.elementskey-hf-conditions-save', function () {
        var $btn   = $(this);
        var $modal = $('#elementskey-hf-conditions-modal');
        var postId = $modal.find('input[name="template_id"]').val();
        var conds  = [];

        $modal.find('.elementskey-hf-condition-row').each(function () {
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
            action:          'elementskey_hf_update_conditions',
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
    $(document).on('click', '.elementskey-hf-clear-select', function () {
        var name = $(this).data('target');
        $(this).closest('.elementskey-hf-field').find('select[name="' + name + '[]"] option').prop('selected', false);
    });

    // Duplicate template
    $(document).on('click', '.elementskey-hf-duplicate-btn', function (e) {
        e.preventDefault();
        var $btn   = $(this);
        var postId = $btn.data('id');

        $btn.prop('disabled', true).text('...');

        $.post(ajaxurl, {
            action: 'elementskey_hf_duplicate_template',
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
    $(document).on('click', '.elementskey-hf-export-btn', function (e) {
        e.preventDefault();
        var $btn   = $(this);
        var postId = $btn.data('id');

        $btn.prop('disabled', true).text('...');

        $.post(ajaxurl, {
            action: 'elementskey_hf_export_template',
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
    $(document).on('click', '.elementskey-hf-import-btn', function (e) {
        e.preventDefault();
        $('#elementskey-hf-import-modal').show();
    });

    // Import dropzone
    $(document).on('click', '#elementskey-hf-dropzone', function () {
        $(this).find('input[type="file"]').trigger('click');
    });

    $(document).on('change', '#elementskey-hf-dropzone input[type="file"]', function () {
        var file = this.files && this.files[0] ? this.files[0] : null;
        $('#elementskey-hf-dropzone').toggleClass('elementskey-hf-dropzone-has-file', !!file);
        $('#elementskey-hf-dropzone .elementskey-hf-dropzone-file').text(file ? 'Selected: ' + file.name : '');
    });

    $(document).on('dragover dragenter', '#elementskey-hf-dropzone', function (e) {
        e.preventDefault();
        $('#elementskey-hf-dropzone').addClass('elementskey-hf-dropzone-dragover');
    });

    $(document).on('dragleave dragend', '#elementskey-hf-dropzone', function (e) {
        e.preventDefault();
        $('#elementskey-hf-dropzone').removeClass('elementskey-hf-dropzone-dragover');
    });

    $(document).on('drop', '#elementskey-hf-dropzone', function (e) {
        e.preventDefault();
        $('#elementskey-hf-dropzone').removeClass('elementskey-hf-dropzone-dragover');
        var files = e.originalEvent.dataTransfer.files;
        if (files && files.length) {
            var input = $('#elementskey-hf-dropzone input[type="file"]')[0];
            input.files = files;
            $(input).trigger('change');
        }
    });

    // Submit import
    $(document).on('click', '.elementskey-hf-import-submit', function () {
        var $btn   = $(this);
        var $form  = $('#elementskey-hf-import-form');
        var file   = $form.find('input[type="file"]')[0].files[0];

        if (!file) { alert('Select a .json file.'); return; }

        var fd = new FormData();
        fd.append('action', 'elementskey_hf_import_template');
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
    $(document).on('change', '#elementskey-hf-select-all', function () {
        $('.elementskey-hf-cb').prop('checked', $(this).is(':checked'));
    });

    // Bulk apply
    $(document).on('click', '#elementskey-hf-bulk-apply', function () {
        var action = $('#elementskey-hf-bulk-action').val();
        if (!action) { alert('Select an action.'); return; }

        var ids = [];
        $('.elementskey-hf-cb:checked').each(function () { ids.push($(this).val()); });
        if (ids.length === 0) { alert('Select templates.'); return; }

        var $btn = $(this);
        $btn.prop('disabled', true).text('Processing...');

        $.post(ajaxurl, {
            action:   'elementskey_hf_bulk_action',
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
    var $condList = $('.elementskey-hf-conditions-list');
    if ($condList.length) {
        $condList.sortable({
            handle:   '.elementskey-hf-drag-handle',
            items:    '.elementskey-hf-condition-row',
            axis:     'y',
            tolerance: 'pointer',
            stop:     function () {
                reindexConditionRows($(this));
            }
        });
    }

    // Drag-drop reorder
    var $tableBody = $('.elementskey-hf-table tbody');
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
                    action: 'elementskey_hf_reorder_templates',
                    order:  order,
                    nonce:  data.reorder_nonce || ''
                }, function (res) {
                    // silently update
                });
            }
        });
    }

})(jQuery);
