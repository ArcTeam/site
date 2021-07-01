<?php
require_once("../config/db.class.php");
$term = trim(strip_tags($_GET['term']));
$obj = new Db;
$test = $obj->simple("select osm_id id, name as label from osm where name ilike '%".$term."%' order by name asc;");
echo json_encode($test);
?>
