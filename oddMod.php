<?php
session_start();
require("inc/db.php");
//se sono in modifica...
$link = $_POST['urlArr'];
$licenza = $_POST['licenzaArr'];

if ($_POST['id']>0) {
    $metadati = "update main.opendata set titolo = '".pg_escape_string($_POST['titolo'])."', categoria = '".pg_escape_string($_POST['categoria'])."', autori = '".pg_escape_string($_POST['autori'])."', '".pg_escape_string($_POST['descrizione'])."' where id = ".$_POST['id'].";";
    $tag = "update main.tags set tags = '".$_POST['tag']."' where rec = ".$_POST['id']." and tab=4;";
    $file = "delete from main.opendatafile where opendata = ".$_POST['id'].";";
    foreach( $link as $key => $n ) {
        $file .= "insert into main.opendatafile(opendata, tipo, link, licenza) values (currval('main.opendata_id_seq'), '".end((explode('.', $n)))."', '".$n."', ".$licenza[$key].");";
    }
    $log = "insert into main.log(tabella,record,operazione, utente) values ('opendata', ".$_POST['id'].", 'U', ".$_SESSION['id'].");";
}else{
    $metadati = "insert into main.opendata(titolo, categoria, autori, descrizione) values ('".pg_escape_string($_POST['titolo'])."', '".pg_escape_string($_POST['categoria'])."', '".pg_escape_string($_POST['autori'])."', '".pg_escape_string($_POST['descrizione'])."');";
    $tag = "insert into main.tags(tags, rec, tab) values('".$_POST['tagList']."', currval('main.opendata_id_seq'), 4);";
    foreach( $link as $key => $n ) {
        $file .= "insert into main.opendatafile(opendata, tipo, link, licenza) values (currval('main.opendata_id_seq'), '".end((explode('.', $n)))."', '".$n."', ".$licenza[$key].");";
    }
    $log = "insert into main.log(tabella,record,operazione, utente) values ('opendata', currval('main.opendata_id_seq'), 'I', ".$_SESSION['id'].");";
}

$query = $metadati." ".$tag." ".$file." ".$log;
$queryExec = pg_query($connection,$query);
if(!$queryExec){
    $msg = "errore: ".pg_last_error($connection)."<br/>".$query;
}else {
    $msg = "Ok, il documento è stato modificato con successo.";
}
?>

<!DOCTYPE html>
<html>
    <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/style.css" rel="stylesheet" media="screen" >
      <style> #mainWrap div{ width: 70%; margin: 50px auto; border: 1px solid; border-radius: 5px;text-align:center;padding:20px;} </style>
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="form content">
                <header>Risultato query</header>
                <div class="success">
                    <p><?php echo $msg; ?></p>
                    <p>Tra 5 secondi verrai reindirizzato automaticamente <?php echo $txt; ?>.</p>
                    <p>Se la pagina non cambia o non vuoi aspettare <a href="<?php echo $_POST['refresh']; ?>">clicca qui</a></p>
                </div>
            </section>
        </div>
        <div style="clear:both !important"></div>
        <footer><?php require("inc/footer.php"); ?></footer>
        <script src="lib/jquery-1.12.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="script/funzioni.js"></script>
    </body>
</html>
