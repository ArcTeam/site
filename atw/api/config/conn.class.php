<?php
class Conn{
  private $dbhost = "91.121.82.80";
  private $dbuser = "arcteam";
  private $dbpwd =  "dbAdminUser";
  private $dbname = "atworks";
  private $dbport = "5437";
  private $dsn;
  public $conn;

  public function __construct(){}
  protected function connect(){
    $this->dsn = "pgsql:host=".$this->dbhost." port=".$this->dbport." user=".$this->dbuser." password=".$this->dbpwd." dbname=".$this->dbname;
    $this->conn = new PDO($this->dsn);
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  public function pdo(){
    if (!$this->conn){ $this->connect();}
    return $this->conn;
  }
  public function __destruct(){
    if ($this->conn){ $this->conn = null; }
  }
}

?>
