
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Formulaire d'édition de menu</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content edit">

<?php if (isset($error)):?>

<div class="error">
<?php echo $error;?>
</div>
<?php endif;?>
<?php if (isset($menu)):?>
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
<?php endif;?>
</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>