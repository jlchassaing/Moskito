
<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<input type="text" name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>" value="<?php echo $data->content()?>" />
</div>