<?php
session_start();
require("inc/db.php");
require("inc/delRecDiv.php");
//dati generali attività
$a ="select a.gid, a.tipo_lavoro as tipo, t.def as cat, l.id, l.nome, a.data_inizio as inizio, a.data_fine as fine, c.ico, st_x(a.geom) as lon, st_y(a.geom) as lat from main.attivita a, liste.subcat t, main.lavoro l, liste.cat c where a.tipo_lavoro = t.id and a.lavoro = l.id and t.cat = c.id and a.gid = ".$_GET['a'].";";
$b = pg_query($connection, $a);
$c = pg_fetch_array($b);
$fine = (!$c['fine'])?'in corso': $c['fine'];
$class = ($c['fine'])? 'oliva': 'rosso';
$mainData  = "<li><span>Lavoro:</span><span>".$c['nome']."</span></li>";
$mainData .= "<li><span>Categoria:</span><span>".$c['cat']."</span></li>";
$mainData .= "<li><span>Inizio:</span><span>".$c['inizio']."</span></li>";
$mainData .= "<li><span>Fine:</span><span class='".$class."'>".$fine."</span></li>";

// fatture emesse


//estensione lavoro
$extq="select st_extent(geom) as ext from main.attivita where lavoro = ".$_GET['l'].";";
$extres = pg_query($connection, $extq);
$ext = pg_fetch_array($extres);
if(!$ext['ext']){
    $extent= "594297.89483444,3654301.447749,6244523.0248882,6589483.3334912";
}
else{
    $coo = explode(",",str_replace(" ", ",", substr($ext['ext'],4,-1)));
    $extent = str_replace(" ", ",", substr($ext['ext'],4,-1));
}

//lista tipo attività
$l="select s.id, s.def from liste.subcat s order by def asc;";
$lq = pg_query($connection,$l);
while($t=pg_fetch_array($lq)){ $tipo .= "<option value='".$t['id']."'>".$t['def']."</option>";}

?>
<!DOCTYPE html>
<html>
  <head>
    <?php require("inc/meta.php"); ?>
      <link href="css/attivitaScheda.css" rel="stylesheet" media="screen" />
  </head>
  <body onload="init()">
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="form ckform">
        <header><?php echo $c['nome']; ?> - scheda attività</header>
          <nav class="toolbar">
            <ul>
              <li><a href="lavori.php" title="Torna all'archivio lavori">lavori</a></li>
              <li><a href="attivita.php" title="Torna all'elenco attività">attività</a></li>
              <li><a href="lavoro.php?l=<?php echo $_GET['l'];?>" title="Torna alla scheda progetto">scheda progetto</a></li>
              <li><a href="#" class='prevent modMainData' title="modifca dati principali">modifica dati</a></li>
              <li class="viewSub"><a href="#" class='prevent' title="Aggiungi ore">aggiungi</a>
                <ul class="subList">
                  <li><a href="#" class='prevent' title="Aggiungi ore">ore lavoro</a></li>
                  <li><a href="#" class='prevent' title="Aggiungi ore">fattura</a></li>
                  <li><a href="#" class='prevent' title="Aggiungi ore">materiale scaricabile</a></li>
                  <li><a href="#" class='prevent' title="Aggiungi ore">foto</a></li>
                </ul>
              </li>
              <li><a href="#" class='prevent delRecord' title="elimina attivita">elimina attività</a></li>
            </ul>
          </nav>
          <section class="sezione inline main">
            <header><span>Dati principali</span></header>
            <article>
              <ul id="mainData"><?php echo $mainData; ?></ul>
            </article>
            <article id="fatture">
              <header><span>Fatture emesse</span></header>
              <ul id="fattureList"></ul>
            </article>
            <article id="ore">
                <header><span>Ore lavoro</span></header>
                <ul id="oreList"></ul>
            </article>
          </section>
          <section id="mappa" class="sezione inline mappa">
            <div id="panel" class="customEditingToolbar"></div>
            <div id="msgGeom"><div id="msgGeomContent">Geometria modificata</div></div>
            <div id="cooDiv"><span>[epsg:4326]</span> <span id="coo"></span></div>
          </section>
          <section class="sezione inline odd">
            <header><span>Materiale scaricabile</span></header>

          </section>
          <section class="sezione inline foto">
            <header><span>Galleria fotogafica</span></header>

          </section>
      </section>
    </div>

    <section id="formDiv" class="hide">
        <form name="postForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="rowButton">
                <label>Categoria: </label>
                <select name="tipo">
                    <option value="" disabled selected >scegli tipo lavoro</option>
                    <?php echo $tipo; ?>
                </select>
            </div>
            <div class="rowButton">
                <label>Inizio lavoro: </label>
                <input type="date" name="inizio" min="<?php echo $c['inizio'];?>" value="">
            </div>
            <div class="rowButton">
                <label>Fine lavoro: </label>
                <input type="date" name="fine" min="" value="">
            </div>
            <div class="rowButton" id="salva">
                <button type="button" name="salva" class="button success">modifica dati</button>
                <button type="button" name="annulla" class="button warning">annulla inserimento</button>
            </div>
            <div class="rowButton" id="msg"></div>
        </form>
    </section>
    <input type="hidden" id="extent" value="<?php echo $extent; ?>">
    <input type="hidden" id="lavoro" value="<?php echo $_GET['l']; ?>">
    <input type="hidden" id="attivita" value="<?php echo $_GET['a']; ?>">
    <input type="hidden" id="lonlat" value="<?php echo $c['lon'].",".$c['lat']; ?>">
    <input type="hidden" id="tipoVal" value="<?php echo $c['tipo']; ?>">
    <input type="hidden" id="inizioVal" value="<?php echo $c['inizio']; ?>">
    <input type="hidden" id="fineVal" value="<?php echo $c['fine']; ?>">
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery-ui-1.14.min.js"></script>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
    <script src="script/funzioni.js"></script>
    <script src="script/varGeom.js"></script>
    <script src="script/mappaAttivitaScheda.js"></script>
    <script>
        $(document).ready(function(){
          var lavoro = $("#lavoro").val();
          var attivita = $("#attivita").val();
          $(".modMainData").on("click", function(){
            var tipoVal = $("#tipoVal").val();
            var inizioVal = $("#inizioVal").val();
            var fineVal = $("#fineVal").val();
            $("select[name=tipo] option[value=" +tipoVal+ "]").prop("selected", true);
            $("input[name=inizio]").val(inizioVal).on("change",function(){ var initDate = $(this).val(); $("input[name=fine]").attr("min",initDate); });
            $("input[name=fine]").attr("min",inizioVal).val(fineVal);
            $("#formDiv").fadeIn('fast');
            $("button[name=salva]").on("click", function(){
              var newTipo = $("select[name=tipo]").val();
              var newInizio = $("input[name=inizio]").val();
              var newFine = $("input[name=fine]").val();
              if(tipoVal==newTipo&&inizioVal==newInizio&&fineVal==newFine){
                $("#msg").text('I dati sono rimasti invariati, se non vuoi modificare il record clicca su "annulla inserimento"');
              }else{
                $.ajax({
                  type: "POST",
                  url: "inc/attivitaUpdate.php",
                  data: {id:attivita, tipo:newTipo, inizio:newInizio, fine:newFine},
                  success: function(data){
                    $("#msg").text('Dati modificati correttamente');
                  },
                  error: function(xhr, status, error) {
                    var err = JSON.parse(xhr.responseText);
                    $("#msg").text(err.Message);
                  }
                });
                $("#formDiv").delay(3000).fadeOut('fast', function(){location.reload();});
              }
            });
          });
          $("button[name=annulla]").click(function(){ $('#formDiv').fadeOut('fast'); $("#msg").text(''); });

          $(".delRecord").on("click",function(){
              var page = "lavoro.php?l="+lavoro;
              delRec("attivita", "gid", attivita, page);
          });
        });

    </script>
  </body>
</html>
