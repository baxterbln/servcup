$(function() {

    $('#grouplist').bootstrapTable({
        pageSize: 10,
        columns: [{
            field: 'id',
            title: LG_Group_ID,
            sortable: true,
            align: 'left',
            valign: 'middle'
        }, {
            field: 'name',
            title: LG_Group_name,
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
});

function operateFormatter(value, row, index) {
        return [
            '<a class="btn btn-secondary list-btn settings" href="javascript:void(0)" title="'+LG_Edit+'">',
            '<i class="fa fa-pencil" title="'+LG_Edit+'" aria-hidden="true"></i>',
            '<span class="sr-only">'+LG_Edit+'</span>',
            '</a>',
            '<a class="btn btn-secondary list-btn delete" href="javascript:void(0)" title="'+LG_Delete+'">',
            '<i class="fa fa-trash-o" title="'+LG_Delete+'" aria-hidden="true"></i>',
            '<span class="sr-only">'+LG_Delete+'</span>',
            '</a>'
        ].join('');
    }

window.operateEvents = {
        'click .settings': function (e, value, row, index) {
            window.location = '/customer/editGroup/'+row.id;
        },
        'click .delete': function (e, value, row, index) {

            bootbox.confirm(LG_confirm_delete_group, function(confirmed) {
                if(confirmed) {
                    $.get( "/customer/deleteGroup/"+row.id, function( data ) {
                        $('#grouplist').bootstrapTable('refresh');
                    });
                }
            });
        }
    };
