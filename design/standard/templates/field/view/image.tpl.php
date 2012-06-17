<?php if ($data->hasContent()):?>
<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>

	<?php $image = $data->content();?>
	<img src="<?php echo $image->get('resize');?>" />


</div>
<?php endif;?>