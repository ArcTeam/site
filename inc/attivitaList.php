<?php
session_start();
require("db.php");
$q = "select a.gid, c.def, a.data_inizio from main.attivita a, liste.subcat c where a.tipo_lavoro = c.id and a.data_fine is null and a.lavoro = ".$_POST['id']." order by data_inizio asc;";
$e = pg_query($connection, $q);
if(!$e){
    $data = pg_last_error($connection);
}else {
    while($att = pg_fetch_array($e)){ $data .= "<option value='".$att['gid']."'>".$att['def']." - ".$att['data_inizio']."</option>"; }
}
echo $data;
?>
