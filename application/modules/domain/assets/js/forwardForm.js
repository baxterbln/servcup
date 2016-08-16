$(document).ready(function() {

    $("#domain").focus(function() {
        $('.domain').hide();
    });
    $("#sub").focus(function() {
        $('.domain').hide();
    });
    $("#destination").focus(function() {
        $('.destination').hide();
    });
    $("#domain_redirect").change(function() {
        $('.domain_redirect').hide();
    });
    $("#sub").focusout(function() {
        sub = $(this).val();
        if (!validateSubdomain(sub)) {
            $('.domain').text(LG_subdomain_not_valid).show();
        }
    });

    $("#destination").focusout(function() {
        destination = $(this).val();
        if(destination == "") {
            $('.destination').text(LG_destination_empty).show();
            return;
        }
        if (!validateDestination(destination)) {
            $('.destination').text(LG_invalid_destination).show();
            return;
        }
    });

    $("#saveDomain").click(function(e) {
        e.preventDefault();

        if (!validateSubdomain($("#sub").val()) || $('#sub').val() == "") {
            $('.domain').text(LG_subdomain_not_valid).show();
            return;
        }

        domain_redirect
        if($('#domain_redirect').val() == "") {
            $('.domain_redirect').text(LG_destination_target_empty).show();
            return;
        }
        if($('#destination').val() == "") {
            $('.destination').text(LG_destination_empty).show();
            return;
        }
        if (!validateDestination($('#destination').val())) {
            $('.destination').text(LG_invalid_destination).show();
            return;
        }

        $.ajax({
                url: '/domain/saveForward',
                type: 'post',
                dataType: 'json',
                data: $('form#DomainForm').serialize(),
                success: function(data) {
                    if(data.status == "501") {
                        if(data.domain != "") {
                            $('.domain').html(data.domain).show();
                        }
                        if(data.sub != "") {
                            $('.sub').html(data.sub).show();
                        }
                        if(data.domain_redirect != "") {
                            $('.domain_redirect').html(data.domain_redirect).show();
                        }
                        if(data.destination != "") {
                            $('.'+data.destination).html(data.destination).show();
                        }
                    }
                    else if(data.status == "503") {
                        window.location.href = "/login";
                    } else {

                        var update = true;

                        if($('#alias_id').val() == "") {
                            update = false;
                            $('#alias_id').val(data.alias_id);
                        }
                        $('#domain_id').val(data.domain_id);
                        $('.err').hide();
                        $('#domain').prop('disabled', true).attr('disabled',true);
                        $('#sub').prop('disabled', true).attr('disabled',true);

                        if (update) {
                            bootbox.alert(LG_changes_successful, function() {
                                window.location.href = "/domain/forwards";
                            });
                        }else{
                            bootbox.alert(LG_add_forward_successful, function() {
                                window.location.href = "/domain/forwards";
                            });
                        }
                    }
                }
            });
    });

});


function validateSubdomain(sub) {
    // strip off "http://" and/or "www."
    sub = sub.replace("http://", "");
    sub = sub.replace("www.", "");

    var reg = /^[a-zA-Z0-9-.]*$/;
    return reg.test(sub);
}

function validateDestination(destination) {

    var reg = /^(?:http|https)\:\/\/([a-zA-Z0-9\._-]*)(?:[a-zA-Z0-9\._\/\-]*)$/;
    return reg.test(destination);
}
