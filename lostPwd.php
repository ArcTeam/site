<?php
session_start();
//require ('class/mailer/PHPMailerAutoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'class/vendor/autoload.php';
require('inc/db.php');
if(!empty($_POST)){
    $checkQuery = "SELECT usr.id, rubrica.utente, rubrica.email, usr.salt FROM main.usr, main.rubrica WHERE usr.rubrica = rubrica.id and usr.attivo = 1 and rubrica.email = '".$_POST['email']."'; ";
    $checkExec = pg_query($connection, $checkQuery);
    if (!$checkExec) {
        $msg = 'errore nella query: '.pg_last_error($connection);
        $class = 'error';
    }else{
        $row = pg_num_rows($checkExec);
        if ($row == 0) {
            $msg = "Attenzione, la mail non è presente nel database o il tuo utente è stato disattivato!<br/>Contatta l'amministratore di sistema all'indirizzo info@arc-team.com";
            $class='warning';
        }else {
            $dati=pg_fetch_array($checkExec);
            $pwd = "";
            $pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
            for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}
            $key = '$2y$11$';
            $salt = $dati['salt'];
            $password =hash('sha512',$pwd . $salt);
            $newPwdQ = "UPDATE main.usr SET pwd='$password' where id = ".$dati['id'].";";
            $newPwdExec = pg_query($connection,$newPwdQ);
            if(!$newPwdExec){
                $msg = "Errore nella query di aggiornamento: " . pg_last_error($connection);
                $class = 'error';
            }else{
                $utente = $dati['utente'];
                $email = $_POST['email'];
                $altBody = "Ciao $utente,\nè stata fatta una richiesta per una nuova password sul sito www.arc-team.com, ed è stata indicata questa come mail di recupero, se non sei stato tu ad inviare la richiesta ignora questa mail e contatta l'amministratore del sistema all'indirizzo info@arc-team.com per segnalare una possibile violazione della tua mail.\nLa nuova password è : $pwd \nTi consigliamo di cambiare la password temporanea.\n \nUn saluto dallo staff.";
                $body = file_get_contents('mail/rescuePwd.html');
                $body = str_replace('%utente%', $utente, $body);
                $body = str_replace('%password%', $pwd, $body);
                
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->SMTPDebug = 2;
                $mail->Debugoutput = 'html';
                $mail->Host = "smtps.aruba.it";
                $mail->Port = 465;
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth = true;
                $mail->Username = 'beppenapo@arc-team.com';
                $mail->Password = 'Strat0Caster';                              
                //$mail->Mailer = "smtp";
                $mail->setFrom('arcteam.archaeology@gmail.com', 'Arc-Team');
                $mail->addReplyTo('arcteam.archaeology@gmail.com', 'Arc-Team');
                $mail->addAddress($email, $utente);
                $mail->Subject = 'Recupero password per il tuo account su arc-team.com';
                $mail->isHTML(true);
                $mail->msgHTML($body, dirname(__FILE__));
                $mail->AltBody = $altBody;
                if (!$mail->send()) {
                    $msg = "errore: " . $mail->ErrorInfo;
                }else {
                    $msg = "Password rigenerata con successo!<br/>Un mail con la nuova password è stata inviata all'indirizzo:  ".$dati['email']."<br/>La mail potrebbe impiegare alcuni minuti prima di essere consegnata, se non arriva contata l'amministratore di sistema all'indirizzo info@arc-team.com<br/>Si consiglia di modificare la password assegnata dal server.";
                    $class = 'success';
                    header ("Refresh: 5; URL=login.php");
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <style>
        section.form{ width: 60%;}
        section.form input{border-radius:3px 0px 0px 3px; width:50%;font-size:1rem;line-height:1rem;}
        form{text-align:center;padding:20px 0px;}
        #testo{font-size:.8rem}
        input[name='email']{padding:3px 5px;}
        button[name='lpButt']{ width: 35px; font-size: 1rem; border-radius: 0px 3px 3px 0px; margin-left: -5px;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="content form">
        <header>Rigenera password!</header>
        <div id="testo">
            <p>Per motivi di sicurezza le mail salvate nel database sono criptate e non è possibile recuperarle. Ad ogni richiesta di recupero password il sistema ne crea una nuova che verrà inviata via mail.</p>
            <p>Per generare la nuova password inserisci nel form sottostante l'indirizzo di posta elettronica utilizzato al momento della registrazione.</p>
            <p>Se la mail non ti arriva controlla nella spam</p>
            <p>Se continui ad avere problemi di ricezione contatta i referenti del sistema all'indirizzo info@arc-team.com</p>
        </div>
        <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
            <input name="email" type="email" size="15" placeholder="inserisci email" class="" required >
            <button class="lpButt" name="lpButt" type="submit" title="chiedi una nuova password al server"><i class="fa fa-cogs"></i></button>
        </form>
        <div class='<?php echo $class; ?>' id="msg"><?php echo $msg; ?></div>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="script/funzioni.js"></script>
  </body>
</html>
