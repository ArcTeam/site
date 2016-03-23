<?php
require("inc/db.php");
$tipo = $_POST['tipo'];
$utente = pg_escape_string($_POST['utente']);
$email = $_POST['email'];
$indirizzo = pg_escape_string($_POST['indirizzo']);
$codfisc = strtoupper($_POST['codfisc']);
$telefono = $_POST['telefono'];
$cellulare = $_POST['cellulare'];
$fax = $_POST['fax'];
$link = $_POST['link'];
$note = pg_escape_string($_POST['note']);
if($tipo > 1){
  $pwd = "";
  $pwdRand = array_merge(range('A','Z'), range('a','z'), range(0,9));
  for($i=0; $i < 10; $i++) {$pwd .= $pwdRand[array_rand($pwdRand)];}

  $key = '$2y$11$';
  $salt = substr(hash('sha512',uniqid(rand(), true).$key.microtime()), 0, 22);
  $password =hash('sha512',$pwd . $salt);
  $p = "insert into main.usr(pwd, salt, attivo, rubrica) values ('$password', '$salt', 1, currval('main.rubrica_id_seq'));";
}else{
  $p = '';
}
$query = "BEGIN; insert into main.rubrica(tipo, utente, email, indirizzo, codfisc, telefono, cell, fax, url, note) values ($tipo, '$utente', '$email', '$indirizzo', '$codfisc', '$telefono', '$cellulare', '$fax','$link','$note'); $p COMMIT; ";
$e = pg_query($connection, $query);
if(!$e){ $msg = "<div class='alert error'>Errore nella query".pg_last_error($connection)."</div>"; }else{ $msg = "<div class='alert success'>ok utente creato!</div>"; }
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <style>
        form{width:80%;margin:0px auto;}
        form div.row{margin-bottom:15px;}
        form label{display:block;}
        form textarea,form select, form input{width:95%;}
        .success{width:80%;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <section id="main">
      <?php echo $msg; ?>
      <?php if($tipo > 1){echo "<div style ='text-align:center;'>Per accedere al sistema l'utente dovrà utilizzare la seguente password generata automaticamente dal sistema:<br><strong>$pwd</strong></div>";} ?>
    </section>
  </body>
</html>
