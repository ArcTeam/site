<?php
session_start();
require("inc/db.php");
include("inc/cut.php");
$extq="select st_extent(st_transform(geom, 3857)) as ext from main.lavoro_metadati;";
$extres = pg_query($connection, $extq);
$ext = pg_fetch_array($extres);
$coo = explode(",",str_replace(" ", ",", substr($ext['ext'],4,-1)));
$extent = str_replace(" ", ",", substr($ext['ext'],4,-1));

//post
$postq = "select * from main.post order by data desc limit 5;";
$postr = pg_query($connection,$postq);
$r = pg_num_rows($postr);
while($post = pg_fetch_array($postr)){
    $data = explode(" ",$post["data"]);
    $p = strip_tags($post['testo']);
    $p = cutHtmlText($p, 140, "...", false, false, false);
    $postList .= "<li>";
    $postList .= "<span class='headline'>";
    $postList .= "<a href='post.php?p=".$post['id']."' title='Visualizza post completo'>".$post['titolo']."</a>";
    $postList .= "</span>";
    $postList .= "<p class='date'>".$data[0]."</p>";
    $postList .= "<div>".$p."</div>";
    $postList .= "</li>";
}

?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <link href="lib/flexslider/flexslider.css" rel="stylesheet" media="screen" />
  </head>
  <body onload="init()">
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section id="mappa">
        <div id ="mappaTool">
          <header class="cursor main">Naviga tra i nostri lavori <br/><i class="fa fa-map-marker"></i> <span id="coo"></span></header>
          <article id="legend">
            <ul>
                <li><span>Legenda dei lavori suddivisi per categorie</span></li>
                <li><i class="fa fa-university"></i> Archeologia</li>
                <li><i class="fa fa-video-camera "></i> Documentazione</li>
                <li><i class="fa fa-desktop"></i> Informatica</li>
                <li><i class="fa fa-graduation-cap"></i> Didattica</li>
                <li><i class="fa fa-flask"></i> Laboratorio</li>
                <li><i class="fa fa-comments-o"></i> Convegni</li>
            </ul>
          </article>
          <article id="switchLayer" style="display:none;">
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
        <header><i class="fa fa-wrench"></i> Scopri i nostri servizi</header>
        <article id="serviziInfo"><?php require("inc/servizi.php"); ?></article>
      </section>

      <section id="main">
          <article id="ator" class="mainDiv">
              <header class="sectionMain"><a href="http://arc-team-open-research.blogspot.it/" title="[link esterno] Vai alla pagina iniziale di ATOR" target="_blank"> Arc-Team Open Research <i class="fa fa-link"></i></a></header>
              <script src="http://feeds.feedburner.com/blogspot/YduRN?format=sigpro" type="text/javascript" ></script><noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/blogspot/YduRN"></a><br/>Powered by FeedBurner</p> </noscript>
          </article>
          <article id="post" class="mainDiv">
              <header class="sectionMain"><a href="post.php" title="archivio post">News from Arc-Team <i class="fa fa-th-list"></i></a></header>
              <ul><?php echo $postList; ?></ul>
          </article>
          <article id="social" class="mainDiv">
              <header class="sectionMain">Twitter <i class="fa fa-twitter" aria-hidden="true"></i></header>
              <article id="tweetBeppe">
                  <a class="twitter-timeline" href="https://twitter.com/beppenapo" data-widget-id="301622279031365632">Tweet di @beppenapo</a>
                  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
              </article>
              <article id="tweetLuca">
                  <a class="twitter-timeline" href="https://twitter.com/ArcTeamATOR" data-widget-id="301623125618073600">Tweet di @ArcTeamATOR</a>
                  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
              </article>
              <article id="faceb" class="inline"><ul></ul></article>
          </article>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <input type="hidden" id="extent" value="<?php echo $extent; ?>">
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="lib/flexslider/jquery.flexslider.js" charset="utf-8" type="text/javascript"></script>
    <script src="script/funzioni.js"></script>
    <script src="script/mappaIndex.js"></script>
    <script>
    $(document).ready(function(){
      $("a#home").addClass('actHome');
      $(".headline a").addClass('transition');
      var hMap = $( window ).height()-100+"px";
      $("#mappa").css("height",hMap);
      $('#mappa header').click(function(){
          return false;
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

      $('.flexslider').flexslider({
          animation: 'slide',
          easing: 'easeInQuad',
          controlNav: true,
          directionNav: true,
          pauseOnAction: false,
          pauseOnHover: true,
          slideshowSpeed: 10000,
          animationSpeed: 700,
          before: function() {$('.serviziTxt').hide();},
          after: function() {$('.serviziTxt').fadeIn(2000);}
      });

    });
    </script>
  </body>
</html>
