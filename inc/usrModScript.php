<?php
session_start();
require("db.php");

//dati generali
/*************************************************************************************/
$a="select r.tipo, r.utente, r.email, r.indirizzo, r.codfisc, r.telefono, r.cell, r.fax, r.url, r.note, u.img from main.rubrica r, main.usr u where u.rubrica = r.id and u.id = ".$_SESSION['id'];
$b = pg_query($connection,$a);
$c = pg_fetch_array($b);

$tipoq="select * from liste.tipo_utente order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);

//tag utente
$tagUsr = "select t.tag from liste.tag t, main.tags ts where ts.tag = t.id and ts.rec = ".$_SESSION['id']." and ts.tab = 2 order by t.tag asc;";
$tagUsrQ = pg_query($connection,$tagUsr);
$tagUsrRow = pg_num_rows($tagUsrQ);
if($tagUsrRow > 0){
    $tagpresarr = array();
    while ($tagprest = pg_fetch_array($tagUsrQ)) {
        $x['tag'] = $tagprest['tag'];
        array_push($tagpresarr,$x);
    }
    $tagpresList = json_encode($tagpresarr);
}else{
    $tagpresList = 'noTag';
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
