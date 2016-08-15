<?php
session_start();
require_once("inc/db.php");
$a="select r.id, r.tipo, r.utente, r.email, r.indirizzo, r.codfisc, r.telefono, r.cell, r.fax, r.url, r.note from main.rubrica r where r.id = ".$_GET['x'];
$b = pg_query($connection,$a);
$c = pg_fetch_array($b);

$fq = "select tipo from liste.tipo_utente where id = ".$c['tipo'];
$fe = pg_query($connection,$fq);
$filtro = pg_fetch_array($fe);

$tipoq="select * from liste.tipo_utente where tipo = ".$filtro['tipo']." order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);
?>
<!DOCTYPE html>
<html>
    <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/usrMod.css" rel="stylesheet" media="screen" >
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="form content">
                <header>Dati generali</header>
                <form action="usrModDati.php" method="post" name="usrModForm">
                    <input type="hidden" name='refresh' value='rubrica.php' >
                    <input type="hidden" name='usrId' value='<?php echo $c["id"]?>' >
                    <div class="row">
                        <div class="inline" style="width:32%;">
                            <label>*Utente: </label>
                            <textarea name="utente" required><?php echo $c['utente']; ?></textarea>
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
                            <label>*E-mail: </label>
                            <input type="email" name="email" value="<?php echo $c['email']; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:67%;">
                            <label>Indirizzo: </label>
                            <textarea name="indirizzo"><?php echo $c['indirizzo']; ?></textarea>
                        </div>
                        <div class="inline" style="width:29%;">
                            <label>Codice fiscale / P.Iva: </label>
                            <input type="text" name="codfisc" value="<?php echo $c['codfisc']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:32%;">
                            <label>Telefono: </label>
                            <input type="text" name="telefono" value="<?php echo $c['telefono']; ?>">
                        </div>
                        <div class="inline" style="width:32%;">
                            <label>Cellulare: </label>
                            <input type="text" name="cell" value="<?php echo $c['cell']; ?>">
                        </div>
                        <div class="inline" style="width:32%;">
                            <label>Fax: </label>
                            <input type="text" name="fax" value="<?php echo $c['fax']; ?>">
                        </div>
                        <div class="inline" style="width:100%">
                            <label>Sito web: </label>
                            <input type="link" name="url" value="<?php echo $c['url']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <label>Note: </label>
                            <textarea name="note" style="height:100px;"><?php echo $c['note']; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <button type="submit" name="usrMod" value="modifica dati"><i class="fa fa-save"></i> Modifica dati</button>
                        </div>
                    </div>
                    <span class="msg"><?php echo $msg; ?></span>
                </form>
            </section>
        </div>
        <div style="clear:both !important"></div>
        <footer><?php require("inc/footer.php"); ?></footer>
        <script src="lib/jquery-1.12.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="lib/tag/tagmanager.js"></script>
        <script src="script/funzioni.js"></script>
    </body>
</html>
