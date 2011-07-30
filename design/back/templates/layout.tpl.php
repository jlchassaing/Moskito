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
<div id="page" class="clear">
<div id="header">
<div id="title">
<h1><a href="<?php $this->url('/back');?>" ><img src="<?php $this->designurl("images/moskito.png");?>" /></a></h1>
</div>
<div id="user">
		<?php $user = lcUser::getCurrentUser()?>
		<?php if ($user):?>
		<p>Bonjour <?php echo $user['login'];?>, <a href="<?php $this->url('/user/logout');?>" >se déconnecter</a></p>
		<?php endif;?>

</div>
<div id="topmenu">
<ul>
<li><a href="<?php $this->url('/content/menu/manage');?>" >Gestion du menu</a></li>
<li><a href="<?php $this->url('/content/manage');?>" >Gestion du contenu</a></li>
<li><a href="<?php $this->url('/user/manage');?>" >Gestion des utilisateurs</a></li>
</ul>
</div>
</div>

<?php echo $MainResult;?>
</div>
<!-- DEBUG -->
</body>
</html>