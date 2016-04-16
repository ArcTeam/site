<?php
session_start();
require("inc/db.php");
$a = "select p.id,p.titolo,p.testo,p.data,r.utente from main.post p, main.usr u, main.rubrica r where p.utente = u.id and u.rubrica = r.id and p.id =".$_GET['p'];
$b = pg_query($connection,$a);
$p = pg_fetch_array($b);
$data = explode(" ",$p['data']);
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" />
      <style>
        #meta{display:block;text-align:right;font-style:italic;font-size:.8rem;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="form ckform">
        <header><?php echo $p['titolo']; ?></header>
        <span id="meta">Scritto da <strong><?php echo $p['utente']; ?></strong> il <strong><?php echo $data[0]; ?></strong></span>
        <article><?php echo $p['testo']; ?></article>
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
                                $("#linkPost").attr("href", "postView.php?p="+data);
                            }
                        }
                    });
                }
            });
        });
    </script>
  </body>
</html>
