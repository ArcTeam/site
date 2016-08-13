<?php
session_start();
require("inc/db.php");
$a ="select * from main.rubrica order by utente asc;";
$b = pg_query($connection, $a);
while($c = pg_fetch_array($b)){
    $utente .= "<tr>";
    $utente .= "<td>".$c['utente']."</td>";
    $utente .= "<td>".$c['indirizzo']."</td>";
    $utente .= "<td>".$c['codfisc']."</td>";
    $utente .= "<td>".$c['email']."</td>";
    $utente .= "<td>".$c['cell']."</td>";
    $utente .= "<td>".$c['telefono']."</td>";
    $utente .= "<td>".$c['fax']."</td>";
    $utente .= "<td><a href='".$c['url']."' target='_blank' title='[link esterno]'>".$c['url']."</td>";
    $utente .= "<td>".$c['note']."</td>";
    $utente .= "<td><a href='rubricaMod.php?x=".$c['id']."' title='modifica dati di ".$c['utente']."'><i class='fa fa-wrench' aria-hidden='true'></i></a></td>";
    $utente .= "</tr>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require("inc/meta.php"); ?>
        <link href="css/style.css" rel="stylesheet" media="screen" />
        <link href="lib/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" />
        <style>
            .footable th:nth-child(1){width:150px;}
            .footable th:nth-child(10){width:20px; text-align:center;}
            .footable th:nth-child(5){width:90px;}
            /*.footable th:nth-child(4){width:10%;}*/
        </style>
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="content">
                <?php if (isset($_SESSION['id'])) {?>
                <header>Rubrica</header>
                <section class="toolbar">
                    <div class="listTool">
                        <a href="usrAdd.php" title="inserisci un nuovo utente"><i class="fa fa-plus"></i>nuovo utente</a>
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
                            <th>Utente</th>
                            <th data-sort-ignore="true" data-hide="phone">Indirizzo</th>
                            <th data-sort-ignore="true" data-hide="all">Cod.Fisc.</th>
                            <th>Email</th>
                            <th>Cellulare</th>
                            <th data-sort-ignore="true" data-hide="all">Telefono</th>
                            <th data-sort-ignore="true" data-hide="all">Fax</th>
                            <th data-sort-ignore="true" data-hide="all">Sito web</th>
                            <th data-sort-ignore="true" data-hide="all">Note</th>
                            <th data-sort-ignore="true"></th>
                        </tr>
                    </thead>
                    <tbody><?php echo $utente; ?></tbody>
                    <tfoot class="hide-if-no-paging">
                        <tr>
                            <td colspan="4">
                                <div class="pagination pagination-centered"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <?php }else{include("inc/noAccess.php");}?>
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
