<form id="PageSpeedForm">
        <div class="container-fluid" style="padding-top: 20px;">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label"><?php echo lang('exclude cache dir'); ?>:</label>
                    <div class="col-sm-6">
                        <div class="input-group add-on">
                            <input class="form-control" placeholder="wp-admin" name="SelxcludeDir" id="SelxcludeDir">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit" id="addDirPs"><i class="glyphicon glyphicon-plus" style="top: 0px"></i></button>
                            </div>
                        </div>
                        <ul id="PsExcludeDir" style="list-style: none; margin-left: -35px;">
                            <?php if(isset($excludePsDirs)) { echo $excludePsDirs; }?>
                        </ul>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">UseAnalyticsJs <a href="https://developers.google.com/speed/pagespeed/module/filter-insert-ga" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="UseAnalyticsJs" name="UseAnalyticsJs" value="1"<?php if(isset($ps->UseAnalyticsJs) && $ps->UseAnalyticsJs == 'on') { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row" id="AnalyticsIDBlock">
                    <label class="col-sm-6 form-control-label">AnalyticsID:</label>
                    <div class="col-sm-4">
                        <input class="form-control boxed" placeholder="" type="text" id="AnalyticsID" name="AnalyticsID" value="<?php if(isset($ps->AnalyticsID)) { echo $ps->AnalyticsID; } else { echo ""; }?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">ModifyCachingHeaders <a href="https://developers.google.com/speed/pagespeed/module/configuration#ModifyCachingHeaders" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="ModifyCachingHeaders" name="ModifyCachingHeaders" value="1"<?php if(isset($ps->ModifyCachingHeaders) && $ps->ModifyCachingHeaders == 'on') { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">XHeaderValue:</label>
                    <div class="col-sm-6">
                        <input class="form-control boxed" placeholder="Powered By ..." type="text" id="XHeaderValue" name="XHeaderValue" value="<?php if(isset($ps->XHeaderValue)) { echo $ps->XHeaderValue; } else { echo ""; }?>">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">RunExperiment <a href="https://developers.google.com/speed/pagespeed/module/experiment" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="RunExperiment" name="RunExperiment" value="1"<?php if(isset($ps->RunExperiment) && $ps->RunExperiment == 'on') { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">DisableRewriteOnNoTransform <a href="https://developers.google.com/speed/pagespeed/module/configuration#notransform" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="DisableRewriteOnNoTransform" name="DisableRewriteOnNoTransform" value="1"<?php if(isset($ps->DisableRewriteOnNoTransform) && $ps->DisableRewriteOnNoTransform == 'on') { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">LowercaseHtmlNames <a href="https://developers.google.com/speed/pagespeed/module/configuration#lower_case" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="LowercaseHtmlNames" name="LowercaseHtmlNames" value="1"<?php if(isset($ps->LowercaseHtmlNames) && $ps->LowercaseHtmlNames == 'on') { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">PreserveUrlRelativity <a href="https://developers.google.com/speed/pagespeed/module/configuration#preserve-url-relativity" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="PreserveUrlRelativity" name="PreserveUrlRelativity" value="1"<?php if(isset($ps->PreserveUrlRelativity) && $ps->PreserveUrlRelativity == 'on') { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
            </div>
            <div style="clear: both"></div>
            <h3 class="subTitle">PageSpeed Filter</h3>

            <div class="col-md-6 col-sm-6 col-xl-6">
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">add_head <a href="https://developers.google.com/speed/pagespeed/module/filter-head-add#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="add_head" name="add_head" value="1"<?php if(isset($ps->add_head) && $ps->add_head == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">combine_css <a href="https://developers.google.com/speed/pagespeed/module/filter-css-combine#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="combine_css" name="combine_css" value="1"<?php if(isset($ps->combine_css) && $ps->combine_css == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">combine_javascript <a href="https://developers.google.com/speed/pagespeed/module/filter-js-combine#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="combine_javascript" name="combine_javascript" value="1"<?php if(isset($ps->combine_javascript) && $ps->combine_javascript == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">convert_meta_tags <a href="https://developers.google.com/speed/pagespeed/module/filter-convert-meta-tags#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="convert_meta_tags" name="convert_meta_tags" value="1"<?php if(isset($ps->convert_meta_tags) && $ps->convert_meta_tags == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">extend_cache <a href="https://developers.google.com/speed/pagespeed/module/filter-cache-extend#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="extend_cache" name="extend_cache" value="1"<?php if(isset($ps->extend_cache) && $ps->extend_cache == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">fallback_rewrite_css_urls <a href="#" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="fallback_rewrite_css_urls" name="fallback_rewrite_css_urls" value="1"<?php if(isset($ps->fallback_rewrite_css_urls) && $ps->fallback_rewrite_css_urls == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">flatten_css_imports <a href="https://developers.google.com/speed/pagespeed/module/filter-css-rewrite#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="flatten_css_imports" name="flatten_css_imports" value="1"<?php if(isset($ps->flatten_css_imports) && $ps->flatten_css_imports == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xl-6">
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">inline_css <a href="https://developers.google.com/speed/pagespeed/module/filter-css-inline#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="inline_css" name="inline_css" value="1"<?php if(isset($ps->inline_css) && $ps->inline_css == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">inline_import_to_link <a href="https://developers.google.com/speed/pagespeed/module/filter-css-inline-import#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="inline_import_to_link" name="inline_import_to_link" value="1"<?php if(isset($ps->inline_import_to_link) && $ps->inline_import_to_link == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">inline_javascript <a href="https://developers.google.com/speed/pagespeed/module/filter-js-inline#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="inline_javascript" name="inline_javascript" value="1"<?php if(isset($ps->inline_javascript) && $ps->inline_javascript == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">rewrite_css <a href="https://developers.google.com/speed/pagespeed/module/filter-css-rewrite#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="rewrite_css" name="rewrite_css" value="1"<?php if(isset($ps->rewrite_css) && $ps->rewrite_css == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">rewrite_images <a href="https://developers.google.com/speed/pagespeed/module/reference-image-optimize#rewrite_images" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="rewrite_images" name="rewrite_images" value="1"<?php if(isset($ps->rewrite_images) && $ps->rewrite_images == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">rewrite_javascript <a href="https://developers.google.com/speed/pagespeed/module/filter-js-minify#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="rewrite_javascript" name="rewrite_javascript" value="1"<?php if(isset($ps->rewrite_javascript) && $ps->rewrite_javascript == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 form-control-label">rewrite_style_attributes_with_url <a href="https://developers.google.com/speed/pagespeed/module/filter-rewrite-style-attributes#description" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>:</label>
                    <div class="col-sm-1">
                        <label>
                           <input class="checkbox" type="checkbox" id="rewrite_style_attributes_with_url" name="rewrite_style_attributes_with_url" value="1"<?php if(isset($ps->rewrite_style_attributes_with_url) && $ps->rewrite_style_attributes_with_url == 1) { echo 'checked="checked"'; } ?>>
                           <span></span>
                       </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <input type="submit" class="btn btn-primary" id="savePS" value="Speichern">
                    </div>
                </div>
            </div>
        </div>
    </form>
