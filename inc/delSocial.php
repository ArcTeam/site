<?php
session_start();
require_once('db.php');
$del = "delete from main.usr_social where id = ".$_POST['id'].";";
$exec = pg_query($connection, $del);
if($exec){echo "ok";}else{echo "errore: ".pg_last_error($connection);}
?>
