

<h2>Gestion du contenu</h2>

<div class="menu">
<ul>
<li><a href="<?php $this->url('role/list');?>" >Gestion des roles</a></li>
</ul>
</div>

<?php ?>

<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Liste des utilisateurs</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">

<form name="creat" action="<?php $this->url('/users/action');?>" method="post" >

<div class="buttons">

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
<a href="<?php $this->url('users/edit/'.$item->attribute('id'));?>" title="Edit the user" >
<img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Edit" /></a>
<a href="<?php $this->url("users/delete/".$item->attribute('id')."/".$item->attribute('lang'));?>" title="Remove" >
<img src="<?php $this->designurl('images/icones/delete-18x18.png');?>" alt="Remove the content" /></a>

</td>
</tr>

<?php endforeach;?>

<tr class="lastline light buttons">
<td colspan="3" class="first last">
<div class=buttons>
<input type="submit" name="CreateUserButton" value="Ajouter un utilisateur" />
</div>
</td>
</tr>

</table>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>