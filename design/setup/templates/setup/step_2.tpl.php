<div class="install_menu">
<?php $this->includeTpl('menu/rightmenu.tpl.php',array('stepslist'=>$stepslist,'current_step'=>$current_step));?>
</div>
<div class="install_msg">

<form action="<?php $this->url('setup/step');?>" method="post" >

<h2><?php echo $title; ?></h2>

<p>Select the database to use for this installation.</p>

<div class="field">
<label for="host">databases</label>
<select name="DataBaseNameValue">
<?php foreach ($dbs as $db):?>
<option value="<?php echo $db;?>"><?php echo $db;?></option>
<?php endforeach;?>
</select>

</div>


<div class="buttons">
	<input type="hidden" value="<?php echo $current_step; ?>" name="CurrentStepValue" />
	<input type="hidden" name="SetDBSettings" value="1" />
	<input type="submit" value="Next" name="NextStepButton" />
</div>

</form>

</div>
<div class="progressbar"></div>