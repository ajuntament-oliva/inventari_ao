<?php
$page_title = 'Departaments';
require_once('includes/load.php');

// Check user permission (uncomment if needed)
// page_require_level(1);

// Obtindre departaments de la BDA 
$departaments = $db->query("SELECT MIN(id) as id, departament FROM departaments GROUP BY departament ORDER BY departament");

// Redirecció
if (isset($_POST['selec_departament'])) {
  $departament_seleccionat = $_POST['departament_id'];
  header("Location: view_departament.php?id=" . (int) $departament_seleccionat);
  exit();
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-body">
        <form method="post" action="">
          <div class="form-group">
            <label for="departament_id">Departaments:</label>
            <select name="departament_id" id="departament_id" class="form-control" required>
              <option value="">Selecciona'n un</option>
              <?php while ($departament = $departaments->fetch_assoc()): ?>
                <option value="<?php echo (int) $departament['id']; ?>">
                  <?php echo remove_junk(ucwords($departament['departament'])); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <button type="submit" name="selec_departament" class="btn btn-info">Ves a Dispositius</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>