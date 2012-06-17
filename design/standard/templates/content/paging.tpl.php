<?php

$nbPages = $elements_count / $nbitems;
$pagination = "<div class=\"pagination\"><ul>";
for($i = 0; $i <$nbPages; $i++)
{
    $poffset = $i * $nbitems;
    $pageNumber = $i+1;
    if ($offset == $poffset)
    {
        $pagination .= "<li class=\"current\">$pageNumber</li>\n";
    }
    else
    {
        $pagination .= "<li><a href=\"".$url."/(offset)/".$poffset."\" >$pageNumber</a></li>\n";
    }

}

$pagination .= "</ul></div>";

echo $pagination;
?>