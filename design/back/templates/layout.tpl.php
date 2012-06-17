<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr-FR" lang="fr-FR">
<head>
    <title>LiteCms / code name : moskito </title>
	<meta name="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Content-language" content="fr-FR" />
	<?php lcStyleLoader::load('litecms.css');?>

	<!--[if lt IE 9]>
	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	<![endif]-->

<?php $this->require_script(array("jquery-1.6.2.min.js","jquery-ui/jquery-ui-1.8.16.custom.min.js"));?>
	<?php lcScriptLoader::load();?>

	<script type="text/javascript">document.documentElement.className = "js";</script>

</head>
<body>
<div id="page" class="clear">
<div id="header">
<div id="title">
<h1><a href="<?php $this->url('/');?>" ><img src="<?php $this->designurl("images/moskito/logo.png");?>"  alt="Moskito lite CMS"/></a></h1>
</div>
<div id="user">
		<?php $user = lcUser::getCurrentUser()?>
		<?php if ($user):?>
		<p>Bonjour <span class="username"> <?php echo $user->attribute('login');?></span> | paramètres | <a href="<?php $this->url('/user/logout');?>" class="disconnect" >se déconnecter</a></p>
		<?php endif;?>

</div>
<div id="topmenu">
<ul>
<!--  <li><a href="<?php $this->url('/content/menu/manage');?>" >Menu</a></li> -->
<li><a href="<?php $this->url('/content/view/full/1');?>" >Contenu</a></li>
<li><a href="<?php $this->url('/user/manage');?>" >Utilisateurs</a></li>
<li><a href="<?php $this->url('/section/list');?>" >Sections</a></li>
<li><a href="<?php $this->url('/setup/upgrade');?>" >Setup</a></li>
<li><a href="<?php $this->url('/cache/manage');?>" >Gestion du cache</a></li>
</ul>
</div>
</div>

<div id="left_menu">

<div class="borderbox">

<div class="tl"><div class="tr"><div class="tc"><h2 class="blocktitle">Contenu</h2></div></div></div>
<div class="ml"><div class="mr"><div class="mc">

<div class="content">


<div id="content_menu">

</div>

<script>

$(function()
{

var InitMenu = <?php echo json_encode(lcContentMenu::fetchFullMenu(1));?>

var RequestUrl = "<?php $this->url('ajax/call/content::treemenu');?>"

var SiteUrl = "<?php $this->url('/');?>"

var path = <?php echo json_encode($path);?>

function addMenuToElement(elementID,contentMenu)
{
	var current = false;
	var currentDepth = -1;
	var html="";
	var ul = false;
	var isFirst = true;
	var liClass = "opened";
	var newUl = null;
	var startI = 0;
	var menuContainer = $(elementID);
    var firstID = '#node_'+contentMenu[0].node_id;
    var url = "";
    var reg=new RegExp("(//)", "g");
    if (elementID == firstID)
    {
       isFirst = false;
       startI = 1;
    }


	for (i=startI ; i < contentMenu.length; i++)
	{

	    current = contentMenu[i];
	    if (currentDepth < current.depth)
	    {
	        newUl = $('<ul>');
	        newUl.attr('id','parent_'+current.node_id);

	        if (!ul)
	        {
	               ul = newUl;
	               menuContainer.append(ul);
	        }
	        else
	        {
	            ul.children(':last-child').append(newUl);
	            ul = newUl;
	        }

	    }

	    if (!isFirst) liClass = "closed";
	    else liClass = "opened";
	    html = "<li class=\""+liClass+"\" id=\"node_"+current.node_id+"\" ><a class=\"opener\" href=\"#\" >";

	    if (liClass == "opened")
	        html = html + "[-]</a> ";
	    else
	        html = html + "[+]</a> ";

	     url = SiteUrl+current.path_string;
	     url = url.replace(reg,"/");

	     html = html + "<a href=\""+url+"\" >"+current.name+"</a></li>";
	     li = $(html);
	     li.find('a.opener').click(function(e){

	            e.preventDefault();

	            if ($(this).parent().hasClass('opened'))
	            {
	                $(this).parent().children('ul').animate({
	                        opacity: 'toggle',
	                        height: 'toggle'
	                    }, 300);

	                $(this).parent().removeClass('opened');
	                $(this).parent().addClass('closed');
	                $(this).html('[+]');
	            }
	            else if ($(this).parent().hasClass('closed'))
	            {
		           /* if ($(this).parent().has('ul').length == 0)
		            {
		            	  var parent_id = $(this).parent().attr('id');
		            	  var parent_node_id = parent_id.split("_")[1];

		            	  var ajaxCallUrl = RequestUrl+"::"+parent_node_id;

		            	  $.ajax({
		            		  url: ajaxCallUrl,
		            		  success: function(data){
		            		    addMenuToElement("#"+parent_id,data);
		            		  }
		            		});
		            }
	                $(this).parent().children('ul').animate({
	                        opacity: 'toggle',
	                        height: 'toggle'
	                    }, 300);

	                $(this).parent().removeClass('closed');
	                $(this).parent().addClass('opened');
	                $(this).html('[-]');*/
	            	open($(this));
		         }


	        });
	     ul.append(li);

	    isFirst = false;
	    currentDepth = current.depth;

	}



}

	function open(elt)
    {
        if (elt.parent().has('ul').length == 0)
        {
              var parent_id = elt.parent().attr('id');
              var parent_node_id = parent_id.split("_")[1];

              var ajaxCallUrl = RequestUrl+"::"+parent_node_id;

              $.ajax({
                  url: ajaxCallUrl,
                  success: function(data){
                    addMenuToElement("#"+parent_id,data);
                  }
                });
        }
        elt.parent().children('ul').animate({
                opacity: 'toggle',
                height: 'toggle'
            }, 300);

        elt.parent().removeClass('closed');
        elt.parent().addClass('opened');
        elt.html('[-]');
    }

addMenuToElement('#content_menu',InitMenu);

if (path.length > 0)
{
	var p = "";
    for(i=0;i<path.length;i++)
    {
        p = $("#node_"+path[i].node_id);
    	if (p)
    	{
    	    if (p.hasClass('closed'))
    	    {
    	    	 open(p.find('a.opener'));
    	    }

        }
    }
}

});

</script >


</div>

</div></div></div>
    <div class="bl"><div class="br"><div class="bc"></div></div></div>

</div>


</div>
<div id="main">
<?php echo $MainResult;?>
</div>
</div>
<!-- DEBUG -->
</body>
</html>