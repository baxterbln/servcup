var strength = {
    0: "Worst",
    1: "Bad",
    2: "Weak",
    3: "Good",
    4: "Strong"
}

var password = document.getElementById('customerPassword');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

password.addEventListener('input', function() {
    var val = password.value;
    var result = zxcvbn(val);

    meter.value = result.score;
});

$(function() {

    checkField('firstname');
    checkField('lastname');
    checkField('city');
    checkField('customerPassword');
    checkField('email');
    checkField('street');
    checkField('zipcode');
    checkField('customerPassword');

    // go to next customer tab and save base data
    $('.nextTab').click(function(event) {

            event.preventDefault();

            $.ajax({
                url: '/customer/saveCustomer',
                type: 'post',
                dataType: 'json',
                data: $('form#CustomerBase').serialize(),
                success: function(data) {
                    if(data.status == "500") {
                        window.location = '/login';
                    }
                    else if(data.status == "501") {

                        if(data.city != "") {
                            $('.city').html(data.city).show();
                        }
                        if(data.customerPassword != "") {
                            $('.customerPassword').html(data.customerPassword).show();
                        }
                        if(data.email != "") {
                            $('.email').html(data.email).show();
                        }
                        if(data.firstname != "") {
                            $('.firstname').html(data.firstname).show();
                        }
                        if(data.lastname != "") {
                            $('.lastname').html(data.lastname).show();
                        }
                        if(data.street != "") {
                            $('.street').html(data.street).show();
                        }
                        if(data.zipcode != "") {
                            $('.zipcode').html(data.zipcode).show();
                        }
                    } else {
                        // SET (NEW) CUSTOMERID IN HIDDEN FIELDS
                        $(".customer_id").val(data.customer_id);
                        $(this).parents('.tab-pane').removeClass('in active');

                        var currentId = $(this).parents('.tab-pane').attr("id");
                        var nextId = $(this).parents('.tab-pane').next().attr("id");

                        $('*[data-target="#' + currentId + '"]').removeClass('active');
                        $('*[data-target="#' + nextId + '"]').addClass('active');
                        $('#' + nextId).addClass('in').addClass('active').show();
                        $('[href=#' + nextId + ']').show();
                    }
                }
            });

        return false;
    })

    // Save addentional customer data
    $('.SaveAdditional').click(function() {
        $.ajax({
            url: '/customer/saveAdditional',
            type: 'post',
            dataType: 'json',
            data: $('form#CustomerExtend').serialize(),
            success: function(data) {
                if (!data.success) {
                    //alert(response.error);
                } else {


                }
            }
        });
    })

    $('#active').click(function() {
        if (this.checked) {
            this.checked = true;
            $('#activeUser').val('1');
        } else {
            this.checked = false;
            $('#activeUser').val('0');
        }
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
