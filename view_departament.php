<?php
$page_title = 'Dispositius del Departament';
require_once('includes/load.php');

if (isset($_GET['id'])) {
  $departament_id = (int) $_GET['id'];

  // Consultar BDA conseguir dispositius
  $dispositius = $db->query("SELECT id, dispositiu FROM dispositius WHERE departament_id = $departament_id ORDER BY dispositiu");

  // Obtindre nom departament
  $departament = $db->query("SELECT departament FROM departaments WHERE id = $departament_id LIMIT 1")->fetch_assoc();
} else {
  header("Location: departaments.php");
  exit();
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <h2><?php echo remove_junk(ucwords($departament['departament'])); ?> - Dispositius</h2>
    <form method="GET" action="dispositiu_detall.php">
      <div class="form-group">
        <label for="dispositiu_select">Selecciona un dispositiu:</label>
        <select class="form-control" id="dispositiu_select" name="id" onchange="this.form.submit()">
          <option value="">Selecciona un dispositiu</option>
          <?php while ($dispositiu = $dispositius->fetch_assoc()): ?>
            <option value="<?php echo (int) $dispositiu['id']; ?>">
              <?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
    </form>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>