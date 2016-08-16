$(function() {

    $("#path").focus(function() {
        $('.path').hide();
    });
    $("#sub").focus(function() {
        $('.domain').hide();
    });

    $('#path').typeahead({
        items: 20,
        source: function(query, process) {
            return $.get('/domain/directorys', {
                q: query
            }, function(data) {
                return process(data);
            });
        }
    });

    $('.tt-query').css('background-color', '#fff');


    $("#saveDomain").click(function(e) {
        e.preventDefault();

        if (!validateSubdomain($("#sub").val()) || $('#sub').val() == "") {
            $('.domain').text(LG_subdomain_not_valid).show();
            return;
        }

        $.ajax({
            url: '/domain/saveSubdomain',
            type: 'post',
            dataType: 'json',
            data: $('form#DomainForm').serialize(),
            success: function(data) {
                if (data.status == "501") {
                    if (data.domain != "") {
                        $('.domain').html(data.domain).show();
                    }
                    if (data.sub != "") {
                        $('.sub').html(data.sub).show();
                    }
                    if (data.path != "") {
                        $('.path').html(data.path).show();
                    }
                } else if (data.status == "503") {
                    window.location.href = "/login";
                } else {

                    var update = true;

                    if ($('#sub_id').val() == "") {
                        update = false;
                        $('#sub_id').val(data.sub_id);
                    }
                    $('#domain_id').val(data.domain_id);
                    $('.err').hide();
                    $('#domain').prop('disabled', true).attr('disabled', true);
                    $('#sub').prop('disabled', true).attr('disabled', true);

                    if (update) {
                        bootbox.alert(LG_changes_successful, function() {
                            window.location.href = "/domain/subdomains";
                        });
                    } else {
                        bootbox.alert(LG_add_forward_successful, function() {
                            window.location.href = "/domain/subdomains";
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
