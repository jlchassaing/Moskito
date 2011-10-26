<?php $dataMap = $object->dataMap();?>
<h1><?php echo $object->attribute('name');?></h1>

<?php echo $dataMap['description']->content()?>


<?php $childrens = lcContentNodeObjectHandler::fetchChildrens($node_id);?>

<?php foreach ($childrens as $item)
{
    $this->moduleView('content','view','line',$item);
}?>

