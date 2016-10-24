<?php
session_start();
require("inc/db.php");
require("class/cut.php");
$a ="select l.id, l.anno, c.categoria, l.nome, l.descrizione from main.lavoro l, liste.cat c where l.tipo = c.id order by l.anno desc, l.nome asc;";
$b = pg_query($connection, $a);
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
        <header>Archivio lavori</header>
        <section class="toolbar">
            <div class="listTool">
                <?php if(isset($_SESSION["id"])){ ?>
                <a href="lavoroIns.php" title="inserisci un nuovo lavoro"><i class="fa fa-plus"></i>nuovo lavoro</a>
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
        <table class="tableList footable toggle-arrow-tiny" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
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
        });
    </script>
  </body>
</html>
