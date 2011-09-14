<?php $this->require_script(array("jquery-1.4.3.min.js","tinymce/tiny_mce.js"));?>

<script type="text/javascript">
	tinyMCE.init({
		/*theme : "advanced",
        mode : "textareas",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,*/
		theme : "advanced",
	    mode: "exact",
	   // plugins : "advimage",
	    elements : "<?php echo "xmlEdit_".$data->attribute('id');?>",
	    theme_advanced_toolbar_location : "top",
	    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,"
	    + "justifyleft,justifycenter,justifyright,justifyfull,formatselect,"
	    + "bullist,numlist,outdent,indent",
	    theme_advanced_buttons2 : "link,unlink,anchor,image,separator,"
	    +"undo,redo,cleanup,code,separator,sub,sup,charmap",
	    theme_advanced_buttons3 : "",
	    theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
       // file_browser_callback : 'FileBrowser'
            /*,
	    height:"350px",
	    width:"600px"*/
	});
	function FileBrowser(field_name, url, type, win) {


	    var cmsURL = "<?php $this->url('/tinymce/dialog'); ?>";    // script URL - use an absolute path!
	    if (cmsURL.indexOf("?") < 0) {
	        //add the type as the only query parameter
	        cmsURL = cmsURL + "/" + type;
	    }
	    else {
	        //add the type as an additional query parameter
	        // (PHP session ID is now included if there is one at all)
	        cmsURL = cmsURL + "/" + type;
	    }

	    tinyMCE.activeEditor.windowManager.open({
	        file : cmsURL,
	        title : 'My File Browser',
	        width : 420,  // Your dimensions may differ - toy around with them!
	        height : 400,
	        resizable : "yes",
	        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
	        close_previous : "no"
	    }, {
	        window : win,
	        input : field_name
	    });
	    return false;
	  }
</script>
<div class="field">
<label for="field_<?php echo $data->attribute('identifier');?>"><?php echo $data->attribute('name');?></label>
<textarea name="field_<?php echo $data->attribute('identifier')."_".$data->attribute('datatype');?>" id="<?php echo "xmlEdit_".$data->attribute('id');?>" name="elm1"   ><?php echo $data->content();?></textarea>
</div>