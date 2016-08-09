<?php
require("db.php");
if($_POST['usrPwdMod']){
  $pwd= $_POST['newPwd'];
  $salt= $_SESSION['salt'];
  $password =hash('sha512',$pwd . $salt);

  $check="select pwd from main.usr where id = ".$_SESSION['id'];
  $checkres = pg_query($connection, $check);
  $array = pg_fetch_array($checkres, 0, PGSQL_ASSOC);

  if($password==$array['pwd']){
    $msgPwd = "Attenzione, la password digitata corrisponde a quella attuale!";
  }else{
    $insert = " UPDATE main.usr SET pwd = '$password' WHERE id = ".$_SESSION['id'];
    $result = pg_query($connection, $insert);
    if(!$result){
      $msgPwd = "Salvataggio fallito: " . pg_last_error($connection);
    }else{
      $msgPwd = "Salvataggio avvenuto correttamente!<br/>Dal prossimo login potrai utilizzare la nuova password.";
    }
  }
}
if($_POST['usrMod']){
  $upq = "update main.rubrica set tipo = ".$_POST['tipo']." , utente = '".pg_escape_string($_POST['utente'])."', email='".pg_escape_string($_POST['email'])."', indirizzo = '".pg_escape_string($_POST['indirizzo'])."', codfisc = '".pg_escape_string($_POST['codfisc'])."', telefono = '".pg_escape_string($_POST['telefono'])."', fax = '".pg_escape_string($_POST['fax'])."', cell= '".pg_escape_string($_POST['cell'])."', url = '".pg_escape_string($_POST['url'])."', note = '".pg_escape_string($_POST['note'])."' where id = ".$_SESSION['rubrica'];
  $upexec = pg_query($connection, $upq);
  if($upexec){
    $msg = "ok, i tuoi dati sono stati modificati.";
    header ("Refresh: 3; URL=index.php");
  }else{
    $msg = "attenzione, errore ". pg_last_error($connection);
  }
}
//dati generali
$a="select r.tipo, r.utente, r.email, r.indirizzo, r.codfisc, r.telefono, r.cell, r.fax, r.url, r.note, u.img from main.rubrica r, main.usr u where u.rubrica = r.id and u.id = ".$_SESSION['id'];
$b = pg_query($connection,$a);
$c = pg_fetch_array($b);

$tipoq="select * from liste.tipo_utente order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);

//profilo pubblico
//tag
$tagUsr = "select t.tag from liste.tag t, main.tags ts where ts.tag = t.id and ts.rec = ".$_SESSION['id']." and ts.tab = 2 order by t.tag asc;";
$tagUsrQ = pg_query($connection,$tagUsr);
$tagUsrRow = pg_num_rows($tagUsrQ);
if($tagUsrRow > 0){
    $tagpresarr = array();
    while ($tagprest = pg_fetch_array($tagUsrQ)) {
        $x['id'] = $tagprest['id'];
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
$s = "SELECT u.id, s.nome, s.ico, u.link FROM liste.social s, main.usr_social u WHERE
 u.social = s.id AND u.usr = ".$_SESSION['id']." ORDER BY s.nome ASC;";
$sq = pg_query($connection, $s);
$sqRow = pg_num_rows($sq);



if($_POST['socialMod']){
    $uploaddir = 'img/usr/';
    $file = $uploaddir . basename($_FILES['updateImg']['name']);
    if (!isset($_FILES['updateImg']) || !is_uploaded_file($_FILES['updateImg']['tmp_name'])) {
        echo 'Non hai inviato nessun file...';
    }else if(move_uploaded_file($_FILES['updateImg']['tmp_name'], $file)) {
        chmod($file, 0777);
        $qi="update main.usr set img = '".$_FILES['updateImg']['name']."' where id = ".$_SESSION['id'].";";
        $qir = pg_query($connection, $qi);
        if($qir){
            $_SESSION['img']= $file;
            $msgSocial .= "ok, file caricato";
        }else{
            $msgSocial .= "errore nella query: ".pg_last_error($connection);
        }
    } else {
    	$msgSocial .= "errore: ".$_FILES["updateImg"]["error"];
    }
}
?>
