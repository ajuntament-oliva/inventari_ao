<?php
$page_title = 'Modificar Propietari i Dispositiu';
require_once('includes/load.php');

// Activa la visualització d'errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obté l'ID del departament
$departament_id = $_GET['departament_id'] ?? null;
if (!$departament_id) {
  $session->msg('d', 'No s\'ha proporcionat cap ID de departament.');
  redirect('departaments.php', false);
}

// Consulta del departament
$departament_query = $db->query("SELECT * FROM departaments WHERE id = '{$departament_id}'");
$departament = $departament_query->fetch_assoc();
if (!$departament) {
  $session->msg('d', 'No s\'ha trobat cap departament amb aquest ID.');
  redirect('departaments.php', false);
}

// Consulta dels dispositius del departament
$dispositius_query = $db->query("SELECT * FROM dispositius WHERE departament_id = '{$departament_id}'");

// Dades del dispositiu seleccionat
$dispositiu = null;
$propietari = null;

if (isset($_POST['dispositiu'])) {
  $dispositiu_id = (int) $_POST['dispositiu'];

  // Consulta per obtenir el dispositiu seleccionat
  $dispositiu_query = $db->query("SELECT * FROM dispositius WHERE id = '{$dispositiu_id}'");
  $dispositiu = $dispositiu_query->fetch_assoc();

  // Consulta per obtenir el propietari associat al dispositiu
  $propietari_query = $db->query("
        SELECT p.id, p.nom, p.cognom 
        FROM dispositiu_propietari dp
        JOIN propietaris p ON dp.propietari_id = p.id
        WHERE dp.dispositiu_id = '{$dispositiu_id}'
        LIMIT 1
    ");
  $propietari = $propietari_query->fetch_assoc();
}

if (isset($_POST['edit_device'])) {
  $dispositiu_id = (int) $_POST['dispositiu'];
  $propietari_nom = remove_junk($db->escape($_POST['nom'] ?? ''));
  $propietari_cognom = remove_junk($db->escape($_POST['cognom'] ?? ''));
  $propietari_id = (int) $_POST['propietari_id'];

  // Actualitza el propietari
  $sql_update_owner = "UPDATE propietaris SET nom = '$propietari_nom', cognom = '$propietari_cognom' WHERE id = $propietari_id";
  if ($db->query($sql_update_owner)) {
    $session->msg('s', "Propietari actualitzat amb èxit.");
  } else {
    $session->msg('d', "Error actualitzant el propietari: " . $db->error);
  }

  redirect('edit_dispositiu_detall.php?departament_id=' . $departament_id, false);
}

include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <?php echo $session->display_msg(); ?>
  </div>
  <div class="col-md-3"></div>
</div>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">Modificar Propietari i Dispositiu -
          <?php echo remove_junk(ucwords($departament['departament'])); ?>
        </h4>
      </div>
      <div class="panel-body">
        <form method="post" action="" novalidate>
          <div class="form-group">
            <label for="dispositiu">Selecciona el dispositiu:</label>
            <select name="dispositiu" id="dispositiu" class="form-control" required>
              <option value="">Selecciona'n un</option>
              <?php while ($row = $dispositius_query->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php echo ($dispositiu && $dispositiu['id'] == $row['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($row['dispositiu']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="nom">Nom del Propietari:</label>
            <input type="text" name="nom" id="nom" class="form-control"
              value="<?php echo isset($propietari['nom']) ? htmlspecialchars($propietari['nom']) : ''; ?>" required>
            <input type="hidden" name="propietari_id"
              value="<?php echo isset($propietari['id']) ? $propietari['id'] : ''; ?>">
          </div>

          <div class="form-group">
            <label for="cognom">Cognom del Propietari:</label>
            <input type="text" name="cognom" id="cognom" class="form-control"
              value="<?php echo isset($propietari['cognom']) ? htmlspecialchars($propietari['cognom']) : ''; ?>"
              required>
          </div>


          <a href="departaments.php" class="btn btn-danger">Torna a Departaments</a>
          <button type="submit" name="edit_device" class="btn btn-primary">Actualitza</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>