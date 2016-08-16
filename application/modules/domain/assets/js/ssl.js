//$(document).on({
//    ajaxStart: function() { $('#myPleaseWait').modal('show'); },
//ajaxStop: function() { $('#myPleaseWait').modal('hide'); }
//});

$(function() {

    $("#loading-div-background").css({
        opacity: 1.0
    });

    $('#ssllist').bootstrapTable({
        pageSize: 25,
        columns: [{
                field: 'domains',
                title: LG_Domain,
                sortable: true,
                align: 'left',
                valign: 'middle',
                formatter: domainLister
            }, {
                field: 'SSLCertificateCreated',
                title: LG_Active,
                sortable: true,
                align: 'center',
                valign: 'middle',
                formatter: enableFormater
            }, {
                field: 'SSLCertificateType',
                title: LG_SSL_Type,
                sortable: true,
                align: 'left',
                valign: 'middle'
            }, {
                field: 'SSLCertificateCreated',
                title: LG_Created,
                align: 'center',
                valign: 'middle',
                formatter: dataFormater
            }, {
                field: 'SSLCertificateExpire',
                title: LG_Expired,
                align: 'center',
                valign: 'middle',
                formatter: dataFormater
            }, {
                field: 'operate',
                title: '',
                align: 'left',
                valign: 'middle',
                events: operateEvents,
                formatter: operateFormatter
            }

        ]
    });
});

window.operateEvents = {
    'click .create': function(e, value, row, index) {
        $("#loading-div-background").show();
        $.ajax({
            url: '/domain/createCertificate',
            type: 'post',
            dataType: 'json',
            data: {
                domain_id: row.id,
                domain: row.domain
            },
            success: function(data) {
                $("#loading-div-background").hide();

                if (data.status != 200) {
                    bootbox.alert(LG_not_owner, function() {});
                } else {
                    bootbox.alert(LG_domain_delete_successful, function() {
                        $('#ssllist').bootstrapTable('refresh');
                    });
                }
            }
        });
    },
    'click .delete': function(e, value, row, index) {
        $("#loading-div-background").show();
        $.ajax({
            url: '/domain/revokeCertificate',
            type: 'post',
            dataType: 'json',
            data: {
                domain_id: row.id,
                domain: row.domain
            },
            success: function(data) {
                $("#loading-div-background").hide();
                if (data.status != 200) {
                    bootbox.alert(LG_not_owner, function() {});
                } else {
                    bootbox.alert(LG_domain_delete_successful, function() {
                        $('#ssllist').bootstrapTable('refresh');
                    });
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

    console.log(row.SSLCertificateCreated);
    var disable = '';

    if (row.SSLCertificateCreated != null && row.SSLCertificateCreated != "0000-00-00 00:00:00") {
        disable = ' disabled';
    }


    var buttons = [
        '<a class="btn btn-secondary list-btn create' + disable + '" href="javascript:void(0)" title="' + LG_Create_Certificate + '">',
        '<i class="fa fa-magic" title="' + LG_Create_Certificate + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Create_Certificate + '</span>',
        '</a>',
        '<a class="btn btn-secondary list-btn delete" href="javascript:void(0)" title="' + LG_Delete + '">',
        '<i class="fa fa-trash-o" title="' + LG_Delete + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Delete + '</span>',
        '</a>',
        '<a class="btn btn-secondary list-btn suspend" href="javascript:void(0)" title="' + LG_Suspend + '">',
        '<i class="fa fa-power-off" title="' + LG_Suspend + '" aria-hidden="true"></i>',
        '<span class="sr-only">' + LG_Suspend + '</span>',
        '</a>'
    ].join('');

    return buttons;
}

function dataFormater(value, row, index) {

    if (value == null || value == "0000-00-00 00:00:00") {
        return "";
    }

    d = new Date(value);
    var yyyy = d.getFullYear().toString();
    var mm = (d.getMonth() + 101).toString().slice(-2);
    var dd = (d.getDate() + 100).toString().slice(-2);
    return dd + '.' + mm + '.' + yyyy;
}

function enableFormater(value, row, index) {
    if (value == null || value == "0000-00-00 00:00:00") {
        return '<i class="fa fa-times" title="Settings" aria-hidden="true"></i>';
    } else {
        return '<i class="fa fa-check" title="Settings" aria-hidden="true"></i>';
    }
}

function domainLister(value, row, index) {
    var listing = '';

    for (var i = 0, len = value.length; i < len; i++) {

        listing += value[i]
        if (i < len - 1) {
            listing += "<br />";
        }

    }
    return listing;
}
