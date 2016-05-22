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
$postq = "select * from main.post where pubblica = 1 order by data desc limit 5;";
$postr = pg_query($connection,$postq);
$r = pg_num_rows($postr);
while($post = pg_fetch_array($postr)){
    $data = explode(" ",$post["data"]);
    $p = strip_tags($post['testo']);
    $p = cutHtmlText($p, 140, "...", false, false, false);
    $postList .= "<li>";
    $postList .= "<span class='headline'>";
    $postList .= "<a href='postView.php?p=".$post['id']."' class='transition' title='Visualizza post completo'>".$post['titolo']."</a>";
    $postList .= "</span>";
    $postList .= "<p class='date'>".$data[0]."</p>";
    $postList .= "<div class='postList'>".$p."</div>";
    $postList .= "</li>";
}

//tag cloud
$tag = "select t.id, t.tag,
            case
                when count(tt.tag) < 10 then '.8rem'::text
		        when count(tt.tag) between 10 and 25 then '1rem'::text
		        when count(tt.tag) between 26 and 50 then '1.2rem'::text
                when count(tt.tag) between 51 and 75 then '1.4rem'::text
                when count(tt.tag) between 76 and 100 then '1.6rem'::text
                when count(tt.tag) > 100 then '1.8rem'::text
            end as css
        from liste.tag t, main.tags tt where tt.tag=t.id
        group by t.id, t.tag
        order by tag asc;";
$tagres = pg_query($connection,$tag);
while($t=pg_fetch_array($tagres)){
    $tags .= "<a href='searchTag.php?tag=".$t['id']."' title='cerca contenuti con tag ".$t['tag']."' class='tag transition' style='font-size: ".$t['css']." !important;'>".$t['tag']."</a>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require("inc/meta.php"); ?>
        <link href="css/index.css" rel="stylesheet" media="screen" />
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
                <div class="mainDiv">
                    <article id="ator">
                        <header class="sectionMain">Geek corner <i class="fa fa-code"></i></header>
                        <ul>
                            <li>
                                <div><i class="fa fa-code fa-5x"></i> ATOR (Arc-Team Open Research) è un blog che nasce dall'esigenza di condividere le nostre esperienze in ambito informatico, i problemi riscontrati durante lo sviluppo di un software o i test su applicazioni varie, le soluzioni adottate per risolvere tali problemi. La vasta comunità internazionale che supporta il blog e l'alto livello tecnico degli utenti hanno reso il blog un punto di riferimento per tuto ciò che riguarda l'informatica applicata ai Beni Culturali...ma no solo! Di seguito gli ultimi post inseriti, in fondo il link alla pagina principale del blog.</div>
                            </li>
                        </ul>
                        <script src="http://feeds.feedburner.com/blogspot/YduRN?format=sigpro" type="text/javascript" ></script>
                        <noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds.feedburner.com/blogspot/YduRN"></a><br/>Powered by FeedBurner</p> </noscript>
                    </article>
                    <article id="youtube">
                        <header class="sectionMain">YouTube Channel <i class="fa fa-youtube-play"></i></header>
                        <ul>
                            <li>
                                <div><i class="fa fa-youtube fa-5x"></i> Nel nostro canale YouTube potete vedere i video delle nostre attività principali, delle nostre missioni all'estero, oltre ad alcuni video tutorial sui software "open" usati maggiormente in archeologia. Di seguito gli ultimi video caricati, in fondo a il link al nostro canale ufficiale</div>
                            </li>
                        </ul>
                        <p class="footerArticle"><a href="https://www.youtube.com/channel/UCa-GKE-c3Be1k8g0nqoawxw" target="_blank" title="[link esterno] Visualizza tutti i video del nostro canale"><i class="fa fa-youtube-play"></i> Visualizza tutti i video del nostro canale</a></p>
                    </article>
                </div>
                <div class="mainDiv">
                    <article id="post">
                        <header class="sectionMain">News from Arc-Team <i class="fa fa-th-list"></i></header>
                        <ul>
                            <li>
                                <div><i class="fa fa-th-list fa-5x"></i> Notizie, eventi, nuovi progetti, nuovi cantieri e tante altre informazioni dal mondo Arc-Team. Di seguito gli ultimi post inseriti, in fondo il link per accedere all'archivio completo.</div>
                            </li>
                            <?php echo $postList; ?>
                        </ul>
                        <p class="footerArticle"><a href="post.php" target="_blank" title="Visualizza tutti i post"><i class="fa fa-th-list"></i> Archivio completo</a></p>
                    </article>
                    <article id="tag">
                        <header class="sectionMain">Tag Cloud <i class="fa fa-tags" aria-hidden="true"></i></header>
                        <ul>
                            <li>
                                <div><i class="fa fa-tags fa-5x"></i> L'utilizzo di parole chiave (tag) in contesti come siti web, blog, giornali on-line ecc, permette di creare una sorta di "classificazione" dei contenuti. Lo scopo di tale classificazione è quello di rendere più semplice la fruizione dei contenuti da parte degli utenti, permettendo una rapida ricerca proprio per parole chiave.<br>Di seguito i tags più utilizzati, clicca su un termine per visualizzare tutti i post associati al tag selezionato</div>
                            </li>
                            <li><?php echo $tags; ?></li>
                        </ul>
                        <p class="footerArticle"><a href="#" title="Pagina di ricerca"><i class="fa fa-tags"></i> pagina di ricerca avanzata</a></p>
                    </article>
                </div>
                <div class="mainDiv">
                    <article id="media">
                        <header class="sectionMain">OpenDataDocuments <i class="fa fa-creative-commons"></i></header>
                        <ul>
                            <li>
                                <div><i class="fa fa-creative-commons fa-5x"></i> La libera circolazione delle idee è alla base del nostro lavoro, per questo abbiamo dedicato una sezione del nostro sito alla condivisione di articoli scientifici e presentazioni che la nostra ditta ha prodotto negli anni. Di seguito gli ultimi 5 documenti caricati, in fondo i link all'archivio completo.</div>
                                <div>

                                </div>
                            </li>
                        </ul>
                        <p class="footerArticle"><a href="#" title="Visualizza l'elenco completo dei documenti disponibili per il download"><i class="fa fa-creative-commons"></i> Visualizza l'elenco completo degli open data documents</a></p>
                    </article>
                    <article id="social">
                        <header class="sectionMain">Twitter <i class="fa fa-twitter" aria-hidden="true"></i></header>
                        <ul>
                            <li>
                                <div><i class="fa fa-twitter fa-5x"></i> Social Arc-Team. Di seguito gli ultimi tweet dagli account dei membri Arc-Team</div>
                                <div>

                                </div>
                            </li>
                        </ul>
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
                </div>
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

            $(".fbsubscribelink").prepend('<a href="http://arc-team-open-research.blogspot.it/" title="[link esterno] Vai alla pagina iniziale di ATOR" target="_blank"><i class="fa fa-rss" aria-hidden="true"></i> Visita il nostro blog</a>').addClass("footerArticle");

            var k="AIzaSyCN8A47a_r_cz0gW8cy1JXBQAs_nnOOSw0";
            var c="UCa-GKE-c3Be1k8g0nqoawxw";
            $.getJSON('https://www.googleapis.com/youtube/v3/search?key=AIzaSyCN8A47a_r_cz0gW8cy1JXBQAs_nnOOSw0&channelId=UCa-GKE-c3Be1k8g0nqoawxw&part=snippet,id&order=date&maxResults=5', function(data) {
                var trovati = [];
                $.each(data.items, function(k,v) {
                    var id = v.id.videoId ;
                    var url = "http://www.youtube.com/watch?v="+id;
                    var urlFrame = "https://www.youtube.com/embed/"+id;
                    var title = v.snippet.title;
                    var thumbSource = v.snippet.thumbnails.medium.url;
                    var thumbW = v.snippet.thumbnails.medium.width;
                    var thumbH = v.snippet.thumbnails.medium.height;
                    var iframe = "<iframe width='100%' src='"+urlFrame+"' frameborder='0' allowfullscreen></iframe>";
                    $("#youtube > ul").append("<li><span class='headline'><a href = '"+url+"' title='guarda video su youtube' target='_blank'>"+title+"</a></span>"+iframe+"</li>");
                });
            });
        });
        </script>
    </body>
</html>
