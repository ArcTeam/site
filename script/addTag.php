<?php
session_start();
require("../inc/db.php");
$tag=strtolower($_POST['tag']);
$check = "select * from liste.tag where tag = '".$tag."';";
$checkRes = pg_query($connection, $check);
$n = pg_num_rows($checkRes);
if($n == 0){
    $newtag = "insert into liste.tag(tag) values('".$tag."');";
    $ntRes = pg_query($connection, $newtag);
}
?>
