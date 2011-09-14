<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Assigner un noeud à une section</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">


<form action="<?php $this->url("section/action");?>" method="post">

<?php if (is_array($fullMenu) and count($fullMenu) > 0):?>
<table>

<tr>
<th class="first small" /><th class="last">name</th>
</tr>

<?php foreach($fullMenu as $key=>$menu):?>

<tr class="<?php if ($key%2 == 0) echo 'dark'; else echo 'light';?>">
<td class="first"><input type="checkbox" value="<?php echo $menu['node_id']?>" name="NodeId[]" /></td>
<td class="last"><?php echo $menu['name']; ?></td>

</tr>

<?php endforeach;?>

<tr class="lastline buttons">
<td colspan="3" class="first last">
<div class=buttons>
<input type="submit" name="AssignToSectionButton" value="Ajouter" />
</div>
</td>
</tr>

</table>
<?php else: ?>

<p>Aucun menu défini</p>

<?php endif;?>



</form>



</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>