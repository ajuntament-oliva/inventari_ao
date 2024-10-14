<?php
$page_title = 'Característiques';
require_once('includes/load.php');

if (isset($_GET['id'])) {
    $dispositiu_id = (int) $_GET['id'];

    // Consultar BDA per obtindre les característiques del dispositiu
    $caracteristiques = $db->query("SELECT uid, id_anydesck, processador, ram, capacitat, marca, dimensions, tipus FROM caracteristiques_detalls WHERE dispositiu_id = $dispositiu_id ORDER BY id");

    // Consultar BDA per obtindre el nom del dispositiu, propietari, tipus i departament
    $dispositiu = $db->query("SELECT d.dispositiu, d.id as dispositiu_id, p.nom, p.cognom, dp.propietari_id, d.departament_id
                                    FROM dispositius d 
                                    JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id 
                                    JOIN propietaris p ON dp.propietari_id = p.id 
                                    WHERE d.id = ?", [$dispositiu_id])->fetch_assoc();

} else {
    header("Location: departaments.php");
    exit();
}

include_once('layouts/header.php');
?>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                // Mostrar la taula basada en el tipus de dispositiu
                switch ($dispositiu['dispositiu']) {
                    case 'Monitor':
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> -
                                    Característiques</label>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Marca</th>
                                        <th>Dimensions</th>
                                        <th>Propietari/a</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($caracteristica = $caracteristiques->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo remove_junk(ucwords($caracteristica['marca'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['dimensions'])); ?></td>
                                            <td><a
                                                    href="propietari_dispositius.php?id=<?php echo (int) $dispositiu['propietari_id']; ?>">
                                                    <?php echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom'])); ?>
                                                </a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        break;

                    case 'Teclat':
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> -
                                    Característiques</label>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Marca</th>
                                        <th>Tipus</th>
                                        <th>Propietari/a</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($caracteristica = $caracteristiques->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo remove_junk(ucwords($caracteristica['marca'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['tipus'])); ?></td>
                                            <td><a
                                                    href="propietari_dispositius.php?id=<?php echo (int) $dispositiu['propietari_id']; ?>">
                                                    <?php echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom'])); ?>
                                                </a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        break;

                    case 'Torre':
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> -
                                    Característiques</label>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>UID</th>
                                        <th>ID AnyDesk</th>
                                        <th>Processador</th>
                                        <th>RAM</th>
                                        <th>Capacitat</th>
                                        <th>Propietari/a</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($caracteristica = $caracteristiques->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo remove_junk(ucwords($caracteristica['uid'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['id_anydesck'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['processador'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['ram'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['capacitat'])); ?></td>
                                            <td><a
                                                    href="propietari_dispositius.php?id=<?php echo (int) $dispositiu['propietari_id']; ?>">
                                                    <?php echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom'])); ?>
                                                </a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        break;

                    case 'Portàtil':
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> -
                                    Característiques</label>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>UID</th>
                                        <th>ID AnyDesk</th>
                                        <th>Processador</th>
                                        <th>RAM</th>
                                        <th>Capacitat</th>
                                        <th>Marca</th>
                                        <th>Propietari/a</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($caracteristica = $caracteristiques->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo remove_junk(ucwords($caracteristica['uid'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['id_anydesck'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['processador'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['ram'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['capacitat'])); ?></td>
                                            <td><?php echo remove_junk(ucwords($caracteristica['marca'])); ?></td>
                                            <td><a
                                                    href="propietari_dispositius.php?id=<?php echo (int) $dispositiu['propietari_id']; ?>">
                                                    <?php echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom'])); ?>
                                                </a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        break;

                    default:
                        echo "<p>No hi ha característiques disponibles per a aquest dispositiu.</p>";
                        break;
                }
                ?>
                <div class="mt-3">
                    <a href="view_departament.php?id=<?php echo (int) $dispositiu['departament_id']; ?>"
                        class="btn btn-danger">Torna enrere</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>