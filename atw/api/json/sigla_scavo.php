<?php
require_once("../config/db.class.php");
$term = trim(strip_tags($_GET['term']));
$obj = new Db;
$test = $obj->simple("select id, sigla as value, nome_lungo as label from scavo where sigla ilike '%".$term."%' or nome_lungo ilike '%".$term."%' order by sigla asc;");
echo json_encode($test);
?>
