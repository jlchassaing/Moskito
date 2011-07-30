<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr-FR" lang="fr-FR">
<head>
    <title>LiteCms / code name : moskito </title>
	<meta name="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Content-language" content="fr-FR" />
	<style type="text/css">
		@import url(<?php $this->designurl("/stylesheets/litecms.css");?>);
	</style>
</head>
<body>
<div id="page">
<div id="header">
<div id="title">
<h1><a href="<?php $this->url('/back');?>" ><img src="<?php $this->designurl("images/moskito.png");?>" /></a></h1>
</div>
</div>
<div id="main">
<?php echo $MainResult;?>
</div>
</div>
<!-- DEBUG -->
</body>
</html>