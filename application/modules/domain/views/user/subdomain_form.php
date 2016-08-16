<article class="content item-editor-page">
    <div class="title-block">
        <h1 class="title"><?php echo $title;?></h1>
    </div>
        <div class="card card-block">
                    <form id="DomainForm">
                    <input type="hidden" class="domain_id" name="domain_id" id="domain_id" value="<?php if(isset($domain->id)) { echo $domain->parent_id; } ?>">
                    <input type="hidden" class="domain_id" name="sub_id" id="sub_id" value="<?php if(isset($domain->parent_id)) { echo $domain->id; } ?>">
                        <div class="container-fluid" style="padding-top: 20px;">
                            <div class="col-md-12 col-sm-12 col-xl-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Domainname'); ?>:</label>
                                    <div class="col-sm-2">
                                        <input class="form-control boxed" placeholder="subdomain" type="text" id="sub" name="sub" <?php if(isset($sub)) { ?>value="<?php echo $sub;?>" disabled="disabled"<?php  } ?>>
                                        <label class="domain err"></label>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control boxed" type="text" id="domain" name="domain" <?php if(isset($domain->parent_id)) { echo 'disabled="disabled"'; } ?>>
                                            <?php
                                            foreach ($domains as $key => $value) {
                                                if(isset($domain->parent_id) && $value->id == $domain->parent_id) {
                                                    echo '<option value="'.$value->id.'" selected="selected">'.$value->domain.'</option>';
                                                }else{
                                                    echo '<option value="'.$value->id.'">'.$value->domain.'</option>';
                                                }
                                            }
                                             ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Path'); ?>:</label>
                                    <div class="col-sm-6">
                                        <input class="form-control boxed" placeholder="/htdocs/folder" data-provide="typeahead" autocomplete="off" type="text" id="path" name="path" value="<?php if(isset($domain->path)) { echo $domain->path; } ?>">
                                        <label class="path err"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('PHP version'); ?>:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control boxed" type="text" id="php" name="php">
                                            <option value="5.6.24"<?php if(isset($domain->php_version) && $domain->php_version == "5.6.24") { echo ' selected="selected"'; }?>>Version 5.6.24</option>
                                            <option value="7.0.9"<?php if(isset($domain->php_version) && $domain->php_version == "7.0.9") { echo ' selected="selected"'; }?>>Version  7.0.9.</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="active" name="active" value="1"<?php if(isset($domain->active) && $domain->active == 1 ) { echo 'checked="checked"'; } ?>>
                                           <span><?php echo lang('Domain active'); ?></span>
                                       </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="cache" name="cache" value="1" <?php if(isset($domain->cache) && $domain->cache == 1 ) { echo 'checked="checked"'; } ?>>
                                           <span><?php echo lang('Caching active'); ?></span>
                                       </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="cgi" name="cgi" value="1" <?php if(isset($domain->cgi) && $domain->cgi == 1 ) { echo 'checked="checked"'; } ?>>
                                           <span><?php echo lang('CGI active'); ?></span>
                                       </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="ssi" name="ssi" value="1" <?php if(isset($domain->ssi) && $domain->ssi == 1 ) { echo 'checked="checked"'; } ?>>
                                           <span><?php echo lang('SSI active'); ?></span>
                                       </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="ruby" name="ruby" value="1" <?php if(isset($domain->ruby) && $domain->ruby == 1 ) { echo 'checked="checked"'; } ?>>
                                           <span><?php echo lang('ruby active'); ?></span>
                                       </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="python" name="python" value="1" <?php if(isset($domain->python) && $domain->python == 1 ) { echo 'checked="checked"'; } ?>>
                                           <span><?php echo lang('python active'); ?></span>
                                       </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <div class="col-sm-12 text-right">
                                            <input type="submit" class="btn btn-primary" id="saveDomain" value="Speichern">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </form>
        </div>
</article>
