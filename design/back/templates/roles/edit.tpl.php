
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Créer un role</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content edit">
<?php if (isset($error)):?>
<div class="error">
<h3>Erreur</h3>
<p><?php echo $error;?></p>
</div>
<?php endif;?>
<form action="<?php $this->url('role/edit');?>" method="post" >

<div class="field">

<label for="rolename" >Libellé</label>
<input type="text" name="RoleNameValue" id="rolename" value="<?php if (isset($role)) echo $role->attribute('name');?>" />
</div>

<div class="buttons">
<?php if (isset($role)):?>
<input type="hidden" name="RoleIdValue" value="<?php echo $role->attribute('id');?>"/>
<?php endif;?>

<input type="submit" name="<?php if (isset($role)) echo "SaveButton"; else echo "AddNewButton";?>" value="Enregistrer" />
<input type="cancel" value="Annuler" />
</div>

</form>
</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>