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
      <link href="css/index.css" rel="stylesheet" media="screen" />
  </head>
  <body onload="init()">
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section id="mappa">
        <div id ="mappaTool">
          <header class="cursor"><i class="fa fa-arrow-down"></i> Naviga tra i nostri lavori!</header>
          <article id="switchLayer"></article>
        </div>
      </section>
      <section id="main" class="inline">
        <script src="http://feeds.feedburner.com/blogspot/YduRN?format=sigpro" type="text/javascript" ></script>
        <noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/blogspot/YduRN"></a><br/>Powered by FeedBurner</p> </noscript>
      </section>
      <aside id="mainAside" class="inline">

      </aside>
    </div>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="script/funzioni.js"></script>
    <script src="script/mappaIndex.js"></script>
    <script>
    $(document).ready(function(){
      $("a#home").addClass('actHome');
      $(".headline a").addClass('transition');
      var hMap = $( window ).height()-140+"px";
      $("#mappa").css("height",hMap);
      $('#mappa header').click(function(){
          var arrow = $('#mappa header i');
          var toggle = $('#switchLayer');
          toggle.toggleClass('aperto');
          if(toggle.hasClass('aperto')){
              toggle.slideDown('fast');
              rotate(arrow,180);
          }else{
              toggle.slideUp('fast');
              rotate(arrow,360);
          }
      });
    });
    </script>
  </body>
</html>
