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
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.5&appId=377540852406533";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section id="mappa">
        <div id ="mappaTool">
          <header class="cursor"><i class="fa fa-arrow-down"></i> Naviga tra i nostri lavori!</header>
          <article id="switchLayer"></article>
        </div>
      </section>
      <section id="main" class="inline">
        <article id="ator" class="inline">
          <script src="http://feeds.feedburner.com/blogspot/YduRN?format=sigpro" type="text/javascript" ></script><noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/blogspot/YduRN"></a><br/>Powered by FeedBurner</p> </noscript>
        </article>
        <article class="inline">
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
      /*var obj = 'aside#mainAside';
      var top = $(obj).offset().top - 80;
      console.log(top);
      $(window).scroll(function (event) {
        var y = $(this).scrollTop();
        if (y >= top) { $(obj).addClass('fixed'); } else { $(obj).removeClass('fixed'); }
      });*/

      $.getJSON('https://graph.facebook.com/v2.2/258977573543/feed?access_token=CAACEdEose0cBAMmFByu5F6oX8pgZAZCDILsV6GD64gJ8v8qNJBOjOAE5TlFzkZAtCHCmre6ZA31nKNiUPUfbzqfU7l4MEAfflNCxL3PT1jFJgfSZCdPYj9qOQRi61sMzme38SnQZCjiVTJKBp6UsXH7kkFCuZBQn9b17WuXxVqb22SAlCbEUDTZBX1U22znPFwizXSUliZAWqKQZDZD', function(result) {
       console.log(result);
        //var t = result['paging'];
        $.each(result, function(key, val) {
          $.each(val, function(key, res) {
            console.log(res.description);
            $("#faceb ul").append("<li>"+res.id+" "+res.message+"</li>");
          });
        });
      });


    });
    </script>
  </body>
</html>
