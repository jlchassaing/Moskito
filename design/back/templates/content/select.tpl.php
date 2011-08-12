<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle"><?php if ($parent_title['parent_node_id'] !== false):?><a href="<?php $this->url("content/select/".$parent_title['parent_node_id'])?>" title="previus" class="upper"><img src="<?php $this->designurl("images/icones/top-18x18.png")?>" alt="previus"/></a><?php endif;?> <?php echo $parent_title['object_name'];?> : Selectionnez un contenu</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">

<form action="<?php $this->url("content/select");?>" method="post">

<?php if (is_array($fullMenu) and count($fullMenu) > 0):?>

<table>

<tr>
<th class="first small" /><th class="last">name</th>
</tr>
<?php $dolink = false;?>
<?php foreach($fullMenu as $key=>$menu):?>

<tr class="<?php if ($key%2 == 0) echo 'dark'; else echo 'light';?>">
<td class="first"><input type="checkbox" value="<?php echo $menu['node_id']?>" name="NodeId[]" /></td>
<?php $dolink = true;?>
<td class="last">
<?php if ($menu['children_count'] > 0): ?>
<a href="<?php $this->url("content/select/".$menu['node_id'])?>" title="open" ><?php echo $menu['object_name']; ?></a>
<?php else:?>
<?php echo $menu['object_name']; ?>
<?php endif;?>
</td>
</tr>

<?php endforeach;?>

<tr class="lastline buttons">
<td colspan="3" class="first last">
<div class=buttons>
<input type="submit" name="SelectedButton" value="Ajouter" />
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