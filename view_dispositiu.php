<?php
$page_title = 'Vore un dispositiu en concret';
require_once('includes/load.php');

$dispositiu_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($dispositiu_id) {
    $dispositiu = $db->query("SELECT d.dispositiu, c.* 
                                FROM dispositius d
                                JOIN caracteristiques_detalls c ON d.id = c.dispositiu_id 
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
                <h4><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?></h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                <a href="#" class="btn btn-danger">Torna enrere</a>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<?php include_once('layouts/footer.php'); ?>