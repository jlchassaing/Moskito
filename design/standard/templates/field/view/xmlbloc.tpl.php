<?php if ($data->attribute('formfield')):?>
<?php $this->require_script(array("jquery-1.4.3.min.js","tinymce/tiny_mce.js"));?>

<script type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
        mode : "textareas",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,



			});
</script>
<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<textarea name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>" id="<?php echo "xmlEdit_".$data->attribute('id');?>" name="elm1"   ><?php echo $data->content();?></textarea>
</div>

<?php else:?>

<div class="<?php echo $data->attribute('identifier');?>">
<?php echo $data->content();?>
</div>
<?php endif;?>