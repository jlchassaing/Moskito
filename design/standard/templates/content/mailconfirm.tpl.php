<h2>Your mail has been sent with this datas</h2>
<?php foreach ($Object->dataMap() as $field):?>

<div class="field">
<h3 class="field_name"><?php echo $field->attribute('name');?></h3>
<div class="field_content"><?php echo $field->content()?></div>
</div>
<?php endforeach;?>