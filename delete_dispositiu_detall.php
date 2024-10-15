<?php
$page_title = 'Dispositius del departament';
require_once('includes/load.php');

// Verificar si se ha recibido el ID del departamento
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $departament_id = (int) $_GET['id'];

  // Consultar la base de datos para obtener el nombre del departamento
  $result = $db->query("SELECT departament FROM departaments WHERE id = $departament_id LIMIT 1");
  if ($result && $result->num_rows > 0) {
    $departament = $result->fetch_assoc();
    
    // Consultar la base de datos para obtener los dispositius del departamento
    $dispositius = $db->query("SELECT id, dispositiu FROM dispositius WHERE departament_id = $departament_id ORDER BY dispositiu");

  } else {
    // Si no se encuentra el departamento, mostrar un mensaje de error
    $error = "No s'ha trobat el departament.";
  }
} else {
  // Si no se pasa un ID de departamento válido
  $error = "ID de departament no vàlid.";
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-body">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger">
            <?php echo $error; ?>
          </div>
          <a href="departaments.php" class="btn btn-danger">Torna enrere</a>
        <?php else: ?>
          <form method="GET" action="dispositiu_detall.php">
            <input type="hidden" name="departament_id" value="<?php echo (int) $departament_id; ?>">
            <div class="form-group">
              <label for="dispositiu_select">
                <?php echo remove_junk(ucwords($departament['departament'])); ?> - Dispositius
              </label>
              <select class="form-control" id="dispositiu_select" name="id" required>
                <option value="">Selecciona'n un</option>
                <?php while ($dispositiu = $dispositius->fetch_assoc()): ?>
                  <option value="<?php echo (int) $dispositiu['id']; ?>">
                    <?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <a href="departaments.php" class="btn btn-danger">Torna enrere</a>
            <button type="submit" class="btn btn-info">Mira un dispositiu</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>