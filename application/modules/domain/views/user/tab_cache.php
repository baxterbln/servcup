<form id="CacheForm">
        <div class="container-fluid" style="padding-top: 20px;">
            <div class="col-md-12 col-sm-12 col-xl-12">
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label"><?php echo lang('exclude cache dir'); ?>:</label>
                    <div class="col-sm-6">
                        <div class="input-group add-on">
                            <input class="form-control" placeholder="wp-admin" name="excludeCacheDir" id="excludeCacheDir">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit" id="addDir"><i class="glyphicon glyphicon-plus" style="top: 0px"></i></button>
                            </div>
                        </div>
                        <ul id="excludeDirs" style="list-style: none; margin-left: -35px;">
                            <?php if(isset($excludeCacheDirs)) { echo $excludeCacheDirs; }?>
                        </ul>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label"><?php echo lang('exclude cache files'); ?>:</label>
                    <div class="col-sm-6">
                        <input class="form-control boxed" <?php if(isset($cache->excludeCacheFiles)) { echo 'value=".'.str_replace("|", ", .", $cache->excludeCacheFiles).'"'; }?> placeholder=".jpg, .gif, .json" id="excludeCacheFiles" name="excludeCacheFiles">
                        <label class="excludeCacheFiles err"></label>
                    </div>
                </div>
                <h3 class="subTitle"><?php echo lang('cache time'); ?></h3>
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Default:</label>
                    <div class="col-sm-1">
                        <input class="form-control boxed" placeholder="30" id="DurantionDefault" name="DurantionDefault" value="<?php if(isset($cache->DurantionDefault)) { echo $cache->DurantionDefault; } else { echo "30"; }?>">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control boxed" id="CacheDurantion" name="CacheDurantion">
                            <option value="m">Minuten</option>
                            <option value="h">Stunden</option>
                            <option  value="d" selected="selected">Tage</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="DurantionDefault err"></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Seiten-Status 200:</label>
                    <div class="col-sm-1">
                        <input class="form-control boxed" placeholder="30" id="Durantion200" name="Durantion200" value="<?php if(isset($cache->Durantion200)) { echo $cache->Durantion200; } else { echo "30"; }?>">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control boxed" id="CacheDurantion200" name="CacheDurantion200">
                            <option value="m">Minuten</option>
                            <option value="h">Stunden</option>
                            <option  value="d" selected="selected">Tage</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="Durantion200 err"></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Seiten-Status 301:</label>
                    <div class="col-sm-1">
                        <input class="form-control boxed" placeholder="30" id="Durantion301" name="Durantion301" value="<?php if(isset($cache->Durantion301)) { echo $cache->Durantion301; } else { echo "30"; }?>">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control boxed" id="CacheDurantion301" name="CacheDurantion301">
                            <option value="m" selected="selected">Minuten</option>
                            <option value="h">Stunden</option>
                            <option value="d">Tage</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="Durantion301 err"></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Seiten-Status 302:</label>
                    <div class="col-sm-1">
                        <input class="form-control boxed" placeholder="30" id="Durantion302" name="Durantion302" value="<?php if(isset($cache->Durantion302)) { echo $cache->Durantion302; } else { echo "30"; }?>">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control boxed" id="CacheDurantion302" name="CacheDurantion302">
                            <option value="m" selected="selected">Minuten</option>
                            <option value="h">Stunden</option>
                            <option value="d">Tage</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="Durantion302 err"></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Seiten-Status 404:</label>
                    <div class="col-sm-1">
                        <input class="form-control boxed" placeholder="30" id="Durantion404" name="Durantion404" value="<?php if(isset($cache->Durantion404)) { echo $cache->Durantion404; } else { echo "1"; }?>">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control boxed" id="CacheDurantion404" name="CacheDurantion404">
                            <option value="m" selected="selected">Minuten</option>
                            <option value="h">Stunden</option>
                            <option value="d">Tage</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="Durantion404 err"></label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <input type="submit" class="btn btn-primary" id="saveCache" value="Speichern">
                    </div>
                </div>
            </div>
        </div>
    </form>
