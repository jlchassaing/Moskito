<?php $this->require_script(array("jquery-1.7.min.js"));?>




<h2><?php echo $object->attribute('name')?></h2>

<?php foreach ($object->dataMap() as $field):?>

<?php $this->attributeView("field/view",$field);?>

<?php endforeach;?>

<div id="test">hello</div>

<script type="text/javascript" >

$("#test").click(function(){

var url = '<?php $this->url('/ajax/call');?>';

$.get( url,function(html){
	afficher(html);
	});


function afficher(html)
{
    $('#result').html(html);
}

});



</script>

<div id="result"></div>