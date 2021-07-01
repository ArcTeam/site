<?php
/**
 *
 */
require("config/db.class.php");
class Us extends Db{
  public $idUs;
  public $us;
  function __construct($id = null){
    if (isset($id) && $id !== null) {
      $this->idUs = $id;
      $this->usNum();
    }
  }
  protected function usNum(){
    $usNum = $this->simple("select us from us where id = ".$this->idUs.";");
    $this->us =  $usNum[0]['us'];
  }
  public function datiUs(){
    $obbligatori = $this->simple("select s.nome_lungo as nome_scavo, us.* from scavo s join us on us.scavo = s.id and us.id = ". $this->idUs . ";");
    $area = $this->simple("select area, saggio, settore, ambiente, quadrato from us_area where us = ". $this->idUs . ";");
    $info = $this->simple("select * from us_info where us = ". $this->idUs . ";");
    $componenti = $this->simple("select * from componenti where us = ". $this->idUs . ";");
    $rapporti = $this->simple("select * from us_rapporti where us = ". $this->idUs . "  order by us2 asc;");
    $out = array("obbligatori" => $obbligatori, "area" => $area[0], "info" => $info[0], "componenti" => $componenti[0], "rapporti" => $rapporti);
    return $out;
  }
  public function addUs($dati=array()){
    //TODO: se è la prima us devi controllare il numero impostato nella tabella scavo_config e partire da lì, altrimenti continua aggiungendo un numero all'ultima US inserita
    $sql = "select coalesce(max(us), null, 0) + 1 as us from us where scavo = ".$dati['us']['scavo'].";";
    $maxNum = $this->simple($sql);
    $dati['us']['us'] = $maxNum[0]['us'];
    try {
      $this->begin();
      $usResponse = $this->usInsert($dati['us']);
      if (isset($dati['us_area']) && count($dati['us_area']) > 0) {
        $dati['us_area']['us'] = $usResponse['field'][0];
        $this->usSectionInsert("us_area",$dati['us_area']);
      }
      if (isset($dati['us_info']) && count($dati['us_info']) > 0) {
        $dati['us_info']['us'] = $usResponse['field'][0];
        $this->usSectionInsert("us_info",$dati['us_info']);
      }
      if (isset($dati['componenti']) && count($dati['componenti']) > 0) {
        $dati['componenti']['us'] = $usResponse['field'][0];
        $this->usSectionInsert("componenti",$dati['componenti']);
      }
      if (isset($dati['rapporti']) && count($dati['rapporti']) > 0) {
        foreach ($dati['rapporti'] as $key => $value) {
          $rapporti = array("us"=>$usResponse['field'][0],"rapporto"=>$key, "us2"=>$value);
          $usRapporti = $this->usSectionInsert("us_rapporti",$rapporti);
        }
      }
      $this->commitTransaction();
      return array('res'=>true, 'us'=>$maxNum[0]['us']);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }
  private function usSectionInsert($tab,$dati = array()){
    try {
      $sql = $this->buildInsertQuery($tab,$dati);
      return $this->prepared($sql,$dati);
    } catch (\Exception $e) {
      $this->rollback();
      return $e->getMessage();
    }
  }
  private function usInsert($dati=array()){
    $sql = "insert into us(scavo, us, tipo, compilazione, compilatore, definizione) values (:scavo, :us, :tipo, :compilazione, :compilatore, :definizione) returning id;";
    return $this->returning($sql,$dati);
  }

  public function updateUs($dati = array()){
    $mainFilter = array("id"=>$dati['us']['id']);
    $childFilter = array("us"=>$dati['us']['id']);
    try {
      $this->begin();
      $usSql = $this->buildUpdate("us",$mainFilter, $dati['us']);
      $this->prepared($usSql,$dati['us']);
      if (isset($dati['us_area']) && count($dati['us_area']) > 0) {
        $usAreaSql = $this->buildUpdate("us_area",$childFilter, $dati['us_area']);
        $this->prepared($usAreaSql,$dati['us_area']);
      }
      if (isset($dati['us_info']) && count($dati['us_info']) > 0) {
        $usInfoSql = $this->buildUpdate("us_info",$childFilter, $dati['us_info']);
        $this->prepared($usInfoSql,$dati['us_info']);
      }
      if (isset($dati['componenti']) && count($dati['componenti']) > 0) {
        $usComponentiSql = $this->buildUpdate("componenti",$childFilter, $dati['componenti']);
        $this->prepared($usComponentiSql,$dati['componenti']);
      }
      if (isset($dati['rapporti']) && count($dati['rapporti']) > 0) {
        $this->simple("delete from us_rapporti where us = ".$dati['us']['id'].";");
        foreach ($dati['rapporti'] as $key => $value) {
          $rapporti = array("us"=>$dati['us']['id'],"rapporto"=>$key, "us2"=>$value);
          $this->usSectionInsert("us_rapporti",$rapporti);
        }
      }
      $this->commitTransaction();
      return array('res'=>true, 'main'=>$usSql, 'info'=>$usInfoSql, 'area'=>$usAreaSql);
    } catch (\Exception $e) {
      $this->rollback();
      return $e->getMessage();
    }

  }
  public function deleteUs(int $id){}
}

?>
