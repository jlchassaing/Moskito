<h2>Gestion du contenu</h2>



<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Liste des contenus</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">

<form name="creat" action="<?php $this->url('/content/action');?>" method="post" >
<table>
<tr>
<th class="small first" /><th>nom</th><th>type</th><th>Publié le </th><th class="actions last">actions</th>
</tr>
<?php foreach ($contents as $key=>$item):?>

<tr class="<?php if ($key % 2 == 0) echo "dark";else echo "light";?>" >
<td class="first"><input type="checkbox" name="contentIds[]" value="<?php echo $item->attribute('id'); ?>" /></td>
<td ><a href="<?php echo $item->urlAlias();?>" title="Object Id : <?php echo $item->attribute('id'); ?>" ><?php echo $item->attribute('object_name');?></a></td>
<td><?php echo $item->attribute('class_identifier');?></td>
<td><?php echo date("d-m-Y H:i:s",$item->attribute('created'));?></td>
<td class="last">
<a href="<?php $this->url("content/edit/".$item->attribute('id')."/".$item->attribute('lang'));?>" title="Editer" >
<img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Edit the content" /></a>
<a href="<?php $this->url("content/delete/".$item->attribute('id')."/".$item->attribute('lang'));?>" title="Supprimer" >
<img src="<?php $this->designurl('images/icones/delete-18x18.png');?>" alt="Remove the content" /></a>

</td>
</tr>

<?php endforeach;?>

<tr class="lastline buttons">
<td colspan="2" class="first"><input type="submit" name="DeleteSelectedContent" value="Supprimer" /></td>
<td colspan="3" class="last">
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

<div class="buttons">
<input type="submit" name="CreateContentButton" value="Créer" />
</div>
</div>
</td>
</tr>

</table>
</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>