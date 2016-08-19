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
    <div id="mainWrap">
      <section class="content">
        <header>Archivio Arc-team OpenDataDocuments</header>
        <section class="toolbar">
            <div class="listTool">
                <?php if(isset($_SESSION["id"])){ ?><a href="odForm.php" title="inserisci un nuovo post"><i class="fa fa-plus"></i>nuova risorsa</a><?php } ?>
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
                    $tagQuery="SELECT tag.tag FROM liste.tag, main.tags WHERE tags.tag = tag.id AND tags.tab = 4 AND tags.rec = ".$doc['id'].";";
                    $tagRes= pg_query($connection, $tagQuery);
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
                    echo        "<article class='inline'>";
                    echo            "<h1>Abstract</h1>";
                    echo            "<p>".$doc['descrizione']."</p>";
                    echo        "</article>";
                    echo        "<article class='inline'>";
                    echo            "<h1>Metadati</h1>";
                    echo            "<ul>";
                    echo                "<li><span class='label'>Autori: </span><span class='dati'>".$doc['autori']."</span></li>";
                    echo                "<li><span class='label'>Pubblicato da: </span><span class='dati'>".$doc['utente']."</span></li>";
                    echo                "<li><span class='label'>Pubblicato il: </span><span class='dati'>".$data."</span></li>";
                    echo            "</ul>";
                    echo        "</article>";
                    echo        "<article class='inline'>";
                    echo            "<h1>Risorse disponibili</h1>";
                    echo            "<ul>";
                    while($file = pg_fetch_array($fileRes)){echo "<li><a href='".$file['link']."' target='_blank' title ='[link esterno] Apri o scarica elemento'>".$cat."</a> (tipo file: <strong>".$file['tipo']."</strong>, licenza: <a href='".$file['url']."' target='_blank' title='[link esterno] ".$file['licenza']."'>".$file['sigla']."</a>)</li>";}
                    echo            "</ul>";
                    echo            "<h1>Tag</h1>";
                    while($tag = pg_fetch_array($tagRes)){echo "<span class='tag'>".$tags['tag']."</span>";}
                    echo        "</article>";
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
            $('.clear-filter').click(function(e) { $('.footable').trigger('footable_clear_filter'); });
            $("#filtro").keyup(function(){ filterTable(filtro,'dati'); });
        });
    </script>
  </body>
</html>
