<?php $dataMap = $object->dataMap();?>

<form action="<?php $this->url('/content/action');?>" method="post">

<input type="submit" name="EditButton" value="Modifier" />
<input type="hidden" name="ContentObjectIDValue" value="<?php echo $object->attribute('id')?>" />
</form>

<h1><?php echo $object->attribute('name');?></h1>




<?php foreach ($object->dataMap() as $field):?>

<?php $this->attributeView("field/view",$field);?>

<?php endforeach;?>

