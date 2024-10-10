<?php
$page_title = 'Modificar Propietari i Dispositiu';
require_once('includes/load.php');

// Activa la visualització d'errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_GET['dispositiu_id']) && !empty($_GET['dispositiu_id'])) {
  $dispositiu_id = (int) $_GET['dispositiu_id'];
} else {
  $session->msg('d', "Dispositiu ID no vàlid.");
  redirect('departaments.php', false);
  exit;
}

$dispositiu = $db->query("SELECT * FROM dispositius WHERE id = $dispositiu_id")->fetch_assoc();
if (!$dispositiu) {
    $session->msg('d', "Dispositiu no trobat.");
    redirect('departaments.php', false);
    exit;
}

$departament_id = $dispositiu['departament_id']; // Asegúrate de que esta clave existe en $dispositiu
$departament_query = $db->query("SELECT * FROM departaments WHERE id = $departament_id");
$departament = $departament_query->fetch_assoc();
if (!$departament) {
    $session->msg('d', "Departament no trobat amb ID: $departament_id.");
    redirect('departaments.php', false);
    exit;
}

// Consultar la informació del propietari associat al dispositiu
$dispositiu_assoc = $db->query("SELECT propietari_id FROM dispositiu_propietari WHERE dispositiu_id = $dispositiu_id");
if ($dispositiu_assoc) {
  $propietari_id = $dispositiu_assoc->fetch_assoc()['propietari_id'] ?? null;
} else {
  $session->msg('d', "No propietari found for this dispositiu.");
  redirect('departaments.php', false);
  exit;
}

// If there's no propietari_id, handle the error
if (!$propietari_id) {
  $session->msg('d', "Propietari not found.");
  redirect('departaments.php', false);
  exit;
} else {
  // Fetch propietari details
  $propietari = $db->query("SELECT * FROM propietaris WHERE id = $propietari_id")->fetch_assoc();
  if (!$propietari) {
    $session->msg('d', "Propietari details not found.");
    redirect('departaments.php', false);
    exit;
  }
}

// Consultar la informació del propietari associat al dispositiu
$propietari_id = $db->query("SELECT propietari_id FROM dispositiu_propietari WHERE dispositiu_id = $dispositiu_id")->fetch_assoc()['propietari_id'];
$propietari = $db->query("SELECT * FROM propietaris WHERE id = $propietari_id")->fetch_assoc();

// Si es confirma el formulari
if (isset($_POST['edit_device'])) {
  $propietari_nom = remove_junk($db->escape($_POST['nom'] ?? ''));
  $propietari_cognom = remove_junk($db->escape($_POST['cognom'] ?? ''));
  $dispositiu_nom = remove_junk($db->escape($_POST['dispositiu'] ?? ''));

  // Actualització del propietari
  if ($propietari_nom && $propietari_cognom) {
    $sql_update_owner = "UPDATE propietaris SET nom = '$propietari_nom', cognom = '$propietari_cognom' WHERE id = $propietari_id";
    if (!$db->query($sql_update_owner)) {
      $session->msg('d', "Error actualitzant el propietari: " . $db->error);
    }
  }

  // Actualització del dispositiu
  if ($dispositiu_nom) {
    $sql_update_device = "UPDATE dispositius SET dispositiu = '$dispositiu_nom' WHERE id = $dispositiu_id";
    if ($db->query($sql_update_device)) {
      // Actualitzar característiques segons el tipus de dispositiu
      $uid = $id_anydesck = $processador = $ram = $capacitat = $marca = $dimensions = $tipus = '';
      if ($dispositiu_nom == 'Monitor') {
        $marca = remove_junk($db->escape($_POST['marca'] ?? ''));
        $dimensions = remove_junk($db->escape($_POST['dimensions'] ?? ''));
      } elseif ($dispositiu_nom == 'Teclat') {
        $marca = remove_junk($db->escape($_POST['marca'] ?? ''));
        $tipus = remove_junk($db->escape($_POST['tipus'] ?? ''));
      } elseif ($dispositiu_nom == 'Torre' || $dispositiu_nom == 'Portàtil') {
        $uid = remove_junk($db->escape($_POST['uid'] ?? ''));
        $id_anydesck = remove_junk($db->escape($_POST['id_anydesck'] ?? ''));
        $processador = remove_junk($db->escape($_POST['processador'] ?? ''));
        $ram = remove_junk($db->escape($_POST['ram'] ?? ''));
        $capacitat = remove_junk($db->escape($_POST['capacitat'] ?? ''));
        if ($dispositiu_nom == 'Portàtil') {
          $marca = remove_junk($db->escape($_POST['marca'] ?? ''));
        }
      }

      // Actualització de les característiques
      $sql_update_features = "UPDATE caracteristiques_detalls SET uid = '$uid', id_anydesck = '$id_anydesck', processador = '$processador', 
                ram = '$ram', capacitat = '$capacitat', marca = '$marca', dimensions = '$dimensions', tipus = '$tipus' 
                WHERE dispositiu_id = $dispositiu_id";
      if ($db->query($sql_update_features)) {
        $session->msg('s', "Dispositiu i característiques actualitzades amb èxit.");
        redirect('edit_dispositiu_detall.php?dispositiu_id=' . $dispositiu_id, false);
      } else {
        $session->msg('d', "Error actualitzant les característiques: " . $db->error);
      }
    } else {
      $session->msg('d', "Error actualitzant el dispositiu: " . $db->error);
    }
  } else {
    $session->msg('d', "Tots els camps obligatoris han d'estar plens.");
  }
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
      <h4 class="panel-title">Afegir Propietari i Dispositiu - <?php echo remove_junk(ucwords($departament['departament'])); ?></h4>
      </div>
      <div class="panel-body">
        <form method="post" action="" novalidate>
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

          <div class="form-group">
            <label>Selecciona el dispositiu:</label><br>
            <div class="radio">
              <label><input type="radio" name="dispositiu" value="Monitor" <?php echo $dispositiu['dispositiu'] == 'Monitor' ? 'checked' : ''; ?>> Monitor</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="dispositiu" value="Teclat" <?php echo $dispositiu['dispositiu'] == 'Teclat' ? 'checked' : ''; ?>> Teclat</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="dispositiu" value="Torre" <?php echo $dispositiu['dispositiu'] == 'Torre' ? 'checked' : ''; ?>> Torre</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="dispositiu" value="Portàtil" <?php echo $dispositiu['dispositiu'] == 'Portàtil' ? 'checked' : ''; ?>> Portàtil</label>
            </div>
          </div>

          <a href="departaments.php" class="btn btn-danger">Cancel·la</a>
          <button type="submit" name="edit_device" class="btn btn-primary">Actualitza</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>