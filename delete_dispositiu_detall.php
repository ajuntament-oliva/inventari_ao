<?php
$page_title = 'Eliminar Dispositiu';
require_once('includes/load.php');

$message = '';

if (isset($_GET['departament_id'])) {
  $departament_id = (int) $_GET['departament_id'];

  // Modificar la consulta para unir dispositius y caracteristiques_detalls, y ordenar por data_creacio y hora_creacio
  $dispositius = $db->query("
    SELECT d.id, d.dispositiu 
    FROM dispositius d
    JOIN caracteristiques_detalls cd ON d.id = cd.dispositiu_id
    WHERE d.departament_id = $departament_id
    ORDER BY cd.data_creacio ASC, cd.hora_creacio ASC
  ");

  $result = $db->query("SELECT departament FROM departaments WHERE id = $departament_id LIMIT 1");
  if ($result && $result->num_rows > 0) {
    $departament = $result->fetch_assoc();
  } else {
    $message = "No s'ha trobat el departament.";
  }

  if (isset($_POST['confirm_delete'])) {
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
              <label for="dispositiu_select"><?php echo remove_junk(ucwords($departament['departament'])); ?> -
                Dispositius</label>
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
            <button type="button" class="btn btn-primary" id="btnEliminar">Eliminar</button>
          </form>
        <?php else: ?>
          <p class="text-danger">No hi ha dispositius disponibles per aquest departament.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<!-- Modal de confirmació -->
<div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmació d'eliminació</h5>
      </div>
      <div class="modal-body">
        <p>Estàs segur que vols eliminar aquest dispositiu?</p>
      </div>
      <div class="modal-footer">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          <form method="POST" action="" id="deleteForm" style="display: inline;">
            <input type="hidden" name="dispositiu_id" id="dispositiu_id_confirm">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Sí</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
<script src="libs/js/delete_dispositiu_detall_dinamic.js"></script>