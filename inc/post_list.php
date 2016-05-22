<?php
session_start();
require("db.php");
$vis = $_POST['vis'];
$a ="SELECT p.id, p.data, p.titolo, r.utente FROM main.post p, main.usr u, main.rubrica r WHERE p.usr = u.id AND u.rubrica = r.id AND p.pubblica = $vis order by data desc;";

$b = pg_query($connection, $a);
$arr = array();
while ($obj = pg_fetch_object($b)) { $arr[] = $obj;}
echo json_encode($arr);
?>
