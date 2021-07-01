<?php
require_once("models/login.class.php");
$login = new Login;
$entra = $login->login($_POST);
echo json_encode($entra);
?>
