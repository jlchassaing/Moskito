
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle"><?php echo $object->attribute('name')?></h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content">



<?php foreach ($object->dataMap() as $field):?>

<?php $this->attributeView("field/view",$field);?>

<?php endforeach;?>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>