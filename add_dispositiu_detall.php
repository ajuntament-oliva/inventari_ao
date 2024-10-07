<?php
$page_title = 'Afegir Propietari i Dispositiu';
require_once('includes/load.php');

// Activa la visualització d'errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtindre d'id del departament actual
// Obtindre d'id del departament actual
$departament_id = isset($_GET['departament_id']) ? (int) $_GET['departament_id'] : 0;

if (isset($_POST['add_owner'])) {
  $propietari_nom = remove_junk($db->escape($_POST['nom']));
  $propietari_cognom = remove_junk($db->escape($_POST['cognom']));
  $dispositiu_nom = isset($_POST['dispositiu']) ? remove_junk($db->escape($_POST['dispositiu'])) : '';
  $uid = remove_junk($db->escape($_POST['uid']));
  $id_anydesck = remove_junk($db->escape($_POST['id_anydesck']));
  $processador = remove_junk($db->escape($_POST['processador']));
  $ram = remove_junk($db->escape($_POST['ram']));
  $capacitat = remove_junk($db->escape($_POST['capacitat']));

  // Verificar si el departament existeix
  $check_department = $db->query("SELECT id FROM departaments WHERE id = $departament_id");
  if ($check_department && $check_department->num_rows > 0) {
      if ($propietari_nom && $propietari_cognom && $dispositiu_nom && $uid && $id_anydesck && $processador && $ram && $capacitat) {
          // Inserir nou propietari
          $sql_insert_owner = "INSERT INTO propietaris (nom, cognom) VALUES ('$propietari_nom', '$propietari_cognom')";
          if ($db->query($sql_insert_owner)) {
              $propietari_id = $db->insert_id();

              // Inserir nou dispositiu seleccionat
              $sql_insert_device = "INSERT INTO dispositius (dispositiu, departament_id) VALUES ('$dispositiu_nom', $departament_id)";
              if ($db->query($sql_insert_device)) {
                  $dispositiu_id = $db->insert_id();

                  // Inserir relació entre dispositiu i propietari
                  $sql_insert_device_owner = "INSERT INTO dispositiu_propietari (dispositiu_id, propietari_id) VALUES ($dispositiu_id, $propietari_id)";
                  if ($db->query($sql_insert_device_owner)) {
                      // Inserir característica per al dispositiu
                      $sql_insert_feature = "INSERT INTO caracteristiques_dispositiu (dispositiu_id, uid, id_anydesck, processador, ram, capacitat) VALUES ($dispositiu_id, '$uid', '$id_anydesck', '$processador', '$ram', '$capacitat')";
                      if ($db->query($sql_insert_feature)) {
                          $session->msg('s', "Propietari, dispositiu i característiques afegits amb èxit.");
                          redirect('add_dispositiu_detall.php?departament_id=' . $departament_id, false);
                      } else {
                          $session->msg('d', "No s'ha pogut afegir les característiques: " . $db->error);
                      }
                  } else {
                      $session->msg('d', "No s'ha pogut afegir la relació dispositiu-propietari: " . $db->error);
                  }
              } else {
                  $session->msg('d', "No s'ha pogut afegir el dispositiu: " . $db->error);
              }
          } else {
              $session->msg('d', "No s'ha pogut afegir el propietari: " . $db->error);
          }
      } else {
          $session->msg('d', "Es requerixen tots els camps.");
      }
  } else {
      $session->msg('d', "El departament seleccionat no existix o consulta fallida: " . $db->error);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-body">
        <h4>Afegir Propietari i Dispositiu</h4>
        <?php if ($session->has_msg()): ?>
          <?php echo $session->display_msg(); ?>
        <?php endif; ?>
        <form method="post" action="">
          <input type="hidden" name="departament_id" value="<?php echo $departament_id; ?>">
          <div class="form-group">
            <label>Selecciona el dispositiu:</label><br>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_monitor" value="Monitor"
                required>
              <label class="form-check-label" for="dispositiu_monitor">Monitor</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_teclat" value="Teclat"
                required>
              <label class="form-check-label" for="dispositiu_teclat">Teclat</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_torre" value="Torre"
                required>
              <label class="form-check-label" for="dispositiu_torre">Torre</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_portatil" value="Portàtil"
                required>
              <label class="form-check-label" for="dispositiu_portatil">Portàtil</label>
            </div>
          </div>
          <div class="form-group">
            <label for="nom">Nom Propietari:</label>
            <input type="text" name="nom" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="cognom">Cognom Propietari:</label>
            <input type="text" name="cognom" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="uid">UID:</label>
            <input type="text" name="uid" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="id_anydesck">id_anydesck:</label>
            <input type="text" name="id_anydesck" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="processador">Processador:</label>
            <input type="text" name="processador" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="ram">RAM:</label>
            <input type="text" name="ram" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="capacitat">Capacitat:</label>
            <input type="text" name="capacitat" class="form-control" required>
          </div>

          <a href="departaments.php" class="btn btn-danger">Torna a Departaments</a>
          <button type="submit" name="add_owner" class="btn btn-primary">Afegir Propietari, Dispositiu i
            Característica</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>