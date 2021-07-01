<?php
require_once("models/lavoro.class.php");
$lavoro = new Lavoro;
$funzione = $_POST['dati']['trigger'];
unset($_POST['dati']['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($lavoro);
  echo $trigger;
}

function getWork($lavoro){return json_encode($lavoro->getWork($_POST['dati']['id']));}
function getUs($lavoro){return json_encode($lavoro->getUs($_POST['dati']['id']));}
function getOre($lavoro){return json_encode($lavoro->getOre($_POST['dati']['id']));}
function getDiario($lavoro){return json_encode($lavoro->getDiario($_POST['dati']['id']));}
function setDiario($lavoro){return json_encode($lavoro->setDiario($_POST['dati']['id']));}
function getFotopiani($lavoro){return json_encode($lavoro->getFotopiani($_POST['dati']['id']));}
function addWork($lavoro){return json_encode($lavoro->addWork($_POST['dati']));}
function addOre($lavoro){return json_encode($lavoro->addOre($_POST['dati']));}
function addDiario($lavoro){return json_encode($lavoro->addDiario($_POST['dati']));}
function updateDiario($lavoro){return json_encode($lavoro->updateDiario($_POST['dati']));}
function addFotopiano($lavoro){return json_encode($lavoro->addFotopiano($_POST['dati']));}

?>
