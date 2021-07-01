<?php
require("config/db.class.php");
class Sacchetto extends Db{
  public $config = array();
  public $tipologia;

  function __construct($scavo = null, $tipologia = null){
    if($scavo){ $this->config = parent::getScavoConfig($scavo); }
    if($tipologia){ $this->tipologia = $tipologia; }
  }

  public function usList(){
    $sql = "select id, us, definizione from us where scavo = ".$this->config['scavo']." order by us desc;";
    return $this->simple($sql);
  }

  public function numeroLiberoSacchetto(){
    $sqlInv = "select coalesce(max(inventario),0) inventario from sacchetto where scavo = ".$this->config['scavo'].";";
    $sqlNum = "select coalesce(max(numero),0) numero from sacchetto where scavo = ".$this->config['scavo']." and tipologia = ".$this->tipologia.";";
    switch ($this->tipologia) {
      case 1: $filter='campione'; break;
      case 2: $filter='reperto'; break;
      case 3: $filter='sacchetto'; break;
    }
    $numInv = $this->simple($sqlInv);
    $numRes = $this->simple($sqlNum);
    $numero = $numRes[0]['numero'] == 0 ? $this->config[$filter] : $numRes[0]['numero'] + 1;
    $inventario = $numInv[0]['inventario'] == 0 ? $this->config['inventario'] : $numInv[0]['inventario'] + 1;
    return array("inventario"=>$inventario, "numero" => $numero);
  }

  public function addReperto($dati = array()){
    $n = $this->numeroLiberoSacchetto();
    $dati['sacchetto']['inventario'] = $n['inventario'];
    $dati['sacchetto']['numero'] = $n['numero'];
    try {
      $this->begin();
      $sacchettoId = $this->nuovoSacchetto($dati['sacchetto']);
      if ($dati['reperto']) {
        $dati['reperto']['sacchetto'] = $sacchettoId['field'][0];
        $this->nuovoReperto($dati['reperto']);
      }
      if ($dati['campione']) {
        $dati['campione']['sacchetto'] = $sacchettoId['field'][0];
        $this->nuovoCampione($dati['campione']);
      }
      $this->commitTransaction();
      return array('res'=>true, 'inventario'=>$n['inventario'], 'numero'=>$n['numero']);
    } catch (\Exception $e) {
      $this->pdo->rollback();
      return $e->getMessage();
    }

  }

  public function updateSacchetto(int $id){}
  public function delSacchetto(int $id){}

  private function nuovoSacchetto($dati = array()){
    $sql = "insert into sacchetto(scavo, tipologia, data, compilatore, us, descrizione, inventario, numero) values (:scavo, :tipologia, :data, :compilatore, :us, :descrizione, :inventario, :numero) returning id;";
    return $this->returning($sql,$dati);
  }
  private function nuovoReperto($dati = array()){
    $sql = "insert into reperto(sacchetto, materiale, tipologia) values (:sacchetto, :materiale, :tipologia)";
    return $this->prepared($sql,$dati);
  }
  private function nuovoCampione($dati = array()){
    $sql = "insert into campione(sacchetto, tipologia) values (:sacchetto, :tipologia)";
    return $this->prepared($sql,$dati);
  }

  public function repertiPie(int $id = null){
    $filter = $id !== null ? "where scavo.id = ".$id : "";
    $sql="SELECT l.value tipologia, count(r.*) AS tot FROM reperto r JOIN list.tipologia l ON r.tipologia = l.id JOIN sacchetto s ON r.sacchetto = s.id JOIN us ON s.us = us.id JOIN scavo ON us.scavo = scavo.id ".$filter." GROUP BY l.value;";
    return $this->simple($sql);
  }
  public function getReperti(int $id = null){
    $dati=[];
    $filter = $id !== null ? "WHERE scavo.id = ".$id : "";
    $sql="SELECT s.id, s.us, s.inventario, s.numero, s.descrizione reperto, m.value materiale, t.value tipologia FROM sacchetto s JOIN reperto r ON r.sacchetto = s.id JOIN list.materiale m ON r.materiale = m.id JOIN list.tipologia t ON r.tipologia = t.id JOIN us ON s.us = us.id JOIN scavo ON us.scavo = scavo.id ".$filter." ORDER BY numero DESC;";
    $dati['reperti'] = $this->simple($sql);
    return $dati;
  }
}

?>
