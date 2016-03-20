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
        section.formContent{ width: 80%;}
        input[type=text],textarea{width:90%;}
        textarea{height:400px;}
      </style>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <div id="mainWrap">
      <section class="formContent">
        <header>Inserisci un nuovo post</header>
        <form name="postForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
          <div class="rowButton"><input type="text" name="titolo" placeholder="Inserisci il titolo del post" required ></div>
          <div class="rowButton"><textarea name="testo" id="testo" placeholder="Inserisci i contenuti del nuovo post" required ></textarea></div>
          <div class="rowButton"><input type="submit" name="submit" value="salva post"></div>
          <span id="msg"><?php echo $msg; ?></span>
        </form>
      </section>
    </div>
    <footer><?php require("inc/footer.php"); ?></footer>
    <script src="lib/jquery-1.12.0.min.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="script/funzioni.js"></script>
    <script>
        $(document).ready(function(){
            CKEDITOR.replace( 'testo' );
        });
    </script>
  </body>
</html>
