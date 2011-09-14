<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr-FR" lang="fr-FR">
<head>
    <title>LiteCms / code name : moskito </title>
	<meta name="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Content-language" content="fr-FR" />
	<?php lcStyleLoader::load('litecms.css');?>

	<!--[if lt IE 9]>
	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	<![endif]-->

	<?php lcScriptLoader::load();?>

</head>
<body>
<div id="page" class="clear">
<div id="header">
<div id="title">
<h1><a href="<?php $this->url('/back');?>" ><img src="<?php $this->designurl("images/moskito/logo.png");?>"  alt="Moskito lite CMS"/></a></h1>
</div>
<div id="user">
		<?php $user = lcUser::getCurrentUser()?>
		<?php if ($user):?>
		<p>Bonjour <span class="username"> <?php echo $user->attribute('login');?></span> | paramètres | <a href="<?php $this->url('/user/logout');?>" class="disconnect" >se déconnecter</a></p>
		<?php endif;?>

</div>
<div id="topmenu">
<ul>
<li><a href="<?php $this->url('/content/menu/manage');?>" >Menu</a></li>
<li><a href="<?php $this->url('/content/manage');?>" >Contenu</a></li>
<li><a href="<?php $this->url('/user/manage');?>" >Utilisateurs</a></li>
<li><a href="<?php $this->url('/section/list');?>" >Sections</a></li>
</ul>
</div>
</div>

<?php echo $MainResult;?>
</div>
<!-- DEBUG -->
</body>
</html>