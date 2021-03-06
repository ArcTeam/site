<?php
session_start();
require("inc/db.php");
require("class/funzioni.php");
require("inc/delRecDiv.php");
$a = "SELECT p.id, p.titolo, p.testo, l.data, r.utente FROM main.log l, main.usr u, main.post p, main.rubrica r WHERE l.record = p.id AND l.utente = u.id AND u.rubrica = r.id AND l.tabella = 'post' AND l.operazione = 'I' AND p.id =".$_GET['p'];
$b = pg_query($connection,$a);
$p = pg_fetch_array($b);
$data = explode(" ",$p['data']);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require("inc/meta.php"); ?>
        <link href="css/postView.css" rel="stylesheet" media="screen" />
    </head>
    <body>
        <input type="hidden" name="post" value="<?php echo $_GET['p']; ?>">
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="form ckform post">
                <header id="titolo"><?php echo $p['titolo']; ?></header>
                <nav class="toolbar">
                    <ul>
                        <li><a href="post.php" title="Torna all'archivio lavori"><i class="fa fa-list" aria-hidden="true"></i> post</a></li>
                        <?php if(isset($_SESSION['id'])){?>
                        <li><a href="postForm.php?t=1&p=<?php echo $_GET['p'];?>" id="mod"><i class="fa fa-pencil" aria-hidden="true"></i> modifica post</a></li>
                        <li><a href="#" id="del" class="delRecord prevent"><i class="fa fa-times" aria-hidden="true"></i> elimina post</a></li>
                        <?php } ?>
                    </ul>
                </nav>
                <article><?php echo $p['testo']; ?></article>
            </section>
            <section class="form ckform metadata">
                <header>INFO POST</header>
                <div id="meta">Post scritto da <strong><?php echo $p['utente']; ?></strong> il <strong><?php echo $data[0]; ?></strong></div>
                <header>TAGS</header>
                <div id="tag"><?php echo tag($_GET['p'],1);; ?></div>
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
            <section id="delRec">
                <div class="warning" id="deleteMsg"><span></span></div>
                <div class="rowButton" id="deleteButton">
                    <button type="button" name="confermaDel" class="button error ">conferma</button>
                    <button type="button" name="chiudiDel" class="button base ">annulla</button>
                </div>
            </section>
        </div>
        <footer><?php require("inc/footer.php"); ?></footer>
        <script src="lib/jquery-1.12.0.min.js"></script>
        <script src="ckeditor/ckeditor.js"></script>
        <script src="ckeditor/adapters/jquery.js"></script>
        <script src="script/funzioni.js"></script>
        <script>
        $(document).ready(function(){
            var post = $("input[name=post]").val();
            $("a#post").addClass('actPost prevent');
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
            $(".delRecord").on("click",function(){ delRec("post", "id", post, "post.php"); });
        });
        </script>
    </body>
</html>
