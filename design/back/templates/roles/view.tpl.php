<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle"><a href="<?php $this->url('role/list');?>" >Gestion des rôles</a> : <?php echo $role->attribute('name')?></h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="table-content">
<?php $rules = $role->attribute('rules');?>
<form action="<?php $this->url('role/action');?>" method="post" >
<?php if (count($rules) > 0):?>

<table>
<tr>
<th class="first small" /><th>module</th><th>fonction</th><th>params</th><th class="actions last">actions</th>
</tr>

<?php foreach ($rules as $key=>$rule): ?>
<tr class="<?php if ($key % 2 == 0) echo "dark";else echo "light";?>" >
<td class="first"><input type="checkbox" name="ruleId[]" value="<?php echo $rule->attribute('id');?>" /></td>
<td><?php echo $rule->attribute('module');?></td>
<td><?php echo $rule->attribute('function');?></td>
<td><?php $params=$rule->attribute('params') ;
if (is_array($params))
{
    foreach($params as $key=>$value){

        echo "$key =>";
        if (is_array($value))
            echo implode("; ",$value);
        else
            echo " $value";
    }
}?></td>
<td class="last"><a href="<?php $this->url("role/ruledit/".$rule->attribute('id'));?>" ><img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="Edit the content" /></a></td>
</tr>
<?php endforeach;?>

<?php else:?>
<tr class="light"><td colspan="5" class="first last">Aucun role définit</td></tr>

<?php endif;?>

<tr class="lastline buttons">
<td colspan="5" class="first last">
<div class="buttons">

<input type="hidden" name="RoleIdValue" value="<?php echo $role->attribute('id');?>" />
<input type="submit" name="addRuleButton" value="Ajouter" />
<input type="submit" name="deleteSelectedRuleButton" value="Supprimer" />
<input type="button" value="Annuler" />
</div>
</td>
</tr>

</table>
</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>