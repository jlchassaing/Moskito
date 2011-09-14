<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Gestion des rôles</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<?php if (count($roles) > 0):?>
<div class="table-content">
<form action="<?php $this->url('role/action');?>" method="post">
<table>
<tr>
<th class="small first"/><th>Nom</th><th class="actions last">Actions</th>
</tr>

<?php foreach ($roles as $key=>$role): ?>
<tr class="<?php if ($key % 2 == 0) echo "dark";else echo "light";?>">
<td class="first"><input type="checkbox" name="roleId[]" value="<?php echo $role->attribute('id');?>" /></td>
<td ><a href="<?php $this->url("role/view/".$role->attribute('id'));?>" ><?php echo $role->attribute('name');?></a></td>
<td class="last">
<a href="<?php $this->url("role/edit/".$role->attribute('id'));?>" title="Editer" >
<img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Edit the role" /></a>
<a href="<?php $this->url("role/delete/".$role->attribute('id'));?>" title="Supprimer" >
<img src="<?php $this->designurl('images/icones/delete-18x18.png');?>" alt="Remove the role" /></a>
</td>
</tr>
<?php endforeach;?>


<tr class="lastline buttons">
<td colspan="3" class="first last"><input type="submit" name="AddNewRole" value="créer un role" /></td>


</tr>

</table>
</form>
</div>
<?php else:?>
<div class="content">
<p>Aucun role définit</p>
</div>
<?php endif;?>



</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>