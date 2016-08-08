<?php
session_start();
require_once("inc/usrModScript.php");
?>
<!DOCTYPE html>
<html>
    <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/usrMod.css" rel="stylesheet" media="screen" />
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="form content">
                <header>Dati generali</header>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="usrModForm">
                    <div class="row">
                        <div class="inline" style="width:32%;">
                            <label>*Utente: </label>
                            <textarea name="utente" required><?php echo $c['utente']; ?></textarea>
                        </div>
                        <div class="inline" style="width:32%;">
                            <label>*Tipo utente: </label>
                            <select name="tipo" required>
                                <?php
                                while($tipo = pg_fetch_array($tipoexec)){
                                    $sel = ($tipo['id']== $c['tipo']) ? 'selected' : '';
                                    echo "<option value='".$tipo['id']."' ".$sel.">".$tipo['definizione']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="inline" style="width:32%;">
                            <label>*E-mail: </label>
                            <input type="email" name="email" value="<?php echo $c['email']; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:67%;">
                            <label>Indirizzo: </label>
                            <textarea name="indirizzo"><?php echo $c['indirizzo']; ?></textarea>
                        </div>
                        <div class="inline" style="width:29%;">
                            <label>Codice fiscale / P.Iva: </label>
                            <input type="text" name="codfisc" value="<?php echo $c['codfisc']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:32%;">
                            <label>Telefono: </label>
                            <input type="text" name="telefono" value="<?php echo $c['telefono']; ?>">
                        </div>
                        <div class="inline" style="width:32%;">
                            <label>Cellulare: </label>
                            <input type="text" name="cell" value="<?php echo $c['cell']; ?>">
                        </div>
                        <div class="inline" style="width:32%;">
                            <label>Fax: </label>
                            <input type="text" name="fax" value="<?php echo $c['fax']; ?>">
                        </div>
                        <div class="inline" style="width:100%">
                            <label>Sito web: </label>
                            <input type="link" name="url" value="<?php echo $c['url']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <label>Note: </label>
                            <textarea name="note" style="height:100px;"><?php echo $c['note']; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <button type="submit" name="usrMod" value="modifica dati"><i class="fa fa-save"></i> Modifica dati</button>
                        </div>
                    </div>
                    <span class="msg"><?php echo $msg; ?></span>
                </form>
                <header>Profilo pubblico</header>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="socialForm" id="socialForm" enctype="multipart/form-data">
                    <input type="hidden" name="sessionImg" value="<?php echo $_SESSION['img']; ?>" >
                    <div class="row">
                        <div class="inline" id="myImg"></div>
                        <div class="inline" id="uploadButton">
                            <button type="button" name="triggerUpload"><i class="fa fa-save"></i> Modifica immagine</button>
                            <input type="file" name="updateImg" id="updateImg" accept="image/*" style="display:none;">
                            <p class='msg' id="uploadMsg"></p>
                        </div>
                    </div>
                    <div class="row">
                        <label>Modifica skills: </label>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <span class="msg"><?php echo $msgSocial; ?></span>
                            <button type="submit" name="socialMod" value="modifica social"><i class="fa fa-save"></i> Modifica profilo pubblico</button>
                        </div>
                    </div>
                </form>
                <header>Modifica password</header>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="usrPwdModForm">
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <label>Nuova password: </label>
                            <input type="password" id="newPwd" name="newPwd" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <label>Ridigita password: </label>
                            <input type="password" id="checkPwd" name="checkPwd" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <button type="submit" name="usrPwdMod" value="modifica password"><i class="fa fa-save"></i> Modifica password</button>
                        </div>
                    </div>
                    <span class="msg"><?php echo $msgPwd; ?></span>
                </form>
            </section>
        </div>
        <div style="clear:both !important"></div>
        <footer><?php require("inc/footer.php"); ?></footer>
        <script src="lib/jquery-1.12.0.min.js"></script>
        <script src="script/funzioni.js"></script>
        <script>
            var p = document.getElementById("newPwd");
            var cp = document.getElementById("checkPwd");
            function validatePassword(){
                if(p.value.length < 6){
                    p.setCustomValidity("La password deve contenere almeno 6 caratteri!");
                }else{
                    p.setCustomValidity('');
                    if(p.value != cp.value) {
                        cp.setCustomValidity("Attenzione, le password non coincidono!");
                    } else {
                        cp.setCustomValidity('');
                    }
                }
            }
            p.onchange = validatePassword;
            cp.onkeyup = validatePassword;

            function renderImage(file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    preview = event.target.result;
                    $('#myImg').html("<img class='preview' src='" + preview + "' />");
                    $("#uploadMsg").text("L'anteprima dell'immagine è puramente indicativa, possibili distorsioni verranno eliminate al salvataggio");
                }
                reader.readAsDataURL(file);
            }

            $(document).ready(function(){
                var i = $("input[name='sessionImg']").val();
                $("#myImg").css({"background-image":"url("+i+")"});
                $("button[name='triggerUpload']").on("click", function(){ $("input[name=updateImg]").click(); });
                $("input[name=updateImg]").on("change", function() {
                    var file= this.files[0];
                    if(file.size>=2*1024*1024) {
                        $("#uploadMsg").text("Attenzione! La dimensione massima permessa per un'immagine è di 2MB mentre l'immagine che hai caricato è di "+formatBytes(file.size));
                        $("#socialForm").get(0).reset();
                        return;
                    }
                    if(!file.type.match('image/*')) {
                        $("#uploadMsg").text("Attenzione! possono essere caricate solo immagini mentre tu stai cercando di caricare un file di tipo "+file.type);
                        $("#socialForm").get(0).reset();
                        return;
                    }

                    renderImage(file);
                });
            });
        </script>
    </body>
</html>
