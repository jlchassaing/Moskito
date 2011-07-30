<h2>Gestion du contenu</h2>

<div class="menu">
<ul>
<li><a href="<?php $this->url('role/list');?>" >Gestion des roles</a></li>
</ul>
</div>

<?php ?>
<form name="creat" action="<?php $this->url('/users/action');?>" method="post" >

<div class="buttons">
<input type="submit" name="CreateUserButton" value="Ajouter un utilisateur" /> 
</div>
</form>
<table>
<tr>
<th>login</th><th>role </th><th>actions</th>
</tr>
<?php foreach ($users as $item):?>

<tr>
<td><a href="<?php $this->url('user/view/'.$item->attribute('login'));?>" title="preview" ><?php echo $item->attribute('login');?></a></td>
<td><?php echo $item->attribute('role');?></td>
<td>
<form name="form_<?php echo $item->attribute('id'); ?>" action="<?php $this->url('/users/action');?>" method="post" >
<input type="submit" name="EditButton" value="Modifier" />
<input type="submit" name="RemoveButton" value="Supprimer" />
<input type="hidden" name="ContentObjectIDValue" value="<?php echo $item->attribute('id'); ?>" />


</form>
</td>
</tr>

<?php endforeach;?>

</table>