<?php
  require_once("inc/session.php");
?>
<!doctype html>
<html lang="it">
  <head>
    <?php require_once("inc/meta.html"); ?>
    <?php require_once("inc/css.html"); ?>
    <style media="screen">
      label{text-transform: uppercase;font-size: 12px; margin-bottom: 0;}
      legend{font-size: 16px;}
    </style>
  </head>
  <body>
    <input type="hidden" name="usId" value="<?php echo $_POST['usId']; ?>">
    <?php require_once("inc/header.html"); ?>
    <?php require_once("inc/nav.html"); ?>
    <?php require_once("inc/toast.html"); ?>
    <main class="container pt-5 my-5">
      <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-lightbulb d-inline-block" style="width:30px;vertical-align:top"></i><span class="d-inline-block" style="width:calc(100% - 30px);">il numero di US ti verrà comunicato al salvataggio del record</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <nav class="nav mb-2">
        <a href="#" class="nav-link btn btn-outline-dark btn-sm p-1 mr-1 backBtn" id="backBtn" data-toggle="tooltip" title="annulla operazione"><i class="fas fa-arrow-left"></i> <span></span> </a>
        <a href="#" class="nav-link btn btn-outline-danger btn-sm p-1 printBtn" data-toggle="tooltip" title="momentaneamente disabilitato" id="printBtn" disabled><i class="fas fa-print"></i> stampa</a>
      </nav>
      <form class="form bg-white py-5 px-3 border rounded shadow" id="formUs">
        <div class="form-row">
          <div class="col border-bottom mb-3">
            <h4 class="d-block m-0" id="titleForm"></h4>
            <small class="text-danger font-weight-bold">Campi obbligatori</small>
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col-md-3">
            <label for="scavo" class="text-danger font-weight-bold"><i class="fas fa-question-circle" data-toggle="tooltip" title="La lista mostra gli scavi aperti.<br/>Se lo scavo non è presente va prima aggiunto"></i> Scavo</label>
            <select class="form-control form-control-sm" id="scavo" name="scavo" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
            <input type="hidden" name="postId" value="<?php echo $_POST['id']; ?>">
          </div>
          <div class="col-md-3">
            <label for="tipo" class="text-danger font-weight-bold">Tipo US</label>
            <select class="form-control form-control-sm" id="tipo" name="tipo" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
            <input type="hidden" name="tipoUS" value="<?php echo $_POST['tipo']; ?>">
          </div>
          <div class="col-md-3">
            <label for="compilazione" class="text-danger font-weight-bold">Data compilazione</label>
            <input type="date" id="compilazione" class="form-control form-control-sm" name="compilazione" min="2005-01-01" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="col-md-3">
            <label for="compilatore" class="text-danger font-weight-bold">Responsabile</label>
            <select class="form-control form-control-sm" id="compilatore" name="compilatore" required>
              <option value="" selected disabled>--select from list--</option>
            </select>
            <input type="hidden" name="usrAct" value="<?php echo $_SESSION['id']; ?>">
          </div>
        </div>
        <div class="form-row mb-3">
          <div class="col">
            <label for="definizione" class="text-danger font-weight-bold" >Definizione e posizione</label>
            <textarea id="definizione" name="definizione" class="form-control form-control-sm" rows="3" placeholder="inserisci una breve definizione" required></textarea>
          </div>
        </div>
        <div class="hiddenField">
          <div class="form-row mb-3">
            <div class="col">
              <button type="button" class="btn btn-info w-100" data-toggle="collapse" data-target="#campi-secondari" aria-pressed="false" autocomplete="off"><span class="collapseBtn">nascondi</span> campi non obbligatori</button>
            </div>
          </div>
          <div class="collapse" id="campi-secondari">
            <div class="form-row mb-3">
              <div class="col">
                <div class="alert alert-warning">
                  <i class="fas fa-lightbulb d-inline-block" style="width:30px;vertical-align:top"></i><span class="d-inline-block" style="width:calc(100% - 30px);">i seguenti campi non sono obbligatori in questa fase ma alcuni di essi dovranno comunque essere compilati per poter chiudere la scheda</span>
                </div>
              </div>
            </div>
            <fieldset class="border p-3 mb-3">
              <legend class="w-auto p-1 border bg-light rounded">Info area</legend>
              <div class="form-row">
                <div class="col-md-2">
                  <label for="area">Area</label>
                  <input type="text" class="form-control form-control-sm all field" id="area" name="area" value="">
                </div>
                <div class="col-md-2">
                  <label for="saggio">Saggio</label>
                  <input type="text" class="form-control form-control-sm all field" id="saggio" name="saggio" value="">
                </div>
                <div class="col-md-2">
                  <label for="settore">Settore</label>
                  <input type="text" class="form-control form-control-sm all field" id="settore" name="settore" value="">
                </div>
                <div class="col-md-2">
                  <label for="ambiente">Ambiente</label>
                  <input type="text" class="form-control form-control-sm all field" id="ambiente" name="ambiente" value="">
                </div>
                <div class="col-md-2">
                  <label for="quadrato">Quadrato</label>
                  <input type="text" class="form-control form-control-sm all field" id="quadrato" name="quadrato" value="">
                </div>
              </div>
            </fieldset>
            <fieldset class="border p-3 mb-3">
              <legend class="w-auto p-1 border bg-light rounded">Caratteristiche US</legend>
              <div class="form-row">
                <div class="col-md-3">
                  <label>Criteri di distinzione</label>
                  <div class="border rounded p-2">
                    <div class="form-check field usp">
                      <input class="form-check-input" type="checkbox" value="consistenza" id="criterio_consistenza" name="criterio">
                      <label class="form-check-label" for="criterio_consistenza">consistenza</label>
                    </div>
                    <div class="form-check field usp">
                      <input class="form-check-input" type="checkbox" value="colore" id="colore" name="criterio">
                      <label class="form-check-label" for="colore">colore</label>
                    </div>
                    <div class="form-check field usp">
                      <input class="form-check-input" type="checkbox" value="contenuto" id="contenuto" name="criterio">
                      <label class="form-check-label" for="contenuto">contenuto</label>
                    </div>
                    <div class="form-check field usn">
                      <input class="form-check-input" type="radio" value="morfologia" id="morfologia" name="criterio">
                      <label class="form-check-label" for="morfologia">morfologia</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <label>Modo di formazione</label>
                  <div class="border rounded p-2">
                    <div class="form-check">
                      <div class="d-inline-block w-50">
                        <input class="form-check-input" type="radio" value="naturale" id="naturale" name="formazione1">
                        <label class="form-check-label" for="naturale">naturale</label>
                      </div>
                      <div class="d-inline-block">
                        <input class="form-check-input" type="radio" value="artificiale" id="artificiale" name="formazione1">
                        <label class="form-check-label" for="artificiale">artificiale</label>
                      </div>
                    </div>
                    <div class="form-check">
                      <div class="d-inline-block w-50">
                        <input class="form-check-input" type="radio" value="sincronico" id="sincronico" name="formazione2">
                        <label class="form-check-label" for="sincronico">sincronico</label>
                      </div>
                      <div class="d-inline-block">
                        <input class="form-check-input" type="radio" value="diacronico" id="diacronico" name="formazione2">
                        <label class="form-check-label" for="diacronico">diacronico</label>
                      </div>
                    </div>
                    <div class="form-check">
                      <div class="d-inline-block w-50">
                        <input class="form-check-input" type="radio" value="intenzionale" id="intenzionale" name="formazione3">
                        <label class="form-check-label" for="intenzionale">intenzionale</label>
                      </div>
                      <div class="d-inline-block">
                        <input class="form-check-input" type="radio" value="non intenzionale" id="non_intenzionale" name="formazione3">
                        <label class="form-check-label" for="non_intenzionale">non intenzionale</label>
                      </div>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="non determinabile" id="non_determinabile" name="formazione4">
                      <label class="form-check-label" for="non_determinabile">non determinabile</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 field usp">
                  <label for="consistenza">Consistenza</label>
                  <select class="form-control form-control-sm" id="consistenza" name="consistenza">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
                <div class="col-md-2 field usp  ">
                  <label for="colore">Colore</label>
                  <input type="text" id="colore" class="form-control form-control-sm" name="colore" value="">
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-3">
                  <label for="foto_digitali">Foto digitali</label>
                  <select class="form-control form-control-sm" id="foto_digitali" name="foto_digitali">
                    <option value="true" selected>presenti</option>
                    <option value="false">assenti</option>
                  </select>
                </div>
                <div class="col-md-3 field usp">
                  <label for="modalita">Modalita di scavo</label>
                  <select class="form-control form-control-sm" id="modalita" name="modalita">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="conservazione">Stato di conservazione</label>
                  <select class="form-control form-control-sm" id="conservazione" name="conservazione">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="affidabilita">Affidabilità archeologica</label>
                  <select class="form-control form-control-sm" name="affidabilita" id="affidabilita" value=''>
                    <option value="">--seleziona valore--</option>
                  </select>
                </div>
              </div>
            </fieldset>
            <fieldset class="border p-3 mb-3 field usp">
              <legend class="w-auto p-1 border bg-light rounded">Componenti</legend>
              <div class="form-row">
                <div class="col-md-4">
                  <label for="geologici">Geologici</label>
                  <textarea name="geologici" class="form-control form-control-sm" id="geologici" rows="8"></textarea>
                </div>
                <div class="col-md-4">
                  <label for="organici">Organici</label>
                  <textarea name="organici" class="form-control form-control-sm" id="organici" rows="8"></textarea>
                </div>
                <div class="col-md-4">
                  <label for="artificiali">Artificiali</label>
                  <textarea name="artificiali" class="form-control form-control-sm" id="artificiali" rows="8"></textarea>
                </div>
              </div>
            </fieldset>
            <fieldset class="border p-3 mb-3">
              <legend class="w-auto p-1 border bg-light rounded">Descrizione</legend>
              <div class="form-row">
                <div class="col-12">
                  <label for="descrizione">Descrizione</label>
                  <textarea name="descrizione" id="descrizione" class="form-control form-control-sm" rows="8"></textarea>
                </div>
              </div>
            </fieldset>
            <fieldset class="border p-3 mb-3" id="rapportiSection">
              <legend class="w-auto p-1 border bg-light rounded">Rapporti</legend>
              <div class="form-row" id="alertRapporti">
                <div class="col-12">
                  <div class="alert alert-info">
                    Seleziona il tipo di US per attivare i rapporti specifici
                  </div>
                </div>
              </div>
              <div id="rapportiWrap">
                <div class="form-row">
                  <div class="col">
                    <p class="border-bottom">Contemporanei</p>
                  </div>
                </div>
                <div class="form-row mb-3" id="contemporaneiDiv"></div>
                <div class="form-row">
                  <div class="col">
                    <p class="border-bottom">Anteriori</p>
                  </div>
                </div>
                <div class="form-row mb-3" id="anterioriDiv"></div>
                <div class="form-row">
                  <div class="col">
                    <p class="border-bottom">Posteriori</p>
                  </div>
                </div>
                <div class="form-row mb-3" id="posterioriDiv"></div>
              </div>
            </fieldset>
            <fieldset class="border p-3 mb-3">
              <legend class="w-auto p-1 border bg-light rounded">Altre info</legend>
              <div class="form-row mb-3">
                <div class="col-md-4">
                  <label for="osservazioni">Osservazioni</label>
                  <textarea name="osservazioni" id="osservazioni" class="form-control form-control-sm" rows="6"></textarea>
                </div>
                <div class="col-md-8">
                  <label for="interpretazione">Interpretazione</label>
                  <textarea name="interpretazione" id="interpretazione" class="form-control form-control-sm" rows="6"></textarea>
                </div>
              </div>
              <div class="form-row mb-3">
                <div class="col-md-3 field usp">
                  <label for="elem_datanti">Elementi datanti</label>
                  <textarea class="form-control form-control-sm" name="elem_datanti" id="elem_datanti" rows="3"></textarea>
                </div>
                <div class="col-md-3 field usp">
                  <label for="datazione">Datazione</label>
                  <textarea class="form-control form-control-sm" name="datazione" id="datazione" rows="3"></textarea>
                </div>
                <div class="col-md-3">
                  <label for="periodo">Periodo o fase</label>
                  <textarea class="form-control form-control-sm" name="periodo" id="periodo" rows="3"></textarea>
                </div>
                <div class="col-md-3 field usp">
                  <label for="dati_quantit">Dati quantitativi dei reperti</label>
                  <textarea name="dati_quantit" id="dati_quantit" class="form-control form-control-sm" rows="3"></textarea>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="form-row mt-3">
          <div class="col">
            <button type="submit" class="btn btn-primary" name="submit"></button>
            <a href="#" class="btn btn-outline-secondary backBtn">annulla</a>
          </div>
        </div>
      </form>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="js/addUs.js" charset="utf-8"></script>
  </body>
</html>
