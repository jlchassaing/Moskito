<h2>Installation steps</h2>
<ul>
<?php foreach($stepslist as $key=>$value):?>
<li <?php if ($key == $current_step) echo "class=\"current\""; elseif ($key < $current_step) echo "class=\"done\"";?> > <?php echo $value['name'] ?> </li>

<?php endforeach;?>
</ul>