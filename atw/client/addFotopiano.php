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
      <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-lightbulb d-inline-block" style="width:30px;vertical-align:top"></i><span class="d-inline-block" style="width:calc(100% - 30px);">il numero di fotopiano ti verrà comunicato al salvataggio del record</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form bg-white py-5 px-3 border rounded shadow" id="formFotopiano">
        <div class="form-row">
          <div class="col border-bottom mb-3">
            <h4 class="d-block m-0">Registra un nuovo fotopiano</h4>
            <small class="text-danger">i campi in rosso sono obbligatori</small>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col-md-4">
            <label for="scavo" class="text-danger">Scavo</label>
            <select class="form-control" id="scavo" name="scavo" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
            <input type="hidden" name="postId" value="<?php echo $_POST['id']; ?>">
          </div>
          <div class="col-md-4">
            <label for="data" class="text-danger">Data</label>
            <input type="date" id="data" class="form-control" name="data" min="2005-01-01" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="col-md-4">
            <label for="scavo" class="text-danger">Operatore</label>
            <select class="form-control" id="operatore" name="operatore" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
            <input type="hidden" name="usrAct" value="<?php echo $_SESSION['id']; ?>">
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col">
            <label for="us" class="text-danger">US</label>
            <input type="text" id="us" name="us" class="form-control" value="" placeholder="es: us1, us2, us-3 ..." required>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col">
            <label for="note">Note fotopiano</label>
            <textarea id="note" name="note" class="form-control" rows="8" placeholder="inserisci note"></textarea>
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
    <script src="js/addFotopiano.js" charset="utf-8"></script>
  </body>
</html>
