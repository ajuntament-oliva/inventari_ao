<?php
$page_title = 'Característiques';
require_once('includes/load.php');

if (isset($_GET['id'])) {
    $dispositiu_id = (int) $_GET['id'];

    // Consultar BDA per obtindre les característiques del dispositiu
    $caracteristiques = $db->query("SELECT caracteristica FROM caracteristiques_dispositiu WHERE dispositiu_id = $dispositiu_id ORDER BY caracteristica");

    // Consultar BDA per obtindre el nom del dispositiu i propietari
    $dispositiu = $db->query("SELECT d.dispositiu, d.id as dispositiu_id, p.nom, p.cognom, dp.propietari_id 
        FROM dispositius d 
        JOIN dispositiu_propietari dp ON d.id = dp.dispositiu_id 
        JOIN propietaris p ON dp.propietari_id = p.id 
        WHERE d.id = ?", [$dispositiu_id])->fetch_assoc(); // Pass the id as a parameter
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
                            <th><?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?> - Característiques</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($caracteristica = $caracteristiques->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo remove_junk(ucwords($caracteristica['caracteristica'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($caracteristiques->num_rows == 0): ?>
                            <tr>
                                <td>No hi ha característiques disponibles per a aquest dispositiu.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-3">
                    <strong>Propietari:</strong>
                    <a href="propietari_dispositius.php?id=<?php echo (int) $dispositiu['propietari_id']; ?>">
                        <?php echo remove_junk(ucwords($dispositiu['nom'] . ' ' . $dispositiu['cognom'])); ?>
                    </a>
                </div>
                <div class="mt-3">
                    <a href="departaments.php" class="btn btn-danger">Torna a Departaments</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>