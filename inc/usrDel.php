<?php
session_start();
require("db.php");
$delete = "delete from main.rubrica where id = ".$_POST['id'].";";
$delete .= "insert into main.log(tabella,record,operazione, utente) values ('rubrica', ".$_POST['id'].", 'D', ".$_SESSION['id'].");";
$deleteRes = pg_query($connection,$delete);
if(!$deleteRes){
    $stato = "<span class='error inline'>errore nella query: ".pg_last_error($connection)."</span>";
}else {
    $stato = "<span class='success inline'>Ok, l'utente è stato definitivamente eliminato dalla rubrica</span>";
}
echo $stato;
?>
