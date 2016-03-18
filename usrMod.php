<?php
session_start();
require("inc/db.php");
if($_POST['usrPwdMod']){
  $pwd= $_POST['newPwd'];
  $salt= $_SESSION['salt'];
  $password =hash('sha512',$pwd . $salt);

  $check="select pwd from main.usr where id = ".$_SESSION['id'];
  $checkres = pg_query($connection, $check);
  $array = pg_fetch_array($checkres, 0, PGSQL_ASSOC);

  if($password==$array['pwd']){
    $msgPwd = "Attenzione, la password digitata corrisponde a quella attuale!";
  }else{
    $insert = " UPDATE main.usr SET pwd = '$password' WHERE id = ".$_SESSION['id'];
    $result = pg_query($connection, $insert);
    if(!$result){
      $msgPwd = "Salvataggio fallito: " . pg_last_error($connection);
    }else{
      $msgPwd = "Salvataggio avvenuto correttamente!<br/>Dal prossimo login potrai utilizzare la nuova password.";
    }
  }
}
if($_POST['usrMod']){
  $upq = "update main.rubrica set tipo = ".$_POST['tipo']." , utente = '".pg_escape_string($_POST['utente'])."', email='".pg_escape_string($_POST['email'])."', indirizzo = '".pg_escape_string($_POST['indirizzo'])."', codfisc = '".pg_escape_string($_POST['codfisc'])."', telefono = '".pg_escape_string($_POST['telefono'])."', fax = '".pg_escape_string($_POST['fax'])."', cell= '".pg_escape_string($_POST['cell'])."', url = '".pg_escape_string($_POST['url'])."', note = '".pg_escape_string($_POST['note'])."' where id = ".$_SESSION['rubrica'];
  $upexec = pg_query($connection, $upq);
  if($upexec){
    $msg = "ok, i tuoi dati sono stati modificati.";
    header ("Refresh: 3; URL=index.php");
  }else{
    $msg = "attenzione, errore ". pg_last_error($connection);
  }
}

$a="select r.tipo, r.utente, r.email, r.indirizzo, r.codfisc, r.telefono, r.cell, r.fax, r.url, r.note from main.rubrica r, main.usr u where u.rubrica = r.id and u.id = ".$_SESSION['id'];
$b = pg_query($connection,$a);
$c = pg_fetch_array($b);

$tipoq="select * from liste.tipo_utente order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);


?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <style>
        .formContent{width:80%;}
        .formContent header{width:80% !important;margin:0px auto 20px;}
        form{width:80%;margin:0px auto;}
        form div.row{margin-bottom:15px;}
        form label{display:block;}
        form textarea,form select, form input{width:95%;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="formContent">
        <header>Dati generali</header>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="usrModForm">
          <div class="row">
            <div class="inline" style="width:32%;">
              <label>*Utente: </label><textarea name="utente" required><?php echo $c['utente']; ?></textarea>
            </div>
            <div class="inline" style="width:32%;">
              <label>*Tipo utente: </label>
              <select name="tipo" required>
                <?php
                  while($tipo = pg_fetch_array($tipoexec)){
                    $sel = ($tipo['id']== $c['tipo']) ? 'selected' : '';
                    echo "<option value='".$tipo['id']."' ".$sel.">".$tipo['definizione']."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="inline" style="width:32%;">
              <label>*E-mail: </label><input type="email" name="email" value="<?php echo $c['email']; ?>" required>
            </div>
          </div>
          <div class="row">
            <div class="inline" style="width:67%;"><label>Indirizzo: </label><textarea name="indirizzo"><?php echo $c['indirizzo']; ?></textarea></div>
            <div class="inline" style="width:29%;"><label>Codice fiscale / P.Iva: </label><input type="text" name="codfisc" value="<?php echo $c['codfisc']; ?>"></div>
          </div>
          <div class="row">
            <div class="inline" style="width:32%;"><label>Telefono: </label><input type="text" name="telefono" value="<?php echo $c['telefono']; ?>"></div>
            <div class="inline" style="width:32%;"><label>Cellulare: </label><input type="text" name="cell" value="<?php echo $c['cell']; ?>"></div>
            <div class="inline" style="width:32%;"><label>Fax: </label><input type="text" name="fax" value="<?php echo $c['fax']; ?>"></div>
            <div class="inline" style="width:100%"><label>Sito web: </label><input type="link" name="url" value="<?php echo $c['url']; ?>"></div>
          </div>
          <div class="row"><div class="inline" style="width:100%"><label>Note: </label><textarea name="note" style="height:100px;"><?php echo $c['note']; ?></textarea></div></div>
          <div class="row"><div class="inline" style="width:100%"><input type="submit" name="usrMod" value="modifica dati" class="cursor"></div></div>
          <span class="msg"><?php echo $msg; ?></span>
        </form>
        <header>Modifica password</header>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="usrPwdModForm">
          <div class="row">
            <div class="inline" style="width:100%"><label>Nuova password: </label><input type="password" id="newPwd" name="newPwd" required></div>
          </div>
          <div class="row">
            <div class="inline" style="width:100%"><label>Ridigita password: </label><input type="password" id="checkPwd" name="checkPwd" required></div>
          </div>
          <div class="row">
            <div class="inline" style="width:100%"><input type="submit" name="usrPwdMod" value="modifica password" class="cursor"></div>
          </div>
          <span class="msg"><?php echo $msgPwd; ?></span>
        </form>
      </section>
    </div>
    <div style="clear:both !important"></div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="script/funzioni.js"></script>
    <script>
      var p = document.getElementById("newPwd");
      var cp = document.getElementById("checkPwd");
      function validatePassword(){
        if(p.value.length < 6){
          p.setCustomValidity("La password deve contenere almeno 6 caratteri!");
        }else{
          p.setCustomValidity('');
          if(p.value != cp.value) {
            cp.setCustomValidity("Attenzione, le password non coincidono!");
          } else {
            cp.setCustomValidity('');
          }
        }
      }
      p.onchange = validatePassword;
      cp.onkeyup = validatePassword;
    </script>
  </body>
</html>
