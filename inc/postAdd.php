<?php
session_start();
require_once("db.php");

$q = "BEGIN;";
$q .= "insert into main.post(titolo, testo, pubblica, cat) values('".pg_escape_string($_POST["titolo"])."','".$_POST['post']."', ".$_POST['stato'].", ".$_POST['cat'].");";
$q .= "insert into main.tags(tags, rec, tab) values('".$_POST['tag']."', currval('main.post_id_seq'), 1);";
$q .= "insert into main.log(tabella,record,operazione, utente) values ('post', currval('main.post_id_seq'), 'I', ".$_SESSION['id'].");";
$q .= "COMMIT;";
$r = pg_query($connection,$q);
if(!$r){$result = "errore: ".pg_last_error($connection);}
else{
    $c = "select max(id) as last from main.post;";
    $d = pg_query($connection,$c);
    $e = pg_fetch_array($d);
    $result = $e['last'];
}
echo $result;
?>
