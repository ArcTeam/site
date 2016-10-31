<?php
session_start();
require("db.php");
$delete = "delete from main.opendata where id = ".$_POST['id'].";";
$delete .= "insert into main.log(tabella,record,operazione, utente) values ('opendata', ".$_POST['id'].", 'D', ".$_SESSION['id'].");";
$deleteRes = pg_query($connection,$delete);
if(!$deleteRes){
    $stato = "<span class='error inline'>errore nella query: ".pg_last_error($connection)."</span>";
}else {
    $stato = "<span class='success inline'>Ok, il documento è stato definitivamente eliminato dall'archivio</span>";
}
echo $stato;
?>
