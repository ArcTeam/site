<?php
session_start();
require("inc/db.php");
require("class/funzioni.php");
require("inc/delRecDiv.php");
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

//attività
$a = "select a.gid, c.ico, a.data_inizio inizio, a.data_fine fine, s.def categoria, st_x(a.geom) as lon, st_y(a.geom) as lat from main.attivita a, liste.cat c, liste.subcat s where a.tipo_lavoro = s.id and s.cat = c.id and a.lavoro = ".$_GET['l']." order by inizio asc;";
$b = pg_query($connection, $a);
while($att = pg_fetch_array($b)){
    $fine=(!$att['fine'])?'in corso':$att['fine'];
    $attivita .= "<li>";
    $attivita .= "<span class='modAtt'><a href='attivita_scheda.php?a=".$att['gid']."&l=".$_GET['l']."' class='link base' title='accedi alla scheda attività'><i class='fa ".$att['ico']."' aria-hidden='true'></i></a></span>";
    $attivita .= "<span class='cat'>".$att['categoria']."</span>";
    $attivita .= "<span class='inizio'>".$att['inizio']."</span>";
    $attivita .= "<span class='fine'>".$fine."</span>";
    $attivita .= "<span class='centraSpan'><a href='#' class='prevent link centra base' data-lonlat='".$att['lon'].",".$att['lat']."' title='centra mappa sull&#39;oggetto'><i class='fa fa-map-marker' aria-hidden='true'></i></a></span>";
    $attivita .= "<span class='fattura'></span>";
    $attivita .= "</li>";
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
                <ul>
                    <li><a href="lavori.php" title="Torna all'archivio lavori"><i class="fa fa-list" aria-hidden="true"></i> lavori</a></li>
                    <li class="viewSub"><a href="#" class='prevent' title="Modifica"><i class="fa fa-pencil" aria-hidden="true"></i> modifca</a>
                        <ul class="subList">
                            <li><a href="lavoroIns.php?p=<?php echo $_GET['l']; ?>" title="Modifica dati principali lavoro">modifica dati</a></li>
                            <li><a href="attivitaForm.php?lavoro=<?php echo $_GET['l'];?>&ext=<?php echo $extent;?>&mod=1" title="Modifica geometrie">modifica geometrie</a></li>
                            <li><a href="#" class='prevent delRecord' title="elimina lavoro">elimina lavoro</a></li>
                        </ul>
                    </li>
                    <li class="viewSub"><a href="#" class='prevent' title="aggiungi"><i class="fa fa-plus" aria-hidden="true"></i> aggiungi</a>
                        <ul class="subList">
                            <li><a href="attivitaForm.php?lavoro=<?php echo $_GET['l'];?>&ext=<?php echo $extent;?>&mod=0" title="Aggiungi attività">attività</a></li>
                            <li><a href="#" class='prevent' title="Aggiungi odd">materiale scaricabile</a></li>
                            <li><a href="#" class='prevent' title="Aggiungi foto">foto</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div id="colSx">
                <section class="sezione inline main">
                    <header><span>Dati principali</span></header>
                    <article>
                        <div id="descr">
                            <?php echo nl2br($c['descrizione']); ?>
                            <div><strong>Inizio progetto: </strong> <?php echo $c['anno']; ?> | <strong>Categoria: </strong><?php echo $c['categoria']; ?></div>
                            <div id="tag"><?php echo tag($_GET['l'],3); ?></div>
                        </div>
                    </article>
                </section>
                <section class="sezione inline attivita">
                    <header><span>Attività</span></header>
                    <ul id="attList"><?php echo $attivita; ?></ul>
                </section>
            </div>
            <section id="mappa" class="sezione inline mappa">
                <div id="cooDiv"><span>[epsg:4326]</span> <span id="coo"></span></div>
                <div id="noGeom" class="error">Nessuna attività presente per questo lavoro</div>
            </section>
            <section class="sezione inline odd">
                <header><span>Materiale scaricabile</span></header>

            </section>
            <section class="sezione inline foto">
                <header><span>Galleria fotogafica</span></header>

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
    <script src="script/funzioni.js"></script>
    <script src="script/varGeom.js"></script>
    <script src="script/mappaLavoro.js"></script>
    <script>
        $(document).ready(function(){
            var lavoro = $("#lavoro").val();
            $(".centra").on("click",function(){
                var ll = $(this).data("lonlat");
                ll = ll.split(',');
                setCenter(ll[0],ll[1]);
            });
            $(".delRecord").on("click",function(){ delRec("lavoro", "id", lavoro, "lavori.php"); });
        });

    </script>
  </body>
</html>
