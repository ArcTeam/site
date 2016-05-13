<?php
session_start();
require("inc/db.php");
//lista tag
$t = "select tag from liste.tag order by tag asc;";
$tq = pg_query($connection, $t);
//while($tag = pg_fetch_array($tq)){echo $tag['tag'].", ";}
$tag = array();
while ($obj = pg_fetch_array($tq)) { $tag[] = $obj['tag'];}
$tagList = json_encode($tag);

?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/post_new.css" rel="stylesheet" media="screen" />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
        <section class="form ckform">
            <header>Inserisci un nuovo post</header>
            <form name="postForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="rowButton">aggiungi tag: <input type="text" name="tags" placeholder="Tags" class="tm-input" ></div>
                <div class="rowButton"><input type="text" name="titolo" placeholder="Inserisci il titolo del post" ></div>
                <div class="rowButton"><textarea name="testo" id="testo"></textarea></div>
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
                        url: 'inc/post_add.php',
                        type: 'POST',
                        data: {tag:tag,stato:stato,titolo:titolo,post:post},
                        success: function(data){
                            if(data.indexOf("errore") !== -1){
                                $("#msg span").text(data);
                            }else{
                                $("#msg span").text("");
                                $("input[type=submit]").hide();
                                $("#msg div").fadeIn('fast');
                                $("#linkPost").attr("href", "post_view.php?p="+data);
                            }
                        }
                    });
                }
            });
            var dataList = <?php echo $tagList; ?>;
            $(".tm-input").tagsManager({
                hiddenTagListName: 'tagList',
                hiddenTagListId: 'tagList',
                deleteTagsOnBackspace: false,
                AjaxPush: 'script/addTag.php',
            })
            .autocomplete({source:dataList});

            $(".radioLabel").on("click", function(){
                $(this).addClass('checked').siblings().removeClass('checked');
            });
        });
    </script>
  </body>
</html>
