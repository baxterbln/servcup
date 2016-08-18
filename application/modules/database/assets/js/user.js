$(function() {

    $('#userlist').bootstrapTable({
        pageSize: 25,
        columns: [{
                field: 'username',
                title: LG_Username,
                sortable: true,
                align: 'left',
                valign: 'middle'
            }, {
                field: 'remote',
                title: LG_Access_from,
                sortable: true,
                align: 'left',
                valign: 'middle'
            }, {
                field: 'operate',
                title: '',
                align: 'left',
                events: operateEvents,
                formatter: operateFormatter
            }

        ]
    });

    $('#username').focus(function() {
        $(".username").hide();
    });

    $('#password').focus(function() {
        $(".password").hide();
        $(".password_repeat").hide();
    });

    $('#password_repeat').focus(function() {
        $(".password").hide();
        $(".password_repeat").hide();
    });

    $('#addUser').click(function(e) {
        e.preventDefault();

        // validate
        var error = false;
        var regx = /^[A-Za-z0-9_]+$/;
        if (!regx.test($('#username').val())) {
            $(".username").text(LG_username_character).show();
            error = true;
        }
        if ($('#password').val() == "") {
            $(".password").text(LG_password_empty).show();
            error = true;
        }
        if ($('#password').val() != $('#password_repeat').val()) {
            $(".password_repeat").text(LG_password_repeat_error).show();
            error = true;
        }

        if (!error) {
            $.ajax({
                url: '/database/addUser',
                type: 'post',
                dataType: 'json',
                data: $('form#dbUserAdd').serialize(),
                success: function(data) {
                    if (data.status == "503") {
                        window.location.href = "/login";
                    } else if (data.status == "501") {

                        if (data.username != "") {
                            $('.username').html(data.username).show();
                        }
                        if (data.password != "") {
                            $('.password').html(data.password).show();
                        }
                        if (data.password_repeat != "") {
                            $('.password_repeat').html(data.password_repeat).show();
                        }
                    } else {
                        bootbox.alert(LG_domain_delete_successful, function() {
                            $('#userlist').bootstrapTable('refresh');
                        });
                    }
                }
            });
        }
    });

    $('#generate').click(function(e) {
        e.preventDefault();
        var password = makePasswd();
        $('#password').prop('type', 'text').val(password);
        $('#password_repeat').val(password);
    });
});

function makePasswd() {
    var passwd = '';
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    for (i = 1; i < 10; i++) {
        var c = Math.floor(Math.random() * chars.length + 1);
        passwd += chars.charAt(c)
    }

    return passwd;

}

window.operateEvents = {
    'click .settings': function(e, value, row, index) {
        window.location = '/domain/edit/' + row.id + '/' + row.domain;
        //alert('You click settings action, row: ' + JSON.stringify(row));
    },
    'click .stats': function(e, value, row, index) {
        var win = window.open('/domain/stats', '_blank');
        win.focus();
    },
    'click .delete': function(e, value, row, index) {

        bootbox.dialog({
            message: LG_confirm_delete_domain_message,
            title: LG_confirm_delete_domain,
            buttons: {
                danger: {
                    label: LG_Delete,
                    className: "btn-danger",
                    callback: function() {
                        $.ajax({
                            url: '/domain/deleteDomain',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                domain_id: row.id,
                                domain: row.domain
                            },
                            success: function(data) {
                                if (data.status != 200) {
                                    bootbox.alert(LG_not_owner, function() {});
                                } else {
                                    bootbox.alert(LG_domain_delete_successful, function() {
                                        $('#domainlist').bootstrapTable('refresh');
                                    });
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
    },
    'click .suspend': function(e, value, row, index) {

        var setStatus = 0;
        var confirm_message = LG_confirm_suspend_domain;
        var success_message = LG_domain_deactivated;

        if (row.active == 0) {
            setStatus = 1;
            confirm_message = LG_confirm_unsuspend_domain;
            success_message = LG_domain_activated;
        }
        bootbox.confirm(confirm_message, function(confirmed) {
            if (confirmed) {
                $.ajax({
                    url: '/domain/suspendDomain',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        domain_id: row.id,
                        domain: row.domain,
                        status: setStatus
                    },
                    success: function(data) {
                        if (data.status != 200) {
                            bootbox.alert(LG_not_owner, function() {});
                        } else {
                            bootbox.alert(success_message, function() {
                                $('#domainlist').bootstrapTable('refresh');
                            });
                        }
                    }
                });
            }
        });
    },
};

function operateFormatter(value, row, index) {
    return [
        '<a class="btn btn-secondary list-btn settings" href="javascript:void(0)" title="' + LG_Edit + '">',
        '<i class="fa fa-pencil" title="' + LG_Edit + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Edit + '</span>',
        '</a>',
        '<a class="btn btn-secondary list-btn delete" href="javascript:void(0)" title="' + LG_Delete + '">',
        '<i class="fa fa-trash-o" title="' + LG_Delete + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Delete + '</span>',
        '</a>',
        '<a class="btn btn-secondary list-btn suspend" href="javascript:void(0)" title="' + LG_Suspend + '">',
        '<i class="fa fa-power-off" title="' + LG_Suspend + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Suspend + '</span>',
        '</a>',
        '<a class="btn btn-secondary stats" href="javascript:void(0)" title="' + LG_Statistics + '">',
        '<i class="fa fa-bar-chart" title="' + LG_Statistics + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Statistics + '</span>',
        '</a>'
    ].join('');
}
