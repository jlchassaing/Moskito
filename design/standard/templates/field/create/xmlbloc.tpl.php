<?php $this->require_script(array("jquery-1.4.3.min.js","tinymce/tiny_mce.js"));?>
<script type="text/javascript">
	tinyMCE.init({
		theme : "advanced",
        mode : "textareas",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true


			});
</script>
<div class="field">
<label for="field_<?php echo $data['Identifier'];?>"><?php echo $data['Name'];?></label>
<textarea name="field_<?php echo $data['Identifier']."_".$data['DataType'];?>" id="elm1" name="elm1"  ></textarea>
</div>