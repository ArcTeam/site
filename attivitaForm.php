<?php
session_start();
require("inc/db.php");
if(isset($_GET['lavoro'])){
    $ql="select * from main.lavoro where id = ".$_GET['lavoro'];
    $qe = pg_query($connection,$ql);
    $anno = pg_fetch_array($qe);
    $maxDate = $anno['anno']."-01-01";
}
$header=($_GET['mod']==0)?'Inserisci attività':'Modifica geometrie';

//lista tipo attività
$l="select s.id, s.def from liste.subcat s order by def asc;";
$lq = pg_query($connection,$l);
while($t=pg_fetch_array($lq)){ $tipo .= "<option value='".$t['id']."'>".$t['def']."</option>";}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/postForm.css" rel="stylesheet" media="screen" />
      <link href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" >
      <style>
        #mappa{position: relative;width:100%;height:600px;margin:0px;padding:0px;background:#eee;}
        #panel div{ font-family: "FontAwesome"; width: 40px; height: 40px; font-size: 1.3rem; line-height: 40px; background: rgba(0, 60, 136, 0.5);margin:1px;text-align:center;color:#fff;cursor:pointer;}
        #panel div:hover{background: rgba(0, 60, 136, 0.7);}
        #panel div[class*="ItemActive"] { background: #82C914 !important;}
        #panel div[class*="olControlNavigation"]:before{content: "\f047";}
        #panel div[class*="olControlDrawFeaturePoint"]:before{content: "\f041";}
        #panel div[class*="olControlModifyFeature"]:before{content: "\f021";}
        #panel div[class*="olControlSaveFeature"]:before{content: "\f164";}
        #panel div[class*="olControlDeleteFeature"]:before{content: "\f014";}
        #formDiv,#deleteGeom{ position: absolute; background: #fff; padding: 20px; z-index: 1001; border-radius: 4px; border: 1px solid rgba(0,0,0,0.3); box-shadow: 0px 0px 10px rgba(0,0,0,0.7);}
        #formDiv{top: 50px; right: 50px; width: 300px;}
        #formDiv header{font-size:1.5rem; padding:0px; margin:0px;}
        #formDiv label{display:inline-block;width:90px;}
        #deleteGeom{top: 200px; left: 20%; width: 60%;}
        #coo{position:absolute;top:5px;right:10px;color:#000;text-shadow:0px 0px 5px rgba(0, 0, 0, 1);z-index: 1000;font-size:.9rem;}
        #geocoder{position:absolute;top:10px;left:100px;z-index:1001;}
        input[name='go']{border-radius:4px 0px 0px 4px;border-right:none !important;width:300px;}
        button[name='search']{margin-left:-4px;padding:7px;cursor:pointer;color:#fff;background:rgb(103,148,196);border:none;border-radius:0px 4px 4px 0px;}
        button[name='search']:hover{background:rgb(61,112,171);}
        button[name='search'] > i{margin:4px;}
        #resultSearch{ margin-top: -1px; width: 312px; max-height: 400px;overflow:auto;background: #fff; border: 1px solid #999; border-radius: 0px 0px 4px 4px;font-size:.8rem;}
        #resultSearchList li{padding:6px;cursor:pointer;}
        #resultSearchList li:nth-child(even){background:#e8e7ff;}
        #resultSearchList li:hover{color:#fff;background:rgb(103,148,196);}
        #hideSearch{ display: block; padding: 6px; cursor: pointer; background: rgb(103,148,196); color: #fff;}
        .postSave, #msg, .postDel, #deleteGeom{display: none;}
      </style>
  </head>
  <body onload="init()">
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
        <section class="form ckform">
            <header><?php echo $header; ?></header>
            <nav class="toolbar">
                <ul>
                    <li><a href="lavori.php" title="Torna all'archivio lavori">archivio lavori</a></li>
                    <li><a href="lavoro.php?l=<?php echo $_GET['lavoro']; ?>" title="Torna alla scheda lavoro di cui fa parte l'attività">scheda lavoro</a></li>
                </ul>
            </nav>
            <section id="mappa">
                <div id="panel" class="customEditingToolbar"></div>
                <div id="geocoder">
                    <input type="text" name="go" placeholder="cerca un luogo">
                    <button type="button" name="search" class="transition"><i class='fa fa-search' aria-hidden="true"></i></button>
                    <div id="resultSearch"><ul id="resultSearchList"></ul><span id='hideSearch'>chiudi lista</span></div>
                </div>
                <div id="coo"></div>
                <section id="formDiv" class="hide">
                    <form name="postForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                        <input type="hidden" name="lavoro" id="lavoro" value="<?php echo $_GET['lavoro']; ?>" >
                        <input type="hidden" name="attivita" value="<?php echo $id; ?>" >
                        <input type="hidden" name="fid" id="fid" value="" >
                        <header class="headForm">Metadati attività</header>
                        <div class="rowButton">
                            <label>Categoria: </label>
                            <select name="tipo">
                                <option value="" disabled selected >scegli tipo lavoro</option>
                                <?php echo $tipo; ?>
                            </select>
                        </div>
                        <div class="rowButton">
                            <label>Inizio lavoro: </label>
                            <input type="date" name="inizio" min="<?php echo $maxDate;?>" value="">
                        </div>
                        <div class="rowButton">
                            <label>Fine lavoro: </label>
                            <input type="date" name="fine" min="" value="">
                        </div>
                        <div class="rowButton" id="salva">
                            <button type="button" name="salva" class="button success">salva punto</button>
                            <button type="button" name="annulla" class="button warning">annulla inserimento</button>
                        </div>
                        <div class="rowButton" id="msg"></div>
                        <div class="rowButton postSave" id="mod">
                            <button type="button" name="continua" class="button base">Continua modifica</button>
                            <button type="button" name="chiudi" class="button base">Chiudi sessione</button>
                        </div>
                        <div class="rowButton postSave" id="fattura">
                            <button type="button" name="fattura" class="button base">Aggiungi fattura</button>
                            <button type="button" name="chiudi" class="button base">Chiudi sessione</button>
                        </div>
                    </form>
                </section>
                <div id="deleteGeom">
                    <div class="warning" id="deleteMsg"><span></span></div>
                    <div class="rowButton" id="deleteButton">
                        <button type="button" name="confermaDel" class="button error preDel">Conferma eliminazione</button>
                        <button type="button" name="continuaDel" class="button base postDel">Continua modifica</button>
                        <button type="button" name="chiudi" class="button base postDel">Chiudi sessione</button>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <input type="hidden" id="ext" value="<?php echo $_GET['ext']; ?>" >
    <input type="hidden" id="modGeom" value="<?php echo $_GET['mod']; ?>" >
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery-ui-1.14.min.js"></script>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
    <script src="script/funzioni.js"></script>
    <script src="script/varGeom.js"></script>
    <script src="script/mappaAttivita.js"></script>
    <script>
        $(document).ready(function(){
            /********* GEOCODE NOMINATIM   ********/
            $("#resultSearch").hide();
            $("button[name='search']").on("click",function(){var q = $("input[name='go']").val(); cercaIndirizzo(q);});
            $("input[name=inizio]").on("change",function(){
                var initDate = $(this).val();
                $("input[name=fine]").attr("min",initDate);
            });
            $("button[name='salva']").on("click",function(){insert();});
            $("button[name='confermaDel']").on("click",function(){elimina();});
            $("button[name=annulla], button[name=continua]").click(function(){
                $('#formDiv').fadeOut('fast');
                newpoi.refresh({force:true});
                $("#fid").val('');
                $("select[name=tipo]").val('');
                $("input[name=inizio]").val('');
                $("input[name=fine]").val('');
            });
            $("button[name=continua]").click(function(){
                $("#salva").delay(1000).show();
                $("#mod").delay(1000).hide();
            });
            $("button[name=continuaDel]").click(function(){
                $("#deleteGeom").fadeOut(500, function(){
                    $('.preDel').show();
                    $('.postDel').hide();
                });
            });
            $("button[name=chiudi]").click(function(){ window.location.href = "lavoro.php?l="+lavoro });
        });
    </script>
  </body>
</html>
