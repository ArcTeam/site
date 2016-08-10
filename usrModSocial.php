<?php
session_start();
require_once('inc/db.php');
$uploaddir = 'img/usr/';
$file = $uploaddir . basename($_FILES['updateImg']['name']);
if (!isset($_FILES['updateImg']) || !is_uploaded_file($_FILES['updateImg']['tmp_name'])) {
    $msg .= 'La foto non è stata modificata.<br/>';
}else if(move_uploaded_file($_FILES['updateImg']['tmp_name'], $file)) {
    chmod($file, 0777);
    $qi="update main.usr set img = '".$_FILES['updateImg']['name']."' where id = ".$_SESSION['id'].";";
    $qir = pg_query($connection, $qi);
    if($qir){
        $_SESSION['img']= $file;
        $msg .= "La foto è stata modificata con successo.<br/>";
    }else{
        $msg .= "errore modifica foto: ".pg_last_error($connection)."<br/>";
    }
} else {
	$msg .= "errore upload foto: ".$_FILES["updateImg"]["error"]."<br/>";
}
//********** gestione tag ********************/
$tags = explode(",",$_POST["tagList"]);
$resetTag = "delete from main.tags where rec = ".$_SESSION['id']." AND tab = 2;";
foreach ($tags as $tag) {
    $a = "select id from liste.tag where tag = '".$tag."'";
    $b = pg_query($connection, $a);
    $c = pg_fetch_array($b);
    $addTag .= "insert into main.tags(tag, rec, tab) values(".$c['id'].", ".$_SESSION['id'].", 2);";
}
$tagq = "BEGIN;";
$tagq .= $resetTag;
$tagq .= $addTag;
$tagq .= "COMMIT;";
$tagr = pg_query($connection,$tagq);
if(!$tagr){
    $msg .= "errore modifica skills: ".pg_last_error($connection)."<br/>";
}else{
    $msg .= "Skills modificate con successo<br/>";
}
//********** gestione social network ********************/
if(isset($_POST['tipo'])){
    $tipo = $_POST['tipo'];
    $link = $_POST['link'];
    foreach( $tipo as $key => $n ) {
        $addSocial .= "insert into main.usr_social(usr,social,link) values(".$_SESSION['id'].", ".$n.", '".$link[$key]."');";
        //$msgSocial .= "tipo: ".$n." link: ".$link[$key].", ";
    }
    $sq = "BEGIN;".$addSocial."COMMIT;";
    $sr = pg_query($connection,$sq);
    if(!$sr){
        $msg .= "errore modifica social: ".pg_last_error($connection)."<br/>";
    }else{
        $msg .= "Social modificati con successo<br/>";}
}else{
    $msg .= "nessun social aggiunto<br/>";
}
header ("Refresh: 5; URL=usrMod.php");
?>
<!DOCTYPE html>
<html>
    <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" >
      <style> #mainWrap div{ width: 70%; margin: 50px auto; border: 1px solid; border-radius: 5px;text-align:center;padding:20px;} </style>
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="form content">
                <header>Risultato query</header>
                <div class="success">
                    <?php echo $msg; ?>
                    <p>Tra 5 secondi verrai reindirizzato automaticamente nella tua pagina personale.</p>
                    <p>Se la pagina non cambia o non vuoi aspettare <a href="usrMod.php">clicca qui</a></p>
                </div>
            </section>
        </div>
        <div style="clear:both !important"></div>
        <footer><?php require("inc/footer.php"); ?></footer>
        <script src="lib/jquery-1.12.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="script/funzioni.js"></script>
    </body>
</html>
