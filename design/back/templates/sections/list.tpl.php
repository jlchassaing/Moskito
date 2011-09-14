<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Gérez les sections</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">


<form action="<?php $this->url("section/action");?>" method="post">

<?php if (is_array($sections) and count($sections) > 0):?>
<table>

<tr>
<th class="first small" /><th>name</th><th class="actions" >actions</th>
</tr>

<?php foreach($sections as $key=>$section):?>

<tr class="<?php if ($key % 2 == 0) echo "dark";else echo "light";?>">
<td class="first"><input type="checkbox" value="<?php echo $section->attribute('id')?>" name="SectionsId[]" /></td>
<td><a href="<?php $this->url('section/edit/'.$section->attribute('id'))?>" ><?php echo $section->attribute('name'); ?></a> [<?php echo $section->elementsCount();?>]</td>
<td class="last actions"><a href="<?php $this->url('section/assign/'.$section->attribute('name'));?>" title="Ajouter un noeud à la section" >
<img src="<?php $this->designurl('images/icones/add-18x18.png');?>" alt="Add to section" /></a>
<a href="<?php $this->url('section/edit/'.$section->attribute('name'));?>" title="Editer la section" >
<img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Edit the section" /></a>
</td>
</tr>

<?php endforeach;?>

<tr class="lastline  buttons">
<td colspan="3" class="first last">
<div class=buttons>
<input type="submit" name="AddSectionButton" value="Ajouter" />
<input type="submit" name="ModifiySectionButton" value="Modifier" />
<input type="submit" name="RemoveSectionButton" value="Supprimer" />
</div>
</td>
</tr>

</table>
<?php else: ?>

<p>Aucune section défine</p>

<?php endif;?>



</form>



</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>