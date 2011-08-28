<div class="field">
<label for="field_<?php echo $data['Identifier'];?>"><?php echo $data['Name'];?></label>
<?php $disabled = "";
        if (isset($data['FormField']) and $data['FormField'] )
        $disabled = "disabled";
      else
        $disabled = "";
?>
<input type="text" name="field_<?php echo $data['Identifier']."_".$data['DataType'];?>" value="" <?php echo $disabled?> />
</div>