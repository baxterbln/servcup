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

    $('#CustomerBase').validate({
        rules: {
            firstname: {
                required: true,
            },
            lastname: {
                required: true,
            },
            street: {
                required: true,
            },
            zipcode: {
                required: true,
            },
            city: {
                required: true,
            },
            email: {
                required: true,
            },
            customerPassword: {
                required: true,
            },
            group: {
                required: true,
            }
        },
        submitHandler: function (form) { // for demo
            alert('valid form submitted'); // for demo
            return false; // for demo
        },
        highlight: function (element) {
            $(element).css('background', 'rgba(91, 192, 222, 0.2)');
        },
        unhighlight: function(element) {
            $(element).css('background', '#ffffff');
        }
    });

    // go to next customer tab and save base data
    $('.nextTab').click(function() {
        if($("#CustomerBase").valid()) {

            $.ajax({
                url: '/customer/saveCustomer',
                type: 'post',
                dataType: 'json',
                data: $('form#CustomerBase').serialize(),
                success: function(data) {
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
                    }
                    else if(data.status == "500") {
                        window.location = '/login';
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
        }
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
                if (!response.success) {
                    alert(response.error);
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
