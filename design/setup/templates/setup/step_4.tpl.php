<div class="install_menu">
<?php $this->includeTpl('menu/rightmenu.tpl.php',array('stepslist'=>$stepslist,'current_step'=>$current_step));?>
</div>
<div class="install_msg">


<h2><?php echo $title; ?></h2>

<p>The installation is finished.</p>
<p>You can access to the website <a href="<?php $this->url("/");?>" target="_blank" >front end</a> and <a href="<?php $this->url("/back");?>" target="_blank" >back office</a>.

</div>
<div class="progressbar"></div>