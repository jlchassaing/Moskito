<div class="install_menu">
<?php $this->includeTpl('menu/rightmenu.tpl.php',array('stepslist'=>$stepslist,'current_step'=>$current_step));?>
</div>
<div class="install_msg">

<form action="<?php $this->url('setup/step');?>" method="post">

<h2><?php echo $title ?></h2>

<?php if ($message == "not-writable"):?>

<p>Some directories are not writable by the account running apache.</p>

<?php $apuser = exec('whoami');?>
<pre>
cd <?php echo $GLOBALS['SETTINGS']['siteRootDir'];?>

chown -R <?php echo "$apuser:$apuser"?> var settings
chmod -R ug+w var settings
</pre>

<?php endif;?>

<div class="buttons">
	<input type="hidden" value="<?php echo $current_step + 1; ?>" name="CurrentStepValue" />
	<input type="submit" value="Next" name="NextStepButton" />
</div>

</form>
</div>
<div class="progressbar"></div>
