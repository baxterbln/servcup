<article class="content item-editor-page">
    <div class="title-block">
        <h1 class="title"><?php echo $title;?></h1>
    </div>
        <div class="card card-block">
                    <form id="DomainForm">
                    <input type="hidden" class="alias_id" name="alias_id" id="alias_id" value="<?php if(isset($domain->id)) { echo $domain->id; } ?>">
                    <input type="hidden" class="domain_id" name="domain_id" id="domain_id" value="<?php if(isset($domain->parent_id)) { echo $domain->parent_id; } ?>">
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
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Forward type'); ?>:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control boxed" type="text" id="domain_redirect" name="domain_redirect">
                                            <option value=""><?php echo lang('Please select'); ?></option>
                                            <option value="R"<?php if(isset($domain->redirect) && $domain->redirect == "R") { echo ' selected="selected"'; }?>>R  (force=Google rank killer.)</option>
                                            <option value="L"<?php if(isset($domain->redirect) && $domain->redirect == "L") { echo ' selected="selected"'; }?>>L (last rule)</option>
                                            <option value="R,L"<?php if(isset($domain->redirect) && $domain->redirect == "R,L") { echo' selected="selected"'; }?>>R,L</option>
                                            <option value="R=301,L"<?php if(isset($domain->redirect) && $domain->redirect == "R=301,L") { echo ' selected="selected"'; }?>>R=301,L (Permanent)</option>
                                        </select>
                                        <label class="domain_redirect err"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Forward destination'); ?>:</label>
                                    <div class="col-sm-6">
                                        <input class="form-control boxed" placeholder="http://www.domain-target.com" type="text" id="destination" name="destination" value="<?php if(isset($domain->redirect_destination)) { echo $domain->redirect_destination; } ?>">
                                        <label class="destination err"></label>
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
