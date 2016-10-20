<?php
function tag($id, $tab){
    require("inc/db.php");
    $t = "select ts.tags from main.tags ts where ts.rec = ".$id." and ts.tab = ".$tab." order by ts.tags asc";
    $tr = pg_query($connection,$t);
    $tagList = pg_fetch_array($tr);
    $tagListArr = explode(",",$tagList['tags']);
    asort($tagListArr);
    foreach ($tagListArr as $tag) { $tags .= "<span class='tag'>".$tag."<i class='fa fa-tag'></i></span>"; }
    return $tags;
}

function bgcolor(){return "#".dechex(rand(0,10000000));}
?>
