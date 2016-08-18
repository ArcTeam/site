<?php
session_start();
require ('class/mailer/PHPMailerAutoload.php');
require("inc/db.php");
$tipo = $_POST['tipo'];
$utente = pg_escape_string($_POST['utente']);
$email = $_POST['email'];
$indirizzo = pg_escape_string($_POST['indirizzo']);
$codfisc = strtoupper($_POST['codfisc']);
$piva = strtoupper($_POST['piva']);
$telefono = $_POST['telefono'];
$cellulare = $_POST['cellulare'];
$fax = $_POST['fax'];
$link = $_POST['link'];
$note = pg_escape_string($_POST['note']);
$msg = '';
if($tipo > 1){
  $pwd = "";
  $pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
  for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}

  $key = '$2y$11$';
  $salt = substr(hash('sha512',uniqid(rand(), true).$key.microtime()), 0, 22);
  $password =hash('sha512',$pwd . $salt);
  $p = "insert into main.usr(pwd, salt, attivo, rubrica) values ('$password', '$salt', 1, currval('main.rubrica_id_seq'));";
  $p .= "insert into main.log(tabella, record,operazione, utente) values ('usr', currval('main.usr_id_seq'), 'I', ".$_SESSION['id'].");";

  $altBody = "Ciao $utente,\nè stato creato un account sul sito www.arc-team.com collegato a questa mail. Se credi sia un errore contatta subito l'amministratore di sistema all'indirizzo info@arc-team.com segnalando la ricezione di una mail non richiesta.\nIn caso contrario ti comunichiamo che a nuova password è : $pwd \nTi consigliamo di cambiare la password temporanea.\n \nUn saluto dallo staff.";
  $mail = new PHPMailer;
  $body = file_get_contents('mail/newAccount.html');
  $body = str_replace('%utente%', $utente, $body);
  $body = str_replace('%password%', $pwd, $body);
  $mail->isSMTP();
  //$mail->SMTPDebug = 2;
  //$mail->Debugoutput = 'html';
  $mail->Host = "smtps.aruba.it";
  $mail->Mailer = "smtp";
  $mail->Port = 465;
  $mail->SMTPSecure = 'ssl';
  $mail->SMTPAuth = true;
  $mail->Username = 'info@arc-team.com';
  $mail->Password = 'Arc-T3amV3';
  $mail->setFrom('info@arc-team.com', 'Arc-Team');
  $mail->addReplyTo('info@arc-team.com', 'Arc-Team');
  $mail->addAddress($email, $utente);
  $mail->Subject = 'Creazione nuovo account su arc-team.com';
  $mail->isHTML(true);
  $mail->msgHTML($body, dirname(__FILE__));
  $mail->AltBody = $altBody;
}else{
  $p = '';
}
$query = "BEGIN;";
$query .= "insert into main.rubrica(tipo, utente, email, indirizzo, codfisc, piva, telefono, cell, fax, url, note) values ($tipo, '$utente', '$email', '$indirizzo', '$codfisc', '$piva', '$telefono', '$cellulare', '$fax','$link','$note');";
$query .= $p;
$query .= "insert into main.log(tabella, record,operazione, utente) values ('rubrica', currval('main.rubrica_id_seq'), 'I', ".$_SESSION['id'].");";
$query .=  "COMMIT; ";
$e = pg_query($connection, $query);
if(!$e){
    $msg .= "Errore nella query".pg_last_error($connection);
}else{
    $msg .= "Ok utente creato!";
    if($tipo > 1){
        if (!$mail->send()) {
            $msg .= "<br/>errore invio mail: " . $mail->ErrorInfo;
        }else {
            $msg .= "<br/>Un mail con la nuova password è stata inviata all'indirizzo:  ".$email;
        }
    }
}
header ("Refresh: 5; URL=rubrica.php");
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
                    <p>Tra 5 secondi verrai reindirizzato automaticamente nella rubrica.</p>
                    <p>Se la pagina non cambia o non vuoi aspettare <a href="rubrica.php">clicca qui</a></p>
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
