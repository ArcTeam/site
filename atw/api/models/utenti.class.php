<?php
session_start();
require("config/db.class.php");
class Utente extends Db{
  function __construct(){}

  public function getRubrica(){
    $sql = "select r.id, c.id as id_classe, c.classe, r.nome, r.email, r.indirizzo, r.cod_fisc, r.piva, r.telefono, r.mobile, r.web, r.note from rubrica r, list.soggetto_rubrica c where r.classe = c.id order by nome asc, email asc, id asc;";
    return $this->simple($sql);
  }
  public function getClassRubrica(){
    $list = $this->simple("select classe from list.soggetto_rubrica order by classe asc;");
    return $list;
  }
  public function addRubrica($dati=array()){
    $sql = "insert into rubrica(classe, nome, email, indirizzo, cod_fisc, piva, telefono, mobile, web, note) values (:classe, :nome, :email, :indirizzo, :cod_fisc, :piva, :telefono, :mobile, :web, :note);";
    return $this->prepared($sql,$dati);
  }
}
?>
