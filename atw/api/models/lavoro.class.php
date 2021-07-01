<?php
session_start();
require("config/db.class.php");
class Lavoro extends Db{
  function __construct(){}

  public function getWork(int $id){
    $sql = "select s.id, s.nome_lungo as nome, s.sigla, inizio, s.fine, s.ore_stimate as tot_ore, s.descrizione, c.name as comune, l.localizzazione, l.lon, l.lat, r.nome as direttore from scavo s inner join rubrica r on s.direttore = r.id left join localizzazione l on l.scavo = s.id left join osm c on l.comune = c.osm_id where s.id = ".$id.";";
    return $this->simple($sql);
  }

  public function getUs(int $id){
    $out=array();
    $sql = "select us.id, us.us, tipo.id id_tipo, tipo.value as tipo, us.definizione, us_info.descrizione, us.compilazione, concat(usr.nome,' ', usr.cognome) as compilatore, us.chiusa from us, us_info, list.tipo_us tipo, users usr where us_info.us = us.id and us.tipo = tipo.id and us.compilatore = usr.id and us.scavo = ".$id." and chiusa = 't' ORDER by us desc;";
    $chiuse = $this->simple($sql);
    $sql = "select us.id, us.us, tipo.id id_tipo, tipo.value as tipo, us.definizione, us_info.descrizione, us.compilazione, concat(usr.nome,' ', usr.cognome) as compilatore, us.chiusa from us, us_info, list.tipo_us tipo, users usr where us_info.us = us.id and us.tipo = tipo.id and us.compilatore = usr.id and us.scavo = ".$id." and chiusa = 'f' ORDER by us desc;";
    $aperte = $this->simple($sql);
    $out['chiuse']=$chiuse;
    $out['aperte']=$aperte;
    return $out;
  }

  public function getOre(int $id = null){
    $filter = $id !== null ? "and o.scavo = ".$id : "";
    $sql = "select o.id, o.data, o.ore, concat(u.cognome,' ',u.nome) as operatore from ore o, users u where o.operatore = u.id ".$filter." order by o.data desc;";
    return $this->simple($sql);
  }

  public function getDiario(int $id = null){
    $filter = $id !== null ? "and d.scavo = ".$id : "";
    $sql = "select d.id, d.data, d.descrizione, concat(u.cognome,' ',u.nome) as operatore from diario d, users u where d.operatore = u.id ".$filter." order by d.data desc;";
    return $this->simple($sql);
  }
  public function setDiario(int $id){
    $sql = "select data, descrizione from diario where id = ".$id." ;";
    return $this->simple($sql);
  }

  public function getFotopiani(int $id = null){
    $output = array();
    $filter = $id !== null ? "and fotopiano.scavo = ".$id : "";
    $sql="select * from fotopiano where elaborato = 'f' ".$filter." order by num_fotopiano asc;";
    $output['da_elaborare'] = $this->simple($sql);
    $sql="select * from fotopiano where elaborato = 't' ".$filter." order by num_fotopiano asc;";
    $output['elaborati'] = $this->simple($sql);
    return $output;
  }

  public function addWork($dati=array()){
    try {
      $this->pdo()->beginTransaction();
      $dati['scavo']['creato_da'] = $_SESSION['id'];
      $scavoId = $this->addScavo($dati['scavo']);
      $dati['config']['scavo']=$scavoId['field'][0];
      $this->addScavoConfig($dati['config']);
      if (isset($dati['localizzazione'])) {
        $dati['localizzazione']['scavo']=$scavoId['field'][0];
        $this->addLocalizzazione($dati['localizzazione']);
      }
      $this->pdo()->commit();
      return array('res'=>true, 'id'=>$scavoId['field'][0]);
    } catch (\PDOException $e) {
      $this->pdo->rollBack();
      return $e->getMessage();
    }
  }

  private function addScavo($dati = array()){
    try {
      $sql = "insert into scavo(nome_lungo, sigla, inizio, ore_stimate, descrizione, direttore, creato_da) values (:nome, :sigla, :inizio, :ore, :descrizione, :direttore, :creato_da) returning id";
      return $this->returning($sql,$dati);
    } catch (\Exception $e) {
      return $e->getMessage();
    }

  }
  private function addScavoConfig($dati=array()){
    try {
      $sql = "insert into scavo_config(scavo, us, inventario, reperto, campione, sacchetto, fotopiano, tomba) values (:scavo, :us, :inventario, :reperto, :campione, :sacchetto, :fotopiano, :tomba)";
      $this->prepared($sql,$dati);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }
  private function addLocalizzazione($dati = array()){
    try {
      $field = array();
      $val = array();
      foreach ($dati as $key => $value) {
        $field[] = $key;
        $val[]= ":".$key;
      }
      $sql = "insert into localizzazione(".implode(", ", $field).") values (".implode(", ", $val).") ";
      $this->prepared($sql,$dati);
    } catch (\Exception $e) {
      throw $e;
    }
  }

  public function updateWork(int $id){}
  public function deleteWork(int $id){}

  public function addOre($dati=array()){
    $sql = "insert into ore(scavo, data, ore, operatore) values (:scavo, :data, :ore, :operatore)";
    return $this->prepared($sql,$dati);
  }
  public function updateOre(int $id){}
  public function deleteOre(int $id){}

  public function addDiario($dati=array()){
    $sql = "insert into diario(scavo, data, operatore, descrizione) values (:scavo, :data, :operatore, :descrizione)";
    return $this->prepared($sql,$dati);
  }
  public function updateDiario(array $dati){
    $sql = "update diario set descrizione = :descrizione where id = :diario";
    return $this->prepared($sql,$dati);
  }
  public function deleteDiario(int $id){}

  public function addFotopiano($dati=array()){
    $sql = "select coalesce(max(num_fotopiano), null, 0) + 1 as num from fotopiano where scavo = ".$dati['scavo'].";";
    $maxNum = $this->simple($sql);
    $dati['num_fotopiano'] = $maxNum[0]['num'];
    $sql = "insert into fotopiano(scavo, data, us, autore, note, num_fotopiano) values (:scavo, :data, :us, :autore, :note, :num_fotopiano) returning num_fotopiano;";
    return $this->returning($sql,$dati);
  }
  public function updateFotopiano(int $id){}
  public function deleteFotopiano(int $id){}

  public function addReperto($dati=array()){
    $sql = "select coalesce(max(reperto), null, 0) + 1 as reperto from reperto where scavo = ".$dati['scavo'].";";
    $maxNum = $this->simple($sql);
    $dati['reperto'] = $maxNum[0]['reperto'];
    $sql = "insert into reperto(scavo, us, reperto, materiale, tipologia, note, data, operatore) values (:scavo, :us, :reperto, :materiale, :tipologia, :note, :data, :operatore) returning reperto;";
    return $this->returning($sql,$dati);
  }



}
?>
