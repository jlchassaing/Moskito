<h2>Gestion des rôles</h2>
<h3>Ajouter un rôle</h3>
<?php if (isset($error)):?>
<div class="error">
<h3>Erreur</h3>
<p><?php echo $error;?></p>
</div>
<?php endif;?>
<form action="<?php $this->url('role/add');?>" method="post" >

<div class="field">

<label for="rolename" >Libellé</label>
<input type="text" name="RoleNameValue" id="rolename" value="" />
</div>

<div class="buttons">

<input type="submit" name="SaveButton" value="Enregistrer" />
<input type="cancel" value="Annuler" />
</div>

</form>