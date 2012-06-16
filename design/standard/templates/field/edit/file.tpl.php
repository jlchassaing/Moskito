<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<?php if ($data->hasContent()):?>
	<?php $file = $data->content();?>
	<a href="<?php echo $file->url();?>" ><?php echo $file->fileName();?></a>
<?php endif;?>
<input type="file" name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>" value="" />
</div>