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
      <form class="form bg-white py-5 px-3 border rounded shadow" id="formOre">
        <input type="hidden" name="scavo" value="<?php echo $_POST['id']; ?>">
        <div class="form-row">
          <div class="col border-bottom mb-3">
            <h3 class="text-center"><?php echo $_POST['name']; ?></h3>
            <h4 class="d-block m-0 text-center">Aggiungi ore</h4>
            <small class="text-danger">tutti i campi sono obbligatori</small>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col-md-2">
            <label for="ore">Ore</label>
            <input type="number" id="ore" class="form-control" name="ore" placeholder="ore effettuate" step="0.5" min="1" max="15" value="8" required>
          </div>
          <div class="col-md-4">
            <label for="data">Data</label>
            <input type="date" id="data" class="form-control" name="data" min="2005-01-01" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="col-md-6">
            <label for="scavo">Operatore</label>
            <select class="form-control" id="compilatore" name="compilatore" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
            <input type="hidden" name="usrAct" value="<?php echo $_SESSION['id']; ?>">
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
    <script src="js/addOre.js" charset="utf-8"></script>
  </body>
</html>
