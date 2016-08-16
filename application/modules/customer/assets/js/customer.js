$(function() {

    $('#customerlist').bootstrapTable({
        pageSize: 10,
        columns: [{
            field: 'customer_id',
            title: LG_customer_id,
            sortable: true,
            align: 'left',
            valign: 'middle'
        }, {
            field: 'company',
            title: LG_company,
            sortable: true,
            align: 'left',
            valign: 'middle'
        }, {
            field: 'name',
            title: LG_Lastname,
            align: 'left',
            valign: 'middle'
        }, {
            field: 'firstname',
            title: LG_Firstname,
            align: 'left',
            valign: 'middle',
        }, {
            field: 'zipcode',
            title: LG_Zipcode,
            align: 'left',
            valign: 'middle',
        }, {
            field: 'city',
            title: LG_City,
            align: 'left',
            valign: 'middle',
        }, {
            field: 'country',
            title: LG_Country,
            align: 'left',
            valign: 'middle',
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
            '</a>',
            '<a class="btn btn-secondary suspend" href="javascript:void(0)" title="'+LG_Suspend+'">',
            '<i class="fa fa-power-off" title="'+LG_Suspend+'" aria-hidden="true"></i>',
            '<span class="sr-only">'+LG_Suspend+'</span>',
            '</a>'
        ].join('');
    }

window.operateEvents = {
        'click .settings': function (e, value, row, index) {
            window.location = '/customer/edit/'+row.id;
            //alert('You click settings action, row: ' + JSON.stringify(row));
        },
        'click .delete': function (e, value, row, index) {
            alert('You click delete action, row: ' + JSON.stringify(row));
        },
        'click .suspend': function (e, value, row, index) {
            alert('You click suspend action, row: ' + JSON.stringify(row));
        },
    };
