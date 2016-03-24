<?php
require("inc/db.php");
//select tipo
$tipoq="select * from liste.tipo_utente order by definizione asc;";
$tipoexec = pg_query($connection,$tipoq);
?>
<!DOCTYPE html>
<html>
  <head>
      <?php require("inc/meta.php"); ?>
  </head>
  <body>
    <header id="main"><?php require("inc/header.php"); ?></header>
    <section id="main">

    </section>

  </body>
</html>
