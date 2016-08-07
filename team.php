<?php
session_start();
require("inc/db.php");

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
            </section>
            <section id="collaboratori">
                <header class="sectionMain"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Collaboratori</header>
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
