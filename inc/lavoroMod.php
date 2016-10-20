<?php
session_start();
require_once("db.php");
if (!empty($_POST['tag'])) {
    $checkTag = "select count(id) as tags from main.tags where rec =".$_POST['id']." and tab = 3;";
    $checkTagQuery = pg_query($connection,$checkTag);
    $checkTagRes = pg_fetch_array($checkTagQuery);
    if($checkTagRes['tags'] == 0){ $tag = "insert into main.tags(tags, rec, tab) values('".$_POST['tag']."', ".$_POST['id'].", 3);";}
    else { $tag = "update main.tags set tags = '".$_POST['tag']."' where rec = ".$_POST['id']." and tab=3;"; }
}else {
    $tag = "delete from main.tags where rec = ".$_POST['id']." and tab=3;";
}
$q = "BEGIN;";
$q .= "update main.lavoro set nome = '".pg_escape_string($_POST["nome"])."', descrizione = '".pg_escape_string($_POST['descr'])."', tipo =  ".$_POST['tipo'].", anno = ".$_POST['anno']." where id = ".$_POST['id'].";";
$q .= $tag;
$q .= "insert into main.log(tabella,record,operazione, utente) values ('lavoro', ".$_POST['id'].", 'U', ".$_SESSION['id'].");";
$q .= "COMMIT;";
$r = pg_query($connection,$q);
if(!$r){$result = "errore: ".pg_last_error($connection);}else{ $result = $_POST['id'];}
echo $result;
?>
