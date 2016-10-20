<?php
session_start();
require("inc/db.php");
$a ="SELECT rubrica.id, usr.id as usr, rubrica.utente, rubrica.email, rubrica.indirizzo, rubrica.codfisc, rubrica.piva,  rubrica.telefono, rubrica.cell, rubrica.fax, rubrica.url, rubrica.note, tipo_utente.id as id_tipo, tipo_utente.tipo, tipo_utente.definizione as categoria, usr.attivo,
case
	when tipo_utente.tipo = 1 then 'warning'::text
	else ''::text
end as classe_utente,
case
	when usr.attivo = 1 then 'attivo'::text
	when usr.attivo = 0 then 'disabilitato'::text
end as stato,
case
	when usr.attivo = 1 then 'success'::text
	when usr.attivo = 0 then 'error'::text
end as classe_stato
FROM main.rubrica
left join main.usr on usr.rubrica = rubrica.id
inner join liste.tipo_utente on rubrica.tipo = tipo_utente.id
order by utente asc;";
$b = pg_query($connection, $a);
while($c = pg_fetch_array($b)){
    $nome = str_ireplace("'", "&#146", $c['utente']);
    $indirizzo = str_ireplace("'", "&#146", $c['indirizzo']);
    $utente .= "<tr title='clicca sulla riga dell&#146;utente per avere maggiori informazioni'>";
    $utente .= "<td>".$nome."</td>";
    $utente .= "<td>".$indirizzo."</td>";
    $utente .= "<td>".$c['codfisc']."</td>";
    $utente .= "<td>".$c['piva']."</td>";
    $utente .= "<td>".$c['email']."</td>";
    $utente .= "<td>".$c['cell']."</td>";
    $utente .= "<td>";
    if ($c['tipo']==1) {
        $utente .= "<span class='".$c['classe_utente']."' style='display:block;'>".$c['categoria']."</span>";
    }else {
        $utente .= "<span class='".$c['classe_stato']."' style='display:block;'>".$c['categoria']."<br/>".$c['stato']."</span>";
    }
    $utente .= "</td>";
    $utente .= "<td>".$c['telefono']."</td>";
    $utente .= "<td>".$c['fax']."</td>";
    $utente .= "<td><a href='".$c['url']."' target='_blank' title='[link esterno]'>".$c['url']."</td>";
    $utente .= "<td>".$c['note']."</td>";
    if ($_SESSION['classe']==2) {
        $utente .= "<td class='action'>";
        $utente .= "<ul class='hoverUl'>";
        $utente .=   "<li><a href='#' class='prevent' title='azioni'><i class='fa fa-cog' aria-hidden='true'></i></a>";
        $utente .=     "<ul>";
        $utente .=       "<li><a href='rubricaMod.php?x=".$c['id']."' class='modUsr' title='modifica dati utente'><i class='fa fa-wrench' aria-hidden='true'></i> modifica dati utente</a></li>";
        if ($c['tipo']==1) {
            $utente .= "<li><a href='#' data-utente='".$nome."' data-id='".$c['id']."' class='prevent delUsr' title='elimina ".$nome." dalla rubrica'><i class='fa fa-times' aria-hidden='true'></i> elimina ".$nome." dalla rubrica</a></li>";
            $utente .= "<li><a href='#' data-email='".$c['email']."' data-utente='".$nome."' data-id='".$c['id']."' class='prevent attivaUsr' title='promuovi ".$nome." a utente di sistema'><i class='fa fa-user' aria-hidden='true'></i> promuovi ".$nome." a utente di sistema</a></li>";
        }else {
            if ($_SESSION['id']!=$c['usr']) {
                $ico = $c['attivo'] == 1 ? 'fa-thumbs-o-down':'fa-thumbs-o-up';
                $title = $c['attivo'] == 1 ? 'disattiva':'attiva';
                $utente .= "<li><a href='#' data-utente='".$nome."' data-stato='".$c['attivo']."' data-id='".$c['usr']."' class='prevent statoUsr' title='".$title." ".$nome."'><i class='fa ".$ico."' aria-hidden='true'></i> ".$title." ".$nome."</a><li>";
            }
        }
        $utente .=    "</li>";
        $utente .=   "</ul>";
        $utente .=  "</ul>";
        $utente .= "</td>";
    }
    $utente .= "</tr>";
}

//crea select per attivazione utente di sistema
$tipoq="select * from liste.tipo_utente where tipo = 2 order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);
$formDialog='';
$formDialog .=  "<label style='width:150px;margin-bottom: 10px;' class='inline'>Scegli classe utente: </label>";
$formDialog .= "<select name='usrClass' style='width:250px;margin-bottom: 10px;' class='inline'>";
$formDialog .= "<option selected disabled></option>";
while($opt = pg_fetch_array($tipoexec)){
$formDialog .= "<option value='".$opt['id']."'>".$opt['definizione']."</option>";
}
$formDialog .= "</select><br/>";
$formDialog .=  "<label style='width:150px;' class='inline mailCheck'>Inserisci email: </label>";
$formDialog .=  "<input type='email' name='email' style='width:250px;' class='inline mailCheck'><br/>";

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require("inc/meta.php"); ?>
        <link href="css/style.css" rel="stylesheet" media="screen" />
        <link href="lib/FooTable/css/footable.core.min.css" rel="stylesheet" media="screen" />
        <style>
            .footable th:nth-child(1){width:150px;}
            .footable th:nth-child(6){width:100px;}
            .footable th:nth-child(7){width:150px;}
            .footable th:nth-child(7), .footable td:nth-child(7){padding:5px 10px;}
            .footable th:nth-child(12){width:30px; text-align:center;}
            .footable td{vertical-align: middle;}
            td.action ul{position:relative}
            td.action ul li a{display:block;padding:2px 5px;text-align:center;color:rgba(54,58,63,0.7);}
            td.action ul li a:hover{color:rgba(54,58,63,1);}
            td.action ul ul{position: absolute; margin-left: -300px; margin-top: -30px; width:300px; z-index:10; background: rgb(118,118,118); border: 1px solid rgb(54,58,63); border-radius: 2px;display:none;}
            td.action ul ul li a{display:block;padding:2px 5px; color:rgb(240,240,240);text-align:left;}
            td.action ul ul li a:hover{background:rgb(72,72,72);color:rgb(250,250,250);}
        </style>
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <?php require("inc/dialog.php"); ?>
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
                            <th data-sort-ignore="true" data-hide="all">P.Iva</th>
                            <th>Email</th>
                            <th>Cellulare</th>
                            <th data-sort-ignore="true" data-hide="phone">Categoria</th>
                            <th data-sort-ignore="true" data-hide="all">Telefono</th>
                            <th data-sort-ignore="true" data-hide="all">Fax</th>
                            <th data-sort-ignore="true" data-hide="all">Sito web</th>
                            <th data-sort-ignore="true" data-hide="all">Note</th>
                            <?php if ($_SESSION['classe']==2) {?>
                                <th data-sort-ignore="true" data-ignore="true" data-hide="phone"></th>
                            <?php } ?>
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
                $(".hoverUl > li").hover(function(){$(this).children('ul').toggle();});
                $("a.statoUsr").on("click", function(){
                    var id = $(this).data('id');
                    var stato = $(this).data('stato');
                    var utente = $(this).data('utente');
                    var header = $(this).attr('title');
                    if(stato==1){
                        var testo = "Hai scelto di <strong>disattivare</strong> l'utente "+utente+".</br>Se confermi verrà disabilitato il login e l'utente non potrà più accedere alle aree riservate.<br/>I dati già inseriti dall'utente non verranno cancellati e continueranno ad essere disponibili per la consultazione.";
                    }else {
                        var testo = "Hai scelto di <strong>attivare</strong> l'utente "+utente+".</br>Se confermi verrà riabilitato il login e l'utente potrà tornare ad accedere alle aree riservate.";
                    }
                    $("#dialogContent header").text(header);
                    $("#dialogContent article").html(testo);
                    $("#dialogWrap").fadeIn('fast');
                    $("button[name='conferma']").on("click", function(){ usrAction(id, 'usrStato.php'); });
                });
                $("a.delUsr").on("click", function(){
                    var id = $(this).data('id');
                    var utente = $(this).data('utente');
                    var header = $(this).attr('title');
                    var testo = "Hai scelto di <strong>eliminare</strong> l'utente "+utente+" dalla rubrica generale.</br>Se confermi l'eliminazione tutti i dati dell'utente saranno definitivamente eliminati dal database e non sarà più possibile recuperarli.";
                    $("#dialogContent header").text(header);
                    $("#dialogContent article").html(testo);
                    $("#dialogWrap").fadeIn('fast');
                    $("button[name='conferma']").on("click", function(){ usrAction(id, 'usrDel.php'); });
                });
                $("a.attivaUsr").on("click", function(){
                    var id = $(this).data('id');
                    var utente = $(this).data('utente');
                    var header = $(this).attr('title');
                    var mail = $(this).data('email');
                    if (!mail) {
                        var checkMail = "L'utente non sembra avere una mail di riferimento, per diventare utente di sistema è necessario specificare una mail.";
                        $(".checkMail").show();
                    }else {
                        var checkMail = "Prima di inviare la password controlla che la mail sia corretta o attiva.<br>Stai per inviare una nuova password all'indirizzo: <strong>"+mail+"</strong>";
                        $(".mailCheck").hide();
                    }
                    var testo = "Hai scelto di <strong>abilitare il login</strong> per l'utente "+utente+".<br/>Se confermi l'operazione il sistema genererà una password che verrà inviata via email all'utente selezionato, in questo modo potrà accedere alle aree riservate sulla base della classe utente scelta.<br/>"+checkMail;
                    $("#dialogContent header").text(header);
                    $("#dialogContent article").html(testo);
                    $("#dialogWrap").fadeIn('fast');
                    $(".dialogForm").show();
                    $("button[name='conferma']").on("click", function(){
                        var classe = $("select[name='usrClass']").val();
                        var email = $("input[name='email']").val();
                        if (!classe) {
                            $(".dialogResult").html('Devi selezionare una classe dalla lista').addClass('warning').show();
                        }else if (!mail && !email) {
                            $(".dialogResult").html('Devi inserire unamail valida').addClass('warning').show();
                        }else {
                            usrAction(id, 'usrPromuovi.php', classe, email);
                        }
                    });
                });
            });
        </script>
    </body>
</html>
