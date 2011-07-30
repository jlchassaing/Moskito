<?php $dataMap = $object->dataMap();?>


<h1>Accueil : <?php echo $object->attribute('name');?></h1>

<?php echo $dataMap['description']->content()?>
