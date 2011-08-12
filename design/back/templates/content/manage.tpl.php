<h2>Gestion du contenu</h2>
<?php ?>
<form name="creat" action="<?php $this->url('/content/action');?>" method="post" >
<div class="field">
<label>Langue</label>
<select name="contentLanguageValue">
<?php foreach ($languageList as $key=>$lang) :?>
	<option value="<?php echo $key;?>" <?php if ($key == $language) echo "SELECTED";?> ><?php echo $lang;?></option>
<?php endforeach;?>
</select>
</div>
<div class="field">
<label >Type de contenu</label>
<select name="ContentClassIdentifier">
<?php foreach ($classes as $value): ?>
	<option value="<?php echo $value;?>" ><?php echo $value;?></option>
<?php endforeach;?>
</select>
</div>
<div class="buttons">
<input type="submit" name="CreateContentButton" value="Créer" />
</div>
</form>

<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Gérez les sections</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">

<table>
<tr>
<th>nom</th><th>Publié le </th><th>actions</th>
</tr>
<?php foreach ($contents as $key=>$item):?>

<tr class="<?php if ($key % 2 == 0) echo "dark";else echo "light";?>" >
<td><a href="<?php echo $item->urlAlias();?>" title="Object Id : <?php echo $item->attribute('id'); ?>" ><?php echo $item->attribute('object_name');?></a></td>
<td><?php echo date("d-m-Y H:i:s",$item->attribute('created'));?></td>
<td>
<a href="<?php $this->url('content/edit/'.$item->attribute('id'));?>" title="Editer" >
<img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Edit the content" /></a>
<a href="<?php $this->url('content/edit/'.$item->attribute('id'));?>" title="Supprimer" >
<img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Remove the content" /></a>
<form name="form_<?php echo $item->attribute('id'); ?>" action="<?php $this->url('/content/action');?>" method="post" >
<input type="submit" name="EditButton" value="Modifier" />
<input type="submit" name="RemoveButton" value="Supprimer" />
<input type="hidden" name="ContentObjectIDValue" value="<?php echo $item->attribute('id'); ?>" />
<input type="hidden" name="ContentLanguageValue" value="fre-FR" />

</form>
</td>
</tr>

<?php endforeach;?>

</table>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>