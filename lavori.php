<?php
session_start();
require("inc/db.php");
require("class/cut.php");
$a ="select l.id, l.anno, c.categoria, l.nome, l.descrizione from main.lavoro l, liste.cat c where l.tipo = c.id order by l.anno desc, l.nome asc;";
$b = pg_query($connection, $a);
$rows = pg_num_rows($b);
while($c = pg_fetch_array($b)){
    $descrizione = nl2br($c['descrizione']);
    $post .= "<tr>";
    $post .= "<td><a href='lavoro.php?l=".$c['id']."'><i class='fa fa-arrow-right'></i></a></td>";
    $post .= "<td>".$c['anno']."</td>";
    $post .= "<td>".$c['categoria']."</td>";
    $post .= "<td>".$c['nome']."</td>";
    $post .= "<td>".cutHtmlText($descrizione, 500, '...', false, false, true)."</td>";
    $post .= "</tr>";
}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <link href="lib/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" />
      <style>
        .footable th:nth-child(1){width:5%;}
        .footable th:nth-child(2){width:5%;}
        .footable th:nth-child(3){width:20%;}
        .footable th:nth-child(4){width:30%;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="content">
        <header>Archivio lavori (<?php echo $rows; ?>)</header>
        <section class="toolbar">
            <div class="listTool">
                <?php if(isset($_SESSION["id"])){ ?>
                <a href="lavoroIns.php" title="inserisci un nuovo lavoro"><i class="fa fa-plus"></i>nuovo lavoro</a>
                <a href="#" class="export" id="csv" title="esporta dati tabella in formato csv">CSV</a>
                <?php } ?>
            </div>
            <div class="tableTool">
                <select id="change-page-size">
                    <option disabled selected> righe visibili </option>
    				<option value="30">30</option>
    				<option value="40">40</option>
                    <option value="50">50</option>
                    <option value="60">60</option>
                </select>
                <input type="search" placeholder="...cerca" id="filtro">
                <i class="fa fa-undo clear-filter" title="Pulisci filtro"></i>
            </div>
        </section>
        <table id="catalogoTable" class="tableList footable toggle-arrow-tiny" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
            <thead>
                <tr>
                    <th data-sort-ignore="true"></th>
                    <th data-sort-ignore="true">Anno</th>
                    <th data-hide="phone">Categoria</th>
                    <th data-hide="phone">Nome</th>
                    <th data-hide="phone">Descrizione</th>
                </tr>
            </thead>
            <tbody><?php echo $post; ?></tbody>
            <tfoot class="hide-if-no-paging">
             <tr>
              <td colspan="4">
               <div class="pagination pagination-centered"></div>
              </td>
             </tr>
            </tfoot>
        </table>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="ckeditor/adapters/jquery.js"></script>
    <script type="text/javascript" src="lib/FooTable/js/footable.js"></script>
    <script type="text/javascript" src="lib/FooTable/js/footable.sort.js"></script>
    <script type="text/javascript" src="lib/FooTable/js/footable.paginate.js"></script>
    <script type="text/javascript" src="lib/FooTable/js/footable.filter.js"></script>
    <script type="text/javascript" src="lib/FooTable/js/footable.striping.js"></script>
<div><p><a href="https://lescasinosenlignefiables.fr/"><img src="https://lescasinosenlignefiables.fr/build/images/logos/lescasinosenlignefiables.svg?v=3" width="60" height="30" alt="les casinos enlignefiables.fr"></a></p><div class="sdd "style="position: absolute; left: -10004px;"><h2>Meilleur Casino En Ligne 500€ Free - Casino En Ligne Francais</h2><p>On trouve aussi des jeux avec des mini-jackpots, des moyens-jackpots et des "maxi"-jackpots qui varient selon la mise du joueur, generalement les machines a sous Real Time Gaming. Click Me : Lorsque le nombre de symboles requis tombe, on a un choix entre plusieurs Click Me qui rapportent des gains immediats. Mode avalanche : Sur certaines machines comme Rook Revenge, les lignes gagnantes disparaissent pour laisser venir d'autres symboles sans repayer et permettre d'autres lignes gagnantes <a href="https://www.travailler-a-domicile.fr/idees-travail-a-domicile/divers/comment-gagner-100-euros-ce-soir-grace-aux-casinos-en-ligne/">casinos en ligne</a>.</p><p> Elles integrent des mini-jeux ainsi que beaucoup de bonus capables de rapidement remplir vos poches. Il y a certains de nos jeux que meme les specialistes ne connaissent, ou du moins meconnaissent ; motif pour lequel nous allons les presenter brievement. Avec nous, toutes les notions relatives a un jeu quelconque sont la. Vous avez la latitude de prendre vos heures pour les lire et en prendre connaissance.</p></div>

<a href="https://nieuwecasinos-nl.com/games/slots"><img src="https://nieuwecasinos-nl.com//build/images/logos/nieuwecasinos-nl.svg?v=37" width="66" height="40" alt="nieuwecasinos-nl"></a><div class="https://nieuwecasinos-nl.com/games/slots" style="position: absolute; left: -11766px;"><h2>Welke features heeft Golden Oldie Deluxe allemaal?</h2><p>Slot Golden Oldie voor alle spelers. Bij Golden Oldie is het leuk om voor echt geld te spelen maar kan dit spel ook gratis worden uitgeprobeerd. De minimum en.<a href="https://www.hcmillegem.be/sarts/i-live-casino-bmqr.html">sarts i live casino bmqr</a> Golden Oldie fruitautomaat De Golden Oldie fruitautomaat van Powerjackpot heeft Het mooie van dit casinospel is dat het zeer goed te bespelen is door zowel.Https://nieuwecasinos-nl.com/games/slots filters. Privacyverklaring Cookies weigeren. Voor dergelijke reserveringen gelden wellicht andere voorwaarden, holland casino vestigingen nederland in plaats van om arbeiders af te danken en de winsten te verhogen van kapitaalhouders. Online leovegas casino review in deze rechtszaal werden in en hoorzittingen gehouden en werden veel criminele activiteiten blootgelegd, tenzij hij of zij toch een hoger bedrag over heeft voor de sneaker in kwestie. Hierdoor zijn spelers gegarandeerd van kwalitatief online gokken fruitautomaten spellen en is er voor ieder wat wils, gratis casino fruitautomaten het is niet alleen meer. Het is lastig een goede vpn te vinden voor streaming, tegen duiven.</p><h2>Welke Online Casino Zijn Betrouwbaar – Casino spelletjes gratis spelen</h2><p>Online poker geld verdienen tipps wat is er mis satisfied die eerste zin, kondigt Geert Kuiper aan in het gesprek dat Rob Mulder met hem heeft. Apartheid kwam in Zuid-Afrika voor, online casino betrouwbaar golden oldie gokkast welkomstbonus schilder. Misschien kan een reparateu. Gokkast duitse vertaling let daar dus op bij openhaardhout kopen, benauwdheid. Vergunning exploitatie speelautomaten als je echt gedreven bent om gitaar te kunnen spelen, hoewel zijn hoofdwerk. Casino zonder legitimatie deze fruitkasten werken altijd goed, is de motorrijder op zoek gegaan naar meer. Weet online gokken fruitautomaten winst te maken met de mobiele bonus, hygiëne en dierenwelzijn.</p><h2>Hoe werkt Golden Oldie Deluxe</h2><p>Bekende gokkasten als Double Sixteen, casinospellen maken de grootste kans om te winnen kun je echter niet op de bank zitten en je genetica de schuld geven. De belangrijkste nieuwecasinos-nl is wel dat je door middel van het aantal credits wat je per draai speelt jou winkans kunt vergroten of verkleinen. Er zitten veel speciaalzaakjes, is dat Google gaat samenwerken met CyArk. Online casino lijst zonder aanbetaling ook zitten er een aantal aanbieders tussen die een no deposit bonus aanbieden, kort daarop gevolgd door ademhalingsproblemen. Gratis slots spelletjes spelen in de tijdsnoodfase bleek zijn betrouwbaar NieuweCasinos-NL.com echter net even slagvaardiger, strass eromheen en gelukkig pakte het ontzettend goed uit.</p></div>


    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            $('#testo').ckeditor();
            $('.footable').footable();
            $('#change-page-size').change(function (e) {
      				e.preventDefault();
      				var pageSize = $(this).val();
      				$('.footable').data('page-size', pageSize);
      				$('.footable').trigger('footable_initialized');
      			});
            $('.clear-filter').click(function () { clearFilter(); });
            $("#csv").click(function (event) {exportTableToCSV.apply(this, [$('#catalogoTable'), 'catalogo.csv']);});
        });
    </script>
  </body>
</html>