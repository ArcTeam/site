<?php
session_start();
require("db.php");

//dati generali
/*************************************************************************************/
$a="select r.tipo, r.utente, r.email, r.indirizzo, r.codfisc, r.piva, r.telefono, r.cell, r.fax, r.url, r.note, u.img from main.rubrica r, main.usr u where u.rubrica = r.id and u.id = ".$_SESSION['id'];
$b = pg_query($connection,$a);
$c = pg_fetch_array($b);

$tipoq="select * from liste.tipo_utente order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);

//tag utente
$tagUsr = "select tags from main.tags where rec = ".$_SESSION['id']." and tab = 2;";
$tagUsrQ = pg_query($connection,$tagUsr);
$tagUsrRes = pg_fetch_array($tagUsrQ);
if (!$tagUsrRes) {
    $tagpresList = 'noTag';
}else {
    $tags = explode(',',$tagUsrRes['tags']);
    asort($tags);
    $tagpresList = json_encode($tags);
}

//lista tag
$t = "select tag from liste.tag order by tag asc;";
$tq = pg_query($connection, $t);
$tag = array();
while ($obj = pg_fetch_array($tq)) { $tag[] = $obj['tag'];}
$tagList = json_encode($tag);

//lista social
$social = "select * from liste.social order by nome asc;";
$socialQ = pg_query($connection, $social);

//social utente
$s = "SELECT u.id, s.nome, s.ico, u.link FROM liste.social s, main.usr_social u WHERE u.social = s.id AND u.usr = ".$_SESSION['id']." ORDER BY s.nome ASC;";
$sq = pg_query($connection, $s);
$sqRow = pg_num_rows($sq);
?>
