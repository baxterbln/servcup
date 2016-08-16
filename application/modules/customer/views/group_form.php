    <article class="content item-editor-page">
        <div class="title-block">
            <h1 class="title"><?php echo $title;?></h1>
        </div>
            <div class="card card-block">
                        <form id="GroupForm">
                        <input type="hidden" class="group_id" name="group_id" id="group_id" value="<?php if(isset($group->group_id)) { echo $group->group_id; } ?>">
                            <div class="container-fluid" style="padding-top: 20px;">
                                <div class="col-md-6 col-sm-6 col-xl-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Gruppenname:</label>
                                        <div class="col-sm-6">
                                            <input class="form-control boxed" placeholder="" type="text" id="name" name="name" value="<?php if(isset($group->name)) { echo $group->name; } ?>">
                                            <label class="name err"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid" style="padding-top: 20px; margin-left: -15px;">
                                <div class="col-md-12 col-sm-12 col-xl-12" style="padding-bottom: 20px; ">
                                    <h3><?php echo lang('Manage Customers'); ?></h3>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_customer" <?php if(isset($group->manage_customer) && $group->manage_customer == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_customer'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="add_customer" <?php if(isset($group->add_customer) && $group->add_customer == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('add_customer'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="edit_customer" <?php if(isset($group->edit_customer) && $group->edit_customer == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('edit_customer'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="delete_customer" <?php if(isset($group->delete_customer) && $group->delete_customer == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('delete_customer'); ?></span>
                                           </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12" style="padding-bottom: 20px; ">
                                    <h3><?php echo lang('Manage Groups'); ?></h3>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_groups" <?php if(isset($group->manage_groups) && $group->manage_groups == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_groups'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="add_groups" <?php if(isset($group->add_groups) && $group->add_groups == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('add_groups'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="edit_groups" <?php if(isset($group->edit_groups) && $group->edit_groups == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('edit_groups'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="delete_groups" <?php if(isset($group->delete_groups) && $group->delete_groups == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('delete_groups'); ?></span>
                                           </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12" style="padding-bottom: 20px; ">
                                    <h3><?php echo lang('Manage Billing'); ?></h3>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_billing" <?php if(isset($group->access_billing) && $group->access_billing == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_billing'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_orders" <?php if(isset($group->access_orders) && $group->access_orders == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_orders'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="activate_orders" <?php if(isset($group->activate_orders) && $group->activate_orders == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('activate_orders'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="add_orders" <?php if(isset($group->add_orders) && $group->add_orders == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('add_orders'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="delete_orders" <?php if(isset($group->delete_orders) && $group->delete_orders == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('delete_orders'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_invoice" <?php if(isset($group->access_invoice) && $group->access_invoice == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_invoice'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="add_invoice" <?php if(isset($group->add_invoice) && $group->add_invoice == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('add_invoice'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_products" <?php if(isset($group->access_products) && $group->access_products == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_products'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="add_products" <?php if(isset($group->add_products) && $group->add_products == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('add_products'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="edit_products" <?php if(isset($group->edit_products) && $group->edit_products == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('edit_products'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="delete_products" <?php if(isset($group->delete_products) && $group->delete_products == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('delete_products'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_payments" <?php if(isset($group->access_payments) && $group->access_payments == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_payments'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_overview" <?php if(isset($group->access_overview) && $group->access_overview == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_overview'); ?></span>
                                           </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12" style="padding-bottom: 20px; ">
                                    <h3><?php echo lang('Manage Websites'); ?></h3>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_domain" <?php if(isset($group->manage_domain) && $group->manage_domain == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_domain'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_alias" <?php if(isset($group->manage_alias) && $group->manage_alias == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_alias'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_ssl" <?php if(isset($group->manage_ssl) && $group->manage_ssl == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_ssl'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_cache" <?php if(isset($group->manage_cache) && $group->manage_cache == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_cache'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_ps" <?php if(isset($group->manage_ps) && $group->manage_ps == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_ps'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_cdn" <?php if(isset($group->manage_cdn) && $group->manage_cdn == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_cdn'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_mail" <?php if(isset($group->manage_mail) && $group->manage_mail == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_mail'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_mailfwd" <?php if(isset($group->manage_mailfwd) && $group->manage_mailfwd == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_mailfwd'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_database" <?php if(isset($group->manage_database) && $group->manage_database == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_database'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_ftp" <?php if(isset($group->manage_ftp) && $group->manage_ftp == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_ftp'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_dns" <?php if(isset($group->manage_dns) && $group->manage_dns == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_dns'); ?></span>
                                           </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12" style="padding-bottom: 20px; ">
                                    <h3><?php echo lang('Access to tools'); ?></h3>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_backup" <?php if(isset($group->manage_backup) && $group->manage_backup == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_backup'); ?></span>
                                           </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_tools" <?php if(isset($group->access_tools) && $group->access_tools == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_tools'); ?></span>
                                           </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12" style="padding-bottom: 20px; ">
                                    <h3><?php echo lang('Access to ticketsystem'); ?></h3>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="access_support" <?php if(isset($group->access_support) && $group->access_support == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('access_support'); ?></span>
                                           </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12" style="padding-bottom: 20px; ">
                                    <h3><?php echo lang('Manage Server'); ?></h3>
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>
                                               <input class="checkbox" type="checkbox" id="manage_server" <?php if(isset($group->manage_server) && $group->manage_server == 1 ) { echo 'checked="checked"'; } ?>>
                                               <span><?php echo lang('manage_server'); ?></span>
                                           </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12" style="margin-top: -20px; ">
                                    <div class="form-group">
                                        <div class="col-sm-12 text-right">
                                            <input type="submit" class="btn btn-primary" id="savePermission" value="Speichern">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
            </div>
    </article>
