<?php
$page_title = 'Afegir Propietari i Dispositiu';
require_once('includes/load.php');

// Activa la visualització d'errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtindre l'ID del departament actual
$departament_id = isset($_GET['departament_id']) ? (int) $_GET['departament_id'] : 0;

// Obtindre el nom del departament
$departament = $db->query("SELECT departament FROM departaments WHERE id = $departament_id")->fetch_assoc();
$nomDepartament = htmlspecialchars($departament['departament']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_owner'])) {
    $propietari_exist = $_POST['propietari_exist'] ?? '';
    $propietari_nom = remove_junk($db->escape($_POST['nom'] ?? ''));
    $propietari_cognom = remove_junk($db->escape($_POST['cognom'] ?? ''));
    $dispositiu_nom = remove_junk($db->escape($_POST['dispositiu'] ?? ''));

    $propietari_id = null;
    $data_creacio = date('Y-m-d');
    $hora_creacio = date('H:i:s');

    // Comprovar si s'ha seleccionat un propietari existent
    if ($propietari_exist) {
      $propietari_id = (int) $propietari_exist;
    } elseif ($propietari_nom && $propietari_cognom) {
      $sql_insert_owner = "INSERT INTO propietaris (nom, cognom) VALUES ('$propietari_nom', '$propietari_cognom')";
      if ($db->query($sql_insert_owner)) {
        $propietari_id = $db->insert_id();
      } else {
        $session->msg('d', "Error afegint el propietari: " . $db->error);
      }
    } else {
      $session->msg('d', "Has de seleccionar un propietari existent o afegir-ne un de nou.");
    }

    // Inserció del dispositiu i la seva relació amb el propietari
    if ($propietari_id && $dispositiu_nom) {
      $sql_insert_device = "INSERT INTO dispositius (dispositiu, departament_id) VALUES ('$dispositiu_nom', $departament_id)";
      if ($db->query($sql_insert_device)) {
        $dispositiu_id = $db->insert_id();

        $sql_insert_device_owner = "INSERT INTO dispositiu_propietari (dispositiu_id, propietari_id) VALUES ($dispositiu_id, $propietari_id)";
        if (!$db->query($sql_insert_device_owner)) {
          $session->msg('d', "Error afegint la relació dispositiu-propietari: " . $db->error);
        }
      } else {
        $session->msg('d', "Error afegint el dispositiu: " . $db->error);
      }
    }

    // Verificació per dispositiu
    $uid = $id_anydesck = $processador = $ram = $capacitat = $marca = $dimensions = $tipus = '';

    if ($dispositiu_nom == 'Monitor') {
      $marca = remove_junk($db->escape($_POST['marca'] ?? ''));
      $dimensions = remove_junk($db->escape($_POST['dimensions'] ?? ''));
      $num_serie = remove_junk($db->escape($_POST['num_serie'] ?? ''));
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
    $data_inicial = !empty($_POST['data_inici']) ? $db->escape($_POST['data_inici']) : NULL;

    // Comprovar si els camps obligatoris estan plens
    if ($propietari_id && $dispositiu_nom) {
      // Inserir característiques
      $sql_insert_feature = "INSERT INTO caracteristiques_detalls 
(dispositiu_id, uid, id_anydesck, num_serie, processador, ram, capacitat, marca, dimensions, tipus, data_inici, data_creacio, hora_creacio) 
VALUES (
    $dispositiu_id, 
    " . ($uid ? "'$uid'" : "NULL") . ", 
    " . ($id_anydesck ? "'$id_anydesck'" : "NULL") . ",
    " . ($num_serie ? "'$num_serie'" : "NULL") . ", 
    " . ($processador ? "'$processador'" : "NULL") . ", 
    " . ($ram ? "'$ram'" : "NULL") . ", 
    " . ($capacitat ? "'$capacitat'" : "NULL") . ", 
    " . ($marca ? "'$marca'" : "NULL") . ", 
    " . ($dimensions ? "'$dimensions'" : "NULL") . ", 
    " . ($tipus ? "'$tipus'" : "NULL") . ", 
    " . ($data_inicial ? "'$data_inicial'" : "NULL") . ",
    '$data_creacio', '$hora_creacio')";
      if ($db->query($sql_insert_feature)) {
        $session->msg('s', "Propietari, dispositiu i característiques afegits amb èxit.");
        redirect('add_dispositiu_detall.php?departament_id=' . $departament_id, false);
      } else {
        $session->msg('d', "Error afegint característiques: " . $db->error);
      }
    } else {
      $session->msg('d', "Tots els camps obligatoris han d'estar plens.");
    }
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
        <h4 class="panel-title">Afegir Propietari i Dispositiu - <?php echo $nomDepartament; ?></h4>
      </div>
      <div class="panel-body">
        <form method="post" action="" novalidate>
          <input type="hidden" name="departament_id" value="<?php echo $departament_id; ?>">

          <div class="form-group">
            <label>Selecciona el dispositiu:</label><br>
            <div class="radio">
              <label>
                <input type="radio" name="dispositiu" id="dispositiu_monitor" value="Monitor" required>
                Monitor
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="dispositiu" id="dispositiu_teclat" value="Teclat" required>
                Teclat
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="dispositiu" id="dispositiu_torre" value="Torre" required>
                Torre
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="dispositiu" id="dispositiu_portatil" value="Portàtil" required>
                Portàtil
              </label>
            </div>
          </div>

          <div class="form-group">
            <label for="propietari_exist">Selecciona un propietari existent:</label>
            <select name="propietari_exist" class="form-control">
              <option value="">Selecciona un propietari</option>
              <?php
              // Obtindre els propietaris amb dispositius en el departament seleccionat
              $sql = "SELECT DISTINCT p.id, 
                                COALESCE(p.nom_actual, p.nom) AS nom, 
                                COALESCE(p.cognom_actual, p.cognom) AS cognom 
                FROM propietaris p
                JOIN dispositiu_propietari dp ON p.id = dp.propietari_id
                JOIN dispositius d ON dp.dispositiu_id = d.id
                WHERE d.departament_id = $departament_id";
              $owners = $db->query($sql)->fetch_all(MYSQLI_ASSOC);
              foreach ($owners as $owner) {
                echo "<option value='{$owner['id']}'>" . htmlspecialchars($owner['nom'] . ' ' . $owner['cognom']) . "</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="nom">Nom del Propietari:</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="cognom">Cognom del Propietari:</label>
            <input type="text" name="cognom" id="cognom" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="data_inici">Data inicial del dispositiu:</label>
            <input type="date" name="data_inici" id="data_inici" class="form-control" required>
          </div>

          <!-- Camps dinàmics -->
          <div class="form-group" id="dynamic-fields">
            <div id="monitor-fields" style="display:none;">
              <div class="form-group">
                <label for="num_serie">Nº de sèrie:</label>
                <input type="text" name="num_serie" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" name="marca" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="dimensions">Polçades:</label>
                <input type="text" name="dimensions" class="form-control" required>
              </div>
            </div>

            <div id="teclat-fields" style="display:none;">
              <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" name="marca" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="tipus">Tipus:</label>
                <input type="text" name="tipus" class="form-control" required>
              </div>
            </div>

            <div id="torre-fields" style="display:none;">
              <div class="form-group">
                <label for="uid">UID:</label>
                <input type="text" name="uid" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="id_anydesck">ID Any Desk:</label>
                <input type="text" name="id_anydesck" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="processador">Processador:</label>
                <input type="text" name="processador" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="ram">RAM:</label>
                <input type="text" name="ram" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="capacitat">Capacitat Disc Dur:</label>
                <input type="text" name="capacitat" class="form-control" required>
              </div>
            </div>

            <div id="portatil-fields" style="display:none;">
              <div class="form-group">
                <label for="uid">UID:</label>
                <input type="text" name="uid" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="id_anydesck">ID Any Desk:</label>
                <input type="text" name="id_anydesck" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" name="marca" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="processador">Processador:</label>
                <input type="text" name="processador" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="ram">RAM:</label>
                <input type="text" name="ram" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="capacitat">Capacitat:</label>
                <input type="text" name="capacitat" class="form-control" required>
              </div>
            </div>
          </div>

          <a href="view_departament.php?id=<?php echo $departament_id; ?>" class="btn btn-danger">Torna enrere</a>
          <button type="submit" name="add_owner" class="btn btn-primary">Afegir</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>
<script src="libs/js/add_dispositiu_detall_dinamic.js"></script>