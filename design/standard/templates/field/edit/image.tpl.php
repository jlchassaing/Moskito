<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<?php if ($data->hasContent()):?>
	<?php $image = $data->content();?>
	<img src="<?php echo $image->get('resize');?>" />
<?php endif;?>
<input type="file" name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>" value="" />
</div>