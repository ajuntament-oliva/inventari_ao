<?php
$page_title = 'Dispositius del Departament';
require_once('includes/load.php');

// Verificar que el ID del departamento se ha pasado
if (isset($_GET['id'])) {
    $departament_id = (int) $_GET['id'];

    // Consultar la base de datos para obtener los dispositivos del departamento
    $dispositius = $db->query("SELECT id, dispositiu FROM dispositius WHERE departament_id = $departament_id ORDER BY nom_dispositiu");

    // Obtener el nombre del departamento (opcional)
    $departament = $db->query("SELECT departament FROM departaments WHERE id = $departament_id LIMIT 1")->fetch_assoc();
} else {
    // Manejar el caso donde no se pasa un ID
    header("Location: departaments.php");
    exit();
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <h2><?php echo remove_junk(ucwords($departament['departament'])); ?> - Dispositius</h2>
    <ul class="list-group">
      <?php while ($dispositiu = $dispositius->fetch_assoc()): ?>
        <li class="list-group-item">
          <a href="dispositiu_detall.php?id=<?php echo (int) $dispositiu['id']; ?>">
            <?php echo remove_junk(ucwords($dispositiu['nom_dispositiu'])); ?>
          </a>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>