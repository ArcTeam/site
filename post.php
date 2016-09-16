<?php
session_start();
require("inc/db.php");
require("inc/cut.php");
if(isset($_GET['x'])){$x=0;}else{$x=1;}
$a = "SELECT post.id,post.titolo, post.testo, log.data, rubrica.utente FROM main.log, main.usr, main.rubrica, main.post WHERE log.utente = usr.id AND log.record = post.id AND usr.rubrica = rubrica.id AND post.pubblica = 1 AND post.cat = 1 AND log.tabella = 'post' AND log.operazione = 'I' order by log.data desc;";
$b = pg_query($connection, $a);
while($c = pg_fetch_array($b)){
    $data = split(" ",$c['data']);
    $data = $data[0];
    //$testo = strip_tags($p['testo']);
    $testo = cutHtmlText($c['testo'], 300, "...", false, false, false);
    $post .= "<tr>";
    $post .= "<td>";
    $post .= "<header class='giallo'>".$c['titolo']."</header>";
    $post .= "<article>";
    $post .= $testo;
    $post .= "<div class='buttonFooter'><a href='postView.php?p=".$c['id']."' title='Leggi tutto'><i class='fa fa-arrow-right hidden-aria='true'></i> Leggi tutto</a></div>";
    $post .= "</article>";
    $post .= "<footer>Pubblicato da <strong>".$c['utente']."</strong> il <strong>".$data."</strong></footer>";
    $post .= "</td>";
    $post .= "</tr>";
}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/post.css" rel="stylesheet" media="screen" >
      <link href="lib/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" >
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="content">
        <header class='giallo'>Archivio post</header>
        <section id="presentazione">
            <span class='inline'><i class="fa fa-th-list fa-5x"></i></span><span class='inline'>Notizie, eventi, nuovi progetti, nuovi cantieri e tante altre informazioni dal mondo Arc-Team.</span>
        </section>
        <section class="toolbar">
            <div class="listTool">
                <?php if(isset($_SESSION["id"])){ ?><a href="postForm.php?t=1" title="inserisci un nuovo post"><i class="fa fa-plus"></i>nuovo post</a><?php } ?>
            </div>
            <div class="tableTool">
                <select id="change-page-size">
                    <option disabled selected> righe visibili </option>
    				<option value="30">30</option>
    				<option value="40">40</option>
                    <option value="50">50</option>
                    <option value="60">60</option>
                </select>
                <?php if(isset($_SESSION['id'])){?><label id="statoLabel">bozze</label><?php } ?>
                <input type="search" placeholder="...cerca" id="filtro">
                <i class="fa fa-undo clear-filter" title="Pulisci filtro"></i>
            </div>
        </section>
        <table class="tableList footable toggle-arrow-tiny" data-page-size="20" data-filter="#filtro" data-filter-text-only="true">
            <thead>
                <tr>
                    <th data-sort-ignore="true"></th>
                </tr>
            </thead>
            <tbody><?php echo $post; ?></tbody>
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

    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            $("a#post").addClass('actPost prevent');
            $('#testo').ckeditor();
            $('.footable').footable();
            $('#change-page-size').change(function (e) {
				e.preventDefault();
				var pageSize = $(this).val();
				$('.footable').data('page-size', pageSize);
				$('.footable').trigger('footable_initialized');
			});
            $('.clear-filter').click(function(e) { $('.footable').trigger('footable_clear_filter'); });
            var vis;
            $("#statoLabel").on("click", function(){
                $(this).toggleClass('checkedLabel');
                if($(this).hasClass('checkedLabel')){vis = 0;}else{vis = 1; }
                $.ajax({
                    url: 'inc/post_list.php',
                    type: 'POST',
                    data: {vis:vis},
                    dataType : 'json',
                    success: function(data){
                        var tr;
                        $.each(data, function(index, item){
                            var id = item.id;
                            var data = item.data;
                            data = data.split(" ");
                            var titolo = item.titolo;
                            var testo = item.testo;
                            var utente = item.utente;
                            tr += "<tr><td><header class='giallo'>"+titolo+"</header><article>"+trimString(testo, 300, ' ', ' ...')+"<div class='buttonFooter'><a href='postView.php?p="+id+"' title='Leggi tutto'><i class='fa fa-arrow-right'></i> Leggi tutto</a></div></article><footer>Pubblicato da <strong>"+utente+"</strong> il <strong>"+data[0]+"</strong></footer></td></tr>";
                            $(".tableList tbody").html(tr);
                            $(".tableList tbody img").remove();
                        });
                    }
                });
            });
            $(".tableList tbody img").remove();
        });
    </script>
  </body>
</html>
