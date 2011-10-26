<?php $this->require_script(array("jquery-1.6.2.min.js",
								  "fancybox/jquery.mousewheel-3.0.4.pack.js",
								  "fancybox/jquery.fancybox-1.3.4.pack.js",
								  "jquery-ui/jquery-ui-1.8.16.custom.min.js",
                                   "smoothdivscroll/jquery.smoothDivScroll-1.1-min.js"));?>
<div class="link">
<a href="<?php echo $object->urlAlias()?>"><?php echo $object->attribute('name');?></a>
</div>



<?php $childrens = lcContentNodeObjectHandler::fetchChildrens($node_id,array('image'));?>

<div id="makeMeScrollable">
	<div class="scrollingHotSpotLeft"></div>
	<div class="scrollingHotSpotRight"></div>
	<div class="scrollWrapper">
		<div class="scrollableArea">
<?php foreach ($childrens as $item):?>
<?php $datamap = $item->dataMap(); $image = $datamap['image']->content();?>
  <img alt="illustration" src="<?php echo $image->get('bigscroll');?>" />
<?php endforeach;?>
	</div>
	</div>
</div>

<script type="text/javascript">
	$(window).load(function() {
		$("div#makeMeScrollable").smoothDivScroll({
			autoScroll: "onstart" ,
			autoScrollDirection: "backandforth",
			autoScrollStep: 1,
			autoScrollInterval: 15,
			startAtElementId: "startAtMe",
			visibleHotSpots: "always"
		});
	});
</script>