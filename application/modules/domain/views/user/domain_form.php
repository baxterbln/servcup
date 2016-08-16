<article class="content item-editor-page">
    <div class="title-block">
        <h1 class="title"><?php echo $title;?></h1>
    </div>

        <div class="card sameheight-item" data-exclude="xs">
                    <div class="card-header card-header-sm bordered" style="background-color: #f0f3f6;">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" id="domainTabNav"> <a class="nav-link active" href="#domainTab" role="tab" data-toggle="tab" style="font-size: 16px;"><?php echo lang('Domain');?></a> </li>
                            <li class="nav-item" id="cacheTabNav"> <a class="nav-link" href="#cacheTab" role="tab" data-toggle="tab" style="font-size: 16px;"><?php echo lang('Caching');?></a> </li>
                            <li class="nav-item" id="pagespeedTabNav"> <a class="nav-link" href="#pagespeedTab" role="tab" data-toggle="tab" style="font-size: 16px;"><?php echo lang('PageSpeed');?></a> </li>
                        </ul>
                    </div>
                    <div class="card-block">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in" id="domainTab">
                                <?php $this->view('tab_domain.php'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="cacheTab">
                                <?php $this->view('tab_cache.php'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pagespeedTab">
                                <?php $this->view('tab_pagespeed.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>

</article>
