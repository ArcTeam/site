<?php
session_start();
require("config/db.class.php");
class Login extends Db{
  function __construct(){}
  public function login($dati=array()){
    try {
      $usrInfo = $this->checkEmail($dati['email']);
      $this->checkAct($dati['email']);
      $this->checkPwd($dati['password']);
      $this->setSession($usrInfo[0]);
      return "Credenziali corrette, stai per accedere all'area riservata";
      // return $usrInfo;
    } catch (\Exception $e) {
      return $e->getMessage();
    } catch (PDOException $e){
      return $e->getMessage();
    }
  }

  private function checkEmail($email){
    try {
      $check = $this->simple("select id from users where email = '".$email."';");
      if (count($check)==0) {
        throw new \Exception("Errore! Email: ".$email." non valida o scritta male", 1);
      }
      return $check;
    } catch (\Exception $e) {
      return $e->getMessage();
    } catch (PDOException $e){
      return $e->getMessage();
    }
  }

  private function checkAct($email){
    $check = $this->simple("select * from users where attivo = 't' and email = '".$email."';");
    if (count($check)==0) {
      throw new \Exception("Errore! Il tuo account è stato disabilitato per motivi di sicurezza, contatta Beppe ;)", 1);
    }
    return $check;
  }

  private function checkPwd($password){
    $check = $this->simple("select id from users where password = crypt('".$password."',password);");
    if (count($check)==0) {
      throw new \Exception("Errore! La password non è corretta o è stata digitata male.<br>Riprova, se l'errore persiste contatta Beppe ;)", 1);
    }
    return true;
  }

  private function setSession($dati=array()){
    $_SESSION['id']=$dati['id'];
    return true;
  }
}
?>
