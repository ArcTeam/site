<?php
session_start();
require_once('inc/db.php');
$pwd= $_POST['newPwd'];
$salt= $_SESSION['salt'];
$password =hash('sha512',$pwd . $salt);
$check="select pwd from main.usr where id = ".$_SESSION['id'];
$checkres = pg_query($connection, $check);
$array = pg_fetch_array($checkres, 0, PGSQL_ASSOC);
if($password==$array['pwd']){
    $msg = "Attenzione, la password digitata corrisponde a quella attuale!<br>Riprova digitando una nuova password.";
}else{
    $insert = "UPDATE main.usr SET pwd = '$password' WHERE id = ".$_SESSION['id'];
    $result = pg_query($connection, $insert);
    if(!$result){
        $msg = "Salvataggio fallito: " . pg_last_error($connection);
    }else{
        $msg = "Salvataggio avvenuto correttamente!<br/>Dal prossimo login potrai utilizzare la nuova password.";
    }
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
