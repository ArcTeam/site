<?php
session_start();
require("inc/db.php");
$extq="select st_extent(st_transform(geom, 3857)) as ext from main.lavoro_metadati;";
$extres = pg_query($connection, $extq);
$ext = pg_fetch_array($extres);
$coo = explode(",",str_replace(" ", ",", substr($ext['ext'],4,-1)));
$extent = str_replace(" ", ",", substr($ext['ext'],4,-1));
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
          <header class="cursor main"><i class="fa fa-arrow-down"></i> Naviga tra i nostri lavori!</header>
          <article id="switchLayer">
            <ul>
            <?php
              $layerq = "select * from liste.tipo_lavoro where id in(select tipo_lavoro from main.lavoro_metadati);";
              $layerqres = pg_query($connection,$layerq);
              while($layer = pg_fetch_array($layerqres)){
                echo "<li><label for='layer".$layer['id']."' class='layerAct'><input type='checkbox' name='layer' value='".$layer['id']."' id='layer".$layer['id']."' checked ><i class='fa fa-check-square-o'></i> ".$layer['definizione']."</label></li>";
              }
            ?>
            </ul>
          </article>
          <article id="infoJob"></article>
        </div>
      </section>
      <section id="servizi">
        <article class="inline">
          <header>Scopri i nostri servizi</header>
          <nav id="serviziContent">
              <?php
                $l = "select * from liste.tipo_lavoro order by definizione asc;";
                $le = pg_query($connection,$l);
                while($s = pg_fetch_array($le)){
                  echo "<a href='#' class='prevent serviziList' data-servizio='".$s['id']."' title='clicca per maggiori informazioni' >".pg_escape_string($s['definizione'])."</a>";
                }
              ?>
          </nav>
        </article>
        <article id="serviziInfo" class="inline">
            <div id="textInfoWrap">
                <span id="text"></span>
                <span id="txtAttribution"></span>
                <span id="imgAttribution"></span>
            </div>
        </article>
      </section>
      <section id="main" class="inline">
        <article id="ator" class="inline">
          <script src="http://feeds.feedburner.com/blogspot/YduRN?format=sigpro" type="text/javascript" ></script><noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/blogspot/YduRN"></a><br/>Powered by FeedBurner</p> </noscript>
        </article>
        <article id="post" class="inline">
          <script src="http://feeds.feedburner.com/blogspot/YduRN?format=sigpro" type="text/javascript" ></script><noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/blogspot/YduRN"></a><br/>Powered by FeedBurner</p> </noscript>
        </article>
      </section>
      <aside id="mainAside" class="inline">
        <article id="tweetBeppe">
          <a class="twitter-timeline" href="https://twitter.com/beppenapo" data-widget-id="301622279031365632">Tweet di @beppenapo</a>
          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </article>
        <article id="tweetLuca">
          <a class="twitter-timeline" href="https://twitter.com/ArcTeamATOR" data-widget-id="301623125618073600">Tweet di @ArcTeamATOR</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </article>
        <article id="faceb" class="inline"><ul></ul></article>

      </aside>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <input type="hidden" id="extent" value="<?php echo $extent; ?>">
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="script/funzioni.js"></script>
    <script src="script/mappaIndexDev.js"></script>
    <script src="script/servizi.js"></script>
    <script>
    $(document).ready(function(){
      $("a#home").addClass('actHome');
      $(".headline a").addClass('transition');
      var hMap = $( window ).height()-75+"px";
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

      var l = 0;
      var tot = $(".serviziList").length;
      function nextBackground() {
          l= ++l % tot;
          $(".serviziList:last-child").removeClass('serviziAct');
          $(".serviziList").eq(l).addClass('serviziAct').prev().removeClass('serviziAct');
          $("#serviziInfo").css({'background': "url('"+backgrounds[l].img+"')", "background-repeat":"no-repeat","background-position":"center center", "background-attachment": "fixed","-webkit-background-size":"cover","-moz-background-size":"cover", "-o-background-size":"cover", "background-size":"cover"});
          $("#text").html(backgrounds[l].txt);
          $("#txtAttribution").html(backgrounds[l].txtAttribution);
          $("#imgAttribution").html(backgrounds[l].imgAttribution);
          timer = setTimeout(nextBackground, 5000);//parte il loop
      }
      $(".serviziList:first-child").addClass('serviziAct');
      $("#serviziInfo").css({'background': "url('"+backgrounds[0].img+"')", "background-repeat":"no-repeat","background-position":"center center", "background-attachment": "fixed","-webkit-background-size":"cover","-moz-background-size":"cover", "-o-background-size":"cover", "background-size":"cover"});
      $("#text").html(backgrounds[0].txt);
      $("#txtAttribution").html(backgrounds[0].txtAttribution);
      $("#imgAttribution").html(backgrounds[0].imgAttribution);
      var timer = setTimeout(nextBackground, 5000);//fa scattare il primo cambio background
      $("#serviziContent").on({
        mouseenter: function(){clearTimeout(timer);},
        mouseleave: function(){
          if(!$(".serviziList").hasClass('stopTimeout')){setTimeout(nextBackground, 5000);}
        }
      });
      $(".serviziList").on("click", function(){
        $(".serviziList").removeClass('serviziAct');
        $(this).addClass('serviziAct stopTimeout');
        var i = $(this).index('.serviziList');
        $("#serviziInfo").css({'background': "url('"+backgrounds[i].img+"')", "background-repeat":"no-repeat","background-position":"center center", "background-attachment": "fixed","-webkit-background-size":"cover","-moz-background-size":"cover", "-o-background-size":"cover", "background-size":"cover"});
        $("#text").html(backgrounds[i].txt);
        $("#txtAttribution").html(backgrounds[i].txtAttribution);
        $("#imgAttribution").html(backgrounds[i].imgAttribution);
        clearTimeout(timer);
      });
    });
    </script>
  </body>
</html>
