
<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<?php $disabled = "";
        if ($data->attribute('formfield'))
        $disabled = "disabled";
      else
        $disabled = "";
?>
<input type="text" name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>" <?php echo $disabled;?> value="<?php echo $data->content()?>" />
</div>