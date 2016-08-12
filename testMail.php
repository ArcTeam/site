<?php
session_start();
require ('class/PHPMailer/PHPMailerAutoload.php');
require('inc/db.php');
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
                    <?php
                        if (!empty($_POST)){
                            $utente = $_POST['name'];
                            $email = $_POST['email'];
                            $mail = new PHPMailer;
                            $body = file_get_contents('testMail.html');
                            $body = str_replace('%utente%', $utente, $body);
                            $body = str_replace('%email%', $email, $body);
                            $mail->isSMTP();
                            $mail->SMTPDebug = 2;
                            $mail->Debugoutput = 'html';
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
                            $mail->Subject = 'test';
                            $mail->isHTML(true);
                            $mail->msgHTML($body, dirname(__FILE__));
                            $mail->AltBody = 'Ciao '.$utente.'\n'.$email.'\n';
                            if (!$mail->send()) {
                                die("errore: " . $mail->ErrorInfo);
                            }else {
                                echo "Hai inviato una mail all'indirizzo: ".htmlspecialchars($email)."<br>";
                                echo "Il destinatario è: ".htmlspecialchars($utente)."!<br>";
                            }
                        }else{
                    ?>
                        <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
                            Name: <input type="text" name="name"><br>
                            Email: <input type="text" name="email"><br>
                            <input type="submit">
                        </form>
                    <?php } ?>
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
