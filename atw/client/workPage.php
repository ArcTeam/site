<?php
  require_once("inc/session.php");
?>
<!doctype html>
<html lang="it">
  <head>
    <?php require_once("inc/meta.html"); ?>
    <?php require_once("inc/css.html"); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <link rel="stylesheet" href="css/workPage.css">
  </head>
  <body>
    <input type="hidden" name="work" value="<?php echo $_POST['id']; ?>">
    <input type="hidden" name="name" value="">
    <?php require_once("inc/header.html"); ?>
    <?php require_once("inc/nav.html"); ?>
    <?php require_once("inc/modal.html"); ?>
    <div class="map" id="map">
      <div class="nomeScavo text-dark"></div>
    </div>
    <div class="">
      <nav id="workPageTool" class="p-2 mb-3 bg-dark text-white">
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-dark text-white dropdown-toggle toggle-scavo" data-toggle="dropdown">scavo</button>
          <div class="dropdown-menu">
            <button class="dropdown-item" type="button" data-form="modScavo">
              <i class="fas fa-edit"></i>
              modifica dati
            </button>
            <button class="dropdown-item text-success" type="button" data-form="chiudiScavo">
              <i class="fas fa-check"></i>
              chiudi scavo
            </button>
            <button class="dropdown-item text-danger" type="button" data-form="delScavo">
              <i class="fas fa-exclamation-triangle"></i>
              elimina scavo
            </button>
          </div>
        </div>
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-dark text-white dropdown-toggle toggle-schede" data-toggle="dropdown">
            <span class="d-none d-lg-inline">compila </span>
            scheda
          </button>
          <div class="dropdown-menu dropdown-inserisci">
            <button class="dropdown-item" type="button" data-form="addUs" data-tipo="1">US positiva</button>
            <button class="dropdown-item" type="button" data-form="addUs" data-tipo="2">US negativa</button>
            <!-- <button class="dropdown-item" type="button" data-form="addUs" data-tipo="3">US tafonomica</button> -->
          </div>
        </div>
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-dark text-white dropdown-toggle toggle-reperti" data-toggle="dropdown">
            <span class="d-none d-lg-inline">nuovo </span>
            sacchetto
          </button>
          <div class="dropdown-menu dropdown-inserisci">
            <button class="dropdown-item" type="button" data-form="addReperto" data-tipo="rr">reperto registrato</button>
            <button class="dropdown-item" type="button" data-form="addReperto" data-tipo="cp">campione</button>
            <button class="dropdown-item" type="button" data-form="addReperto" data-tipo="sg">sacchetto generico</button>
          </div>
        </div>
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-dark text-white dropdown-toggle toggle-tipologia" data-toggle="dropdown">
            <span class="d-none d-lg-inline">aggiungi </span>
            info
          </button>
          <div class="dropdown-menu dropdown-inserisci">
            <button class="dropdown-item" type="button" data-form="addOre">ore</button>
            <button class="dropdown-item" type="button" data-form="addDiario">diario</button>
            <button class="dropdown-item" type="button" data-form="addFotopiano">fotopiano</button>
          </div>
        </div>
      </nav>
    </div>
    <main class="container-fluid">

        <div class="card-columns px-3">
          <div class="card mb-3" id="infoGenerali">
            <div class="card-header bg-primary text-white">
              <h4>info generali</h4>
            </div>
            <ul class="list-group list-group-flush list-info">
              <li class="list-group-item">
                <span class="list-key">Comune</span>
                <span class="list-value" id="comune"></span>
              </li>
              <li class="list-group-item">
                <span class="list-key">Localizzazione</span>
                <span class="list-value" id="localizzazione"></span>
              </li>
              <li class="list-group-item">
                <span class="list-key">Nome</span>
                <span class="list-value" id="nome"></span>
              </li>
              <li class="list-group-item">
                <span class="list-key">Sigla scavo</span>
                <span class="list-value" id="sigla"></span>
              </li>
              <li class="list-group-item">
                <span class="list-key">Data inizio</span>
                <span class="list-value" id="inizio"></span>
              </li>
              <li class="list-group-item">
                <span class="list-key">Descrizione</span>
                <span class="list-value" id="descrizione"></span>
              </li>
              <li class="list-group-item">
                <span class="list-key">Ore stimate</span>
                <span class="list-value" id="ore"></span>
              </li>
              <li class="list-group-item">
                <span class="list-key">Direttore scavo</span>
                <span class="list-value" id="direttore"></span>
              </li>
              <li class="list-group-item statoLavoroItem">
                <span id="statoLavoro"></span>
              </li>
            </ul>
          </div>

          <div class="card mb-3" id="attivita">
            <div class="card-header bg-secondary text-white">
              <h6>Diario giornaliero</h6>
            </div>
            <ul class="list-group list-group-flush list-diario list-scroll"></ul>
          </div>

          <div class="card mb-3" id="ore">
            <div class="card-header bg-secondary text-white">
              <h6>Riepilogo ore <span class="float-right badge badge-light totOre"></span></h6>
            </div>
            <div class="point">
              <svg viewbox="0 0 100 100" width="100%" height="300">
                <circle class="bgRound" cx="50" cy="50" r="40"/>
                <circle class="round" cx="-50" cy="50" r="40"/>
                <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" class="svg-text"></text>
              </svg>
            </div>
            <div class="py-3 px-2">
              <button type="button" class="btn btn-sm btn-outline-info w-100 my-1 toggleList" data-toggle="collapse" data-target="#listaOre"></button>
            </div>
            <div id="listaOre" class="details list-scroll collapse">

            </div>
          </div>

          <div class="card mb-3" id="fotopiani">
            <div class="card-header bg-secondary text-white">
              <h6>Elenco fotopiani<span class="float-right badge badge-light" id="totFotopiani"></span></h6>
            </div>
            <div class="py-3 px-2">
              <div class="progress" style="height:50px;">
                <div class="progress-bar bg-danger noEl" role="progressbar" aria-valuemin="0" aria-valuemax="100"><span id="daElaborareTot"></span> da elaborare</div>
                <div class="progress-bar bg-success el" role="progressbar" aria-valuemin="0" aria-valuemax="100"><span id="elaboratiTot"></span> elaborati</div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-info w-100 my-1 toggleList" data-toggle="collapse" data-target="#wrapListFotopiani"></button>
            </div>
            <div id="wrapListFotopiani" class="collapse">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-nonEl-tab" data-toggle="tab" href="#nonElList" role="tab" aria-controls="nonElList" aria-selected="true">Non elaborati</a>
                  <a class="nav-item nav-link" id="nav-el-tab" data-toggle="tab" href="#elList" role="tab" aria-controls="elList" aria-selected="false">Elaborati</a>
                </div>
              </nav>
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nonElList" role="tabpanel" aria-labelledby="nav-nonEl-tab">
                  <div class="wrapList list-scroll">
                    <div class="list-fotopiani-daFare"></div>
                  </div>
                </div>
                <div class="tab-pane fade" id="elList" role="tabpanel" aria-labelledby="nav-el-tab">
                  <div class="wrapList list-scroll">
                    <div class="list-fotopiani-fatti"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card" id="us">
            <div class="card-header bg-secondary text-white">
              <h6>Elenco Unità stratigrafiche<span class="float-right badge badge-light" id="totUs"></span></h6>
            </div>
            <div class="py-3 px-2">
              <div class="progress" style="height:50px;">
                <div class="progress-bar bg-danger usAperte" role="progressbar" aria-valuemin="0" aria-valuemax="100"><span id="usAperteTot"></span> in lavorazione</div>
                <div class="progress-bar bg-success usChiuse" role="progressbar" aria-valuemin="0" aria-valuemax="100"><span id="usChiuseTot"></span> schede chiuse</div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-info w-100 my-1 toggleList" data-toggle="collapse" data-target="#wrapListUs"></button>
            </div>
            <div id="wrapListUs" class="collapse">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-usAperte-tab" data-toggle="tab" href="#usAperteList" role="tab" aria-controls="usAperteList" aria-selected="true">In lavorazione</a>
                  <a class="nav-item nav-link" id="nav-usChiuse-tab" data-toggle="tab" href="#usChiuseList" role="tab" aria-controls="usChiuseList" aria-selected="false">Schede chiuse</a>
                </div>
              </nav>
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="usAperteList" role="tabpanel" aria-labelledby="nav-usAperte-tab">
                  <div class="list-scroll">
                    <div class="list-us-aperte"></div>
                  </div>
                </div>
                <div class="tab-pane fade" id="usChiuseList" role="tabpanel" aria-labelledby="nav-usChiuse-tab">
                  <div class="wrapList list-scroll">
                    <div class="list-us-chiuse"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card mb-3" id="reperti">
            <div class="card-header bg-secondary text-white">
              <h6>Elenco reperti <span class="float-right badge badge-light" id="totReperti"></span></h6>
            </div>
            <canvas id="findPie" class="d-block mx-auto"></canvas>
            <div class="card-body">
              <button type="button" class="btn btn-sm btn-outline-info w-100 my-1 toggleList" data-toggle="collapse" data-target="#wrapListReperti"></button>
            </div>
            <div class="collapse" id="wrapListReperti">
              <div class="wrapList list-scroll">
                <div class="list-reperti"></div>
              </div>
            </div>
          </div>
        </div>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="js/workPage.js" charset="utf-8"></script>
  </body>
</html>
