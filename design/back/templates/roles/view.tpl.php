<h2>Gestion des rôles</h2>
<h3><?php echo $role->attribute('name')?></h3>
<?php $rules = $role->attribute('rules');?>
<form action="<?php $this->url('role/action');?>" method="post" >
<?php if (count($rules) > 0):?>

<table>
<tr>
<th /><th>module</th><th>fonction</th><th>params</th><th>actions</th>
</tr>

<?php foreach ($rules as $key=>$rule): ?>
<tr class="<?php if ($key % 2 == 0) echo "bgdark";else echo "bglight";?>" >
<td><input type="checkbox" name="roleId[]" value="<?php echo $rule->attribute('id');?>" /></td>
<td><?php echo $rule->attribute('module');?></td>
<td><?php echo $rule->attribute('function');?></td>
<td><?php echo $rule->attribute('params');?></td>
<td><a href="<?php $this->url("role/ruledit/".$rule->attribute('id'));?>" >edit</a></td>
</tr>
<?php endforeach;?>
</table>
<?php else:?>
<p>Aucun role définit</p>

<?php endif;?>

<div class="buttons">

<input type="hidden" name="RoleIdValue" value="<?php echo $role->attribute('id');?>" />
<input type="submit" name="addRuleButton" value="Ajouter" />
<input type="submit" name="deleteSelectedButton" value="Supprimer" />
<input type="button" value="Annuler" />
</div>

</form>