<div class="install_menu">
<?php $this->includeTpl('menu/rightmenu.tpl.php',array('stepslist'=>$stepslist,'current_step'=>$current_step));?>
</div>
<div class="install_msg">

<form action="<?php $this->url('setup/step');?>" method="post">

<h2><?php echo $title ?></h2>

<?php if (!$message['create']):?>

<p>Could not create some directories please make sure that the "var" directory is not missing.</p>
<p>If so please execute the command below:</p>

<pre>
mkdir var
</pre>

<?php endif;?>
<?php if (!$message['write']):?>

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
