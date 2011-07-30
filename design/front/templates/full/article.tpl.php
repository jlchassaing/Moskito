<?php $dataMap = $object->dataMap();?>
<h1><?php echo $object->attribute('name');?></h1>

<?php echo $dataMap['description']->content()?>
