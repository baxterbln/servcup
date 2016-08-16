<form id="SSLForm">
                    <input type="hidden" class="domain_id" name="domain_id" id="domain_id" value="<?php if(isset($domain->id)) { echo $domain->id; } ?>">
                        <div class="container-fluid" style="padding-top: 20px;">
                            <div class="col-md-12 col-sm-12 col-xl-12">
                                <div class="form-group row" id="setAuthcode">
                                    <label class="col-sm-3 form-control-label"></label>
                                    <div class="col-sm-4">
                                        <input type="submit" class="btn btn-primary" id="generateCert" value="<?php echo lang('Generate certificate'); ?>">
                                        <label class="authcode err"></label>
                                    </div>
                                </div>
                                <div class="form-group row" id="setAuthcode">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Certificate'); ?> (.crt):</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control boxed" id="alias" name="alias" rows="4"><?php if(isset($domain->SSLCertificateFile)) { echo $domain->SSLCertificateFile; } ?></textarea>
                                        <label class="authcode err"></label>
                                    </div>
                                </div>
                                <div class="form-group row" id="setAuthcode">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Certificate Key'); ?>:</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control boxed" id="alias" name="alias" rows="4"><?php if(isset($domain->SSLCertificateKeyFile)) { echo $domain->SSLCertificateKeyFile; } ?></textarea>
                                        <label class="authcode err"></label>
                                    </div>
                                </div>
                                <div class="form-group row" id="setAuthcode">
                                    <label class="col-sm-3 form-control-label"><?php echo lang('Certificate Chain'); ?>:</label>
                                    <div class="col-sm-6">
                                        <textarea class="form-control boxed" id="alias" name="alias" rows="4"><?php if(isset($domain->SSLCertificateChainFile)) { echo $domain->SSLCertificateChainFile; } ?></textarea>
                                        <label class="authcode err"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <div class="col-sm-12 text-right">
                                            <input type="submit" class="btn btn-primary" id="saveSSL" value="Speichern">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </form>
