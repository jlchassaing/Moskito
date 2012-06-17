<h2><?php echo $object->attribute('name')?></h2>

<?php foreach ($object->dataMap() as $field):?>

<?php $this->attributeView("field/view",$field);?>

<?php endforeach;?>
