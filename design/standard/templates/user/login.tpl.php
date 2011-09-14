<div id="user-login">
<h2>Veuillez vous authentifier</h2>
<form action="<?php $this->url("user/login");?>" method="post">

<div class="field">
	<label for="login" >Login</label>
	<input type="text" name="login" />
</div>

<div class="field">
	<label for="password" >Password</label>
	<input type="password" name="password" />
</div>

<?php if (isset($_SESSION['last_request'])): ?>
<input type="hidden" name="redirect" value="<?php echo $_SESSION['last_request'];?>" />
<?php endif;?>

<?php if (isset($redirect_to)): ?>
<input type="hidden" name="redirect" value="<?php echo $redirect_to;?>" />
<?php endif;?>

<div class="buttons">
	<input type="submit" name="LoginButton" value="Enter" />
</div>
</form>
</div>