<?php
$page_title = 'Modificant un Departament';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
//page_require_level(1);
?>
<?php
$e_departament = find_by_id('departaments', (int) $_GET['id']);
$groups = find_all('departaments');
if (!$e_departament) {
  $session->msg("d", "No es troba l'id del departament.");
  redirect('departaments.php');
}

// Agafant camps de la BDA
$departaments = $db->query("SELECT departament FROM departaments");
?>

<?php
//Update Departament basic info
if (isset($_POST['update'])) {
  $req_fields = array('departament-titol', 'dispositiu-nom', 'nom-persona', 'cognom-persona');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int) $e_departament['id'];
    $departament = remove_junk($db->escape($_POST['departament-titol']));
    $dispositiu = remove_junk($db->escape($_POST['dispositiu-nom']));
    $nom = remove_junk($db->escape($_POST['nom-persona']));
    $cognom = remove_junk($db->escape($_POST['cognom-persona']));
    $sql = "UPDATE departaments SET departament ='{$departament}', dispositiu ='{$dispositiu}', nom='{$nom}', cognom='{$cognom}' WHERE id='{$db->escape($id)}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Departament actualitzat! ");
      redirect('edit_departament.php?id=' . (int) $e_departament['id'], false);
    } else {
      $session->msg('d', "No s'ha pogut actualitzar!");
      redirect('edit_departament.php?id=' . (int) $e_departament['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_departament.php?id=' . (int) $e_departament['id'], false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
  <div class="col-md-3"></div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Actualitzar <?php echo remove_junk(ucwords($e_departament['departament'])); ?> Departament
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_departament.php?id=<?php echo (int) $e_departament['id']; ?>" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Departament</label>
            <!--<input type="name" class="form-control" name="departament-titol"
              value="<?php echo remove_junk(ucwords($e_departament['departament'])); ?>">-->
            <select name="departament-titol" class="form-control" required>
              <option value="">Selecciona un departament</option>
              <?php while ($departament = $departaments->fetch_assoc()): ?>
                <option value="<?php echo $departament['departament']; ?>">
                  <?php echo $departament['departament']; ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Dispostiu</label>
            <input type="name" class="form-control" name="dispositiu-nom"
              value="<?php echo remove_junk(ucwords($e_departament['dispositiu'])); ?>">
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Nom</label>
            <input type="name" class="form-control" name="nom-persona"
              value="<?php echo remove_junk(ucwords($e_departament['nom'])); ?>">
          </div>
          <div class="form-group">
            <label for="name" class="control-label">Cognom</label>
            <input type="name" class="form-control" name="cognom-persona"
              value="<?php echo remove_junk(ucwords($e_departament['cognom'])); ?>">
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update" class="btn btn-info">Actualitzar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>
<?php include_once('layouts/footer.php'); ?>