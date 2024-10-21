<?php
$page_title = 'Dispositius del departament';
require_once('includes/load.php');

if (isset($_GET['id'])) {
  $departament_id = (int) $_GET['id'];

  // Obtindre el nom del departament
  $result = $db->query("SELECT departament FROM departaments WHERE id = $departament_id LIMIT 1");
  if ($result && $result->num_rows > 0) {
    $departament = $result->fetch_assoc();
  } else {
    $_SESSION['message'] = "No s'ha trobat el departament.";
    header("Location: departaments.php");
    exit();
  }
} else {
  $_SESSION['message'] = "No s'ha proporcionat cap identificador de departament.";
  header("Location: departaments.php");
  exit();
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-body">
        <form method="GET" action="dispositiu_detall.php" id="dispositiuForm">
          <input type="hidden" name="departament_id" value="<?php echo (int) $departament_id; ?>">
          <div class="form-group">
            <?php if (isset($departament)): ?>
              <label for="dispositiu_select"><?php echo remove_junk(ucwords($departament['departament'])); ?> -
                Dispositius</label>
              <select class="form-control" id="dispositiu_select" name="id" required onchange="this.form.submit();">
                <option value="">Selecciona'n un</option>
                <option value="Monitor">Monitor</option>
                <option value="Teclat">Teclat</option>
                <option value="Torre">Torre</option>
                <option value="Portàtil">Portàtil</option>
              </select>
            <?php else: ?>
              <p>No s'ha trobat cap departament.</p>
            <?php endif; ?>
          </div>
          <a href="departaments.php" class="btn btn-danger">Torna enrere</a>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <h5>Accions</h5>
    <a href="add_dispositiu_detall.php?departament_id=<?php echo $departament_id; ?>" class="btn btn-warning"><i
        class="glyphicon glyphicon-plus"></i></a>
    <a href="edit_dispositiu_detall.php?departament_id=<?php echo $departament_id; ?>" class="btn btn-primary"><i
        class="glyphicon glyphicon-pencil"></i></a>
    <a href="read_dispositiu_detall.php?departament_id=<?php echo $departament_id; ?>" class="btn btn-success"><i
        class="glyphicon glyphicon-eye-open"></i></a>
    <a href="delete_dispositiu_detall.php?departament_id=<?php echo $departament_id; ?>" class="btn btn-default"><i
        class="glyphicon glyphicon-trash"></i></a>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>