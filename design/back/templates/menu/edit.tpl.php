<h2>Formulaire d'édition de menu</h2>

<form action="<?php $this->url('/content/menu');?>" method="post" >
<div class="field">
<label for="nom">Libellé</label>
<input type="text" name="MenuMameValue" value="<?php echo $menu->attribute('name');?>" />
</div>
<input type="hidden" name="MenuIdValue" value="<?php echo $menu->attribute('id');?>" />
<div class="buttons">
	<input type="submit" name="SaveMenuButton" value="Enregister" />
	<input type="reset" name="CancelButton" value="Annuler" />
</div>

</form>