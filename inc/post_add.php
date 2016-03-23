<?php
session_start();
require_once("db.php");
$a = "insert into main.post(titolo, testo,utente) values('".pg_escape_string($_POST["titolo"])."','".$_POST['post']."',".$_SESSION["id"].");";
$b = pg_query($connection,$a);
if(!$b){$result = "errore: ".pg_last_error($connection);}
else{
    $c = "select max(id) as last from main.post;";
    $d = pg_query($connection,$c);
    $e = pg_fetch_array($d);
    $result = $e['last'];
}
echo $result;
?>
