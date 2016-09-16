<?php
session_start();
require("db.php");
$a ="SELECT post.id, post.titolo, post.testo, log.data, rubrica.utente FROM main.log, main.usr, main.rubrica, main.post WHERE log.utente = usr.id AND log.record = post.id AND usr.rubrica = rubrica.id AND post.pubblica = ".$_POST['vis']." AND post.cat = 1 AND log.tabella = 'post' AND log.operazione = 'I';";

$b = pg_query($connection, $a);
$arr = array();
while ($obj = pg_fetch_object($b)) { $arr[] = $obj;}
echo json_encode($arr);
?>
