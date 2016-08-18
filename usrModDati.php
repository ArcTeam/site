<?php
session_start();
require_once('inc/db.php');
$utente = (isset($_POST['usrId'])) ? $_POST['usrId'] : $_SESSION['rubrica'];
$txt = (isset($_POST['usrId'])) ? "alla rubrica generale" : "alla tua pagina personale";
$upq = "update main.rubrica set tipo = ".$_POST['tipo']." , utente = '".pg_escape_string($_POST['utente'])."', email='".pg_escape_string($_POST['email'])."', indirizzo = '".pg_escape_string($_POST['indirizzo'])."', codfisc = '".strtoupper($_POST['codfisc'])."', piva = '".strtoupper($_POST['piva'])."', telefono = '".pg_escape_string($_POST['telefono'])."', fax = '".pg_escape_string($_POST['fax'])."', cell= '".pg_escape_string($_POST['cell'])."', url = '".pg_escape_string($_POST['url'])."', note = '".pg_escape_string($_POST['note'])."' where id = ".$utente;
$upexec = pg_query($connection, $upq);
if($upexec){
    $msg = "ok, i tuoi dati sono stati modificati.";
}else{
    $msg = "attenzione, errore ". pg_last_error($connection)." <br/>".$upq;
}
header ("Refresh: 5; URL=".$_POST['refresh']);
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
                    <p>Tra 5 secondi verrai reindirizzato automaticamente <?php echo $txt; ?>.</p>
                    <p>Se la pagina non cambia o non vuoi aspettare <a href="<?php echo $_POST['refresh']; ?>">clicca qui</a></p>
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
