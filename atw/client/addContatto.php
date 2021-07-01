<?php
  require_once("inc/session.php");
?>
<!doctype html>
<html lang="it">
  <head>
    <?php require_once("inc/meta.html"); ?>
    <?php require_once("inc/css.html"); ?>
  </head>
  <body>
    <?php require_once("inc/header.html"); ?>
    <?php require_once("inc/nav.html"); ?>
    <?php require_once("inc/toast.html"); ?>
    <main class="container pt-5 mt-5">
      <form class="form bg-white py-5 px-3 border rounded shadow" id="formRubrica">
        <div class="form-row">
          <div class="col border-bottom mb-3">
            <h4 class="d-block m-0">Aggiungi un nuovo contatto in rubrica</h4>
            <small class="text-danger">i campi in rosso sono obbligatori</small>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col-md-4">
            <label for="classe" class="text-danger">Classe</label>
            <select class="form-control" id="classe" name="classe" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="nome" class="text-danger">Nome soggetto</label>
            <input type="text" id="nome" class="form-control" name="nome" value="" placeholder="Cognome Nome soggetto" required>
          </div>
          <div class="col-md-4">
            <label for="email" class="text-danger">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="" placeholder="Inserisci una email valida" required>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col">
            <button type="button" class="btn btn-info w-100" data-toggle="collapse" data-target="#campi-secondari" aria-pressed="false" autocomplete="off"><span class="collapseBtn">nascondi</span> campi non obbligatori</button>
          </div>
        </div>
        <div class="collapse" id="campi-secondari">
          <div class="form-row mb-3">
            <div class="col-md-3">
              <label for="mobile">Cellulare</label>
              <input type="text" id="mobile" name="mobile" class="form-control" value="" placeholder="numero cellulare">
            </div>
            <div class="col-md-3">
              <label for="telefono">Telefono</label>
              <input type="text" name="telefono" id="telefono" class="form-control" value="" placeholder="telefono fisso">
            </div>
            <div class="col-md-3">
              <label for="cod_fisc">Codice fiscale</label>
              <input type="text" name="cod_fisc" id="cod_fisc" class="form-control" value="" pattern="/^(?:[A-Z][AEIOU][AEIOUX]|[B-DF-HJ-NP-TV-Z]{2}[A-Z]){2}(?:[\dLMNP-V]{2}(?:[A-EHLMPR-T](?:[04LQ][1-9MNP-V]|[15MR][\dLMNP-V]|[26NS][0-8LMNP-U])|[DHPS][37PT][0L]|[ACELMRT][37PT][01LM]|[AC-EHLMPR-T][26NS][9V])|(?:[02468LNQSU][048LQU]|[13579MPRTV][26NS])B[26NS][9V])(?:[A-MZ][1-9MNP-V][\dLMNP-V]{2}|[A-M][0L](?:[1-9MNP-V][\dLMNP-V]|[0L][1-9MNP-V]))[A-Z]$/i" placeholder="codice fiscale">
            </div>
            <div class="col-md-3">
              <label for="piva"><i class="fas fa-info-circle" data-toggle="tooltip" title="inserire la partita IVA in formato numerico, senza le lettere corrispondenti alla nazione"></i> Partita IVA</label>
              <input type="text" size="11" pattern="/^[0-9]{11}$/" id="piva" name="piva" class="form-control" value="" placeholder="partita IVA">
            </div>
          </div>
          <div class="form-row mb-3">
            <div class="col-md-6">
              <label for="indirizzo">Indirizzo</label>
              <input type="text" id="indirizzo" name="indirizzo" class="form-control" value="" placeholder="es. Via Rossi 13, 38023, Cles (TN)">
              <label for="web" class="mt-3">Risorsa web</label>
              <input type="url" id="web" class="form-control" name="web" value="" placeholder="http(s)://url....">
            </div>
            <div class="col-md-6">
              <label for="note">Note</label>
              <textarea name="note" id="note" class="form-control" rows="4" width="100%" placeholder="aggiungi un commento al contatto"></textarea>
            </div>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col">
          </div>
        </div>
        <div class="form-row">
          <div class="col">
            <button type="submit" class="btn btn-primary" name="submit">salva dati</button>
          </div>
        </div>
      </form>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="js/addRubrica.js" charset="utf-8"></script>
  </body>
</html>
