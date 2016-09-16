<?php
session_start();
require("inc/db.php");
if(isset($_GET['odd'])){
    $header = 'Modifica risorsa on-line';
    $p="select * from main.opendata where id=".$_GET['odd'];
    $pr = pg_query($connection,$p);
    $odd = pg_fetch_array($pr);
    $id = $_GET['odd'];
    $categoria = $odd['categoria'];
    $titolo= $odd['titolo'];
    $autori= $odd['autori'];
    $abstract= $odd['descrizione'];

    //tag presenti
    $tagpres = "select tags from main.tags where rec = ".$_GET['odd']." and tab = 4 order by tags asc;";
    $tagpresq = pg_query($connection,$tagpres);
    $tagpresres = pg_fetch_array($tagpresq);
    if (!$tagpresres) {
        $tagpresList = 0;
    }else {
        $tags = explode(',',$tagpresres['tags']);
        asort($tags);
        $tagpresList = json_encode($tags);
    }

    //file
    $f="SELECT opendatafile.id as odf, opendatafile.link, licenze.id as licenza, licenze.sigla, licenze.url FROM liste.licenze, main.opendatafile WHERE opendatafile.licenza = licenze.id AND opendatafile.opendata =".$_GET['odd'].";";
    $fExec = pg_query($connection,$f);
    while($file = pg_fetch_array($fExec)){
        $filetext = substr($file['link'], 0, 90)." ...";
        $files .= "<li><span class='inline'><a href='".$file['link']."' class='genericLink' target='_blank' title='[link esterno]'>".$filetext."</a></span><span class='inline'><a href='".$file['url']."' target='_blank' class='genericLink' title='[link esterno] pagina principale della licenza'>".$file['sigla']."</a></span><a href='#' class='prevent red' title='elimina riga'><i class='fa fa-times' aria-hidden='true'></i></a><input type='hidden' name='urlArr[]' value='".$file['link']."'><input type='hidden' name='licenzaArr[]' value='".$file['licenza']."'></li>";
    }
}else{
    $tagpresList = 0;
    $id = 0;
    $header = 'Inserisci una nuova risorsa on-line';
}

//lista tag
$t = "select tag from liste.tag order by tag asc;";
$tq = pg_query($connection, $t);
$tag = array();
while ($obj = pg_fetch_array($tq)) { $tag[] = $obj['tag'];}
$tagList = json_encode($tag);

//lista licenze
$l = "select * from liste.licenze order by licenza asc;";
$lq = pg_query($connection,$l);
$licenze = "<option selected disabled>scegli licenza</option>";
while($licenza = pg_fetch_array($lq)){$licenze .= "<option value='".$licenza['id']."'>".$licenza['sigla']."</option>";}

?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/oddForm.css" rel="stylesheet" media="screen" />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <link rel="stylesheet" href="lib/tag/tagmanager.css">
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
        <section class="content">
            <header class="main"><?php echo $header; ?></header>
            <section class="banner">
                <header class="submain toggle">Guida alla compilazione <i class="fa fa-question-circle" aria-hidden="true"></i></header>
                <div class="toggled">
                    <p>Tutti i campi sono obbligatori e vanno inserite almeno una tag e almeno una risorsa (sezione "file").</p>
                    <p>Il campo "Categoria" indica il tipo di risorsa che stiamo salvando e corrisponde alla cartella del server di Ronzone in cui stato caricato il file o di cui verrà fatto l'upload da qui (funzione ancora non attiva), in sostanza serve ad indicare al server dove salvare o dove recuperare il file o la cartella (nel caso delle presentazioni in html). Il valore "Html" indica non una file ma una presentazione fatta con Strut, mentre il valore "link esterno" indica una risorsa non presente sui nostri server ma, ad esempio, su Academia o Research Gate.</p>
                    <p>Nella sezione "File" va inserito l'url completo, bisogna fare attenzione ad includere anche l'estensione del file, altrimenti il link viene interpretato come link esterno. Una volta inseriti link e licenza, il record va aggiunto cliccando sul pulsante "+", una volta aggiunto alla lista è possibile da subito controllare che il file non sia corretto o incompleto.</p>
                    <p>I file possono essere caricati direttamente sul server di Ronzone, la cartella si chiama "openLibrary", al suo interno troverete altre 4 sottocartelle corrispondenti alle categorie presenti: paper (articoli e pubblicazioni), talk (presentazioni in pdf, odp, o ppt), poster(pdf o altri file di immagini), html (presentazioni in html).</p>
                    <button type="button" name="chiudi" class="generic toggle">ho capito! nascondimi</button>
                </div>
            </section>
            <form name="oddForm" action="oddMod.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>" >
                <input type="hidden" name='refresh' value='opendatadocs.php' >
                <section>
                    <header class='submain'>Tags</header>
                    <div class="rowButton"><input type="text" name="tags" placeholder="Tags" class="tm-input"></div>
                </section>
                <section class='metadati'>
                    <header class='submain'>Metadati</header>
                    <div class="rowButton">
                        <span class="inline">categoria: </span>
                        <span class="inline">
                            <input type = 'hidden' name="cat" value="<?php echo $categoria; ?>">
                            <select name="categoria" required>
                                <option selected disabled></option>
                                <option value="paper">Articolo, pubblicazione</option>
                                <option value="poster">Poster</option>
                                <option value="talk">Presentazione</option>
                                <option value="html">Html</option>
                                <option value="link">Link esterno</option>
                            </select>
                        </span>
                    </div>
                    <div class="rowButton"><span class="inline">titolo: </span><span class="inline"><input type="text" name="titolo" value="<?php echo $titolo; ?>" required></span></div>
                    <div class="rowButton"><span class="inline">autori: </span><span class="inline"><textarea name="autori" required><?php echo $autori;?></textarea></span></div>
                    <div class="rowButton"><span class="inline">abstract: </span><span class="inline"><textarea name="descrizione" required><?php echo $abstract; ?></textarea></span></div>
                </section>
                <section class=''>
                    <header class='submain'>File</header>
                    <div id="filesShow"><ul id="filesShowList"><?php echo $files; ?></ul></div>
                    <div class="rowButton">
                        <input type="url" name="link" placeholder="url risorsa">
                        <select name="licenza"><?php echo $licenze; ?></select>
                        <button type="button" class='addFile'><i class="fa fa-plus" aria-hidden="true"></i></button>
                        <br/>
                        <span class='red notUrl'>Link non valido, la forma corretta è: http://www.arc-team.com</span>
                    </div>
                </section>

                <div class="rowButton"><input type="submit" name="submit" value="salva"></div>
            </form>
        </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="lib/tag/tagmanager.js"></script>
    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            var dataList = <?php echo $tagList; ?>;
            var prefilled,script, p;
            p = $("input[name=id]").val();
            if(p > 0){
                var tagpresarr = <?php echo $tagpresList; ?>;
                var tags = [];
                if (tagpresarr == 0) {prefilled='';}
                else {
                    $.each(tagpresarr, function(k,v) {tags.push(v); });
                    prefilled=tags;
                }
                var cat = $("input[name='cat']").val();
                console.log(cat);
                //$("select[name='categoria'] option[value='"+cat+"']").prop("selected", true);
                $("select[name='categoria'] option[value='"+cat+"']").prop('selected', true)
            }else{
                prefilled='';
            }
            $(".tm-input").tagsManager({
                prefilled: prefilled,
                hiddenTagListName: 'tagList',
                hiddenTagListId: 'tagList',
                deleteTagsOnBackspace: false,
                AjaxPush: 'inc/addTag.php',
            })
            .autocomplete({source:dataList});

            tagLength();
            $('.tm-input').on('tm:spliced tm:popped tm:pushed', function () { tagLength(); });

            var section = $("form > section").width();
            var span = $(".metadati span:first-child").width();
            var input = section - span;
            $(".metadati span:last-child").css({"width":input-10});

            $(".addFile").on("click", function(){
                var link = $("input[name='link']");
                var licenza = $("select[name='licenza']");
                if(!link.val()){link.addClass('formError'); return false;}
                else if(!isUrl(link.val())) {
                    link.addClass('formError');
                    $(".notUrl").show();
                    return false;
                }else {
                    link.removeClass('formError');
                    $(".notUrl").hide();
                }
                if(!licenza.val()){licenza.addClass('formError'); return false;}else { licenza.removeClass('formError'); }

                $("#filesShowList").append("<li><span class='inline'><a href='"+link.val()+"' class='genericLink' target='_blank' title='[link esterno]'>"+link.val().substring(0, 90)+" ...</a></span><span class='inline'>"+$("select[name='licenza'] option:selected").text()+"</span><a href='#' class='prevent red' title='elimina riga'><i class='fa fa-times' aria-hidden='true'></i></a><input type='hidden' name='urlArr[]' value='"+link.val()+"'><input type='hidden' name='licenzaArr[]' value='"+licenza.val()+"'></li>");
            });
        });
    </script>
  </body>
</html>
