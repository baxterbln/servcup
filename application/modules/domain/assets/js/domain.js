$(function() {

    $('#domainlist').bootstrapTable({
        pageSize: 25,
        columns: [{
                field: 'domain',
                title: LG_Domain,
                sortable: true,
                align: 'left',
                valign: 'middle'
            }, {
                field: 'active',
                title: LG_Active,
                sortable: true,
                align: 'center',
                valign: 'middle',
                formatter: enableFormater
            }, {
                field: 'path',
                title: LG_Path,
                sortable: true,
                align: 'left',
                valign: 'middle'
            }, {
                field: 'php_version',
                title: LG_PHP_Version,
                align: 'center',
                valign: 'middle'
            }, {
                field: 'SSLCertificateCreated',
                title: LG_SSL,
                align: 'center',
                valign: 'middle',
                formatter: checkSSL
            }, {
                field: 'pagespeed',
                title: LG_Pagespeed,
                align: 'center',
                valign: 'middle',
                formatter: enableFormater
            }, {
                field: 'cache',
                title: LG_Cache,
                align: 'center',
                valign: 'middle',
                formatter: enableFormater
            }, {
                field: 'created',
                title: LG_Created,
                align: 'center',
                valign: 'middle',
                formatter: dataFormater
            }, {
                field: 'operate',
                title: '',
                align: 'left',
                events: operateEvents,
                formatter: operateFormatter
            }

        ]
    });
});

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

function dataFormater(value, row, index) {
    d = new Date(value);
    var yyyy = d.getFullYear().toString();
    var mm = (d.getMonth() + 101).toString().slice(-2);
    var dd = (d.getDate() + 100).toString().slice(-2);
    return dd + '.' + mm + '.' + yyyy;
}

function enableFormater(value, row, index) {
    if (value == 1) {
        return '<i class="fa fa-check" title="Settings" aria-hidden="true"></i>';
    } else {
        return '<i class="fa fa-times" title="Settings" aria-hidden="true"></i>';
    }
}

function checkSSL(value, row, index) {
    if (value == null || value == "0000-00-00 00:00:00") {
        return '<i class="fa fa-times" title="Settings" aria-hidden="true"></i>';
    } else {
        return '<i class="fa fa-check" title="Settings" aria-hidden="true"></i>';
    }
}
