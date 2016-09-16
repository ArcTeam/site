<div id="wrapLogo">
    <div class="hover panel">
        <div class="front"></div>
        <div class="back"></div>
    </div>
    <h1><span>A</span>rc-<span>T</span>eam</h1>
</div>
<nav>
  <ul>
    <li><a href="index.php" title="Welcome" id="home"><i class="fa fa-home"></i><span>home</span></a></li>
    <li><a href="post.php" title="Post" id="post"><i class="fa fa-th-list"></i> <span>post</span></a></li>
    <li><a href="#" title="Sul campo" id="works"><i class="fa fa-wrench"></i> <span>works</span></a></li>
    <li><a href="#" title="Free as freedom" id="project"><i class="fa fa-cogs"></i> <span>project</span></a></li>
    <li><a href="opendatadocs.php" title="Share your knowledge" id="odd"><i class="fa fa-creative-commons"></i> <span>open</span></a></li>
    <li><a href="team.php" title="Open your mind" id="team"><i class="fa fa-users"></i> <span>team</span></a></li>
    <!--<li><a href="http://arc-team-open-research.blogspot.it/" title="Happy Hacking with ATOR (ArcTeam Open Research)" id="hack" target="_blank"><i class="fa fa-code"></i> <span>hack</span></a></li>-->
    <?php if(!$_SESSION['id']){?>
    <li><a href="login.php" id="login" title="login"><i class="fa fa-sign-in"></i><span>login</span></a></li>
    <?php }else{ ?>
    <li class="logged">
        <a href="#" id="logged" title="settings">
            <img src="<?php echo $_SESSION['img']; ?>" class="logImg" >
            <span>menù</span>
        </a>
        <ul id="settingUl" class="subMenu">
            <li><a href="post.php" title="elenco post"><i class="fa fa-comments-o"></i> post</a></li>
            <li><a href="lavori.php" title="elenco lavori"><i class="fa fa-wrench"></i> lavori</a></li>
            <li><a href="#" title="gestione attività giornaliere"><i class="fa fa-calendar"></i> attività</a></li>
            <li><a href="#" title="elenco fatture"><i class="fa fa-eur"></i> fatture</a></li>
            <li><a href="rubrica.php" title="gestisci soggetti e utenti in rubrica"><i class="fa fa-users"></i> rubrica e utenti</a></li>
            <li><a href="usrMod.php" title="modifica i tuoi dati personali"><i class="fa fa-user"></i> dati personali</a></li>
            <li><a href="login.php?action=logout" title="termina sessione di lavoro"><i class="fa fa-sign-out"></i> logout</a></li>
        </ul>
    </li>
    <?php } ?>
  </ul>
</nav>
