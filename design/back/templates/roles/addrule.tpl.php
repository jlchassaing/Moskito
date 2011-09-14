<h2>Gestion des rôles</h2>
<h3>Ajouter une rèlge</h3>
<?php if (isset($error)):?>
<div class="error">
<h3>Erreur</h3>
<p><?php echo $error;?></p>
</div>
<?php endif;?>
<form action="<?php $this->url('role/action');?>" method="post" >
<?php if ($roleAddPhase == 1):?>
<div class="field">
<label for="moduleName" >Module</label>
<select name="moduleNameValue">
<option value="all" >all Modules</option>
<?php foreach ($moduleList as $value):?>
	<option value="<?php echo $value?>"><?php echo $value?></option>
<?php endforeach;?>
</select>
</div>
<?php elseif ($roleAddPhase == 2):?>

<h3>Choisissez une fonction pour le module : <?php echo $moduleName;?></h3>
<input type="hidden" name="ModuleNameValue" value="<?php echo $moduleName?>" />
<div class="field">
<label for="functionName" >Fonction</label>
<select name="functionNameValue">
<option value="all" >all functions</option>
<?php foreach ($functionList as $value):?>
	<option value="<?php echo $value;?>"><?php echo $value;?></option>
<?php endforeach;?>
</select>
</div>

<?php elseif ($roleAddPhase == 3):?>

<h3>Configuration de la fonction <?php echo $functionName;?>  pour le module : <?php echo $moduleName;?></h3>
<input type="hidden" name="ModuleNameValue" value="<?php echo $moduleName?>" />
<input type="hidden" name="FunctionNameValue" value="<?php echo $functionName?>" />

<?php foreach ($functionParams as $key=>$value):?>
<div class="functionparam">
<label><?php echo $key;?></label>
<input type="hidden" name="paramFieldList[]" value="<?php echo "param_".$key; ?>" />
<select name="<?php echo "param_".$key; ?>">
<?php foreach($value['list'] as $item): ?>
<option value="<?php echo $item[$value['keys'][0]]; ?>" ><?php echo $item[$value['keys'][1]]; ?></option>
<?php endforeach;?>
</select>
<?php endforeach;?>
</div>

<?php endif;?>


<div class="buttons">
<input type="hidden" name="ProcessPhase" value="<?php echo $roleAddPhase;?>" />
<input type="hidden" name="RoleIdValue" value="<?php echo $roleId?>" />
<input type="submit" name="SaveRuleButton" value="Enregistrer" />
<input type="button" value="Annuler" />
</div>

</form>