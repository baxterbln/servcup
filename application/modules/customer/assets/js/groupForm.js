var _serializeArray = $.fn.serializeArray;

//Now extend it with newer "unchecked checkbox" functionality:
$.fn.extend({
    serializeArray: function() {
        var results = _serializeArray.call(this);

        this.find('input[type=checkbox]').each(function(id, item) {
            var $item = $(item);
            results.push({
                name: $item.attr('id'),
                value: $item.is(":checked") ? 1 : 0
            });
        });
        return results;
    }
});

$(function() {

    checkField('name');

    $('#savePermission').click(function(event) {

        $.ajax({
            url: '/customer/saveGroup',
            type: 'post',
            dataType: 'json',
            data: $('form#GroupForm').serialize(),
            success: function(data) {
                if (data.status == "500") {
                    window.location = '/login';
                } else if (data.status == "501") {
                    if (data.name != "") {
                        $('.name').html(data.name).show();
                    }
                } else {
                    // SET (NEW) group_id IN HIDDEN FIELDS
                    $(".group_id").val(data.group_id);
                }
            }
        });

        return false;
    });

});

function checkField(name)
{
    $('#'+name).on("focus", function(){
        if(  $("."+name).is(":visible") == true )
        {
            $("."+name).hide();
        }
    });
}
