<?php
session_start();
require("inc/db.php");
$a ="select l.id, l.nome, t.definizione as tipo, regexp_replace(ANYARRAY_SORT(ANYARRAY_UNIQ(array_agg(lm.anno)))::text, '[{}]','','g') as anno from main.lavoro l, main.lavoro_metadati lm, liste.tipo_lavoro t where lm.tipo_lavoro = t.id and lm.lavoro = l.id group by l.id, l.nome, t.definizione order by l.nome asc;";
$b = pg_query($connection, $a);
while($c = pg_fetch_array($b)){
    $anno = explode(",",$c['anno']);
    $primo = $anno[0];
    $n=count($anno);
    $anno = ($n==1)?$primo:$primo." - ".$anno[$n-1];
    $post .= "<tr>";
    $post .= "<td><a href='lavoro_view.php?p=".$c['id']."'><i class='fa fa-arrow-right'></i></a></td>";
    $post .= "<td>".$c['nome']."</td>";
    $post .= "<td>".$c['tipo']."</td>";
    $post .= "<td>".$anno."</td>";
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
        .footable th:nth-child(3){width:30%;}
        .footable th:nth-child(4){width:10%;}
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
                <a href="post_new.php" title="inserisci un nuovo lavoro"><i class="fa fa-plus"></i>nuovo lavoro</a>
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
                    <th data-sort-ignore="true">Nome</th>
                    <th data-hide="phone">Tipo lavoro</th>
                    <th data-hide="phone">Anno</th>
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
