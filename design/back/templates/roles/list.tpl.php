<h2>Gestion des rôles</h2>
<p><a href="<?php $this->url("role/add");?>" >ajouter un role</a></p>
<?php if (count($roles) > 0):?>

<table>
<tr>
<th /><th>Nom</th><th>Actions</th>
</tr>

<?php foreach ($roles as $role): ?>
<tr>
<td><input type="checkbox" name="roleId[]" value="<?php echo $role->attribute('id');?>" /></td>
<td><a href="<?php $this->url("role/view/".$role->attribute('id'));?>" ><?php echo $role->attribute('name');?></a></td>
</tr>
<?php endforeach;?>
</table>
<?php else:?>
<p>Aucun role définit</p>

<?php endif;?>