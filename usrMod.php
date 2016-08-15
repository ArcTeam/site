<?php
session_start();
require_once("inc/usrModScript.php");
?>
<!DOCTYPE html>
<html>
    <head>
      <?php require("inc/meta.php"); ?>
      <link href="css/usrMod.css" rel="stylesheet" media="screen" >
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    </head>
    <body>
        <header id="main"><?php require("inc/header.php"); ?></header>
        <div id="mainWrap">
            <section class="form content">
                <header>Dati generali</header>
                <form action="usrModDati.php" method="post" name="usrModForm">
                    <input type="hidden" name='refresh' value='usrMod.php' >
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
                <form action="usrModSocial.php" method="post" name="socialForm" id="socialForm" enctype="multipart/form-data">
                    <input type="hidden" name="sessionImg" value="<?php echo $_SESSION['img']; ?>" >
                    <div class="row">
                        <div class="inline avatar" id="myImg"></div>
                        <div class="inline" id="uploadButton">
                            <button type="button" name="triggerUpload"><i class="fa fa-save"></i> Modifica immagine</button>
                            <input type="file" name="updateImg" id="updateImg" accept="image/*" style="display:none;">
                            <p class='msg' id="uploadMsg"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline"><label>Modifica skills: </label></div>
                        <div class="inline"><input type="text" name="tags" placeholder="Aggiungi tag" class="tm-input" ></div>
                        <input type="hidden" name="modTag" value=''>
                    </div>
                    <div class="row">
                        <div class="inline"><label>Account social: </label></div>
                        <div class="inline">
                            <ul>
                                <?php
                                if($sqRow > 0){
                                    while($socialUsr = pg_fetch_array($sq)){
                                        echo "<li id='social".$socialUsr['id']."'>";
                                        echo "<i class='fa ".$socialUsr['ico']."' aria-hidden='true'></i> ";
                                        echo "<a href='".$socialUsr['link']."' target='_blank' class='genericLink'>".$socialUsr['link']."</a> ";
                                        echo "<a href='#' class='prevent delSocial genericLink' title='Attenzione! Stai per cancellare il link al tuo account social'><i class='fa fa-times' aria-hidden='true'></i></a>";
                                        echo "<span style='display:none'>";
                                        echo "<button type='button' name='delSocialConfirm' class='delSocialButton delSocialConfirm error' data-id='".$socialUsr['id']."'>elimina</button> ";
                                        echo "<button type='button' name='delSocialAnnulla' class='delSocialButton delSocialAnnulla success' >annulla</button> ";
                                        echo "<span>";
                                        echo "</li>";
                                    }
                                }else{
                                    echo "<li>Nessun account registrato</li>";
                                }
                                ?>
                            </ul>
                            <div id="newSocialList">
                                <ul id="newSocialListUl">

                                </ul>
                            </div>
                            <select name="newSocialType">
                                <option selected disabled>Scegli social</option>
                                <?php
                                    while ($socialList = pg_fetch_array($socialQ)) {
                                        echo "<option value='".$socialList['id']."' data-ico='".$socialList['ico']."'>".$socialList['nome']."</option>";
                                    }
                                ?>
                            </select>
                            <input type="url" name="newSocialUrl" placeholder="Inserisci link profilo.">
                            <span id="newSocialUrlMsg"></span>
                            <input type=hidden name='socialArr' value=''>
                            <button type="button" name="newSocialAdd"><i class="fa fa-plus" aria-hidden="true"></i> Aggiungi</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline" style="width:100%">
                            <button type="submit" name="socialMod" value="modifica social"><i class="fa fa-save"></i> Modifica profilo pubblico</button>
                        </div>
                    </div>
                </form>
                <header>Modifica password</header>
                <form action="usrModPwd.php" method="post" name="usrPwdModForm">
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
                </form>
            </section>
        </div>
        <div style="clear:both !important"></div>
        <footer><?php require("inc/footer.php"); ?></footer>
        <script src="lib/jquery-1.12.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="lib/tag/tagmanager.js"></script>
        <script src="script/funzioni.js"></script>
        <script src="script/usrMod.js"></script>
        <script>
            var dataList = <?php echo $tagList; ?>;
            var prefilled;
            var tagpresarr = <?php echo $tagpresList; ?>;
            var tags = [];
        </script>
    </body>
</html>
