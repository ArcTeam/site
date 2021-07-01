<?php
  require_once("inc/session.php");
?>
<!doctype html>
<html lang="it">
  <head>
    <?php require_once("inc/meta.html"); ?>
    <?php require_once("inc/css.html"); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  </head>
  <body>
    <?php require_once("inc/header.html"); ?>
    <?php require_once("inc/nav.html"); ?>
    <?php require_once("inc/toast.html"); ?>
    <main class="container pt-5 mt-5">
      <form class="form bg-white py-5 px-3 border rounded shadow" id="formScavo">
        <div class="form-row mb-3">
          <div class="col border-bottom ">
            <h4 class="d-block m-0">Inserisci un nuovo scavo</h4>
            <small class="text-danger">I campi in rosso sono obbligatori</small>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="nome" class="text-danger"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Nome esteso, es. 'Cles, campi neri'"></i> Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="" placeholder="nome identificativo" required>
          </div>
          <div class="form-group col-md-2">
            <label for="sigla" class="text-danger"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="inserisci la sigla di scavo così come compare sulla lavagnetta e su tutta la documentazione"></i> Sigla scavo</label>
            <input type="text" class="form-control" id="sigla" name="sigla" value="" placeholder="sigla scavo" required>
          </div>
          <div class="col-md-4">
            <label for="direttore" class="text-danger">Direttore scavo</label>
            <select class="form-control" id="direttore" name="direttore" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col">
            <label for="descrizione" class="text-danger">Descrizione</label>
            <textarea name="descrizione" id="descrizione" class="form-control" rows="8" required></textarea>
          </div>
        </div>

        <div class="form-row mb-3">
          <div class="col">
            <button type="button" class="btn btn-info w-100" data-toggle="collapse" data-target="#campi-secondari" aria-pressed="false" autocomplete="off"><span class="collapseBtn">nascondi</span> campi non obbligatori</button>
          </div>
        </div>
        <div class="collapse mb-3" id="campi-secondari">
          <div class="form-row">
            <div class="form-group col">
              <div class="alert alert-warning">
                <i class="fas fa-lightbulb d-inline-block" style="width:30px;vertical-align:top"></i><span class="d-inline-block" style="width:calc(100% - 50px);">i seguenti campi non sono obbligatori in questa fase ma dovranno comunque essere compilati per poter chiudere lo scavo</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="inizio">Inizio lavori</label>
              <input type="date" id="inizio" class="form-control" name="inizio" min="2005-01-01" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group col-md-3">
              <label for="ore">Ore stimate</label>
              <input type="number" id="ore" class="form-control" name="ore" placeholder="ore stimate" step="1" min="0" value="0">
            </div>
            <div class="form-group col-md-3">
              <label for="lon">longitudine (X)</label>
              <input type="number" id="lon" name="lon" class="form-control" min="10" max="13.9" step="0.00001" placeholder="es: 10.12345" value="">
            </div>
            <div class="form-group col-md-3">
              <label for="lat">latitudine (Y)</label>
              <input type="number" id="lat" name="lat" class="form-control" min="45" max="46.9" step="0.00001" placeholder="es: 45.12345" value="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="comuneLabel"><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="per resettare il campo usa il pulsante accanto"></i> Comune</label>
              <div class="input-group mb-3">
                <input type="text" id="comuneLabel" name="comuneLabel" class="form-control" value="" placeholder="Inserisci il nome del Comune" aria-label="Inserisci il nome del Comune" aria-describedby="comune-reset">
                <input type="hidden" id="comune" name="comune" class="form-control" value="">
                <div class="input-group-append">
                  <button class="btn btn-secondary" type="button" id="comune-reset"><i class="fas fa-times"></i></button>
                </div>
              </div>

            </div>
            <div class="form-group col-md-8">
              <label for="localizzazione">Localizzazione</label>
              <input type="text" id="localizzazione" name="localizzazione" class="form-control" value="">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <div class="alert alert-warning">
                <i class="fas fa-lightbulb d-inline-block" style="width:30px;vertical-align:top"></i><span class="d-inline-block" style="width:calc(100% - 50px);">Imposta i numeri di partenza per le varie sezioni della documentazione o lascia i numeri di default.<br>I valori possono essere modificati anche in seguito dalla scheda scavo.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-3">
              <label for="inventario">Inventario</label>
              <input type="number" class="form-control form-control-sm" id="inventario" name="inventario" value="1" min="1" step="1">
            </div>
            <div class="form-group col-3">
              <label for="us">us</label>
              <input type="number" class="form-control form-control-sm" id="us" name="us" value="1" min="1" step="1">
            </div>
            <div class="form-group col-3">
              <label for="reperto">reperto</label>
              <input type="number" class="form-control form-control-sm" id="reperto" name="reperto" value="1" min="1" step="1">
            </div>
            <div class="form-group col-3">
              <label for="campione">campione</label>
              <input type="number" class="form-control form-control-sm" id="campione" name="campione" value="1" min="1" step="1">
            </div>
            <div class="form-group col-3">
              <label for="sacchetto">sacchetto</label>
              <input type="number" class="form-control form-control-sm" id="sacchetto" name="sacchetto" value="1" min="1" step="1">
            </div>
            <div class="form-group col-3">
              <label for="fotopiano">fotopiano</label>
              <input type="number" class="form-control form-control-sm" id="fotopiano" name="fotopiano" value="1" min="1" step="1">
            </div>
            <div class="form-group col-3">
              <label for="tomba">tomba</label>
              <input type="number" class="form-control form-control-sm" id="tomba" name="tomba" value="1" min="1" step="1">
            </div>
          </div>

        </div>

        <div class="form-row">
          <div class="form-group col">
            <button type="submit" class="btn btn-primary" name="submit">salva dati</button>
          </div>
        </div>
      </form>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="js/addWork.js" charset="utf-8"></script>
  </body>
</html>
