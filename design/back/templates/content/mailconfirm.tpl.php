<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Your mail has been sent with this datas</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content">

<?php foreach ($Object->dataMap() as $field):?>

<div class="field">
<h3 class="field_name"><?php echo $field->attribute('name');?></h3>
<div class="field_content"><?php echo $field->content()?></div>
</div>
<?php endforeach;?>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>