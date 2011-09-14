<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr-FR" lang="fr-FR">
<head>
    <title>LiteCms / code name : moskito </title>
	<meta name="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Content-language" content="fr-FR" />
	<?php lcStyleLoader::load('litecms.css');?>

	<?php lcScriptLoader::load();?>

</head>
<body>



<div id="page">

<div id="page-content">

<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content">

<div class="header">
	<div id="title">
	<h1><a href="<?php $this->url('/back');?>" ><img src="<?php $this->designurl("images/moskito.png");?>" /></a></h1>
	</div>

	<div id="user">
		<?php $user = lcUser::getCurrentUser()?>
		<?php if ($user):?>
		<p>Bonjour <span class="username"> <?php echo $user->attribute('login');?></span> | paramètres | <a href="<?php $this->url('/user/logout');?>" class="disconnect" >se déconnecter</a></p>
		<?php endif;?>

</div>
</div>
<?php echo $MainResult;?>

</div>
</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>

</div>

</div>
<div id="fond"></div>
<!-- DEBUG -->
</body>
</html>