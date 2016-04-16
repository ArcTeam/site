<?php
session_start();
require("inc/db.php");
$a ="select p.id, p.titolo, p.data,r.utente from main.post p,main.usr u, main.rubrica r where p.utente = u.id and u.rubrica = r.id order by data desc;";
$b = pg_query($connection, $a);
while($c = pg_fetch_array($b)){
    $data = split(" ",$c['data']);
    $data = $data[0];
    $post .= "<tr>";
    $post .= "<td><a href='post_view.php?p=".$c['id']."'><i class='fa fa-arrow-right'></i></a></td>";
    $post .= "<td>".$c['titolo']."</td>";
    $post .= "<td>".$c['utente']."</td>";
    $post .= "<td>".$data."</td>";
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
        <header>Archivio post</header>
        <section class="toolbar">
            <div class="listTool">
                <?php if(isset($_SESSION["id"])){ ?>
                <a href="post_new.php" title="inserisci un nuovo post"><i class="fa fa-plus"></i>nuovo post</a>
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
                    <th data-sort-ignore="true">Titolo</th>
                    <th data-hide="phone">Autore</th>
                    <th data-hide="phone">Data</th>
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
            var form = $("form[name=postForm]");
            form.submit(function(e){
                e.preventDefault();
                var titolo = $("input[name=titolo]").val();
                var post = $("textarea[name=testo]").val();
                post = post.replace(/(\r\n|\n|\r)/gm,"");
                if(!titolo && !post){$("#msg span").text("Devi inserire un titolo e un testo per il post!");}
                else if(!titolo){$("#msg span").text("Devi inserire un titolo per il post!");}
                else if(!post){$("#msg span").text("Devi inserire un testo per il post!");}
                else{
                    $.ajax({
                        url: 'inc/post_add.php',
                        type: 'POST',
                        data: {titolo:titolo,post:post},
                        success: function(data){
                            if(data.indexOf("errore") !== -1){
                                $("#msg span").text(data);
                            }else{
                                $("#msg span").text("");
                                $("input[type=submit]").hide();
                                $("#msg div").fadeIn('fast');
                                $("#linkPost").attr("href", "post.php?p="+data);
                            }
                        }
                    });
                }
            });
        });
    </script>
  </body>
</html>
