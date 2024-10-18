<?php
$page_title = 'Selecció de Departaments';
require_once('includes/load.php');

// Obtindre departaments de la BDA 
$departaments = $db->query("SELECT MIN(id) as id, departament FROM departaments GROUP BY departament ORDER BY departament");

$msg = [];

// Verificar si s'ha enviat el formulari
if (isset($_POST['departament_id']) && !empty($_POST['departament_id'])) {
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
            <select name="departament_id" id="departament_id" class="form-control" required onchange="this.form.submit()">
              <option value="">Selecciona'n un</option>
              <?php while ($departament = $departaments->fetch_assoc()): ?>
                <option value="<?php echo (int) $departament['id']; ?>">
                  <?php echo remove_junk(ucwords($departament['departament'])); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>