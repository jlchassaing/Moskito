<div id="user-login">

<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Veuillez vous authentifier</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">



<form action="<?php $this->url("user/login");?>" method="post">


<div class="content">

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



</div>

<div class="buttons">
    <input type="submit" name="LoginButton" value="Enter" />
</div>
</form>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>
</div>