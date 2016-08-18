<?php
session_start();
require ('../class/mailer/PHPMailerAutoload.php');
require ("db.php");
$msg='';
$pwd = "";
$u = "select email, utente from main.rubrica where id=".$_POST['id'];
$r = pg_query($connection,$u);
$arr = pg_fetch_array($r);
$utente = $arr['utente'];
if (!$_POST['email']) {
    $email = $arr['email'];
    $addMail = '';
}else {
    $email = $_POST['email'];
    $addMail = ", email = '".$email."' ";
}
$pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}
$key = '$2y$11$';
$salt = substr(hash('sha512',uniqid(rand(), true).$key.microtime()), 0, 22);
$password =hash('sha512',$pwd . $salt);

$p = "BEGIN;";
$p .= "update main.rubrica set tipo = ".$_POST['classe']." ".$addMail." where id = ".$_POST['id'].";";
$p .= "insert into main.usr(pwd, salt, attivo, rubrica) values ('$password', '$salt', 1, ".$_POST['id'].");";
$p .= "insert into main.log(tabella, record,operazione, utente) values ('usr', currval('main.usr_id_seq'), 'I', ".$_SESSION['id'].");";
$p .= "COMMIT;";

$e = pg_query($connection, $p);

if ($e) {
    $msg .= "Ok utente creato!";
    $altBody = "Ciao $utente,\nè stato creato un account sul sito www.arc-team.com collegato a questa mail. Se credi sia un errore contatta subito l'amministratore di sistema all'indirizzo info@arc-team.com segnalando la ricezione di una mail non richiesta.\nIn caso contrario ti comunichiamo che a nuova password è : $pwd \nTi consigliamo di cambiare la password temporanea.\n \nUn saluto dallo staff.";
    $mail = new PHPMailer;
    $body = file_get_contents('../mail/newAccount.html');
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
    if (!$mail->send()) {
        $msg .= "<br/>errore invio mail: " . $mail->ErrorInfo;
    }else {
        $msg .= "<br/>Un mail con la nuova password è stata inviata all'indirizzo:  ".$email;
    }
    $msg = "<span class='success inline'>".$msg."</span>";
}else {
    $msg .= "<span class='error inline'>errore nella query: ".pg_last_error($connection)."</span>";
}
echo $msg;
?>
