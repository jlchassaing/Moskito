<div class="install_menu">
<?php $this->includeTpl('menu/rightmenu.tpl.php',array('stepslist'=>$stepslist,'current_step'=>$current_step));?>
</div>
<div class="install_msg">

<form action="<?php $this->url('setup/step');?>" method="post" >

<h2><?php echo $title; ?></h2>

<?php if (isset($message) and $message != ""):?>
<div class="error"><?php echo $message; ?></div>
<?php endif;?>

<p>Fill in the form with your database settings.</p>

<div class="field">
<label for="host">hostname</label>
<input type="text" name="DBHostNameValue" />
</div>

<div class="field">
<label for="port">port</label>
<input type="text" name="DBPortNameValue" />
</div>

<div class="field">
<label for="username">username</label>
<input type="text" name="DBUserNameValue" />
</div>

<div class="field">
<label for="password">password</label>
<input type="text" name="DBPasswordNameValue" />
</div>

<div class="buttons">
	<input type="hidden" value="<?php echo $current_step; ?>" name="CurrentStepValue" />
	<input type="hidden" name="SetDBSettings" value="1" />
	<input type="submit" value="Next" name="NextStepButton" />
</div>

</form>

</div>
<div class="progressbar"></div>