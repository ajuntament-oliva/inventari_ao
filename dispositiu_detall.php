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
        // Consultar BDA per obtindre el nom del departament
        $departament = $db->query("SELECT departament FROM departaments WHERE id = ? LIMIT 1", [$departament_id])->fetch_assoc();
        
        // Consultar BDA per obtindre les característiques de tots els dispositius del mateix tipus en el departament actual
        $caracteristiques = $db->query("SELECT DISTINCT c.uid, c.id_anydesck, c.num_serie, c.processador, c.ram, c.capacitat, 
                                                            c.marca, c.dimensions, c.tipus, p.id as propietari_id, p.nom, p.cognom, p.nom_actual, p.cognom_actual, p.comentaris
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
?>

<?php
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
                    <h4>
                        <?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?>
                        <?php if (isset($departament['departament'])): ?>
                            - <?php echo remove_junk(ucwords($departament['departament'])); ?>
                        <?php endif; ?>
                    </h4>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if (!$error_message): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead>
                                <tr>
                                    <?php if ($dispositiu['dispositiu'] == 'Monitor') { ?>
                                        <th class="text-center">Marca</th>
                                        <th class="text-center">Polçades</th>
                                        <th class="text-center">Nº de sèrie</th>
                                        <th class="text-center">Propietari/a actual</th>
                                        <th class="text-center">Comentaris</th>
                                    <?php } elseif ($dispositiu['dispositiu'] == 'Teclat') { ?>
                                        <th class="text-center">Marca</th>
                                        <th class="text-center">Tipus</th>
                                        <th class="text-center">Propietari/a actual</th>
                                        <th class="text-center">Comentaris</th>
                                    <?php } elseif ($dispositiu['dispositiu'] == 'Torre' || $dispositiu['dispositiu'] == 'Portàtil') { ?>
                                        <th class="text-center">UID</th>
                                        <th class="text-center">ID AnyDesk</th>
                                        <th class="text-center">Processador</th>
                                        <th class="text-center">RAM</th>
                                        <th class="text-center">Capacitat</th>
                                        <?php if ($dispositiu['dispositiu'] == 'Portàtil') { ?>
                                            <th class="text-center">Marca</th>
                                        <?php } ?>
                                        <th class="text-center">Propietari/a actual</th>
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
                                        <td>
                                            <a href="propietari_dispositius.php?id=<?php echo (int)$caracteristica['propietari_id']; ?>">
                                                <?php if (!empty($caracteristica['nom_actual']) && !empty($caracteristica['cognom_actual'])) {
                                                        echo remove_junk(ucwords($caracteristica['nom_actual'] . ' ' . $caracteristica['cognom_actual']));
                                                    } else {
                                                        echo remove_junk(ucwords($caracteristica['nom'] . ' ' . $caracteristica['cognom']));
                                                    } 
                                                ?>
                                            </a>
                                        </td>
                                        <td class="comentaris">
                                            <?php echo nl2br(remove_junk(($caracteristica['comentaris']))); ?>
                                            <a class="btn btn-primary btn-editar" href="#" data-toggle="modal" data-target="#editModal" data-id="<?php echo (int)$caracteristica['propietari_id']; ?>" data-comentaris="<?php echo htmlspecialchars($caracteristica['comentaris']); ?>"><i
                                            class="glyphicon glyphicon-pencil"></i></a>
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

<!-- Modal editar comentaris -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" data-backdrop="static" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="actualitzar_comentaris.php" method="POST">
                <div class="modal-header">
                    <h3 class="modal-title" id="editModalLabel">Editar Comentaris</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label for="comentaris">Propietari antic</label>
                        <textarea class="form-control" id="edit-comentaris" name="comentaris"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
<script src="libs/js/actualitzar_comentaris.js"></script>