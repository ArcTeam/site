<?php
session_start();
require("inc/db.php");

?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <style>
        .form {width:80%;}
        input[type=text]{width:99%;border-radius:3px;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="form">
        <header>Inserisci un nuovo post</header>
        <form name="postForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
          <div class="rowButton"><input type="text" name="titolo" placeholder="Inserisci il titolo del post" ></div>
          <div class="rowButton"><textarea name="testo" id="testo"></textarea></div>
          <div class="rowButton"><input type="submit" name="submit" value="salva post"></div>
          <div class="rowButton" id="msg">
              <span></span>
              <div class="hide">
                  <a href="index.php" class="button" title="torna alla home page">torna alla home</a>
                  <a href="postList.php" class="button" title="elenco post">elenco post</a>
                  <a href="" class="button" id="linkPost" title="visualizza post creato">visualizza post creato</a>
              </div>
          </div>
        </form>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="ckeditor/adapters/jquery.js"></script>
    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            $('#testo').ckeditor();
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
