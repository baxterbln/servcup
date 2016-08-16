$(function() {

    $('#forwardlist').bootstrapTable({
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
                field: 'redirect',
                title: LG_Type,
                sortable: true,
                align: 'center',
                valign: 'middle',
            }, {
                field: 'redirect_destination',
                title: LG_Target_URL,
                sortable: true,
                align: 'left',
                valign: 'middle'
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
        window.location = '/domain/forward/edit/' + row.id + '/' + row.domain;
        //alert('You click settings action, row: ' + JSON.stringify(row));
    },
    'click .delete': function(e, value, row, index) {

        bootbox.dialog({
            message: LG_confirm_delete_forward_message,
            title: LG_confirm_delete_forward,
            buttons: {
                danger: {
                    label: LG_Delete,
                    className: "btn-danger",
                    callback: function() {
                        $.ajax({
                            url: '/domain/deleteForward',
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
                                        $('#forwardlist').bootstrapTable('refresh');
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
