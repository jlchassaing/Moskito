<?php if ($data->attribute('formfield')):?>

<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<textarea name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>"     ><?php echo $data->content();?></textarea>
</div>

<?php else:?>

<div class="<?php echo $data->attribute('identifier');?>">
<?php echo $data->content();?>
</div>
<?php endif;?>