
<form action="<?php $this->url('/content/form');?>" method="post">


<h2><?php echo $object->attribute('name')?></h2>

<?php foreach ($object->dataMap() as $field):?>


<?php if($field->attribute('identifier') != 'recipient' and $field->attribute('identifier') != 'name')
{
    $this->attributeView("field/view",$field);
}?>

<?php endforeach;?>
<input type="hidden" name="ObjectIdValue" value="<?php echo $object->attribute('id');?>" />
<input type="submit" name="SendFormButton" value="Envoyer" />
</form>