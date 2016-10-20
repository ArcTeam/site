<?php
session_start();
require("inc/db.php");
require("class/funzioni.php");
$a ="select l.id, l.anno, c.categoria, l.nome, l.descrizione from main.lavoro l, liste.cat c where l.tipo = c.id and l.id = ".$_GET['l'].";";
$b = pg_query($connection, $a);
$c = pg_fetch_array($b);

$extq="select st_extent(geom) as ext from main.attivita where lavoro = ".$_GET['l'].";";
$extres = pg_query($connection, $extq);
$ext = pg_fetch_array($extres);
if(!$ext['ext']){
    $extent= "594297.89483444,3654301.447749,6244523.0248882,6589483.3334912";
    $countF = 0;
}
else{
    $coo = explode(",",str_replace(" ", ",", substr($ext['ext'],4,-1)));
    $extent = str_replace(" ", ",", substr($ext['ext'],4,-1));
    $countF = 1;
}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/lavoro.css" rel="stylesheet" media="screen" />
  </head>
  <body onload="init()">
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
        <section class="form ckform">
            <header><?php echo $c['nome']; ?></header>
            <nav class="toolbar">
                <a href="lavori.php" title="Torna all'archivio lavori">archivio lavori</a>
                <a href="lavoroIns.php?p=<?php echo $_GET['l']; ?>" title="Modifica dati principali lavoro">modifica dati</a>
                <a href="attivitaForm.php?lavoro=<?php echo $_GET['l'];?>&ext=<?php echo $extent;?>&mod=0" title="Aggiungi attività">aggiungi attività</a>
                <a href="#" class='prevent' title="Aggiungi fattura">aggiungi fattura</a>
                <a href="attivitaForm.php?lavoro=<?php echo $_GET['l'];?>&ext=<?php echo $extent;?>&mod=1" title="Modifica geometrie">modifica geometrie</a>
            </nav>
            <section class="sezione inline main">
                <header class="act"><span>Dati principali</span></header>
                <article>
                    <div id="descr">
                        <?php echo $c['descrizione']; ?>
                        <div><strong>Inizio progetto: </strong> <?php echo $c['anno']; ?> | <strong>Categoria: </strong><?php echo $c['categoria']; ?></div>
                        <div id="tag"><?php echo tag($_GET['l'],3); ?></div>
                    </div>
                </article>
            </section>
            <section id="mappa" class="sezione inline mappa">
                <div id="cooDiv"><span>[epsg:4326]</span> <span id="coo"></span></div>
                <div id="noGeom" class="error">Nessuna geometria presente per questo lavoro</div>
            </section>
            <section class="sezione inline odd">
                <header class="act oliva"><span>Materiale scaricabile</span></header>

            </section>
            <section class="sezione inline foto">
                <header class="act"><span>Galleria fotogafica</span></header>

            </section>
            <section class="sezione inline attivita">
                <header><span>Attività</span></header>
                <div class="toggle hide"></div>
            </section>
            <section class="sezione inline fatture">
                <header><span>Fatturazione</span></header>
                <div class="toggle hide"></div>
            </section>
        </section>
    </div>
    <input type="hidden" id="extent" value="<?php echo $extent; ?>">
    <input type="hidden" id="lavoro" value="<?php echo $_GET['l']; ?>">
    <input type="hidden" id="countFeat" value="<?php echo $countF; ?>">
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery-ui-1.14.min.js"></script>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
    <!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="lib/tag/tagmanager.js"></script>-->
    <script src="script/funzioni.js"></script>
    <script src="script/varGeom.js"></script>
    <script src="script/mappaLavoro.js"></script>
    <script>
        $(document).ready(function(){

        });

    </script>
  </body>
</html>
