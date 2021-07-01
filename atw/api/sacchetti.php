<?php
require_once("models/sacchetti.class.php");
$scavo = isset($_POST['dati']['scavo']) ? $_POST['dati']['scavo'] : null;
$tipologia = isset($_POST['dati']['tipoId']) ? $_POST['dati']['tipoId'] : null;
$obj = new Sacchetto($scavo, $tipologia);
$funzione = $_POST['dati']['trigger'];
unset($_POST['dati']['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($obj);
  echo $trigger;
}

function scavoConfig($obj){return json_encode($obj->config);}
function numeroLiberoSacchetto($obj){return json_encode($obj->numeroLiberoSacchetto());}
function usList($obj){return json_encode($obj->usList());}
function addReperto($obj){return json_encode($obj->addReperto($_POST['dati']));}
function repertiPie($lavoro){return json_encode($lavoro->repertiPie($_POST['dati']['id']));}
function getReperti($lavoro){return json_encode($lavoro->getReperti($_POST['dati']['id']));}
?>
