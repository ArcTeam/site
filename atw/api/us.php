<?php
require_once("models/us.class.php");
$usId = isset($_POST['dati']['id']) ? $_POST['dati']['id'] : null;
$usObj = new Us($usId);
$funzione = $_POST['dati']['trigger'];
unset($_POST['dati']['trigger']);
if(isset($funzione) && function_exists($funzione)) {
  $trigger = $funzione($usObj);
  echo $trigger;
}

function usNum($usObj){return $usObj->us;}
function datiUs($usObj){return json_encode($usObj->datiUs());}
function addUs($usObj){return json_encode($usObj->addUs($_POST['dati']));}
function updateUs($usObj){return json_encode($usObj->updateUs($_POST['dati']));}
?>
