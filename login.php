<?php
session_start();
require("inc/db.php");
if($_POST['submit'] && $_POST['submit']=="login"){
  $a = "select u.id, r.id as rubrica, r.utente, r.tipo, r.email, u.pwd, u.salt from main.usr u, main.rubrica r where u.rubrica = r.id and u.attivo = 1 and r.email = '".$_POST['email']."'";
  $b = pg_query ($connection,$a);
  $row = pg_num_rows($b);
  $arr= pg_fetch_array($b);
  if($row > 0){
    $pass = $_POST['password'];
    $salt = $arr['salt'];
    $pwd =hash('sha512',$pass . $salt);
    if($pwd === $arr['pwd']){
      $_SESSION['id']=$arr['id'];
      $_SESSION['rubrica']=$arr['rubrica'];
      $_SESSION['utente']==$arr["utente"];
      $_SESSION['classe']=$arr['tipo'];
      $_SESSION['email']=$arr['email'];
      $_SESSION['salt']=$arr['salt'];
      if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
       $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
       $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
      }

      $ip = filter_var($ip, FILTER_VALIDATE_IP);
      $ip = ($ip === false) ? '0.0.0.0' : $ip;
      $login = ("insert into main.login(utente, ip)values(".$arr['id'].", '$ip');");
      $result2=pg_query($connection, $login);
      header("Location:index.php");
    }else{
      $msgLogin = "Attenzione, la password non è corretta!<br>Se non ricordi la password chiedine una nuova al server utilizzando il pulsante 'nuova password'.";
    }
  }else{
    $msgLogin = "Attenzione, login fallito!<br>Riprova facendo attenzione a digitare correttamente l'email o la password.<br>Se il problema persiste il tuo account potrebbe essere non attivo, contatta il responsabile web all'indirizzo:<br>beppenapo@arc-team.com.";
  }
}
if($_GET['action']){
  session_destroy();
  header("Location:index.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <style>
        section.formContent{ width: 40%;}
        section.formContent input{border-radius:0px 5px 5px 0px !important; width:85%;font-size:1.25rem !important;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="formContent">
        <header>Bentornato utente!</header>
        <form name="loginForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
          <div class="rowButton"><i class="fa fa-envelope fa-fw"></i><input type="email" name="email" required ></div>
          <div class="rowButton"><i class="fa fa-key fa-fw"></i><input type="password" name="password" required ></div>
          <div class="rowButton"><i class="fa fa-unlock-alt fa-fw"></i><input type="submit" name="submit" value="login"></div>
          <span id="msgLogin"><?php echo $msgLogin; ?></span>
        </form>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="script/funzioni.js"></script>
  </body>
</html>
