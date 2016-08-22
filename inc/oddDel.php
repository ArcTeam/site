<?php
session_start();
require("db.php");
$delete = "delete from main.opendata where id = ".$_POST['id'].";";
$deleteRes = pg_query($connection,$delete);
if(!$deleteRes){
    $stato = "<span class='error inline'>errore nella query: ".pg_last_error($connection)."</span>";
}else {
    $stato = "<span class='success inline'>Ok, il documento è stato definitivamente eliminato dall'archivio</span>";
}
echo $stato;
?>
