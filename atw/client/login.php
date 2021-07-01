<?php
session_start();
if (isset($_SESSION['id'])) {
  header("Location: index.php");
  exit;
}
?>
<!doctype html>
<html lang="it">
  <head>
    <?php require_once("inc/meta.html"); ?>
    <?php require_once("inc/css.html"); ?>
    <link rel="stylesheet" href="css/loginPage.css">
  </head>
  <body class="bg-secondary">
    <main>
      <section class="bg-white rounded border p-5 m-auto">
        <header class="mb-4">
          <img src="img/logoIntero.jpg" class="img-fluid" alt="">
          <p class="text-center text-secondary">entra nell'area riservata</p>
        </header>
        <form class="mb-4" id="loginForm">
          <input type="email" name="email" value="" placeholder="indirizzo email" class="form-control my-2" required>
          <input type="password" name="password" value="" placeholder="password" class="form-control my-2" required>
          <small class="d-none outMsg my-3"></small>
          <button type="submit" class="btn btn-primary btn-sm form-control"  name="submit">Accedi</button>
        </form>
        <hr>
        <a href="reset_password.php" class="btn btn-outline-secondary btn-sm form-control">password dimenticata?</a>
      </section>
    </main>
    <?php require_once("inc/lib.html"); ?>
    <script src="js/login.js" charset="utf-8"></script>
  </body>
</html>
