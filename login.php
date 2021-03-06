<?php
session_start();
require("inc/db.php");
if($_POST['submit'] && $_POST['submit']=="login"){
  $a = "select u.id, r.id as rubrica, r.utente, r.tipo, r.email,u.attivo, u.pwd, u.salt, u.img from main.usr u, main.rubrica r where u.rubrica = r.id and u.attivo = 1 and r.email = '".$_POST['email']."'";
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
      $_SESSION['attivo']==$arr["attivo"];
      $_SESSION['utente']==$arr["utente"];
      $_SESSION['classe']=$arr['tipo'];
      $_SESSION['email']=$arr['email'];
      $_SESSION['salt']=$arr['salt'];
      $_SESSION['img']="img/usr/".$arr['img'];
      if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
       $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
       $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
      }

      $ip = filter_var($ip, FILTER_VALIDATE_IP);
      $ip = ($ip === false) ? '0.0.0.0' : $ip;
      $login = ("insert into main.login(utente, ip, sito)values(".$arr['id'].", '$ip', 'arcteam');");
      $result2=pg_query($connection, $login);
      header("Location:index.php");
    }else{
      $msgLogin = "Attenzione, la password non è corretta!";
      $class='error';
    }
  }else{
    $msgLogin = "Attenzione, login fallito!<br>Riprova facendo attenzione a digitare correttamente l'email o la password.<br>Se il problema persiste il tuo account potrebbe essere non attivo, contatta il responsabile web all'indirizzo:<br>beppenapo@arc-team.com.";
    $class='error';
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
      <link href="css/login.css" rel="stylesheet" media="screen" />
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="content form">
        <header>Bentornato utente!</header>
        <form name="loginForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
          <div class="rowButton">
              <i class="fa fa-envelope fa-fw iForm"></i>
              <input type="email" name="email" class="bForm" placeholder="inserisci la tua email" required >
          </div>
          <div class="rowButton">
              <i class="fa fa-key fa-fw iForm"></i>
              <input type="password" name="password" class="bForm" placeholder="inserisci la tua password" required >
          </div>
          <div class="rowButton"><button type="submit" name="submit" value="login"><i class="fa fa-unlock-alt fa-fwi"></i> Login</button></div>
          <div id="msgLogin" class='<?php echo $class; ?>'><?php echo $msgLogin; ?></div>
          <a href="lostPwd.php" id="lostPwd" class='inline' title="chiedi una nuova password al server">Hai dimenticato la password?</a>
        </form>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="script/funzioni.js"></script>
  </body>
</html>
