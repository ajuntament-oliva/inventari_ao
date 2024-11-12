<?php
$page_title = 'Vore tots els dispositius i les seues característiques';
require_once('includes/load.php');

$error_message = '';

$departament_id = isset($_GET['departament_id']) ? (int) $_GET['departament_id'] : 0;

$departament = $db->query("SELECT departament FROM departaments WHERE id = $departament_id")->fetch_assoc();
$nomDepartament = htmlspecialchars($departament['departament']);

$dispositius = $db->query("SELECT d.id as dispositiu_id, d.dispositiu, p.nom, p.cognom, p.nom_actual, p.cognom_actual, p.comentaris
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
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3><?php echo remove_junk(ucwords($nomDepartament ?? '')); ?> - Dispositius</h3>
                <?php
                if ($dispositius && $dispositius->num_rows > 0) {
                    while ($dispositiu = $dispositius->fetch_assoc()) {
                        $caracteristiques = $db->query("SELECT uid, id_anydesck, num_serie, processador, ram, capacitat, marca, dimensions, tipus, data_inici, data_final
                                                          FROM caracteristiques_detalls
                                                          WHERE dispositiu_id = " . (int) $dispositiu['dispositiu_id'] . "
                                                          ORDER BY id");
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> - Característiques</label>
                            </div>
                            <table class="table table-bordered table-hover text-center">
                                <thead>
                                    <tr>
                                        <?php if ($dispositiu['dispositiu'] == 'Monitor') { ?>
                                            <th class="text-center">Marca</th>
                                            <th class="text-center">Polçades</th>
                                            <th class="text-center">Nº sèrie</th>
                                            <th class="text-center">Data adquisició</th>
                                            <th class="text-center">Data cessió</th>
                                            <th class="text-center">Propietari/a</th>
                                            <th class="text-center">Comentaris</th>
                                        <?php } elseif ($dispositiu['dispositiu'] == 'Teclat') { ?>
                                            <th class="text-center">Marca</th>
                                            <th class="text-center">Tipus</th>
                                            <th class="text-center">Data adquisició</th>
                                            <th class="text-center">Data cessió</th>
                                            <th class="text-center">Propietari/a</th>
                                            <th class="text-center">Comentaris</th>
                                        <?php } elseif ($dispositiu['dispositiu'] == 'Torre' || $dispositiu['dispositiu'] == 'Portàtil') { ?>
                                            <th class="text-center">UID</th>
                                            <th class="text-center">ID AnyDesk</th>
                                            <th class="text-center">Processador</th>
                                            <th class="text-center">RAM</th>
                                            <th class="text-center">Capacitat Disc Dur</th>
                                            <?php if ($dispositiu['dispositiu'] == 'Portàtil') { ?>
                                                <th class="text-center">Marca</th>
                                            <?php } ?>
                                            <th class="text-center">Data adquisició</th>
                                            <th class="text-center">Data cessió</th>
                                            <th class="text-center">Propietari/a</th>
                                            <th class="text-center">Comentaris</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($caracteristica = $caracteristiques->fetch_assoc()): ?>
                                        <tr>
                                            <?php if ($dispositiu['dispositiu'] == 'Monitor') { ?>
                                                <td><?php echo remove_junk(ucwords($caracteristica['marca'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['dimensions'])); ?></td>
                                                <td><?php echo remove_junk(ucwords($caracteristica['num_serie'])); ?></td>
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
                                            <td><?php $dataInici = DateTime::createFromFormat('Y-m-d', $caracteristica['data_inici']);
                                            echo $dataInici ? $dataInici->format('d/m/Y') : ''; ?></td>
                                            <td><?php $dataFinal = DateTime::createFromFormat('Y-m-d', $caracteristica['data_final']);
                                            echo $dataFinal ? $dataFinal->format('d/m/Y') : ''; ?></td>
                                            <td>
                                                <?php
                                                if (!empty($dispositiu['nom_actual']) && !empty($dispositiu['cognom_actual'])) {
                                                    echo remove_junk(ucwords($dispositiu['nom_actual'] . ' ' . $dispositiu['cognom_actual']));
                                                } else {
                                                    echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom']));
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo nl2br(remove_junk(ucwords($dispositiu['comentaris']))); ?></td>
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
                <div class="panel-footer">
                    <div class="mt-3">
                        <a href="view_departament.php?id=<?php echo (int) $departament_id; ?>"
                            class="btn btn-danger">Torna enrere</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<?php include_once('layouts/footer.php'); ?>