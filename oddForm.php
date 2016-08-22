<?php
session_start();
require("inc/db.php");
if(isset($_GET['odd'])){
    $header = 'Modifica risorsa on-line';
    $p="select * from main.post where id=".$_GET['p'];
    $pr = pg_query($connection,$p);
    $post = pg_fetch_array($pr);
    $id = $_GET['odd'];
    //tag presenti
    $tagpres = "select t.id, t.tag from liste.tag t, main.tags ts where ts.tag = t.id and ts.rec = ".$_GET['p']." and ts.tab = 1 order by t.tag asc;";
    $tagpresq = pg_query($connection,$tagpres);
    $tagpresarr = array();
    while ($tagprest = pg_fetch_array($tagpresq)) {
        $x['id'] = $tagprest['id'];
        $x['tag'] = $tagprest['tag'];
        array_push($tagpresarr,$x);
    }
    $tagpresList = json_encode($tagpresarr);
}else{
    $tagpresList = 'noTag';
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
            <form name="oddForm" action="oddMod.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>" >
                <section>
                    <header class='submain'>Tags</header>
                    <div class="rowButton"><input type="text" name="tags" placeholder="Tags" class="tm-input"></div>
                </section>
                <section class='metadati'>
                    <header class='submain'>Metadati</header>
                    <div class="rowButton">
                        <span class="inline">categoria: </span>
                        <span class="inline">
                            <select name="categoria" required>
                                <option selected disabled></option>
                                <option value="paper">Articolo, pubblicazione</option>
                                <option value="poster">Poster</option>
                                <option value="talk">Presentazione</option>
                                <option value="html">Html</option>
                            </select>
                        </span>
                    </div>
                    <div class="rowButton"><span class="inline">titolo: </span><span class="inline"><input type="text" name="titolo" required></span></div>
                    <div class="rowButton"><span class="inline">autori: </span><span class="inline"><textarea name="autori" required></textarea></span></div>
                    <div class="rowButton"><span class="inline">abstract: </span><span class="inline"><textarea name="descrizione" required></textarea></span></div>
                </section>
                <section class=''>
                    <header class='submain'>File</header>
                    <div id="filesShow"><ul id="filesShowList"></ul></div>
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
            p = $("input[name=get]").val();
            if(p > 0){
                var tagpresarr = <?php echo $tagpresList; ?>;
                var tags = [];
                $.each(tagpresarr, function(k,v) { tags.push(v.tag); });
                prefilled=tags;
                script = 'oddMod.php';
            }else{
                prefilled='';
                script = 'oddAdd.php';
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
                $("#filesShowList").append("<li><span class='inline'>"+link.val()+"</span><span class='inline'>"+$("select[name='licenza'] option:selected").text()+"</span><a href='#' class='prevent red' title='elimina riga'><i class='fa fa-times' aria-hidden='true'></i></a><input type='hidden' name='urlArr[]' value='"+link.val()+"'><input type='hidden' name='licenzaArr[]' value='"+licenza.val()+"'></li>");
            });
        });
    </script>
  </body>
</html>
