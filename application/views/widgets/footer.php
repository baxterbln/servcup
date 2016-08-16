<!-- Reference block for JS -->
<div class="ref" id="ref">
    <div class="color-primary"></div>
    <div class="chart">
        <div class="color-primary"></div>
        <div class="color-secondary"></div>
    </div>
</div>
<script src="/assets/js/vendor.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/jquery.validate.min.js"></script>
<script src="/assets/js/additional-methods.js"></script>
<script src="/assets/js/bootbox.min.js"></script>
<?php
if (isset($jsFiles) && count($jsFiles) > 0) {
foreach ($jsFiles as $key => $value):?>
<script src="<?php echo MODPATH; ?>assets/js/<?php echo $value;?>"></script>
<?php
endforeach;
}
?>
<script src="/assets/js/bootstrap-table.min.js"></script>
<script src="/assets/js/bootstrap-table.min.js"></script>
<script src="/assets/js/extensions/export/bootstrap-table-export.min.js"></script>
<script src="/assets/js/tableExport.min.js"></script>
<script src="/assets/js/locale/bootstrap-table-<?php echo $this->session->userdata('short_lang');?>.js"></script>
<script src="<?php if(isset($jsLang)) { echo $jsLang; }?>"></script>
<script src="/assets/js/locale/validator_<?php echo $this->session->userdata('short_lang');?>.js"></script>
</body>

</html>
