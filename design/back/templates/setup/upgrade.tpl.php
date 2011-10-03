
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Setup - upgrade</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">

<?php if (is_array($scripts)):?>
<table>
<tr>
<th class="small first">
Script</th>
<th class="actions last">
Actions
</th>
</tr>
<?php foreach ($scripts as $key=>$script):?>
<tr>
<td > <?php echo $script?></td>
<td> <a href="<?php $this->url("setup/run/$key/".md5($script))?>" ><img src="<?php $this->designurl('images/icones/gear-18x18.png');?>" alt="Execute the script" /></a></td>

</tr>
<?php endforeach;?>
</table>
<?php endif;?>
</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>