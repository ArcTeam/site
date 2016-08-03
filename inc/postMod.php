<?php
session_start();
require_once("db.php");
$tags = explode(",",$_POST["tag"]);
$resetTag = "delete from main.tags where rec = ".$_POST['id']." AND tab = 1;";
foreach ($tags as $tag) {
    $a = "select id from liste.tag where tag = '".$tag."'";
    $b = pg_query($connection, $a);
    $c = pg_fetch_array($b);
    $addTag .= "insert into main.tags(tag, rec, tab) values(".$c['id'].", ".$_POST['id'].", 1);";
}
$q = "BEGIN;";
$q .= "update main.post set titolo = '".pg_escape_string($_POST["titolo"])."', testo = '".$_POST['post']."', pubblica =  ".$_POST['stato']." where id = ".$_POST['id'].";";
$q .= $resetTag;
$q .= $addTag;
$q .= "COMMIT;";
$r = pg_query($connection,$q);
if(!$r){$result = "errore: ".pg_last_error($connection);}else{ $result = $_POST['id'];}
echo $result;
?>
