<?php
session_start();
require("inc/db.php");
$colori = array("#FBBC05","#82C914","#1da1f2","#9A9A9A","#FF7B59");
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require("inc/meta.php"); ?>
        <link href="css/team.css" rel="stylesheet" media="screen" />
        <link href="lib/flexslider/flexslider.css" rel="stylesheet" media="screen" />
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section id="servizi">
                <header><i class="fa fa-sitemap red" aria-hidden="true"></i> <span class="red">A</span>rc-<span class="red">T</span>eam, chi siamo e cosa facciamo</header>
                <article id="serviziInfo"><?php require("inc/servizi.php"); ?></article>
            </section>
            <section id="ditta">
                <header class="sectionMain"><i class="fa fa-users" aria-hidden="true"></i> La ditta</header>
                <article class="section">
                    <header>Breve presentazione</header>
                    <p>Arc-Team s.r.l. è una società fondata nel 2005 che fornisce diversi servizi nel settore dei beni culturali:</p>
                    <ul>
                        <li>scavi archeologici</li>
                        <li>documentazione informatizzata di strutture architettoniche (bi- e tridimensionale)</li>
                        <li>ricostruzioni tridimensionali</li>
                        <li>stampa 3d</li>
                        <li>impianti museali (realtà virtuale e realtà aumentata)</li>
                        <li>sviluppo di sistemi informatici (WebGIS, Database, Gestionali, ...)</li>
                        <li>ricerca d'archivio e studio del patrimonio storico, archeologico e culturale in genere</li>
                        <li>valutazioni impatto archeologico</li>
                        <li>studi antropologici, paleopatologici, ergonomici e paleonutrizionali</li>
                        <li>archeologia subacquea</li>
                        <li>didattica universitaria</li>
                        <li>riprese aeree mediante droni volanti (UAV)</li>
                    </ul>
                    <header>Info utili</header>
                    <ul>
                        <li>SEDE LEGALE: Piazza Navarrino 13, 38023 Cles (TN)</li>
                        <li>P.IVA/C.Fisc: IT-01941600221</li>
                        <li>PEC: arc-team[~at~]pec.it</li>
                        <li>EMAIL: info[~at~]arc-team.com</li>
                        <li>CURRICULUM: <a href="doc/2015_05_curriculum_ditta_ita.pdf" target="_blank">scarica pdf</a> aggiornato al 2015</li>
                        <li>Altre info le puoi trovare <a href="#foo">in calce al sito</a></li>
                    </ul>
                </article>
            </section>
            <section id="soci">
                <header class="sectionMain"><i class="fa fa-user" aria-hidden="true"></i> Soci</header>
                <article class="section usr">
                    <?php
                        $sociquery = "SELECT u.id as id_usr, r.id as rubrica, r.utente, r.email, r.cell, u.img FROM main.rubrica r, main.usr u WHERE u.rubrica = r.id AND r.tipo = 2 order by utente asc;";
                        $sociexec = pg_query($connection, $sociquery);
                        while ($socio = pg_fetch_array($sociexec)){
                            $id = $socio['id_usr'];
                            $socialQuery = "SELECT social.ico, social.nome, usr_social.link FROM main.usr, main.usr_social, liste.social WHERE usr_social.usr = usr.id AND usr_social.social = social.id AND usr.id = ".$id." order by nome asc;";
                            $socialExec = pg_query($connection, $socialQuery);
                            $tagQuery = "SELECT tags.tags FROM main.usr, main.tags WHERE tags.rec = usr.id AND tags.tab = 2 AND usr.id = ".$id;
                            $tagExec = pg_query($connection, $tagQuery);
                            $tagList = pg_fetch_array($tagExec);
                            $tagListArr = explode(",",$tagList['tags']);
                            asort($tagListArr);
                            echo "<div class='socioWrap'>";
                            echo    "<div class='avatar' style='background-image:url(img/usr/".$socio['img'].")'></div>";
                            echo    "<div class='socioDatiContent'>";
                            echo        "<h1 style='background:";
                            shuffle($colori);
                            echo $colori[0];
                            echo        "'>";
                            echo $socio['utente']."</h1>";
                            echo        "<div class='dati'>";
                            echo            "<ul>";
                            echo                "<li><span class='ico'><i class='fa fa-envelope' aria-hidden='true'></i></span><span class='dato'>".$socio['email']."</span></li>";
                            echo                "<li><span class='ico'><i class='fa fa-phone' aria-hidden='true'></i></span><span class='dato'>".$socio['cell']."</span></li>";
                            while ($social = pg_fetch_array($socialExec)){ echo "<li><span class='ico'><i class='fa ".$social['ico']."' aria-hidden='true'></i></span><span class='dato'><a href='".$social['link']."' target='_blank' title='[link esterno] ".$social['nome']."' class='genericLink transition'>".$social['nome']."</a></span></li>"; }
                            echo            "</ul>";
                            echo        "</div>";
                            echo        "<div class='dati'>";
                            echo            "<h2>Skills</h2>";
                            echo            "<div class='tagWrap'>";
                            foreach ($tagListArr as $tag) { echo "<span class='tag'>".$tag."</span>"; }
                            echo            "</div>";
                            echo        "</div>";
                            echo    "</div>";
                            echo "</div>";
                        }
                    ?>
                </article>
            </section>
            <section id="collaboratori">
                <header class="sectionMain"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Collaboratori</header>
                <article class="section usr">
                    <?php
                        $sociquery = "SELECT u.id as id_usr, r.id as rubrica, r.utente, r.email, r.cell, u.img FROM main.rubrica r, main.usr u WHERE u.rubrica = r.id AND r.tipo = 3 order by utente asc;";
                        $sociexec = pg_query($connection, $sociquery);
                        while ($socio = pg_fetch_array($sociexec)){
                            $id = $socio['id_usr'];
                            $socialQuery = "SELECT social.ico, social.nome, usr_social.link FROM main.usr, main.usr_social, liste.social WHERE usr_social.usr = usr.id AND usr_social.social = social.id AND usr.id = ".$id." order by nome asc;";
                            $socialExec = pg_query($connection, $socialQuery);
                            $tagQuery = "SELECT tags.tags FROM main.usr, main.tags WHERE tags.rec = usr.id AND tags.tab = 2 AND usr.id = ".$id;
                            $tagExec = pg_query($connection, $tagQuery);
                            $tagList = pg_fetch_array($tagExec);
                            $tagListArr = explode(",",$tagList['tags']);
                            asort($tagListArr);
                            echo "<div class='socioWrap'>";
                            echo    "<div class='avatar' style='background-image:url(img/usr/".$socio['img'].")'></div>";
                            echo    "<div class='socioDatiContent'>";
                            echo        "<h1 style='background:";
                            shuffle($colori);
                            echo $colori[0];
                            echo        "'>";
                            echo $socio['utente']."</h1>";
                            echo        "<div class='dati'>";
                            echo            "<ul>";
                            echo                "<li><span class='ico'><i class='fa fa-envelope' aria-hidden='true'></i></span><span class='dato'>".$socio['email']."</span></li>";
                            echo                "<li><span class='ico'><i class='fa fa-phone' aria-hidden='true'></i></span><span class='dato'>".$socio['cell']."</span></li>";
                            while ($social = pg_fetch_array($socialExec)){ echo "<li><span class='ico'><i class='fa ".$social['ico']."' aria-hidden='true'></i></span><span class='dato'><a href='".$social['link']."' target='_blank' title='[link esterno] ".$social['nome']."' class='genericLink transition'>".$social['nome']."</a></span></li>"; }
                            echo            "</ul>";
                            echo        "</div>";
                            echo        "<div class='dati'>";
                            echo            "<h2>Skills</h2>";
                            echo            "<div class='tagWrap'>";
                            foreach ($tagListArr as $tag) { echo "<span class='tag'>".$tag."</span>"; }
                            echo            "</div>";
                            echo        "</div>";
                            echo    "</div>";
                            echo "</div>";
                        }
                    ?>
                </article>
            </section>
        </div>
        <footer id="foo"><?php require("inc/footer.php"); ?></footer>
        <script src="lib/jquery-1.12.0.min.js"></script>
        <script src="lib/flexslider/jquery.flexslider.js" charset="utf-8" type="text/javascript"></script>
        <script src="script/funzioni.js"></script>
        <script>
        $(document).ready(function(){
            $("a#team").addClass('actTeam');
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
