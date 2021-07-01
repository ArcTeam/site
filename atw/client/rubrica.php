<?php
require_once("inc/session.php");
?>
<!doctype html>
<html lang="it">
  <head>
    <?php require_once("inc/meta.html"); ?>
    <?php require_once("inc/css.html"); ?>
    <link rel="stylesheet" href="css/rubrica.css">
  </head>
  <body>
    <?php require_once("inc/header.html"); ?>
    <?php require_once("inc/nav.html"); ?>
    <main class="container-fluid pt-5 mt-5">
      <div class="row">
        <div class="col">
          <table class="table">
            <thead>
              <tr>
                <th>tipologia</th>
                <th>utente</th>
                <th>email</th>
                <th data-breakpoints="lg">mobile</th>
                <th data-breakpoints="lg">telefono</th>
                <th data-breakpoints="lg">indirizzo</th>
                <th data-breakpoints="lg">cod.fisc.</th>
                <th data-breakpoints="lg">P.IVA</th>
                <th data-breakpoints="lg">web</th>
                <th data-breakpoints="lg">note</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
             <tr class="footable-paging">
              <td colspan="10">
               <div class="footable-pagination-wrapper"></div>
              </td>
             </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="card-list card-columns mt-2"></div>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="js/rubrica.js" charset="utf-8"></script>
  </body>
</html>
