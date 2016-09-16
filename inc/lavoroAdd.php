<?php
session_start();
require_once("db.php");

$q = "BEGIN;";
$q .= "insert into main.lavoro(tipo, nome, descrizione, anno) values(".$_POST['tipo'].", '".pg_escape_string($_POST["nome"])."','".pg_escape_string($_POST['descr'])."', ".$_POST['anno'].");";
$q .= "insert into main.tags(tags, rec, tab) values('".$_POST['tag']."', currval('main.lavoro_id_seq1'), 3);";
$q .= "insert into main.log(tabella,record,operazione, utente) values ('lavoro', currval('main.lavoro_id_seq1'), 'I', ".$_SESSION['id'].");";
$q .= "COMMIT;";
$r = pg_query($connection,$q);
if(!$r){$result = "errore: ".pg_last_error($connection);}
else{
    $c = "select max(id) as last from main.lavoro;";
    $d = pg_query($connection,$c);
    $e = pg_fetch_array($d);
    $result = $e['last'];
}
echo $result;
?>
