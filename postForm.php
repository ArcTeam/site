<?php
session_start();
require("inc/db.php");
if(isset($_GET['p'])){
    $p="select * from main.post where id=".$_GET['p'];
    $pr = pg_query($connection,$p);
    $post = pg_fetch_array($pr);
    $id = $_GET['p'];
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
}

//lista tag
$t = "select tag from liste.tag order by tag asc;";
$tq = pg_query($connection, $t);
$tag = array();
while ($obj = pg_fetch_array($tq)) { $tag[] = $obj['tag'];}
$tagList = json_encode($tag);

?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/postForm.css" rel="stylesheet" media="screen" />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
        <section class="form ckform">
            <header>Inserisci un nuovo post</header>
            <form name="postForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="hidden" name="get" value="<?php echo $id; ?>" >
                <input type="hidden" name="s" value="<?php echo $post['pubblica']; ?>" >
                <div class="rowButton">aggiungi tag: <input type="text" name="tags" placeholder="Tags" class="tm-input" ></div>
                <div class="rowButton"><input type="text" name="titolo" placeholder="Inserisci il titolo del post" value="<?php echo $post['titolo']; ?>" ></div>
                <div class="rowButton"><textarea name="testo" id="testo"><?php echo $post['testo']; ?></textarea></div>
                <div class="rowButton">
                    <label class="statoPost">Vuoi salvare il post come bozza o vuoi pubblicarlo subito?<br> Le bozze non saranno visibili agli utenti esterni fino a che non decidi di pubblicarle</label>
                    <div style="text-align:center;">
                        <label for="bozza" class="radioLabel">Salva come bozza</label>
                        <label for="pubblica" class="radioLabel checked">Pubblica direttamente</label>
                        <input type="radio" name="stato" value="0" id="bozza">
                        <input type="radio" name="stato" value="1" id="pubblica" checked >
                    </div>
                </div>
                <div class="rowButton"><input type="submit" name="submit" value="salva post"></div>
                <div class="rowButton" id="msg">
                    <span></span>
                    <div class="hide">
                        <a href="index.php" class="button" title="torna alla home page">torna alla home</a>
                        <a href="post.php" class="button" title="elenco post">elenco post</a>
                        <a href="" class="button" id="linkPost" title="visualizza post creato">visualizza post creato</a>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="ckeditor/adapters/jquery.js"></script>
    <script src="lib/tag/tagmanager.js"></script>
    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            $('#testo').ckeditor();
            var dataList = <?php echo $tagList; ?>;
            var prefilled,script, p;
            p = $("input[name=get]").val();
            if(p > 0){
                var stato = $('input[name=s]').val();
                $("input[name=stato]").attr("checked",false);
                $("input[name=stato][value="+stato+"]").prop("checked",true);
                $(".radioLabel").removeClass('checked');
                if(stato==1){
                    $(".radioLabel[for=pubblica]").addClass('checked');
                }else{
                    $(".radioLabel[for=bozza]").addClass('checked');
                }
                var tagpresarr = <?php echo $tagpresList; ?>;
                var tags = [];
                $.each(tagpresarr, function(k,v) { tags.push(v.tag); });
                prefilled=tags;
                script = 'postMod.php';
            }else{
                prefilled='';
                script = 'postAdd.php';
            }
            $(".tm-input").tagsManager({
                prefilled: prefilled,
                hiddenTagListName: 'tagList',
                hiddenTagListId: 'tagList',
                deleteTagsOnBackspace: false,
                AjaxPush: 'script/addTag.php',
            })
            .autocomplete({source:dataList});
            var form = $("form[name=postForm]");
            form.submit(function(e){
                e.preventDefault();
                var tag = $("input[name=tagList]").val();
                var stato = $("input[name=stato]:checked").val();
                var titolo = $("input[name=titolo]").val();
                var post = $("textarea[name=testo]").val();
                post = post.replace(/(\r\n|\n|\r)/gm,"");
                if(!tag){$("#msg span").text("Devi inserire almeno una tag!");}
                else if(!titolo && !post){$("#msg span").text("Devi inserire un titolo e un testo per il post!");}
                else if(!titolo){$("#msg span").text("Devi inserire un titolo per il post!");}
                else if(!post){$("#msg span").text("Devi inserire un testo per il post!");}
                else{
                    $.ajax({
                        url: 'inc/'+script,
                        type: 'POST',
                        data: {id:p, tag:tag,stato:stato,titolo:titolo,post:post},
                        success: function(data){
                            if(data.indexOf("errore") !== -1){
                                $("#msg span").text(data);
                            }else{
                                $("#msg span").text("");
                                $("input[type=submit]").hide();
                                $("#msg div").fadeIn('fast');
                                $("#linkPost").attr("href", "postView.php?p="+data);
                            }
                        }
                    });
                }
            });

            $(".radioLabel").on("click", function(){ $(this).addClass('checked').siblings().removeClass('checked');});

        });
    </script>
  </body>
</html>
