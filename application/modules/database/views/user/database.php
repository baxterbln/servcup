<article class="content responsive-tables-page">
                    <div class="title-block">
                        <h1 class="title"><?php echo lang('Manage databases') ?></h1>
                    </div>
                    <section class="section">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-block" style="min-height: 500px;">
                                        <section class="dbtable">
                                            <div class="table-responsive">
                                                <table id="dblist" class="table table-striped table-hover"
                                                   data-search="false"
                                                   data-show-refresh="true"
                                                   data-show-toggle="false"
                                                   data-show-export="true"
                                                   data-minimum-count-columns="2"
                                                   data-show-pagination-switch="true"
                                                   data-pagination="true"
                                                   data-id-field="id"
                                                   data-page-list="[10, 25, 50, 100, ALL]"
                                                   data-show-footer="false"
                                                   data-url="/database/getDatabases"
                                                   data-pagination="true"
                                                   data-side-pagination="server">
                                                </table>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-block">
                                        <section class="actions">
                                            <h3 class="title formtitle" style="padding-bottom: 40px;"><?php echo lang('Add DB'); ?></h3>
                                            <form id="saveDBForm">
                                                <input type="hidden" name="db_id" id="db_id" value="">
                                                <div class="form-group row" style="padding-bottom: 20px;">
                                                    <label class="col-sm-4 form-control-label"><?php echo lang('Database Name'); ?>:</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control boxed" type="text" autocomplete="off" id="dbname" name="dbname">
                                                        <label class="dbname err"></label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 form-control-label"><?php echo lang('Username'); ?>:</label>
                                                    <div class="col-sm-8">
                                                        <select id="username" name="username" class="form-control boxed">
                                                            <?php
                                                            if(isset($users)) {
                                                                foreach ($users as $key => $value) {
                                                                    echo '<option value="'. $value->username .'">'. $value->username .'</option>';
                                                                }
                                                            }else{
                                                                echo '<option value="">'. lang('No user exist').'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label class="username err"></label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-12 text-right">
                                                        <input type="submit" class="btn btn-primary" id="saveDB" value="Speichern">
                                                    </div>
                                                </div>
                                            <form>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </article>
