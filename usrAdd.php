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
        form, div.section{width:80%;margin:0px auto;}
        div.section input{vertical-align:middle;margin-right:5px;}
        div.section li label{font-weight:bold; vertical-align: middle; cursor:pointer; display: inline-block; width: 138px;}
        div.section li span{ display: inline-block; width: 600px; vertical-align: top;  font-size: .9rem;}
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
        <div class="section">
            <label>Scegli il tpo di utente da inserire tra le seguenti categorie:</label>
            <ul>
                <li>
                    <input type="radio" name="selUsr" value="1" id="esterno">
                    <label for="esterno">Utente esterno </label>
                    <span>(L'utente verrà inserito nella rubrica generale ma non potrà effettuare il login)</span>
                </li>
                <li>
                    <input type="radio" name="selUsr" value="2" id="interno">
                    <label for="interno">Utente di sistema </label>
                    <span>(All'utente verrà inviata una password per poter accedere al sistema, il tipo di utente scelto determinerà le azioni che il nuovo utente potrà svolgere una volta effettuato il login)</span>
                </li>
            </ul>
        </div>
        <form action="usrAddEsito.php" method="post" name="addUsr">
          <div class="row">
            <div class="inline" style="width:32%;">
              <label>*Tipo utente: </label>
              <select name="tipo" required>
                <option disabled selected></option>
                <?php while($tipo = pg_fetch_array($tipoexec)){echo "<option value='".$tipo['id']."' data-tipo='".$tipo['tipo']."'>".$tipo['definizione']."</option>";} ?>
              </select>
            </div>
            <div class="inline" style="width:32%;">
                <label>*Utente: </label><textarea name="utente" required></textarea>
            </div>
            <div class="inline" style="width:32%;">
              <label id="emailLabel">E-mail: </label><input type="email" name="email">
            </div>
          </div>
          <div class="row">
            <div class="inline" style="width:66%;"><label>Indirizzo: </label><textarea name="indirizzo"></textarea></div>
            <div class="inline" style="width:15%;"><label>Codice fiscale: </label><input type="text" name="codfisc"  style="width:90%;"></div>
            <div class="inline" style="width:15%;"><label>P.Iva: </label><input type="text" name="piva" style="width:90%;"></div>
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
    <script>
        $(document).ready(function(){
            $("form[name='addUsr'] *").prop('disabled', true);
            $("input[name='selUsr']").on("change", function(){
                var isDisabled = $("select[name='tipo']").is(':disabled');
                if(isDisabled){ $("form[name='addUsr'] *").prop('disabled', false); }
                var tipo = $(this).val();
                if(tipo==1){
                    $("select[name='tipo'] option[data-tipo=1]").show();
                    $("select[name='tipo'] option[data-tipo=2]").hide();
                    $("input[name='email']").prop("required", false);
                    $("#emailLabel").text('E-mail');
                }else{
                    $("select[name='tipo'] option[data-tipo=1]").hide();
                    $("select[name='tipo'] option[data-tipo=2]").show();
                    $("input[name='email']").prop("required", true);
                    $("#emailLabel").text('*E-Mail');
                }
            });
        });
    </script>
  </body>
</html>
