<?php
session_start();
require("db.php");
$check = "select attivo from main.usr where id=".$_POST['id'].";";
$checkRes = pg_query($connection,$check);
$attivo = pg_fetch_array($checkRes);
$stato = ($attivo['attivo']==1) ? 0 : 1;
$update = "update main.usr set attivo = ".$stato." where id = ".$_POST['id'].";";
$updateRes = pg_query($connection,$update);
if(!$updateRes){
    $stato = "<span class='error'>errore nella query: ".pg_last_error($connection)."</span>";
}else {
    $stato = "<span class='success'>Ok, lo stato dell'utente è stato modificato</span>";
}
echo $stato;
?>
