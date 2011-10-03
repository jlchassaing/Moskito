<?php $disabled = "";
        if ($data->attribute('formfield'))
        $disabled = "disabled";
      else
        $disabled = "";
?>
<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<textarea name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>"  <?php echo $disabled;?>  ><?php echo $data->content();?></textarea>
</div>