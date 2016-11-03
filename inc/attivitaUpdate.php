<?php
session_start();
require("db.php");
$fine = (!$_POST['fine'])?", data_fine = null": ", data_fine = '".$_POST['fine']."'";
$q = "BEGIN;";
$q .= "update main.attivita set tipo_lavoro = ".$_POST['tipo'].", data_inizio = '".$_POST['inizio']."' ".$fine." where gid = ".$_POST['id'].";";
$q .= "insert into main.log(tabella,record,operazione, utente) values ('attivita', ".$_POST['id'].", 'U', ".$_SESSION['id'].");";
$q .= "COMMIT;";
$r = pg_query($connection,$q);
if(!$r){$result = "errore: ".pg_last_error($connection);}else{ $result = 'Dati modificati correttamente';}
echo $result;
?>
