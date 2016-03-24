
<div class="hover panel">
  <div class="front"></div>
  <div class="back"></div>
</div>

<h1><span>A</span>rc-<span>T</span>eam</h1>
<nav>
  <ul>
    <li><a href="index.php" title="Welcome" id="home"><i class="fa fa-home fa-2x"></i><span class="mobile">home</span></a></li>
    <li><a href="http://arc-team-open-research.blogspot.it/" title="Happy Hacking with ATOR (ArcTeam Open Research)" id="hack" target="_blank"><i class="fa fa-code fa-2x"></i> <span class="mobile">hack</span></a></li>
    <li><a href="#" title="Free as freedom" id="project"><i class="fa fa-cogs fa-2x"></i> <span class="mobile">project</span></a></li>
    <li><a href="#" title="Open your mind" id="team"><i class="fa fa-users fa-2x"></i> <span class="mobile">team</span></a></li>
  </ul>
</nav>
<nav id="login">
  <ul>
    <li>
      <?php
        if(!$_SESSION['id']){?>
          <a href="login.php" title="login"><i class="fa fa-sign-in fa-2x"></i> login</a>
        <?php }else{ ?>
          <a href="#" title="settings"><i class="fa fa-gear fa-2x"></i></a>
          <ul id="settingUl" class="subMenu">
            <li><a href="#"><i class="fa fa-eur"></i> fatture</a></li>
            <li><a href="#"><i class="fa fa-wrench"></i> lavori</a></li>
            <li><a href="#"><i class="fa fa-calendar"></i> attività</a></li>
            <li><a href="#"><i class="fa fa-comments-o"></i> post</a></li>
            <li><a href="#"><i class="fa fa-users"></i> utenti</a></li>
            <li><a href="usrMod.php" title="modifica i tuoi dati personali"><i class="fa fa-user"></i> dati personali</a></li>
            <li><a href="login.php?action=logout" title="termina sessione di lavoro"><i class="fa fa-sign-out"></i> logout</a></li>
          </ul>
        <?php } ?>
    </li>
  </ul>
</nav>
