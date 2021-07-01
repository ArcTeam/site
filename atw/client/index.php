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
    <main class="container-fluid">
      <div class="navbar navbar-expand-lg navbar-light bg-white mt-5">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <form class="form-inline">
            <div class="dropdown mr-2">
              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuAnno" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">filtra anno</button>
              <div class="dropdown-menu dropdown-anno" aria-labelledby="dropdownMenuAnno">
                <button type="button" class="dropdown-item" name="filter" data-filtro='anno' value="0">tutti gli anni</button>
              </div>
            </div>
            <div class="dropdown mr-2">
              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuComune" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">filtra Comune</button>
              <div class="dropdown-menu dropdown-comune" aria-labelledby="dropdownMenuComune">
                <button type="button" class="dropdown-item" data-filtro='osm_id' name="filter" value="0">tutti i Comuni</button>
                <button type="button" class="dropdown-item" data-filtro='osm_id' name="filter" value="null">da inserire</button>
              </div>
            </div>
            <div class="input-group input-group-sm mr-2">
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-sm">nome</span>
              </div>
              <input type="text" list="scavi" name="filter" data-filtro='nome_lungo' class="form-control filter" placeholder="cerca per nome" autocomplete="off">
              <datalist id="scavi"></datalist>
            </div>
            <div class="input-group input-group-sm mr-2">
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-sm">sigla</span>
              </div>
              <input type="text" list="sigle" name="filter" data-filtro='sigla' class="form-control filter" placeholder="cerca per sigla" autocomplete="off">
              <datalist id="sigle"></datalist>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" name="clearFilter" data-toggle="tooltip" title="pulisci filtri">
                <i class="fas fa-trash-alt"></i>
            </button>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col">
        </div>
      </div>
      <div class="card-list card-columns mt-2"></div>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="js/index.js" charset="utf-8"></script>
  </body>
</html>
