<?php $this->require_script(array('tinymce/tiny_mce_popup.js'));?>


<script language="javascript" type="text/javascript">

var FileBrowserDialogue = {
    init : function () {
        // Here goes your code for setting your custom things onLoad.
    },
    mySubmit : function () {
        var URL = document.my_form.my_field.value;
        var win = tinyMCEPopup.getWindowArg("window");

        // insert information now
      //  win.document.getElementById(tinyMCEPopup.getWindowArg("imageid")).value = URL;
		win.document.getElementById("imageid").value = URL;
        // are we an image browser
        if (typeof(win.ImageDialog) != "undefined")
        {
            // we are, so update image dimensions and preview if necessary
            if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
            if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
        }

        // close popup window
        tinyMCEPopup.close();
    }
}

tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);

</script>

<form name="my_form">

<?php
if (isset($content)): ?>
<?php foreach ($content as $value): ?>
    <div class="image">
    <input type="radio" name="imageid" id="imageid" value="<?php echo $value->attribute('id')?>" /><?php echo $value->attribute('name')?>
    <?php $dataMap= $value->dataMap(); ?>
    <?php $this->attributeView("field/view",$dataMap['image']);?>
    </div>

<?php endforeach;?>
<?php endif;?>

<input type="button" value="Submit" onClick="FileBrowserDialogue.mySubmit();">
</form>