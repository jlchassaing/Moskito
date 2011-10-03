
<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Gestion du menu</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content edit">

<form action="<?php $this->url('/content/menu');?>" method="post" >
<?php if (count($menu) == 0):?>
<p>Aucun menu</p>
<?php else:?>

<?php $parent=0;$path=explode("/", $menu[0]['path_ids']); $prec=count($path);?>
<div id="edit-menu">
<ul>
<?php foreach ($menu as $key=>$item):?>
<?php $path=explode("/", $item['path_ids']);?>

<?php if ($prec < count($path)):?>
<ul>
<?php elseif ($prec > count($path)):?>
  <?php for ($i = count($path); $i < $prec;$i++):?>
	</li>
</ul>
</li>
<?php endfor;?>
<?php else:?>
</li>
<?php endif;?>
<li class="show">
<span class="open"></span>
<?php echo $item['name'];?>
 <input type="text" value="<?php echo substr($item['sort_val'],-2);?>" name="MenuNewSortValue[]" size="2"  class="smallinput"/>
<a href="<?php $this->url('content/menu/edit/'.$item['id']);?>" ><img src="<?php $this->designurl('images/icones/edit-18x18.png');?>" alt="modifier" /></a>
					 <a href="<?php $this->url('content/menu/remove/'.$item['id']);?>"><img src="<?php $this->designurl('images/icones/delete-18x18.png');?>" alt="supprimer" /></a>
<input type="hidden" name="MenuIdValue[]" value="<?php echo $item['id'];?>" />
<input type="hidden" name="MenuIdOrder[]" value="<?php $key++; echo $key;?>" />
<input type="hidden" name="MenuSortValue[]" value="<?php echo substr($item['sort_val'],-2);?>" />
<?php $prec = count($path);?>
<?php endforeach;?>
</ul>
</div>
<?php endif;?>
<div class="clear"></div>
<div class="buttons">
<input type="submit" name="SaveMenuUpdates" value="Enregister" />
</div>
</form>

</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>

<?php $this->require_script(array("jquery-1.4.3.min.js"))?>
<script type="text/javascript">
$(function(){
	var openImg = "<img src=\"<?php $this->designurl('images/icones/add-18x18.png')?>\" />";
	var closeImg = "<img src=\"<?php $this->designurl('images/icones/minus-18x18.png')?>\" />";
	$('li ul').hide();
	$('li:has(ul)').each(function(){

			$(this).children('span').empty();
			$(this).children('span').append(openImg);
		});
	$('span').click(function(){
			if ($(this).hasClass('open'))
			{
				$(this).parent().children('ul').show();
				$(this).removeClass('open');
				$(this).addClass('close');
				$(this).empty();
				$(this).append(closeImg);
			}
			else
			{
				$(this).parent().children('ul').hide();
				$(this).removeClass('close');
				$(this).addClass('open');
				$(this).empty();
				$(this).append(openImg);
			}

		});




});

</script>