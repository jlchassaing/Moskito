<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<?php if ($data->hasContent()):?>
	<?php $image = $data->content();?>
	<img src="<?php echo $image->get('resize');?>" />
<?php endif;?>

</div>