<?php
$page_title = 'Dispositius del Propietari';
require_once('includes/load.php');

$departament_id = isset($_GET['departament_id']) ? (int) $_GET['departament_id'] : 0;

if (isset($_GET['id'])) {
    $propietari_id = (int) $_GET['id'];

    // Connexió BDA per obtenir els dispositius del propietari amb les seues característiques
    $dispositius = $db->query("SELECT d.id, d.dispositiu, c.data_inici, c.data_final
                                    FROM dispositius d
                                    JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id
                                    JOIN caracteristiques_detalls c ON d.id = c.dispositiu_id
                                    WHERE dp.propietari_id = $propietari_id
                                    ORDER BY c.data_inici ASC");

    // Obtindre el nom del propietari
    $propietari = $db->query("SELECT nom, cognom, nom_actual, cognom_actual 
                                FROM propietaris 
                                WHERE id = $propietari_id LIMIT 1")->fetch_assoc();

    // Connexió BDA per obtenir el departament del propietari
    $departament = $db->query("SELECT d.id as departament_id, d.departament as departament_nom 
                            FROM departaments d 
                            JOIN dispositius disp ON disp.departament_id = d.id 
                            JOIN dispositiu_propietari dp ON dp.dispositiu_id = disp.id 
                            WHERE dp.propietari_id = $propietari_id 
                            LIMIT 1")->fetch_assoc();

    if (!$departament) {
        header("Location: departaments.php");
        exit();
    }

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
            <div class="panel-heading">
                <h4><?php
                $nom = !empty($propietari['nom_actual']) ? $propietari['nom_actual'] : $propietari['nom'];
                $cognom = !empty($propietari['cognom_actual']) ? $propietari['cognom_actual'] : $propietari['cognom'];
                echo remove_junk(ucwords($nom . ' ' . $cognom));
                ?> - Llista de dispositius</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th class="text-center">Dispositius</th>
                                <th class="text-center">Data adquisició</th>
                                <th class="text-center">Data cessió</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($dispositiu = $dispositius->fetch_assoc()): ?>
                                <tr>
                                    <td><a href="view_dispositiu.php?id=<?php echo $dispositiu['id']; ?>">
                                            <?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?>
                                        </a></td>
                                    <td>
                                        <?php
                                        $dataInici = DateTime::createFromFormat('Y-m-d', $dispositiu['data_inici']);
                                        echo $dataInici ? $dataInici->format('d/m/Y') : '';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($dispositiu['data_final'] == '0000-00-00') {
                                            echo "";
                                        } else {
                                            $dataFinal = DateTime::createFromFormat('Y-m-d', $dispositiu['data_final']);
                                            echo $dataFinal ? $dataFinal->format('d/m/Y') : '';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($dispositius->num_rows == 0): ?>
                                <tr>
                                    <td colspan="5">No hi ha dispositius disponibles per a aquest propietari.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <input type="hidden" name="departament_id" value="<?php echo $departament['departament_id']; ?>">
                <a href="view_departament.php?id=<?php echo $departament['departament_id']; ?>"
                    class="btn btn-danger">Torna enrere</a>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>