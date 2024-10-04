<?php
$page_title = 'Afegir Propietari i Dispositiu';
require_once('includes/load.php');

if (isset($_POST['add_owner'])) {
  $propietari_nom = remove_junk($db->escape($_POST['nom']));
  $propietari_cognom = remove_junk($db->escape($_POST['cognom']));
  $dispositiu_nom = isset($_POST['dispositiu']) ? remove_junk($db->escape($_POST['dispositiu'])) : '';
  $departament_id = isset($_POST['departament_id']) ? (int) $_POST['departament_id'] : 0;

  $check_department = $db->query("SELECT id FROM departaments WHERE id = $departament_id");

  if ($check_department->num_rows > 0) {
    // El departament existeix, continuem amb la inserció
    // Afegir propietari a la BDA
    if ($propietari_nom && $propietari_cognom && $dispositiu_nom) {
      // Inserir nou propietari
      $sql_insert_owner = "INSERT INTO propietaris (nom, cognom) VALUES ('$propietari_nom', '$propietari_cognom')";

      if ($db->query($sql_insert_owner)) {
        $propietari_id = $db->insert_id();

        // Inserir nou dispositiu
        $sql_insert_device = "INSERT INTO dispositius (dispositiu, departament_id) VALUES ('$dispositiu_nom', $departament_id)";
        if ($db->query($sql_insert_device)) {
          $dispositiu_id = $db->insert_id();

          // Inserir relació entre el dispositiu i el nou propietari
          $sql_insert_device_owner = "INSERT INTO dispositiu_propietari (dispositiu_id, propietari_id) VALUES ($dispositiu_id, $propietari_id)";
          if ($db->query($sql_insert_device_owner)) {
            $session->msg('s', "Propietari i dispositiu afegits amb èxit.");
            redirect('add_dispositiu_detall.php', false);
          } else {
            $session->msg('d', 'Ho sentim, no es va poder afegir la relació dispositiu-propietari.');
          }
        } else {
          $session->msg('d', 'Ho sentim, no es va poder afegir el dispositiu.');
        }
      } else {
        $session->msg('d', 'Ho sentim, no es va poder afegir el propietari.');
      }
    } else {
      $session->msg('d', 'Se requereixen tots els camps.');
    }
  } else {
    $session->msg('d', 'El departament seleccionat no existeix.');
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
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_monitor" value="Monitor" required>
              <label class="form-check-label" for="dispositiu_monitor">Monitor</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_teclat" value="Teclat" required>
              <label class="form-check-label" for="dispositiu_teclat">Teclat</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_torre" value="Torre" required>
              <label class="form-check-label" for="dispositiu_torre">Torre</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="dispositiu" id="dispositiu_portatil" value="Portàtil" required>
              <label class="form-check-label" for="dispositiu_portatil">Portàtil</label>
            </div>
          </div>
          <div class="form-group">
            <label for="nom">Nom del Propietari:</label>
            <input type="text" name="nom" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="cognom">Cognom del Propietari:</label>
            <input type="text" name="cognom" class="form-control" required>
          </div>
          <a href="departaments.php" class="btn btn-danger">Torna a Departaments</a>
          <button type="submit" name="add_owner" class="btn btn-primary">Afegir Propietari i Dispositiu</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>