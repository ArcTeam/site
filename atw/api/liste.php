<?php
require_once("config/db.class.php");
$pdo = new Db;
if (isset($_POST['filter'])) {
  $arr = [];
  foreach ($_POST['filter'] as $key => $val) { $arr[]=$key."=".$val; }
  $filter = " where ". join(" and ", $arr);
}else {
  $filter='';
}
$orderBy = isset($_POST['orderBy']) ? " order by ".$_POST['orderBy'] : '';
$sql = "select * from ".$_POST['tab'].$filter.$orderBy.";";
echo json_encode($pdo->simple($sql));
?>
