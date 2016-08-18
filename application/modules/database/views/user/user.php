<article class="content responsive-tables-page">
                    <div class="title-block">
                        <h1 class="title"><?php echo lang('Manage database user') ?></h1>
                    </div>
                    <section class="section">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-block" style="min-height: 500px;">
                                        <section class="usertable">
                                            <div class="table-responsive">
                                                <table id="userlist" class="table table-striped table-hover"
                                                   data-search="false"
                                                   data-show-refresh="true"
                                                   data-show-toggle="true"
                                                   data-show-export="true"
                                                   data-minimum-count-columns="2"
                                                   data-show-pagination-switch="true"
                                                   data-pagination="true"
                                                   data-id-field="id"
                                                   data-page-list="[10, 25, 50, 100, ALL]"
                                                   data-show-footer="false"
                                                   data-url="/database/getUsers"
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
                                            <h3 class="title" style="padding-bottom: 40px;">Benutzer hinzufügen</h3>
                                            <form id="dbUserAdd">
                                                <div class="form-group row" style="padding-bottom: 20px;">
                                                    <label class="col-sm-4 form-control-label"><?php echo lang('Username'); ?>:</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control boxed" type="text" autocomplete="off" id="username" name="username">
                                                        <label class="username err"></label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 form-control-label"><?php echo lang('Password'); ?>:</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control boxed" type="password" autocomplete="off" id="password" name="password">
                                                        <label class="password err"></label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 form-control-label"><?php echo lang('Password repeat'); ?>:</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control boxed" type="password" autocomplete="off" id="password_repeat" name="password_repeat">
                                                        <label class="password_repeat err"></label>
                                                        <span style="padding-top: 5px; float: right"><button type="button" class="btn btn-primary" name="generate" id="generate"><?php echo lang('generate'); ?></button></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label class="col-sm-4 form-control-label" style="line-height: 17px;"><?php echo lang('allow remote access'); ?>:</label>
                                                    <div class="col-sm-8">
                                                        <label>
                                                            <input class="checkbox" type="checkbox" id="remote" name="remote" value="1">
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-12 text-right">
                                                        <input type="submit" class="btn btn-primary" id="addUser" value="Speichern">
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
