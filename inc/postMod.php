<?php
session_start();
require_once("db.php");
if (!empty($_POST['tag'])) {
    $checkTag = "select count(id) as tags from main.tags where rec =".$_POST['id']." and tab = 1;";
    $checkTagQuery = pg_query($connection,$checkTag);
    $checkTagRes = pg_fetch_array($checkTagQuery);
    if($checkTagRes['tags'] == 0){ $tag = "insert into main.tags(tags, rec, tab) values('".$_POST['tag']."', ".$_POST['id'].", 1);";}
    else { $tag = "update main.tags set tags = '".$_POST['tag']."' where rec = ".$_POST['id']." and tab=1;"; }
}else {
    $tag = "delete from main.tags where rec = ".$_POST['id']." and tab=1;";
}
$q = "BEGIN;";
$q .= "update main.post set titolo = '".pg_escape_string($_POST["titolo"])."', testo = '".$_POST['post']."', pubblica =  ".$_POST['stato']." where id = ".$_POST['id'].";";
$q .= $tag;
$q .= "insert into main.log(tabella,record,operazione, utente) values ('post', ".$_POST['id'].", 'U', ".$_SESSION['id'].");";
$q .= "COMMIT;";
$r = pg_query($connection,$q);
if(!$r){$result = "errore: ".pg_last_error($connection);}else{ $result = $_POST['id'];}
echo $result;
?>
