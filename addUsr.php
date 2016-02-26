<?php
require("inc/db.php");
//select tipo
$tipoq="select * from liste.tipo_utente order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);
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
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <section id="main">
      <form action="addUsrEsito.php" method="post" name="addUsr">
        <div class="row">
          <div class="inline" style="width:45%;">
            <label>*Utente: </label><textarea name="utente" required></textarea>
          </div>
          <div class="inline" style="width:15%;">
            <label>*Tipo utente: </label>
            <select name="tipo" style="width:90%" required>
              <option disabled selected required></option>
              <?php while($tipo = pg_fetch_array($tipoexec)){echo "<option value='".$tipo['id']."'>".$tipo['definizione']."</option>";} ?>
            </select>
          </div>
          <div class="inline" style="width:39%;">
            <label>*E-mail: </label><input type="email" name="email" required>
          </div>
        </div>
        <div class="row">
          <div class="inline" style="width:70%;"><label>Indirizzo: </label><textarea name="indirizzo"></textarea></div>
          <div class="inline" style="width:29%;"><label>Codice fiscale / P.Iva: </label><input type="text" name="codfisc"></textarea></div>
        </div>
        <div class="row">
          <div class="inline"><label>Telefono: </label><input type="text" name="telefono"></div>
          <div class="inline"><label>Cellulare: </label><input type="text" name="cellulare"></div>
          <div class="inline"><label>Fax: </label><input type="text" name="fax"></div>
          <div class="inline" style="width:40%"><label>Sito web: </label><input type="link" name="link"></div>
        </div>
        <div class="row"><div class="inline" style="width:100%"><label>Note: </label><textarea name="note" style="height:100px;"></textarea></div></div>
        <div class="row"><div class="inline" style="width:100%"><input type="submit" name="addUsr" value="crea utente" class="cursor"></div></div>
      </form>
    </section>
  </body>
</html>
