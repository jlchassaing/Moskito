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
</head>
<body>
<div id="page">
<div id="header">
<div id="title">
<h1><a href="<?php $this->url('/');?>" ><img src="<?php $this->designurl("images/moskito/logo.png");?>" /></a></h1>
</div>
<div id="topmenu"></div>
</div>
<div id="main">
<?php echo $MainResult;?>
</div>
</div>
<!-- DEBUG -->
</body>
</html>