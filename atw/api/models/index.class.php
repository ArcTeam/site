<?php
session_start();
require("config/db.class.php");
class Index extends Db{

  function __construct(){}
  public function getFilterLavoro(){
    $sql = "select distinct extract('Y' from inizio) as anno from scavo;";
    $anno = $this->simple($sql);
    $sql = "select distinct osm.osm_id, osm.name from osm join localizzazione l on l.comune = osm.osm_id order by 2 asc;";
    $comune = $this->simple($sql);
    $sql = "select distinct sigla from scavo order by 1 asc;";
    $sigla = $this->simple($sql);
    $sql = "select distinct nome_lungo scavo from scavo order by 1 asc;";
    $nome = $this->simple($sql);
    return array("anno"=>$anno, "comune"=>$comune, "sigla"=>$sigla,"scavo"=>$nome);
  }
  public function getLavoro($filtri = array()){
    $where='';
    if(count($filtri) > 0){
      $where =' WHERE ';
      $where .= join(" and ",$filtri);
    }
    $sql = "select s.id, c.osm_id, c.name comune, s.nome_lungo nome, s.sigla, s.descrizione, extract('Y' from s.inizio) anno from scavo s LEFT JOIN localizzazione l ON l.scavo = s.id left join osm c on l.comune = c.osm_id ".$where." order by s.inizio desc, s.nome_lungo asc;";
    // return $sql;
    return $this->simple($sql);
  }
}
?>
