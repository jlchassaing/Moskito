
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Edition de contenu</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content edit">



<form action="<?php $this->url("content/edit")?>" enctype="multipart/form-data" method="post">

<?php foreach ($object->dataMap() as $field):?>

<?php $this->attributeView("field/edit",$field);?>

<?php endforeach;?>



<?php $parent=0;$path=0; $prec=0;$parentArray=array();$decal = "";?>
<div class="field">
Ajouter dans le menu <input type="checkbox" name="AddToAMenu" />
<label>Position dans le menu : parent</label>
<select name="MenuParentValue">
<option value="0" <?php if (isset($menu['parent_node_id']) AND $menu['parent_node_id'] == 0) echo "SELECTED";?> >Root</option>
<?php foreach ($fullMenu as $key=>$item):?>
<?php $path=explode("/", $item['path_ids']);
$decal=""; for ($i=2;$i<count($path);$i++) $decal = $decal."---";?>


<option <?php if (isset($menu['parent_node_id']) AND $menu['parent_node_id'] == $item['node_id']) echo "SELECTED";?> value="<?php echo $item['node_id'];?>"><?php echo $decal.' '.$item['name'];?></option>


<?php endforeach;?>
</select>
</div>


<?php /* <div class="field">
<label>Nom dans le menu</label>
<input type="text" value="<?php if( isset($menu['name'])) echo $menu['name'];?>" name="MenuNameValue" />*/
if (isset($menu['id'])):?>
<input type="hidden" name="ContentMenuIdValue" value="<?php echo $menu['id'];?>" />
<?php endif;?>



<div class="buttons">
<input type="hidden" name="ObjectIdValue" value="<?php echo $object->attribute('id');?>" />
<input type="hidden" name="ContentLanguageValue" value="<?php echo $lang;?>" />
<input type="submit" name="SaveButton" value="Enregistrer" />
<input type="button" value="Annuler" />
</div>

</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>