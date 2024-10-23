<?php
$page_title = 'Vore un dispositiu en concret';
require_once('includes/load.php');

$dispositiu_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($dispositiu_id) {
    $dispositiu = $db->query(" SELECT d.dispositiu, p.nom, p.cognom, p.id AS propietari_id, c.*
                                    FROM dispositius d
                                    JOIN caracteristiques_detalls c ON d.id = c.dispositiu_id 
                                    JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id
                                    JOIN propietaris p ON dp.propietari_id = p.id
                                    WHERE d.id = $dispositiu_id")->fetch_assoc();

    if (!$dispositiu) {
        header("Location: departaments.php");
        exit();
    }
} else {
    header("Location: departaments.php");
    exit();
}

include_once('layouts/header.php');
?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> -
                    <?php echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom'])); ?>
                </h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <!-- Monitor -->
                        <?php if ($dispositiu['dispositiu'] == 'Monitor'): ?>
                            <tr>
                                <th>Marca</th>
                                <td><?php echo $dispositiu['marca']; ?></td>
                            </tr>
                            <tr>
                                <th>Dimensions</th>
                                <td><?php echo $dispositiu['dimensions']; ?></td>
                            </tr>
                        <?php elseif ($dispositiu['dispositiu'] == 'Teclat'): ?>
                            <tr>
                                <th>Marca</th>
                                <td><?php echo $dispositiu['marca']; ?></td>
                            </tr>
                            <tr>
                                <th>Tipus</th>
                                <td><?php echo $dispositiu['tipus']; ?></td>
                            </tr>
                        <?php elseif ($dispositiu['dispositiu'] == 'Torre'): ?>
                            <tr>
                                <th>Uid</th>
                                <td><?php echo $dispositiu['uid']; ?></td>
                            </tr>
                            <tr>
                                <th>Id_anydesck</th>
                                <td><?php echo $dispositiu['id_anydesck']; ?></td>
                            </tr>
                            <tr>
                                <th>Processador</th>
                                <td><?php echo $dispositiu['processador']; ?></td>
                            </tr>
                            <tr>
                                <th>RAM</th>
                                <td><?php echo $dispositiu['ram']; ?></td>
                            </tr>
                            <tr>
                                <th>Capacitat</th>
                                <td><?php echo $dispositiu['capacitat']; ?></td>
                            </tr>
                        <?php elseif ($dispositiu['dispositiu'] == 'Portàtil'): ?>
                            <tr>
                                <th>Uid</th>
                                <td><?php echo $dispositiu['uid']; ?></td>
                            </tr>
                            <tr>
                                <th>Id_anydesck</th>
                                <td><?php echo $dispositiu['id_anydesck']; ?></td>
                            </tr>
                            <tr>
                                <th>Processador</th>
                                <td><?php echo $dispositiu['processador']; ?></td>
                            </tr>
                            <tr>
                                <th>RAM</th>
                                <td><?php echo $dispositiu['ram']; ?></td>
                            </tr>
                            <tr>
                                <th>Capacitat</th>
                                <td><?php echo $dispositiu['capacitat']; ?></td>
                            </tr>
                            <tr>
                                <th>Marca</th>
                                <td><?php echo $dispositiu['marca']; ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Data Creació</th>
                            <td><?php echo $dispositiu['data_creacio']; ?></td>
                        </tr>
                        <tr>
                            <th>Hora Creació</th>
                            <td><?php echo $dispositiu['hora_creacio']; ?></td>
                        </tr>
                        <tr>
                            <th>Data Actualització</th>
                            <td><?php echo ($dispositiu['data_actualitzacio'] == '0000-00-00') ? "No s'ha actualitzat" : $dispositiu['data_actualitzacio']; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Hora Actualització</th>
                            <td><?php echo ($dispositiu['hora_actualitzacio'] == '00:00:00') ? "No s'ha actualitzat" : $dispositiu['hora_actualitzacio']; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <input type="hidden" name="propietari_id" value="<?php echo $dispositiu['propietari_id']; ?>">
                <a href="propietari_dispositius.php?id=<?php echo $dispositiu['propietari_id']; ?>"
                    class="btn btn-danger">Torna enrere</a>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<?php include_once('layouts/footer.php'); ?>