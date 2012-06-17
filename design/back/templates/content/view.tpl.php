
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle"><?php echo $object->attribute('name')?></h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content">

<?php
$settings = lcSettings::getInstance();
$language = $settings->value('lang','current');
$languageList = $settings->value('lang','available');
$classes = lcContentClass::getAvailableContentClasses();

?>

<?php foreach ($object->dataMap() as $field):?>

<?php $this->attributeView("field/view",$field);?>

<?php endforeach;?>

<form action="<?php $this->url("content/action")?>" enctype="multipart/form-data" method="post">
<div class="buttons">
<input type="hidden" name="ObjectIdValue" value="<?php echo $object->attribute('id');?>" />
<input type="hidden" name="ContentNodeId" value="<?php echo $node_id?>" />
<input type="hidden" name="ContentLanguageValue" value="<?php echo $language;?>" />
<input type="submit" name="EditButton" value="Modifier" />
<input type="submit" name="RemoveButton" value="Supprimer" />
</div>
</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>

<?php
$offset=0; $nbitems=10;



$http = lcHTTPTool::getInstance();
if ($http->hasGetVariable("offset"))
{
    $offset = $http->getVariable("offset");
}

$contents =  lcContentNodeObjectHandler::fetchChildrens($node_id,null,array('priority',true),$nbitems,$offset);
$nb_elements = lcContentNodeObjectHandler::fetchCountChildrens($node_id);
?>

<?php if ($nb_elements > 0):?>



<!--  contenus DEBUT -->

<?php $limits = array(5,10,20,30,50); ?>


<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Liste des contenus <?php $this->includeTpl('content/paging.tpl.php',array('offset'=>$offset,
                                                       'nbitems'=>$nbitems,
                                                       'elements_count' => $nb_elements,
                                                       'url' => $this->url($object->urlAlias(),'no')));?> <form action="<?php $this->url("content/manage");?>" method="post" >Eléments par page <select name="ElementsByPage"><?php foreach ($limits as $value):?>

                                                       <option value="<?php echo $value ?>" <?php if ($nbitems == $value) echo "selected"?> ><?php echo $value?></option>
                                                       <?php endforeach;?>
                                                       </select>
                                                       <input type="submit" value="OK" />
                                                       </form>

                                                       </h2> </div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">

<form name="creat" action="<?php $this->url('/content/action');?>" method="post" >
<table>
<tr>
<th class="small first" /><th>nom</th><th>type</th><th>Publié le </th><th>Priorité</th><th class="actions last">actions</th>
</tr>
<?php foreach ($contents as $key=>$item):?>
<?php $menu = $item->attribute('menu'); ?>
<tr class="<?php if ($key % 2 == 0) echo "dark";else echo "light";?>" >
<td class="first"><input type="checkbox" name="contentIds[]" value="<?php echo $item->attribute('id'); ?>" /></td>
<td ><a href="<?php echo $item->urlAlias();?>" title="Object Id : <?php echo $item->attribute('id'); ?> Node Id : <?php echo $menu->attribute('node_id')?>" >
<?php echo $item->attribute('object_name');?></a></td>
<td><?php echo $item->attribute('class_identifier');?></td>
<td><?php echo date("d-m-Y H:i:s",$item->attribute('created'));?></td>
<td>
<input type="text" value="<?php echo $menu->attribute('sort_val');?>" size="4" name="MenuSortValue[]" />
<input type="hidden" value="<?php echo $menu->attribute('node_id'); ?>" name="NodeIdVAlue[]" />

</td>
<td class="last">
<a href="<?php $this->url("content/edit/".$item->attribute('id')."/".$item->attribute('lang'));?>" title="Editer" >
<img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Edit the content" /></a>
<a href="<?php $this->url("content/delete/".$item->attribute('id')."/".$item->attribute('lang'));?>" title="Supprimer" >
<img src="<?php $this->designurl('images/icones/delete-18x18.png');?>" alt="Remove the content" /></a>
<a href="<?php $this->url("content/move/".$item->attribute('id'));?>" title="Déplacer" >
<img src="<?php $this->designurl('images/icones/fleche-droite-18x18.png');?>" alt="Move the content" /></a>

</td>
</tr>

<?php endforeach;?>

<tr class="lastline buttons">
<td colspan="2" class="first"><input type="submit" name="DeleteSelectedContent" value="Supprimer" /></td>
<td colspan="2" >
<div class=buttons>
<div class="field">
<label >Type de contenu</label>
<select name="ContentClassIdentifier">
<?php foreach ($classes as $value): ?>
    <option value="<?php echo $value;?>" ><?php echo $value;?></option>
<?php endforeach;?>
</select>
</div>
<div class="field">
<label>Langue</label>
<select name="contentLanguageValue">
<?php foreach ($languageList as $key=>$lang) :?>
    <option value="<?php echo $key;?>" <?php if ($key == $language) echo "SELECTED";?> ><?php echo $lang;?></option>
<?php endforeach;?>
</select>
</div>

<div class="float-buttons">
<input type="submit" name="CreateContentButton" value="Créer" />
<input type="hidden" name="ParentNodeIDValue" value="<?php echo $node_id;?>" />
</div>
</div>
</td>

<td colspan="2" class="last" ><input type="submit" name="UpdateMenuSorting" value="Priorités" />
<input type="hidden" name="PageURI" value="<?php echo $GLOBALS['request']['fullrequest']?>" /></td>
</tr>

</table>
</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>


<!--  contenus FIN -->
<?php else:?>
<form name="creat" action="<?php $this->url('/content/action');?>" method="post" >
<div class=buttons>
<div class="field">
<label >Type de contenu</label>
<select name="ContentClassIdentifier">
<?php foreach ($classes as $value): ?>
    <option value="<?php echo $value;?>" ><?php echo $value;?></option>
<?php endforeach;?>
</select>
</div>
<div class="field">
<label>Langue</label>
<select name="contentLanguageValue">
<?php foreach ($languageList as $key=>$lang) :?>
    <option value="<?php echo $key;?>" <?php if ($key == $language) echo "SELECTED";?> ><?php echo $lang;?></option>
<?php endforeach;?>
</select>
</div>

<div class="float-buttons">
<input type="submit" name="CreateContentButton" value="Créer" />
<input type="hidden" name="ParentNodeIDValue" value="<?php echo $node_id;?>" />
</div>
</div>
</form>
<?php endif;?>