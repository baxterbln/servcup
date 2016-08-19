$(function() {

    $('#dblist').bootstrapTable({
        pageSize: 25,
        columns: [{
            field: 'db_name',
            title: LG_database,
            sortable: true,
            align: 'left',
            valign: 'middle'
        }, {
            field: 'db_user',
            title: LG_Username,
            sortable: true,
            align: 'left',
            valign: 'middle'
        }, {
            field: 'operate',
            title: '',
            align: 'left',
            events: operateEvents,
            formatter: operateFormatter
        }]
    });

    $('#dbname').focus(function() {
        $(".dbname").hide();
    });

    $('#saveDB').click(function(e) {
        e.preventDefault();

        // validate
        var error = false;
        var regx = /^[A-Za-z0-9_]+$/;
        if (!regx.test($('#dbname').val())) {
            $(".dbname").text(LG_username_character).show();
            error = true;
        }
        if ($('#username').val() == "") {
            $(".username").text(LG_password_empty).show();
            error = true;
        }

        if (!error) {
            $.ajax({
                url: '/database/saveDatabase',
                type: 'post',
                dataType: 'json',
                data: $('form#saveDBForm').serialize(),
                success: function(data) {
                    if (data.status == "503") {
                        window.location.href = "/login";
                    } else if (data.status == "501") {

                        if (data.dbname != "") {
                            $('.dbname').html(data.dbname).show();
                        }
                        if (data.username != "") {
                            $('.username').html(data.username).show();
                        }
                    } else {
                        if ($('#db_id').val() != "") {
                            bootbox.alert(LG_db_added, function() {
                                $('#dblist').bootstrapTable('refresh');
                                resetForm();
                            });
                        } else {
                            bootbox.alert(LG_db_added, function() {
                                $('#dblist').bootstrapTable('refresh');
                                $('#dbname').prop('disabled', false);
                                resetForm();
                            });
                        }
                    }
                }
            });
        }
    });
});

window.operateEvents = {
    'click .edit': function(e, value, row, index) {
        $.ajax({
            url: '/database/getDatabase',
            type: 'post',
            dataType: 'json',
            data: {
                db_id: row.id,
                db_name: row.db_name
            },
            success: function(data) {

                var dbname = data.db_name;
                dbname = dbname.replace("_" + data.customer_id, "");

                $('#dbname').val(dbname);
                $('#db_id').val(data.id);
                $('#db_name').val(data.db_name);
                $('#dbname').prop('disabled', true);
                $("#username").val(data.db_user);
                $(".formtitle").text(LG_edit_db);
            }
        });
    },
    'click .delete': function(e, value, row, index) {

        bootbox.dialog({
            message: LG_confirm_delete_user_message,
            title: LG_confirm_delete_user,
            buttons: {
                danger: {
                    label: LG_Delete,
                    className: "btn-danger",
                    callback: function() {
                        $.ajax({
                            url: '/database/deleteDatabase',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                db_id: row.id,
                                db_name: row.db_name
                            },
                            success: function(data) {
                                if (data.status == "503") {
                                    window.location.href = "/login";
                                } else if (data.status == "500") {
                                    // No access to object
                                    bootbox.alert(LG_access_denied, function() {});
                                } else {
                                    bootbox.alert(LG_db_deleted, function() {});
                                    $('#dblist').bootstrapTable('refresh');
                                }
                            }
                        });
                    }
                },
                main: {
                    label: LG_Cancel,
                    className: "btn-primary",
                    callback: function() {
                    }
                }
            }
        });
    }
};

function operateFormatter(value, row, index) {
    return [
        '<a class="btn btn-secondary list-btn edit" href="javascript:void(0)" title="' + LG_Edit + '">',
        '<i class="fa fa-pencil" title="' + LG_Edit + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Edit + '</span>',
        '</a>',
        '<a class="btn btn-secondary list-btn delete" href="javascript:void(0)" title="' + LG_Delete + '">',
        '<i class="fa fa-trash-o" title="' + LG_Delete + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Delete + '</span>',
        '</a>'
    ].join('');
}

function resetForm() {
    $('#saveDBForm')[0].reset();
    $('#db_id').val('');
    $('#dbname').prop('disabled', false);
    $(".formtitle").text(LG_add_db);
}
