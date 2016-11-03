<?php
session_start();
require("inc/db.php");
if(isset($_GET['p'])){
    $header = 'Modifica scheda lavoro';
    $p="select * from main.lavoro where id=".$_GET['p'];
    $pr = pg_query($connection,$p);
    $post = pg_fetch_array($pr);
    $id = $_GET['p'];
    //tag presenti
    $tagpres = "select tags from main.tags where rec = ".$_GET['p']." and tab = 3;";
    $tagpresq = pg_query($connection,$tagpres);
    $tagpresres = pg_fetch_array($tagpresq);
    if (!$tagpresres) {
        $tagpresList = 0;
    }else {
        $tags = explode(',',$tagpresres['tags']);
        asort($tags);
        $tagpresList = json_encode($tags);
    }
}else{
    $tagpresList = 0;
    $id = 0;
    $header = 'Inserisci scheda lavoro';
}

//lista tag
$t = "select tag from liste.tag order by tag asc;";
$tq = pg_query($connection, $t);
$tag = array();
while ($obj = pg_fetch_array($tq)) { $tag[] = $obj['tag'];}
$tagList = json_encode($tag);

//lista anni
for($i=2000;$i<=date('Y');$i++) {$anni[] = $i;}
rsort($anni);
foreach($anni as $value) {$anniList .= '<option value="'.$value.'">'.$value.'</option>';}

//lista tipo lavoro
$l="select * from liste.cat order by categoria asc;";
$lq = pg_query($connection,$l);
while($t=pg_fetch_array($lq)){ $tipo .= "<option value='".$t['id']."'>".$t['categoria']."</option>";}
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/postForm.css" rel="stylesheet" media="screen" />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <style>
        input[name='lavoro'], textarea[name='descr']{width:100%;}
        textarea[name='descr']{height:300px;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
        <section class="form ckform">
            <header><?php echo $header; ?></header>
            <nav class="toolbar">
                <li><a href="lavori.php" title="Torna all'archivio lavori"><i class="fa fa-list" aria-hidden="true"></i> lavori</a></li>
                <?php if(isset($_GET['p'])){?><li><a href="lavoro.php?l=<?php echo $_GET['p'];?>" title="Torna alla scheda progetto"><i class="fa fa-file-text" aria-hidden="true"></i> scheda progetto</a></li><?php } ?>
            </nav>
            <div>
                <form name="postForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                    <input type="hidden" name="get" value="<?php echo $id; ?>" >
                    <input type="hidden" name="tipo" value="<?php echo $post['tipo']; ?>" >
                    <input type="hidden" name="anno" value="<?php echo $post['anno']; ?>" >
                    <div class="rowButton"><input type="text" name="tags" placeholder="aggiungi tag" class="tm-input" ></div>
                    <div class="rowButton">
                        <select name="tipo">
                            <option value="" disabled selected >scegli tipo lavoro</option>
                            <?php echo $tipo; ?>
                        </select>
                        <select name="anno">
                            <option value="" disabled selected >anno inizio lavoro</option>
                            <?php echo $anniList; ?>
                        </select>
                    </div>
                    <div class="rowButton"><input type="text" name="lavoro" placeholder="Inserisci il nome" value="<?php echo $post['nome']; ?>" ></div>
                    <div class="rowButton"><textarea name="descr" id="descr" placeholder="Aggiungi una descrizione anche breve"><?php echo $post['descrizione']; ?></textarea></div>
                    <div class="rowButton">
                        <input type="submit" name="submit" value="salva lavoro">
                        <input type="button" name="annulla" value="annulla operazione">
                    </div>
                    <div class="rowButton" id="msg">
                        <span></span>
                        <div class="hide">
                            <a href="index.php" class="button" title="torna alla home page">torna alla home</a>
                            <a href="lavori.php" class="button" title="elenco lavori">elenco lavori</a>
                            <a href="" class="button" id="linkLavori" title="visualizza scheda lavoro">visualizza scheda lavoro</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="lib/jquery-ui-1.14.min.js"></script>
    <!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
    <script src="lib/tag/tagmanager.js"></script>
    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            var dataList = <?php echo $tagList; ?>;
            var prefilled,script, p, t, a, link;
            p = $("input[name=get]").val();
            if(p > 0){
                var tagpresarr = <?php echo $tagpresList; ?>;
                var tags = [];
                if (tagpresarr == 0) {
                    prefilled='';
                }else {
                    $.each(tagpresarr, function(k,v) { tags.push(v); });
                    prefilled=tags;
                }
                t = $("input[name='tipo']").val();
                a = $("input[name='anno']").val();
                $("select[name='tipo']  option[value="+t+"]").prop("selected", true);
                $("select[name='anno']  option[value="+a+"]").prop("selected", true);
                script = 'lavoroMod.php';
                link = 'lavoro.php?l='+p;
            }else{
                prefilled='';
                script = 'lavoroAdd.php';
                link = 'lavori.php';
            }
            $(".tm-input").tagsManager({
                prefilled: prefilled,
                hiddenTagListName: 'tagList',
                hiddenTagListId: 'tagList',
                deleteTagsOnBackspace: false,
                AjaxPush: 'inc/addTag.php',
            })
            .autocomplete({source:dataList});
            $("input[name='annulla']").on("click", function(){window.location.href=link;});
            var form = $("form[name=postForm]");
            form.submit(function(e){
                e.preventDefault();
                var tag = $("input[name=tagList]").val();
                var anno = $("select[name=anno]").val();
                var tipo = $("select[name=tipo]").val();
                var nome = $("input[name=lavoro]").val();
                var descr = $("textarea[name=descr]").val();
                if(!tag){$("#msg span").text("Devi inserire almeno una tag!");}
                else if(!tipo){$("#msg span").text("Devi selezionare una tipologia di lavoro dall'elenco!");}
                else if(!anno){$("#msg span").text("Devi selezionare l'anno in cui è partito il lavoro!");}
                else if(!nome && !descr){$("#msg span").text("Devi inserire un nome e una descrizione, anche breve!");}
                else if(!nome){$("#msg span").text("Devi inserire un nome per identificare il lavoro!");}
                else if(!descr){$("#msg span").text("Devi inserire una descrizione, anche breve!");}
                else{
                    //console.log(tag);return false;
                    $.ajax({
                        url: 'inc/'+script,
                        type: 'POST',
                        data: {id:p, tag:tag, tipo:tipo, anno:anno, nome:nome, descr:descr},
                        success: function(data){
                            if(data.indexOf("errore") !== -1){
                                $("#msg span").text(data);
                            }else{
                                $("#msg span").text("");
                                $("input[type=submit]").hide();
                                $("#msg div").fadeIn('fast');
                                $("#linkLavori").attr("href", "lavoro.php?l="+data);
                            }
                        }
                    });
                }
            });
        });
    </script>
  </body>
</html>
