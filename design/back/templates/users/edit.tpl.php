
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Création d'un utilisateur</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content edit">
<?php if (isset($error)):?>
<div class="error">
<h3>Erreur</h3>
<p><?php echo $error;?></p>
</div>
<?php endif;?>
<form action="<?php $this->url("user/edit")?>"  method="post">

<div class="field">
<label for="field_login">Login</label>

<input type="text" name="field_login" value="<?php if (isset($user)) echo $user['login'];?>" />
</div>

<div class="field">
<label for="field_pwd">Password</label>

<input type="password" name="field_pwd"  />
</div>

<div class="field">
<label for="field_pwd">Resaisissez le Mot de passe</label>

<input type="password" name="field_pwd_check"  />
</div>

<div class="field">

<label>Role</label>
<select name="RoleIdValue">

<?php foreach ($roles as $key=>$item):?>

<option <?php if (isset($user['role']) and ($item->attribute('name') == $user['role'])) echo "selected";?> value="<?php echo $item->attribute('name');?>"><?php echo $item->attribute('name');?></option>

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

<?php if (isset($user['id']) ):?>
<input type="hidden" name="UserIdValue" value="<?php echo $user['id'];?>"/>
<?php endif;?>
<input type="submit" name="<?php if (isset($user)) echo "SaveButton"; else echo "SaveNewUserButton";?>" value="Enregistrer" />
<input type="button" value="Annuler" />
</div>

</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>