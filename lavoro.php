<?php
session_start();
require("inc/db.php");
$a ="select l.id, l.anno, c.categoria, l.nome, l.descrizione from main.lavoro l, liste.cat c where l.tipo = c.id and l.id = ".$_GET['l'].";";
$b = pg_query($connection, $a);
$c = pg_fetch_array($b);
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/lavoro.css" rel="stylesheet" media="screen" />
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
        <section class="form ckform">
            <header><?php echo $c['nome']; ?></header>
            <section class="toolbar">
                <div class="listTool">
                    <a href="lavori.php" title="Torna all'archivio lavori">archivio lavori</a>
                    <a href="#" class='prevent' title="Modifica dati principali lavoro">modifica dati</a>
                    <a href="#" class='prevent' title="Aggiungi attività">aggiungi attività</a>
                    <a href="#" class='prevent' title="Aggiungi fattura">aggiungi fattura</a>
                    <a href="#" class='prevent' title="Modifica geometrie">modifica geometrie</a>
                </div>
            </section>
            <div class="inline col sx">
                <section class="sezione inline main">
                    <header class="act"><span><i class="fa fa-angle-down"></i> Dati principali</span></header>
                    <div class="toggle">
                        <div><span>Inizio progetto: </span><span><?php echo $c['anno']; ?></span></div>
                        <div><span>Categoria: </span><span><?php echo $c['categoria']; ?></span></div>
                        <div><span>Ore attività: </span><span><?php echo $c['categoria']; ?></span></div>
                        <div><span>Descrizione: </span><span class="descr"><?php echo $c['descrizione']; ?></span></div>
                    </div>
                </section>
                <section class="sezione inline attivita">
                    <header><span><i class="fa fa-angle-right"></i> Attività</span></header>
                    <div class="toggle hide">
                    </div>
                </section>
                <section class="sezione inline fatture">
                    <header><span><i class="fa fa-angle-right"></i> Fatturazione</span></header>
                    <div class="toggle hide">
                    </div>
                </section>
            </div>
            <div class="inline col">
                <div id="mappa"></div>
            </div>
        </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery-ui-1.14.min.js"></script>
    <!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="lib/tag/tagmanager.js"></script>-->
    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            $(".sezione span:last-child").css({"width":$(".sezione").width()-140});
            var h = $(".sx").outerHeight();
            $("#mappa").css({"height":h});
            $(".sezione header").on("click", function(){
                if(!$(this).hasClass('act')){
                    $(this).addClass('act');
                    $(".toggle").slideUp();
                    $(this).parent().siblings().find("header > span > i").removeClass('fa-angle-right').addClass('fa-angle-down');
                    $(this).parent().siblings().find("header").removeClass('act');
                    $(this).next('.toggle').slideDown();
                    $(this).children('span').children('i').toggleClass('fa-angle-right fa-angle-down');
                }
            });
        });
    </script>
  </body>
</html>
