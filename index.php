<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}

  $msg = [];
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h1>Benvingut/da!</h1>
       <p>Inicia sessió per començar</p>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="auth.php" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Nom d'usuari</label>
              <input type="name" class="form-control" name="username" placeholder="Nom d'usuari">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Constrasenya</label>
            <input type="password" name= "password" class="form-control" placeholder="Constrasenya">
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info  pull-right">Accés</button>
        </div>
    </form>
</div>
<?php include_once('layouts/footer.php'); ?>
