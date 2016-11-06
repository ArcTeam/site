<?php
session_start();
require("inc/db.php");
require("inc/dialog.php");
$a ="select d.id giorno,a.gid attivita,l.id lavoro, r.utente, l.nome, c.def as categoria, a.data_inizio as inizio, d.data, d.ore from main.diario d, main.attivita a, main.lavoro l, liste.subcat c, main.rubrica r, main.usr u where d.attivita = a.gid and a.tipo_lavoro = c.id and a.lavoro = l.id and d.utente = u.id and u.rubrica = r.id order by d.data desc;";
$b = pg_query($connection, $a);
while($c = pg_fetch_array($b)){
    $descrizione = nl2br($c['descrizione']);
    $post .= "<tr>";
    $post .= "<td>".$c['utente']."</td>";
    $post .= "<td>".$c['nome']."</td>";
    $post .= "<td>".$c['categoria']."</td>";
    $post .= "<td>".$c['inizio']."</td>";
    $post .= "<td>".$c['data']."</td>";
    $post .= "<td>".$c['ore']."</td>";
    $post .= "<td>".$c['descrizione']."</td>";
    $post .= "</tr>";
}

//lista utenti
$u = "select u.id, r.utente from main.rubrica r, main.usr u where u.rubrica = r.id order by utente asc;";
$ue = pg_query($connection, $u);
while($usr = pg_fetch_array($ue)){$uList .= "<option value='".$usr[id]."'>".$usr['utente']."</li>";}

//lavori in corso
$l ="select distinct l.id, l.nome from main.lavoro l, main.attivita a where a.lavoro=l.id and a.data_fine is null order by nome asc;";
$le = pg_query($connection, $l);
while($lavori = pg_fetch_array($le)){$lavorilist .= "<option value='".$lavori[id]."'>".$lavori['nome']."</li>";}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <link href="lib/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" />
      <style>
        .footable th:nth-child(1),.footable th:nth-child(3){width:20%;}
        .footable th:nth-child(4){width:10%;}
        .footable th:nth-child(5){width:10%;}
        .footable th:nth-child(6){width:5%;}
        form{display:none;margin-top:20px;}
        #dialogWrap section{max-height:600px !important; overflow:auto;margin-top:2% !important;}
        textarea[name=descrizione]{width: 70%; height: 120px;}
        .rowButton label{display:inline-block;width:85px;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="content">
        <header>Elenco ore</header>
        <section class="toolbar">
            <nav class="toolbar">
                <ul>
                    <?php if(isset($_SESSION["id"])){ ?>
                    <li><a href="#" class="prevent" title="inserisci una nuova giornata lavorativa" id="addOre"><i class="fa fa-plus"></i>aggiungi ore</a></li>
                    <?php } ?>
                </ul>
            </nav>
            <div class="tableTool">
                <select id="userList">
                    <option disabled selected> --seleziona utente-- </option>
                    <option value="0"> elenco completo </option>
                    <?php echo $uList; ?>
                </select>
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
                    <th data-hide="phone">Lavoro</th>
                    <th>Attività</th>
                    <th data-hide="all" data-sort-ignore="true">Inizio attività</th>
                    <th>Giorno</th>
                    <th data-sort-ignore="true">Ore</th>
                    <th data-hide="all">Descrizione</th>
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

    <form method="post" name="addOre">
        <input type="hidden" name="utente" value="<?php echo $_SESSION['id']; ?>">
        <div class="rowButton">
            <label>Utente: </label>
            <select id="usr" name="usr" required>
                <option disabled selected> --seleziona utente-- </option>
                <?php echo $uList; ?>
            </select>
        </div>
        <div class="rowButton">
            <label>Lavoro: </label>
            <select id="lavoro" name="lavoro" required>
                <option disabled selected> --seleziona lavoro in corso-- </option>
                <?php echo $lavorilist; ?>
            </select>
        </div>
        <div class="rowButton">
            <label>Attività: </label>
            <select id="attivita" name="attività" disabled required>
                <option disabled selected> --seleziona lavoro in corso-- </option>
            </select>
        </div>
        <div class="rowButton">
            <label>Data: </label>
            <input name="data" id="data" type="date" placeholder="aaaa-mm-gg" required>
        </div>
        <div class="rowButton">
            <label>Ore: </label>
            <input name="ore" type="number" required>
        </div>
        <div class="rowButton">
            <label>Descrizione: </label>
            <textarea name="descrizione"></textarea>
        </div>
    </form>

    <div id="result"></div>
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
        document.getElementById('data').valueAsDate = new Date();
        $(document).ready(function(){
            var u = $("input[name=utente]").val();
            $('#testo').ckeditor();
            $('.footable').footable();
            $('#change-page-size').change(function (e) {
      				e.preventDefault();
      				var pageSize = $(this).val();
      				$('.footable').data('page-size', pageSize);
      				$('.footable').trigger('footable_initialized');
      			});
            $('.clear-filter').click(function () { clearFilter(); });

            $("a#addOre").on("click", function(){
                var header = $(this).attr('title');
                $("#dialogContent header").text(header);
                $("#usr option[value="+u+"]").prop("selected", true);
                $("form[name=addOre]").append("<input type='submit' name='formSubmit' style='display:none'>");
                $("#dialogContent article").html($("form[name=addOre]"));
                $("form[name=addOre]").show();
                $("#dialogWrap").fadeIn('fast');
                $("#lavoro").on("change", function(){
                    var l = $(this).val();
                    $.post("inc/attivitaList.php", {id:l}, function(data){$("#attivita").prop("disabled",false).html(data);});
                });
                $("button[name='conferma']").on("click", function(){ $("input[name=formSubmit]").click(); });
            });
        });
    </script>
  </body>
</html>
