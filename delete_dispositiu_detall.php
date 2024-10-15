<?php
$page_title = 'Eliminar Dispositiu';
require_once('includes/load.php');

$message = '';

if (isset($_GET['departament_id'])) {
  $departament_id = (int) $_GET['departament_id'];

  $dispositius = $db->query("SELECT id, dispositiu FROM dispositius WHERE departament_id = $departament_id ORDER BY dispositiu");

  $result = $db->query("SELECT departament FROM departaments WHERE id = $departament_id LIMIT 1");
  if ($result && $result->num_rows > 0) {
    $departament = $result->fetch_assoc();
  } else {
    $message = "No s'ha trobat el departament.";
  }

  if (isset($_POST['delete_dispositiu'])) {
    $dispositiu_id = (int) $_POST['dispositiu_id'];
    
    $delete_query = $db->query("DELETE FROM dispositius WHERE id = $dispositiu_id AND departament_id = $departament_id");

    if ($delete_query) {
      $message = "Dispositiu eliminat correctament.";
    } else {
      $message = "Error en eliminar el dispositiu.";
    }
  }
} else {
  $message = "No s'ha especificat un departament.";
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <?php if (!empty($message)): ?>
      <div class="alert alert-<?php echo ($delete_query) ? 'success' : 'danger'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="col-md-3"></div>
</div>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-body">
        <?php if (!empty($departament)): ?>
        <form method="POST" action="">
          <input type="hidden" name="departament_id" value="<?php echo (int) $departament_id; ?>">
          <div class="form-group">
            <label for="dispositiu_select"><?php echo remove_junk(ucwords($departament['departament'])); ?> - Dispositius</label>
            <select class="form-control" id="dispositiu_select" name="dispositiu_id" required>
              <option value="">Selecciona'n un</option>
              <?php while ($dispositiu = $dispositius->fetch_assoc()): ?>
                <option value="<?php echo (int) $dispositiu['id']; ?>">
                  <?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <a href="departaments.php" class="btn btn-danger">Torna enrere</a>
          <button type="submit" name="delete_dispositiu" class="btn btn-primary">Eliminar</button>
        </form>
        <?php else: ?>
          <p class="text-danger">No hi ha dispositius disponibles per aquest departament.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>