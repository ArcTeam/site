<?php
  require_once("inc/session.php");
  $title = isset($_POST['diario']) ? 'Modifica' : 'Aggiungi';
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
      <form class="form bg-white py-5 px-3 border rounded shadow" id="formDiario">
        <input type="hidden" name="scavo" value="<?php echo $_POST['id']; ?>">
        <input type="hidden" name="operatore" value="<?php echo $_SESSION['id']; ?>">
        <input type="hidden" name="diario" value="<?php echo $_POST['diario']; ?>">
        <div class="form-row">
          <div class="col border-bottom mb-3">
            <h3 class="text-center"><?php echo $_POST['name']; ?><br/><span id="titleData"></span></h3>
            <h4 class="d-block m-0 text-center"><?php echo $title; ?> diario attività</h4>
            <small class="text-danger">tutti i campi sono obbligatori</small>
          </div>
        </div>
        <div class="form-row mb-3" id="dataDiv">
          <div class="col-md-4">
            <label for="data">Data</label>
            <input type="date" id="data" class="form-control" name="data" min="2005-01-01" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col">
            <label for="descrizione">Descrizione attività</label>
            <textarea id="descrizione" name="descrizione" class="form-control" rows="8" placeholder="inserisci descrizione dettagliata delle attività giornaliere" required></textarea>
          </div>
        </div>
        <div class="form-row">
          <div class="col">
            <button type="submit" class="btn btn-primary" name="submit">salva dati</button>
            <a href="#" class="btn btn-outline-secondary backBtn">annulla</a>
          </div>
        </div>
      </form>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="js/addDiario.js" charset="utf-8"></script>
  </body>
</html>
