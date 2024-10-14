<?php
$page_title = 'Dispositius del Propietari';
require_once('includes/load.php');

if (isset($_GET['id'])) {
    $propietari_id = (int) $_GET['id'];

    // Connexíó BDA per obtindre els dispositius del propietari
    $dispositius = $db->query("SELECT d.id, d.dispositiu 
                                FROM dispositius d 
                                JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id 
                                WHERE dp.propietari_id = $propietari_id 
                                ORDER BY d.dispositiu");

    // Obtindre el nom del propietari
    $propietari = $db->query("SELECT nom, cognom 
                                FROM propietaris 
                                WHERE id = $propietari_id LIMIT 1")->fetch_assoc();

    // Connexió BDA per obtenir el departament del propietari
    $departament = $db->query("SELECT d.id as departament_id, d.departament as departament_nom 
                            FROM departaments d 
                            JOIN dispositius disp ON disp.departament_id = d.id 
                            JOIN dispositiu_propietari dp ON dp.dispositiu_id = disp.id 
                            WHERE dp.propietari_id = $propietari_id 
                            LIMIT 1")->fetch_assoc();
} else {
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo remove_junk(ucwords($propietari['nom'] . ' ' . $propietari['cognom'])); ?> -
                                Dispositius</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dispositiu = $dispositius->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($dispositius->num_rows == 0): ?>
                            <tr>
                                <td colspan="2">No hi ha dispositius disponibles per a aquest propietari.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-3">
                    <a href="dispositiu_detall.php?departament_id=<?php echo (int) $departament['departament_id']; ?>&id=<?php echo (int) $propietari_id; ?>"
                        class="btn btn-danger">Torna a les Característiques</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>