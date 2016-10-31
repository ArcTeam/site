<?php
session_start();
require("db.php");
$delete = "delete from main.attivita where gid = ".$_POST['gid'].";";
$delete .= "insert into main.log(tabella,record,operazione, utente) values ('attivita', ".$_POST['gid'].", 'D', ".$_SESSION['id'].");";
$deleteRes = pg_query($connection,$delete);
if(!$deleteRes){
    $stato = "errore nella query: ".pg_last_error($connection);
}else {
    $stato = "Ok, il record è stato definitivamente eliminato dall'archivio";
}
echo $stato;
?>
