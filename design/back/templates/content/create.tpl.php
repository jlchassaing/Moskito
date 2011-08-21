
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Création de contenu</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content edit">

<form action="<?php $this->url("content/create")?>" enctype="multipart/form-data"  method="post">

<?php foreach ($class['Fields'] as $field):?>

<?php $this->attributeView("field/create",$field);?>

<?php endforeach;?>


<div class="field">
<label>Parent</label>
<select name="MenuParentValue">
<option value="0" >Root</option>
<?php foreach ($fullMenu as $key=>$item):?>
<?php $path=explode("/", $item['path_ids']);$parent = (int) $item['parent_node_id'];?>

<?php $decal=""; for ($i=2;$i<count($path);$i++) $decal = $decal."---";?>
<option value="<?php echo $item['node_id'];?>"><?php echo $decal.' '.$item['object_name'];?></option>

<?php $prec = count($path);?>
<?php endforeach;?>

</select>
</div>

<?php /*
<div class="field">
<label>Nom dans le menu</label>
<input type="text" value="" name="MenuNameValue" />
</div>
*/ ?>

<div class="buttons">
<input type="hidden" name="ClassId" value="<?php echo $class_id;?>" />
<input type="hidden" name="LanguageValue" value="<?php echo $lang;?>" />
<input type="submit" name="CreateButton" value="Enregistrer" />
<input type="button" value="Annuler" />
</div>

</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>