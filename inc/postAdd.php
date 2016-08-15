<?php
session_start();
require_once("db.php");
$tags = explode(",",$_POST["tag"]);
foreach ($tags as $tag) {
    $a = "select id from liste.tag where tag = '".$tag."'";
    $b = pg_query($connection, $a);
    $c = pg_fetch_array($b);
    $addTag .= "insert into main.tags(tag, rec, tab) values(".$c['id'].", currval('main.post_id_seq'), 1);";
}
$q = "BEGIN;";
$q .= "insert into main.post(titolo, testo, usr, pubblica) values('".pg_escape_string($_POST["titolo"])."','".$_POST['post']."',".$_SESSION["id"].", ".$_POST['stato'].");";
$q .= "insert into main.log(tabella,record,operazione, utente) values ('post', nextval('main.post_id_seq'), 'I', ".$_SESSION['id'].");"
$q .= $addTag;
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
