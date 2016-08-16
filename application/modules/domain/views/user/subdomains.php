<article class="content responsive-tables-page">
                    <div class="title-block">
                        <h1 class="title"><?php echo lang('Manage subdomains') ?></h1>
                    </div>
                    <section class="section">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <section class="example">
                                            <div class="table-responsive">
                                                <div id="toolbar">
                                                    <a href="/domain/addSubdomain" type="button" class="btn btn-primary"><?php echo lang('Add subdomain') ?></a>
                                                </div>
                                                <table id="subdomainlist" class="table table-striped table-hover"
                                                   data-toolbar="#toolbar"
                                                   data-search="true"
                                                   data-show-refresh="true"
                                                   data-show-toggle="true"
                                                   data-show-export="true"
                                                   data-minimum-count-columns="2"
                                                   data-show-pagination-switch="true"
                                                   data-pagination="true"
                                                   data-id-field="id"
                                                   data-page-list="[10, 25, 50, 100, ALL]"
                                                   data-show-footer="false"
                                                   data-url="/domain/getSubdomains"
                                                   data-pagination="true"
                                                   data-side-pagination="server">
                                                </table>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </article>
