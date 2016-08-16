                <form id="DomainForm">
                    <input type="hidden" class="domain_id" name="domain_id" id="domain_id" value="<?php if(isset($domain->id)) { echo $domain->id; } ?>">
                        <div class="container-fluid" style="padding-top: 20px;">
                            <div class="col-md-12 col-sm-12 col-xl-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Domainname'); ?>:</label>
                                    <div class="col-sm-4">
                                        <input class="form-control boxed" placeholder="" id="domain" name="domain" <?php if(isset($domain->domain)) { ?>value="<?php echo $domain->domain;?>" disabled="disabled"<?php  } ?>>
                                        <label class="domain err"></label>
                                    </div>
                                    <div class="col-sm-5" id="domainStatus" style="display: none">
                                        <?php echo lang('Domain not available'); ?>
                                    </div>
                                </div>
                                <div class="form-group row" id="setAuthcode" style="display: none">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Authcode'); ?>:</label>
                                    <div class="col-sm-4">
                                        <input class="form-control boxed" placeholder="" id="authcode" name="authcode">
                                        <label class="authcode err"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Aliase'); ?> <br />(<?php echo lang('Only one alias per row'); ?>):</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control boxed" id="alias" name="alias" rows="4"><?php if(isset($domain->alias)) { echo $domain->alias; } ?></textarea>
                                        <label class="alias err"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Path'); ?>:</label>
                                    <div class="col-sm-6">
                                        <input class="form-control boxed" placeholder="" id="path" name="path" value="<?php if(isset($domain->path)) { echo $domain->path; } else { echo "/"; } ?>">
                                        <label class="path err"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('PHP version'); ?>:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control boxed" id="php" name="php">
                                            <option value="5.6.24"<?php if(isset($domain->php_version) && $domain->php_version == "5.6.24") { echo ' selected="selected"'; }?>>Version 5.6.24</option>
                                            <option value="7.0.9"<?php if(isset($domain->php_version) && $domain->php_version == "7.0.9") { echo ' selected="selected"'; }?>>Version  7.0.9.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Domain redirect'); ?>:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control boxed" id="domain_redirect" name="domain_redirect">
                                            <option value=""><?php echo lang('Please select'); ?></option>
                                            <option value="R"<?php if(isset($domain->redirect) && $domain->redirect == "R") { echo ' selected="selected"'; }?>>R  (force=Google rank killer.)</option>
                                            <option value="L"<?php if(isset($domain->redirect) && $domain->redirect == "L") { echo ' selected="selected"'; }?>>L (last rule)</option>
                                            <option value="R,L"<?php if(isset($domain->redirect) && $domain->redirect == "R,L") { echo' selected="selected"'; }?>>R,L</option>
                                            <option value="R=301,L"<?php if(isset($domain->redirect) && $domain->redirect == "R=301,L") { echo ' selected="selected"'; }?>>R=301,L (Permanent)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="setDestination" <?php if(!isset($domain->redirect) || $domain->redirect == "") { echo 'style="display: none"'; } ?>>
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Redirect URL'); ?>:</label>
                                    <div class="col-sm-6">
                                        <input class="form-control boxed" placeholder="" id="destination" name="destination" value="<?php if(isset($domain->redirect_destination)) { echo $domain->redirect_destination; }?>">
                                        <label class="destination err"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('SEO redirect'); ?>:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control boxed" id="seo_redirect" name="seo_redirect">
                                            <option value=""><?php echo lang('Please select'); ?></option>
                                            <option value="1"<?php if(isset($domain->seo) && $domain->seo == "1") { echo ' selected="selected"'; }?>>domain.tld =&gt; www.domain.tld</option>
                                            <option value="2"<?php if(isset($domain->seo) && $domain->seo == "2") { echo ' selected="selected"'; }?>>www.domain.tld =&gt; domain.tld</option>
                                            <option value="3"<?php if(isset($domain->seo) && $domain->seo == "3") { echo ' selected="selected"'; }?>>*.domain.tld =&gt; domain.tld</option>
                                            <option value="4"<?php if(isset($domain->seo) && $domain->seo == "4") { echo ' selected="selected"'; }?>>*.domain.tld =&gt; www.domain.tld</option>
                                            <option value="5"<?php if(isset($domain->seo) && $domain->seo == "5") { echo ' selected="selected"'; }?>>* =&gt; domain.tld</option>
                                            <option value="6"<?php if(isset($domain->seo) && $domain->seo == "6") { echo ' selected="selected"'; }?>>* =&gt; www.domain.tld</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="active" name="active" value="1"<?php if(!isset($domain->active) || (isset($domain->active) && $domain->active == 1) ) { echo 'checked="checked"'; } ?>>
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
                                    <div class="col-sm-4">
                                        <label>
                                           <input class="checkbox" type="checkbox" id="pagespeed" name="pagespeed" value="1" <?php if(isset($domain->pagespeed) && $domain->pagespeed == 1 ) { echo 'checked="checked"'; } ?>>
                                           <span><?php echo lang('pagespeed active'); ?></span>
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
