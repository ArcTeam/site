<?php
session_start();
require("inc/db.php");
//select tipo
$tipoq="select * from liste.tipo_utente order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <style>
        .form{width:80%;}
        .form header{width:80% !important;margin:0px auto 20px;}
        form{width:80%;margin:0px auto;}
        form div.row{margin-bottom:15px;}
        form label{display:block;}
        form textarea,form select, form input{width:95%;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="form content">
        <header>Inserisci un nuovo utente</header>
        <form action="usrAddEsito.php" method="post" name="addUsr">
          <div class="row">
            <div class="inline" style="width:32%;">
              <label>*Utente: </label><textarea name="utente" required></textarea>
            </div>
            <div class="inline" style="width:32%;">
              <label>*Tipo utente: </label>
              <select name="tipo" required>
                <option disabled selected></option>
                <?php while($tipo = pg_fetch_array($tipoexec)){echo "<option value='".$tipo['id']."'>".$tipo['definizione']."</option>";} ?>
              </select>
            </div>
            <div class="inline" style="width:32%;">
              <label>*E-mail: </label><input type="email" name="email" required>
            </div>
          </div>
          <div class="row">
            <div class="inline" style="width:67%;"><label>Indirizzo: </label><textarea name="indirizzo"></textarea></div>
            <div class="inline" style="width:29%;"><label>Codice fiscale / P.Iva: </label><input type="text" name="codfisc"></div>
          </div>
          <div class="row">
            <div class="inline" style="width:32%;"><label>Telefono: </label><input type="text" name="telefono"></div>
            <div class="inline" style="width:32%;"><label>Cellulare: </label><input type="text" name="cellulare"></div>
            <div class="inline" style="width:32%;"><label>Fax: </label><input type="text" name="fax"></div>
            <div class="inline" style="width:100%"><label>Sito web: </label><input type="link" name="link"></div>
          </div>
          <div class="row"><div class="inline" style="width:100%"><label>Note: </label><textarea name="note" style="height:100px;"></textarea></div></div>
          <div class="row">
            <div class="inline" style="width:100%">
                <button type="submit" name="addUsr"><i class="fa fa-save"></i> Crea utente</button>
            </div>
          </div>
        </form>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="script/funzioni.js"></script>
  </body>
</html>
