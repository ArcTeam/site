<?php
require("conn.class.php");
class Db extends Conn{
  private $string = PDO::PARAM_STR;
  private $integer = PDO::PARAM_INT;
  public function simple($sql){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute();
      return $exec->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      return  "errore: ".$e->getMessage();
    }
  }
  public function prepared($sql, $dati=array()){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute($dati);
      return true;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  public function returning($sql, $dati=array()){
    try {
      $pdo = $this->pdo();
      $exec = $pdo->prepare($sql);
      $exec->execute($dati);
      $returning = $exec->fetchAll();
      return array('res' => true, 'field'=>$returning[0] );
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
  protected function countRow($sql){
    try {
      $pdo = $this->pdo();
      $row = $pdo->query($sql)->rowCount();
      return $row;
    } catch (Exception $e) {
      $this->msg =  "errore: ".$e->getMessage();
    }
  }
  protected function begin(){$this->pdo()->beginTransaction();}
  protected function commitTransaction(){$this->pdo()->commit();}
  protected function rollback(){$this->pdo()->rollBack();}
  protected function buildInsertQuery($tab,$dati = array()){
    $field = [];
    $value = [];
    foreach ($dati as $key => $val) {
      array_push($field,$key);
      array_push($value,":".$key);
    }
    $sql = "insert into ".$tab."(".join(",",$field).") values (".join(",",$value).");";
    return $sql;
  }

  protected function buildUpdate($tab, $filter = array(), $dati = array()){
    $field = [];
    $where = [];
    foreach ($dati as $key => $val) { array_push($field,$key."=:".$key); }
    foreach ($filter as $key => $val) { array_push($where,$key."=".$val); }
    $sql = "update ".$tab." set ".join(",",$field)." where ".join(" AND ", $where);
    return $sql;
  }

  protected function getScavoConfig($scavo){
    $sql = "select * from scavo_config where scavo = ".$scavo.";";
    $res = $this->simple($sql);
    return $res[0];
  }
}
?>
