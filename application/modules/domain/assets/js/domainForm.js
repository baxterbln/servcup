$(document).ready(function() {

    $("#domain").focus(function() {
        $('#domainStatus').hide();
        $('#setAuthcode').hide();
        $('.domain').hide();
    });

    $("#excludeCacheFiles").focus(function() {
        $('.excludeCacheFiles').hide();
    });

    if ($('#cache').is(':checked')) {
        $('#cacheTabNav').show();
    }else{
        $('#cacheTabNav').hide();
    }

    if ($('#pagespeed').is(':checked')) {
        $('#pagespeedTabNav').show();
    }else{
        $('#pagespeedTabNav').hide();
    }

    if ($('#UseAnalyticsJs').is(':checked')) {
        $('#AnalyticsIDBlock').show();
    }else{
        $('#AnalyticsIDBlock').hide();
    }

    $('#UseAnalyticsJs').click(function() {
        if ($('#UseAnalyticsJs').is(':checked')) {
            $('#AnalyticsIDBlock').show();
        }else{
            $('#AnalyticsIDBlock').hide();
        }
    });

    $('#cache').click(function(e) {
        if ($('#cache').is(':checked')) {
            $('#cacheTabNav').show();
        }else{
            $('#cacheTabNav').hide();
        }
    });
    $('#pagespeed').click(function(e) {
        if ($('#pagespeed').is(':checked')) {
            $('#pagespeedTabNav').show();
        }else{
            $('#pagespeedTabNav').hide();
        }
    });

    $("#alias").focus(function() {
        $('.alias').hide();
    });

    $("#path").focus(function() {
        $('.path').hide();
    });

    $("#DurantionDefault").focus(function() {
        $('.DurantionDefault').hide();
    });
    $("#Durantion200").focus(function() {
        $('.Durantion200').hide();
    });
    $("#Durantion301").focus(function() {
        $('.Durantion301').hide();
    });
    $("#Durantion302").focus(function() {
        $('.Durantion302').hide();
    });
    $("#Durantion404").focus(function() {
        $('.Durantion404').hide();
    });

    $('#addDir').click(function(e) {
        e.preventDefault();

        var exist = false;

        $('#excludeDirs li').each(function() {
            if ($(this).text() == $('#excludeCacheDir').val()) {
                bootbox.alert(LG_directory_exist, function() {});
                exist = true;
            }
        });

        var newDir = $('#excludeCacheDir').val();
        elemId = newDir.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')

        if (!exist) {
            var newElement = '<li id="ex_'+elemId+'">'+newDir+' <i class="glyphicon glyphicon-minus" onclick="removeExcludeDir(\'ex_'+elemId+'\')" style="cursor: pointer; top: 2px; color: red"></i></li>';
            $('#excludeDirs').append(newElement);
        }
        $('#excludeCacheDir').val('');
    });

    $('#addDirPs').click(function(e) {
        e.preventDefault();

        var exist = false;

        $('#PsExcludeDir li').each(function() {
            if ($(this).text() == $('#SelxcludeDir').val()) {
                bootbox.alert(LG_directory_exist, function() {});
                exist = true;
            }
        });

        var newDir = $('#SelxcludeDir').val();
        elemId = newDir.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')

        if (!exist) {
            var newElement = '<li id="px_'+elemId+'">'+newDir+' <i class="glyphicon glyphicon-minus" onclick="removeExcludeDir(\'px_'+elemId+'\')" style="cursor: pointer; top: 2px; color: red"></i></li>';
            $('#PsExcludeDir').append(newElement);
        }
        $('#SelxcludeDir').val('');
    });

    $("#saveCache").click(function(e) {
        e.preventDefault();

        var regex = /^([\.a-z\s]+),?([\.a-z\s,]+)$/;
        if (!regex.test($('#excludeCacheFiles').val())) {
            $(".excludeCacheFiles").text(LG_extension_format_wrong).show();
            return;
        }
        if($("#DurantionDefault").val() == "" || $("#DurantionDefault").val() == "0")
        {
            $('.DurantionDefault').text(LG_wrong_cache_time).show();
            return;
        }
        if($("#Durantion200").val() == "" || $("#Durantion200").val() == "0")
        {
            $('.Durantion200').text(LG_wrong_cache_time).show();
            return;
        }
        if($("#Durantion301").val() == "" || $("#Durantion301").val() == "0")
        {
            $('.Durantion301').text(LG_wrong_cache_time).show();
            return;
        }
        if($("#Durantion302").val() == "" || $("#Durantion302").val() == "0")
        {
            $('.Durantion302').text(LG_wrong_cache_time).show();
            return;
        }
        if($("#Durantion404").val() == "" || $("#Durantion404").val() == "0")
        {
            $('.Durantion404').text(LG_wrong_cache_time).show();
            return;
        }

        var result = { };
        var formdata = {};
        var count = 0;
        $('#excludeDirs li').each(function() {
            if($(this).text() != "") {
                result[count++] = $(this).text();
            }
        });

        var $this = $('form#CacheForm')
            , viewArr = $this.serializeArray()
            , cacheSettings = {};

        for (var i in viewArr) {
            cacheSettings[viewArr[i].name] = viewArr[i].value;
        }

        $.ajax({
            url: '/domain/saveCache',
            type: 'post',
            dataType: 'json',
            data: {cacheSettings : JSON.stringify(cacheSettings), domain_id: $('#domain_id').val(), excludeDirs: JSON.stringify(result)},
            success: function(data) {
                if (data.status == "200") {
                    bootbox.alert(LG_changes_successful, function() {});
                }
            }
        });

    });

    $("#saveDomain").click(function(e) {
        e.preventDefault();

        $.ajax({
            url: '/domain/saveDomain',
            type: 'post',
            dataType: 'json',
            data: $('form#DomainForm').serialize(),
            success: function(data) {
                if (data.status == "503") {
                    window.location.href = "/login";
                } else if (data.status == "501") {

                    if (data.path != "") {
                        $('.path').html(data.path).show();
                    }
                    if (data.alias != "") {
                        $('.alias').html(data.alias).show();
                    }
                    if (data.domain != "") {
                        $('.domain').html(data.domain).show();
                    }
                    if (data.field != "") {
                        $('.' + data.field).html(data.domain).show();
                    }

                } else {
                    var update = true;

                    if ($('#domain_id').val() == "") {
                        update = false;
                        $('#domain_id').val(data.domain_id);
                    }
                    $('.err').hide();
                    $('#domain').prop('disabled', true);

                    if (update) {
                        bootbox.alert(LG_changes_successful, function() {
                            //window.location.href = "/domain";
                        });
                    } else {
                        bootbox.alert(LG_add_domain_successful, function() {
                            //window.location.href = "/domain";
                        });
                    }
                }
            }
        });
    });

    $("#savePS").click(function(e) {
        e.preventDefault();

        var result = { };
        var formdata = {};
        var count = 0;
        $('#PsExcludeDir li').each(function() {
            if($(this).text() != "") {
                result[count++] = $(this).text();
            }
        });

        var $this = $('form#PageSpeedForm')
            , viewArr = $this.serializeArray()
            , psSettings = {};

        for (var i in viewArr) {
            psSettings[viewArr[i].name] = viewArr[i].value;
        }

        var data = {
            "UseAnalyticsJs": isChecked($('#UseAnalyticsJs').is(':checked')),
            "AnalyticsID": $('#AnalyticsID').val(),
            "ModifyCachingHeaders": isChecked($('#ModifyCachingHeaders').is(':checked')),
            "XHeaderValue": $('#XHeaderValue').val(),
            "RunExperiment": isChecked($('#RunExperiment').is(':checked')),
            "DisableRewriteOnNoTransform": isChecked($('#DisableRewriteOnNoTransform').is(':checked')),
            "LowercaseHtmlNames": isChecked($('#LowercaseHtmlNames').is(':checked')),
            "PreserveUrlRelativity": isChecked($('#PreserveUrlRelativity').is(':checked')),
            "add_head": isChecked($('#add_head').is(':checked')),
            "combine_css": isChecked($('#combine_css').is(':checked')),
            "combine_javascript": isChecked($('#combine_javascript').is(':checked')),
            "convert_meta_tags": isChecked($('#convert_meta_tags').is(':checked')),
            "extend_cache": isChecked($('#extend_cache').is(':checked')),
            "fallback_rewrite_css_urls": isChecked($('#fallback_rewrite_css_urls').is(':checked')),
            "flatten_css_imports": isChecked($('#flatten_css_imports').is(':checked')),
            "inline_css": isChecked($('#inline_css').is(':checked')),
            "inline_import_to_link": isChecked($('#inline_import_to_link').is(':checked')),
            "inline_javascript": isChecked($('#inline_javascript').is(':checked')),
            "rewrite_css": isChecked($('#rewrite_css').is(':checked')),
            "rewrite_images": isChecked($('#rewrite_images').is(':checked')),
            "rewrite_javascript": isChecked($('#rewrite_javascript').is(':checked')),
            "rewrite_style_attributes_with_url": isChecked($('#rewrite_style_attributes_with_url').is(':checked')),
        };
        console.log(data);


        $.ajax({
            url: '/domain/savePageSpeed',
            type: 'post',
            dataType: 'json',
            data: {psSettings : JSON.stringify(data), domain_id: $('#domain_id').val(), excludeDirs: JSON.stringify(result)},
            success: function(data) {
                if (data.status == "200") {
                    bootbox.alert(LG_changes_successful, function() {});
                }
            }
        });

    });

    $("#domain").focusout(function() {
        console.log($(this).val());
        if ($(this).val().length > 2) {

            domain = $(this).val();
            if (validateDomain(domain)) {

                domain = domain.replace("http://", "");
                domain = domain.replace("www.", "");
                $(this).val(domain);

                $("textarea#alias").text("www." + $(this).val());

                $.post("/domain/DomainAvailable", {
                    domain: $(this).val()
                }, function(data) {
                    if (data.available == 0) {
                        $('#domainStatus').show();
                        if (data.epp == 1) {
                            $('#setAuthcode').show();
                        }
                    }
                });
            }
        }
    });

    $("#alias").focusout(function() {
        if ($(this).val().length > 2 && $('#domain_id').val() == "") {
            $.post("/domain/checkAliasNames", {
                alias: $(this).val()
            }, function(data) {
                if (data.status == "501") {
                    $('.alias').html(data.alias).show();
                }
            });
        }
    });

    $("#domain_redirect").change(function() {
        if ($("#domain_redirect option:selected").val() == 0) {
            $('#setDestination').hide();
        } else {
            $('#setDestination').show();
        }
    });
});

function isChecked(value) {
    if (value == true) {
        return 1;
    }
    return 0;
}

function validateDomain(domain) {
    // strip off "http://" and/or "www."
    domain = domain.replace("http://", "");
    domain = domain.replace("www.", "");

    var reg = /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/;
    return reg.test(domain);
}

function removeExcludeDir(exId)
{
    $('#'+exId).remove();

}
