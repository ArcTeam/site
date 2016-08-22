<?php
session_start();
require("inc/db.php");
$odQuery = "SELECT odd.id, odd.titolo, odd.categoria, odd.autori, odd.descrizione, log.data, rubrica.utente FROM main.log, main.opendata odd, main.usr, main.rubrica WHERE log.utente = usr.id AND odd.id = log.record AND usr.rubrica = rubrica.id AND log.tabella = 'opendata' AND log.operazione = 'I' ORDER BY log.data DESC; ";
$odRes = pg_query($connection, $odQuery);
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/odd.css" rel="stylesheet" media="screen" >
      <link href="lib/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" >
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <?php require("inc/dialog.php"); ?>
    <div id="mainWrap">
      <section class="content">
        <header>Archivio OpenDataDocuments</header>
        <section id="presentazione">
            <span class='inline'><i class="fa fa-creative-commons fa-5x"></i></span><span class='inline'>La libera circolazione delle idee è alla base del nostro lavoro, per questo abbiamo dedicato una sezione del nostro sito alla condivisione di pubblicazioni, articoli scientifici e presentazioni che la nostra ditta ha prodotto negli anni. Alcuni articoli sono su Academia.edu, altri su Research Gate. Per ogni risorsa è specificata la categoria di appartenenza (presentazione, articolo o poster), il tipo di file (pdf, odp, ppt ecc.) e la licenza applicata. Alcune presentazioni sono in formato html, quindi visibili direttamente da browser, e sono state create utilizzando i framework javascript <a href="https://github.com/tantaman/Strut" target="_blank" title="pagina github di Strut"><i class="fa fa-github" aria-hidden="true"></i> strut</a> e <a href="https://github.com/hakimel/reveal.js" target="_blank" title="pagina github di Reveals"><i class="fa fa-github" aria-hidden="true"></i>  reveals.js</a>, per una corretta visualizzazione è necessario che il browser sia abilitato a gestire i contenuti javascript.</span>
        </section>
        <section class="toolbar">
            <div class="listTool">
                <?php if(isset($_SESSION["id"])){ ?><a href="oddForm.php" title="inserisci un nuovo post"><i class="fa fa-plus"></i>nuova risorsa</a><?php } ?>
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
        <table id="dati" class="tableList footable toggle-arrow-tiny" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
            <caption></caption>
            <thead>
                <tr>
                    <th data-sort-ignore="true"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($doc = pg_fetch_array($odRes)){
                    $fileQuery="SELECT odf.tipo, odf.link, licenze.licenza, licenze.sigla, licenze.url FROM main.opendata odd, main.opendatafile odf, liste.licenze WHERE odf.opendata = odd.id AND odf.licenza = licenze.id AND odf.opendata = ".$doc['id'].";";
                    $fileRes= pg_query($connection, $fileQuery);
                    $tagQuery="SELECT tags FROM main.tags WHERE tags.tab = 4 AND tags.rec = ".$doc['id'].";";
                    $tagRes= pg_query($connection, $tagQuery);
                    $tags = pg_fetch_array($tagRes);
                    $tagListArr = explode(",",$tags['tags']);
                    asort($tagListArr);
                    $data = split(" ",$doc['data']);
                    $data = $data[0];
                    switch($doc['categoria']){
                        case 'poster': $cat = 'poster'; break;
                        case 'html': $cat = 'presentazione'; break;
                        case 'talk': $cat = 'presentazione'; break;
                        case 'paper': $cat = 'articolo'; break;
                    }
                    echo "<tr>";
                    echo    "<td>";
                    echo        "<header>".$doc['titolo']."</header>";
                    echo        "<article class='inline abstract'>";
                    echo            "<h1>Abstract</h1>";
                    echo            "<p>".$doc['descrizione']."</p>";
                    echo        "</article>";
                    echo        "<article class='inline meta'>";
                    echo "<div class='metadati'>";
                    echo            "<h1>Metadati</h1>";
                    echo            "<ul>";
                    echo                "<li><span class='label'>Autori: </span><span class='dati'>".$doc['autori']."</span></li>";
                    echo                "<li><span class='label'>Pubblicato da: </span><span class='dati'>".$doc['utente']."</span></li>";
                    echo                "<li><span class='label'>Pubblicato il: </span><span class='dati'>".$data."</span></li>";
                    echo            "</ul>";
                    echo "</div>";
                    echo "<div class='file'>";
                    echo            "<h1>Risorse disponibili</h1>";
                    echo            "<ul>";
                    while($file = pg_fetch_array($fileRes)){echo "<li><a href='".$file['link']."' target='_blank' title ='[link esterno] Apri o scarica elemento'>".$cat."</a> (tipo file: <strong>".$file['tipo']."</strong>, licenza: <a href='".$file['url']."' target='_blank' title='[link esterno] ".$file['licenza']."'>".$file['sigla']."</a>)</li>";}
                    echo            "</ul>";
                    echo "</div>";
                    echo "<div class='tag'>";
                    echo            "<h1>Tag</h1>";
                    echo            "<div class='tagWrap'>";
                    foreach ($tagListArr as $tag) { echo "<span class='tag'>".$tag."</span>"; }
                    echo            "</div>";
                    echo "</div>";
                    echo        "</article>";
                    echo        "<div class='oddToolbar'>";
                    echo            "<a href='oddForm.php?odd=".$doc['id']."' class='button modOdd' title='modifica il documento ".$doc['titolo']."'>modifica</a>";
                    echo            "<a href='#' class='button prevent delOdd' title='Elimina il documento ".$doc['titolo']."' data-id='".$doc['id']."'>elimina</a>";
                    echo        "</div>";
                    echo    "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot class="hide-if-no-paging">
             <tr>
              <td>
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
    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            $('.footable').footable();
            $('#change-page-size').change(function (e) {
				e.preventDefault();
				var pageSize = $(this).val();
				$('.footable').data('page-size', pageSize);
				$('.footable').trigger('footable_initialized');
			});
            var tot = $(".footable tr").length;
            $('.clear-filter').click(function(e) { $('.footable').trigger('footable_clear_filter'); });
            $("#filtro").keyup(function(){
                filterTable(filtro,'dati');
                var rfiltered = $('.footable tbody tr:not(.footable-filtered)').length;
                var test = "tot: "+parseInt(tot)+" / vis: "+parseInt(rfiltered);
                console.log(test);
            });
            $("a.delOdd").on("click", function(){
                var id = $(this).data('id');
                var header = $(this).attr('title');
                var testo = "Hai scelto di <strong>eliminare</strong> un documento dal database.</br>Se confermi l'eliminazione tutti i dati saranno definitivamente eliminati e non sarà più possibile recuperarli.";
                $("#dialogContent header").text(header);
                $("#dialogContent article").html(testo);
                $("#dialogWrap").fadeIn('fast');
                $("button[name='conferma']").on("click", function(){ usrAction(id, 'oddDel.php'); });
            });
        });
    </script>
  </body>
</html>
