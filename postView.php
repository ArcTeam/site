<?php
session_start();
require("inc/db.php");
$a = "select p.id,p.titolo,p.testo,p.data,r.utente from main.post p, main.usr u, main.rubrica r where p.usr = u.id and u.rubrica = r.id and p.id =".$_GET['p'];
$b = pg_query($connection,$a);
$p = pg_fetch_array($b);
$data = explode(" ",$p['data']);

$t = "select t.tag from liste.tag t, main.tags ts where ts.tag = t.id and ts.rec = ".$_GET['p']." and ts.tab = 1 order by t.tag asc";
$tr = pg_query($connection,$t);
while($tag = pg_fetch_array($tr)){$tags .= "<span class='tag'>".$tag['tag']."<i class='fa fa-tag'></i></span>";}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require("inc/meta.php"); ?>
        <link href="css/postView.css" rel="stylesheet" media="screen" />
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="form ckform post">
                <header id="titolo"><?php echo $p['titolo']; ?></header>
                <article><?php echo $p['testo']; ?></article>
                <footer id="toolbar">
                    <a href="post.php" id="list"><i class="fa fa-th-list"></i> archivio post</a>
                    <?php if(isset($_SESSION['id'])){?>
                        <a href="postForm.php?p=<?php echo $_GET['p'];?>" id="mod"><i class="fa fa-wrench"></i> modifica post</a>
                        <a href="#" id="del"><i class="fa fa-times"></i> elimina post</a>
                        <?php } ?>
                </footer>
            </section>
            <section class="form ckform metadata">
                <header>INFO POST</header>
                <div id="meta">Post scritto da <strong><?php echo $p['utente']; ?></strong> il <strong><?php echo $data[0]; ?></strong></div>
                <header>TAGS</header>
                <div id="tag"><?php echo $tags; ?></div>
                <header>COMMENTI</header>
                <div id="disqus_thread"></div>
                <script>
                /**
                 *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                 *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                 */

                /*var disqus_config = function () {
                    this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                    this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                };*/

                (function() {  // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');

                    s.src = '//arc-team.disqus.com/embed.js';

                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
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
