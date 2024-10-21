<?php
$page_title = 'Característiques';
require_once('includes/load.php');

$error_message = '';

if (isset($_GET['id']) && isset($_GET['departament_id'])) {
    $dispositiu_nom = $db->escape($_GET['id']);
    $departament_id = (int) $_GET['departament_id'];

    // Consultar BDA per obtindre el nom del dispositiu seleccionat
    $dispositiu = $db->query("SELECT d.dispositiu, d.id as dispositiu_id 
                               FROM dispositius d 
                               WHERE d.dispositiu = ? AND d.departament_id = ? LIMIT 1",
        [$dispositiu_nom, $departament_id]
    )->fetch_assoc();

    if ($dispositiu) {
        // Consultar BDA per obtindre les característiques de tots els dispositius del mateix tipus en el departament actual
        $caracteristiques = $db->query("SELECT DISTINCT c.uid, c.id_anydesck, c.processador, c.ram, c.capacitat, 
                                                            c.marca, c.dimensions, c.tipus, p.id as propietari_id, p.nom, p.cognom 
                                            FROM caracteristiques_detalls c
                                            JOIN dispositius d ON c.dispositiu_id = d.id
                                            JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id 
                                            JOIN propietaris p ON dp.propietari_id = p.id 
                                            WHERE d.dispositiu = ? AND d.departament_id = ?
                                            ORDER BY d.id", [$dispositiu_nom, $departament_id]);
    } else {
        $error_message = "Dispositiu no trobat.";
    }
} else {
    $error_message = "ID del dispositiu o departament no especificat.";
}

include_once('layouts/header.php');
?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
                        <?php echo remove_junk($error_message); ?>
                    </div>
                <?php else: ?>
                    <h4><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> - Característiques</h4>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if (!$error_message): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
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
                                        <td>
                                            <a href="propietari_dispositius.php?id=<?php echo (int)$caracteristica['propietari_id']; ?>">
                                                <?php echo remove_junk(ucwords($caracteristica['nom'] . ' ' . $caracteristica['cognom'])); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="panel-footer">
                <a href="view_departament.php?id=<?php echo isset($departament_id) ? (int) $departament_id : 0; ?>" class="btn btn-danger">Torna enrere</a>
            </div>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<?php include_once('layouts/footer.php'); ?>