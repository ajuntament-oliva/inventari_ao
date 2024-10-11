<?php
$page_title = 'Modificar Propietari i Dispositiu';
require_once('includes/load.php');

// Activa la visualització d'errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Comprovar si el departament està present a l'URL
if (isset($_GET['departament_id']) && !empty($_GET['departament_id'])) {
  $departament_id = (int) $_GET['departament_id'];
} else {
  $session->msg('d', "Departament ID no vàlid.");
  redirect('departaments.php', false);
  exit;
}

// Obtenir dispositiu_id si està present a l'URL
$dispositiu_id = isset($_GET['dispositiu_id']) ? (int) $_GET['dispositiu_id'] : null;

// Obtenir departament associat
$departament_query = $db->query("SELECT * FROM departaments WHERE id = $departament_id");
$departament = $departament_query->fetch_assoc();
if (!$departament) {
  $session->msg('d', "Departament no trobat amb ID: $departament_id.");
  redirect('departaments.php', false);
  exit;
}

// Consulta per obtenir tots els dispositius del departament
$dispositius_query = $db->query("SELECT id, dispositiu FROM dispositius WHERE departament_id = $departament_id");

// Comprovar si s'ha seleccionat un dispositiu
if ($dispositiu_id) {
  $dispositiu = $db->query("SELECT * FROM dispositius WHERE id = $dispositiu_id")->fetch_assoc();
  if ($dispositiu) {
    // Obtenir propietari del dispositiu
    $dispositiu_assoc = $db->query("SELECT propietari_id FROM dispositiu_propietari WHERE dispositiu_id = $dispositiu_id");
    if ($dispositiu_assoc && $dispositiu_assoc->num_rows > 0) {
      $propietari_id = $dispositiu_assoc->fetch_assoc()['propietari_id'];
      // Obtenir detalls del propietari
      $propietari = $db->query("SELECT * FROM propietaris WHERE id = $propietari_id")->fetch_assoc();
    } else {
      $propietari = null; // No hi ha propietari associat
      $session->msg('d', "No s'ha trobat propietari per al dispositiu seleccionat.");
    }
  } else {
    $session->msg('d', "Dispositiu no trobat. Mostrant altres dispositius del departament.");
  }
} else {
  $dispositiu = null; // No s'ha seleccionat cap dispositiu
  $propietari = null; // No hi ha propietari associat
}

// Si s'ha enviat el formulari per editar el dispositiu
if (isset($_POST['edit_device'])) {
  $propietari_nom = remove_junk($db->escape($_POST['nom'] ?? ''));
  $propietari_cognom = remove_junk($db->escape($_POST['cognom'] ?? ''));

  // Actualització del propietari
  if ($propietari && $propietari_nom && $propietari_cognom) {
    $sql_update_owner = "UPDATE propietaris SET nom = '$propietari_nom', cognom = '$propietari_cognom' WHERE id = $propietari_id";
    if ($db->query($sql_update_owner)) {
      $session->msg('s', "Propietari actualitzat amb èxit.");
    } else {
      $session->msg('d', "Error actualitzant el propietari: " . $db->error);
    }
  }
  redirect('edit_dispositiu_detall.php?departament_id=' . $departament_id . '&dispositiu_id=' . $dispositiu_id, false);
}
?>

<?php include_once('layouts/header.php'); ?>

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
        <h4 class="panel-title">Afegir Propietari i Dispositiu -
          <?php echo remove_junk(ucwords($departament['departament'])); ?>
        </h4>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_dispositiu_detall.php?departament_id=<?php echo (int) $departament_id; ?>&dispositiu_id=<?php echo (int) $dispositiu_id; ?>" novalidate>
          <div class="form-group">
            <label for="dispositiu">Selecciona el dispositiu:</label>
            <select name="dispositiu" id="dispositiu" class="form-control" required>
              <?php while ($row = $dispositius_query->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php echo ($dispositiu && $dispositiu['id'] == $row['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($row['dispositiu']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <?php if ($propietari): ?>
          <div class="form-group">
            <label for="nom">Nom del Propietari:</label>
            <input type="text" name="nom" id="nom" class="form-control"
              value="<?php echo htmlspecialchars($propietari['nom']); ?>" required>
          </div>
          <div class="form-group">
            <label for="cognom">Cognom del Propietari:</label>
            <input type="text" name="cognom" id="cognom" class="form-control"
              value="<?php echo htmlspecialchars($propietari['cognom']); ?>" required>
          </div>
          <?php else: ?>
          <div class="alert alert-warning">No s'ha trobat propietari per al dispositiu seleccionat.</div>
          <?php endif; ?>

          <a href="departaments.php" class="btn btn-danger">Torna a Departaments</a>
          <button type="submit" name="edit_device" class="btn btn-primary">Actualitza</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>
<script src="libs/js/edit_dispositiu_detall_dinamic.js"></script>