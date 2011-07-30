<div class="install_menu">
<?php $this->includeTpl('menu/rightmenu.tpl.php',array('stepslist'=>$stepslist,'current_step'=>$current_step));?>
</div>
<div class="install_msg">

<form action="<?php $this->url('setup/step');?>" method="post" >

<h2><?php echo $title; ?></h2>

<div class="field">
<label for="login">login</label>
<input type="text" value="admin" name="AdminLoginValue" id="login" /><?php if (isset($message['AdminLoginValue'])) echo "<span class=\"error\">".$message['AdminLoginValue']."</span>";?>
</div>
<div class="field">
<label for="email">email</label>
<input type="text" value="" name="AdminEmailValue" id="email" /> <?php if (isset($message['AdminEmailValue'])) echo "<span class=\"error\">".$message['AdminEmailValue']."</span>";?>
</div>

<div class="field">
<label for="password">password</label>
<input type="password" value="" name="AdminPasswordValue" id="password" /><?php if (isset($message['AdminPasswordValue'])) echo "<span class=\"error\">".$message['AdminPasswordValue']."</span>";?>
</div>

<div class="field">
<label for="check">retype password</label>
<input type="text" value="" name="AdminCheckValue" id="check" /><?php if (isset($message['AdminCheckValue'])) echo "<span class=\"error\">".$message['AdminCheckValue']."</span>";?>
</div>


<div class="buttons">
	<input type="hidden" value="<?php echo $current_step; ?>" name="CurrentStepValue" />
	<input type="hidden" value="OK" name="SetAdminUser" />
	<input type="submit" value="Next" name="NextStepButton" />
</div>

</form>

</div>
<div class="progressbar"></div>