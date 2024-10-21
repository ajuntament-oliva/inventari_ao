<?php
$page_title = 'Vore tots els dispositius i les seues característiques';
require_once('includes/load.php');

$error_message = '';

$departament_id = isset($_GET['departament_id']) ? (int)$_GET['departament_id'] : 0;

$departament = $db->query("SELECT departament FROM departaments WHERE id = $departament_id")->fetch_assoc();
$nomDepartament = htmlspecialchars($departament['departament']);

$dispositius = $db->query("SELECT d.id as dispositiu_id, d.dispositiu, p.nom, p.cognom
                                    FROM dispositius d 
                                    JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id
                                    JOIN propietaris p ON dp.propietari_id = p.id 
                                    WHERE d.departament_id = $departament_id
                                    ORDER BY d.id");

include_once('layouts/header.php');
?>

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
            <div class="panel-body">
                <h3><?php echo remove_junk(ucwords($nomDepartament ?? '')); ?> - Dispositius</h3>
                <?php
                if ($dispositius && $dispositius->num_rows > 0) {
                    while ($dispositiu = $dispositius->fetch_assoc()) {
                        $caracteristiques = $db->query("SELECT uid, id_anydesck, processador, ram, capacitat, marca, dimensions, tipus
                                                          FROM caracteristiques_detalls
                                                          WHERE dispositiu_id = " . (int)$dispositiu['dispositiu_id'] . "
                                                          ORDER BY id");
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> - Característiques</label>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <?php if ($dispositiu['dispositiu'] == 'Monitor') { ?>
                                            <th>Marca</th>
                                            <th>Dimensions</th>
                                            <th>Propietari/a</th>
                                        <?php } elseif ($dispositiu['dispositiu'] == 'Teclat') { ?>
                                            <th>Marca</th>
                                            <th>Tipus</th>
                                            <th>Propietari/a</th>
                                        <?php } elseif ($dispositiu['dispositiu'] == 'Torre' || $dispositiu['dispositiu'] == 'Portàtil') { ?>
                                            <th>UID</th>
                                            <th>ID AnyDesk</th>
                                            <th>Processador</th>
                                            <th>RAM</th>
                                            <th>Capacitat</th>
                                            <?php if ($dispositiu['dispositiu'] == 'Portàtil') { ?>
                                                <th>Marca</th>
                                            <?php } ?>
                                            <th>Propietari/a</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($caracteristica = $caracteristiques->fetch_assoc()): ?>
                                        <tr>
                                            <?php if ($dispositiu['dispositiu'] == 'Monitor') { ?>
                                                <td><?php echo remove_junk(ucwords($caracteristica['marca'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['dimensions'])); ?></td>
                                            <?php } elseif ($dispositiu['dispositiu'] == 'Teclat') { ?>
                                                <td><?php echo remove_junk(ucwords($caracteristica['marca'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['tipus'])); ?></td>
                                            <?php } else { ?>
                                                <td><?php echo remove_junk(ucwords($caracteristica['uid'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['id_anydesck'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['processador'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['ram'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['capacitat'])); ?></td>
                                                <?php if ($dispositiu['dispositiu'] == 'Portàtil') { ?>
                                                    <td><?php echo remove_junk(ucwords($caracteristica['marca'])); ?></td>
                                                <?php } ?>
                                            <?php } ?>
                                            <td><?php echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No s'han trobat dispositius per a aquest departament.</p>";
                }
                ?>
                <div class="mt-3">
                    <a href="view_departament.php?id=<?php echo (int)$departament_id; ?>" class="btn btn-danger">Torna enrere</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>