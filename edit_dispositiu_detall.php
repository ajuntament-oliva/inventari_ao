<?php
$page_title = 'Editar Propietari i Dispositiu';
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

// Obtindre tots els dispositius del departament
$dispositius = $db->query("
    SELECT d.id, d.dispositiu, p.id AS propietari_id, p.nom, p.cognom 
    FROM dispositius d 
    JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id 
    JOIN propietaris p ON dp.propietari_id = p.id 
    WHERE d.departament_id = $departament_id
")->fetch_all(MYSQLI_ASSOC);

// Actualitzar les dades si s'ha enviat el formulari
if (isset($_POST['edit_owner'])) {
    $propietari_id = isset($_POST['propietari_id']) ? (int) $_POST['propietari_id'] : 0;
    $propietari_nom = remove_junk($db->escape($_POST['nom'] ?? ''));
    $propietari_cognom = remove_junk($db->escape($_POST['cognom'] ?? ''));
    $dispositiu_id = isset($_POST['dispositiu']) ? (int) $_POST['dispositiu'] : 0;

    // Verificar que els camps no estan buits
    if ($propietari_id && !empty($propietari_nom) && !empty($propietari_cognom) && $dispositiu_id) {
        // Verificar si el propietari existeix en la taula propietaris
        $sql_check_owner = "SELECT id FROM propietaris WHERE id = $propietari_id";
        $result_check_owner = $db->query($sql_check_owner);

        if ($db->num_rows($result_check_owner) > 0) {
            // Actualitzar dades del propietari
            $sql_update_owner = "UPDATE propietaris SET nom = '$propietari_nom', cognom = '$propietari_cognom' WHERE id = $propietari_id";
            if ($db->query($sql_update_owner)) {
                // Actualizar o insertar la combinació dispositiu_id i propietari_id
                $sql_check_dispositiu_propietari = "SELECT * FROM dispositiu_propietari WHERE dispositiu_id = $dispositiu_id AND propietari_id = $propietari_id";
                $result_check_dispositiu_propietari = $db->query($sql_check_dispositiu_propietari);

                if ($db->num_rows($result_check_dispositiu_propietari) == 0) {
                    // Insertar nou propietari
                    $sql_insert_dispositiu_propietari = "INSERT INTO dispositiu_propietari (dispositiu_id, propietari_id) VALUES ($dispositiu_id, $propietari_id)";
                    if ($db->query($sql_insert_dispositiu_propietari)) {
                        $session->msg('s', "Propietari del dispositiu actualitzat amb èxit.");
                    } else {
                        $session->msg('d', "Error actualitzant el propietari del dispositiu: " . $db->error);
                    }
                } else {
                    $session->msg('s', "Propietari del dispositiu actualitzat amb èxit.");
                }
            } else {
                $session->msg('d', "Error actualitzant el propietari: " . $db->error);
            }
        } else {
            $session->msg('d', "El propietari no existeix.");
        }
    } else {
        $session->msg('d', "Tots els camps han d'estar plens i vàlids.");
    }

    redirect('edit_dispositiu_detall.php?departament_id=' . $departament_id);
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
                <h4 class="panel-title">Editar Propietari - <?php echo $nomDepartament; ?></h4>
            </div>
            <div class="panel-body">
                <form method="post" action="" novalidate>
                    <input type="hidden" name="departament_id" value="<?php echo $departament_id; ?>">

                    <div class="form-group">
                        <label for="dispositiu">Selecciona el dispositiu:</label>
                        <select name="dispositiu" id="dispositiu" class="form-control" required>
                            <option value="">Selecciona un dispositiu</option>
                            <?php foreach ($dispositius as $dispositiu): ?>
                                <option value="<?php echo $dispositiu['id']; ?>"
                                    data-nom="<?php echo htmlspecialchars($dispositiu['nom']); ?>"
                                    data-cognom="<?php echo htmlspecialchars($dispositiu['cognom']); ?>"
                                    data-propietari-id="<?php echo $dispositiu['propietari_id']; ?>">
                                    <?php echo htmlspecialchars($dispositiu['dispositiu']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <input type="hidden" name="propietari_id" id="propietari_id" value="">

                    <div class="form-group">
                        <label for="nom">Nom del Propietari:</label>
                        <input type="text" name="nom" id="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cognom">Cognom del Propietari:</label>
                        <input type="text" name="cognom" id="cognom" class="form-control" required>
                    </div>

                    <a href="departaments.php" class="btn btn-danger">Torna a Departaments</a>
                    <button type="submit" name="edit_owner" class="btn btn-primary">Actualitzar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>
<script src="libs/js/edit_dispositiu_detall_dinamic.js"></script>