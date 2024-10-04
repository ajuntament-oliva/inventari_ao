<?php
$page_title = 'Afegir Propietari al Dispositiu';
require_once('includes/load.php');

// Obtindre dispositius de la BDA
$dispositius = $db->query("SELECT id, dispositiu FROM dispositius ORDER BY dispositiu");

if (isset($_POST['add_owner'])) {
    $propietari_nom = remove_junk($db->escape($_POST['nom']));
    $propietari_cognom = remove_junk($db->escape($_POST['cognom']));
    $dispositiu_id = (int) $_POST['dispositiu_id'];

    // Afegir propietari a la BDA
    if ($propietari_nom && $propietari_cognom && $dispositiu_id) {
        // Inserir nou propietari
        $sql_insert_owner = "INSERT INTO propietaris (nom, cognom) VALUES ('$propietari_nom', '$propietari_cognom')";
        
        if ($db->query($sql_insert_owner)) {
            $propietari_id = $db->insert_id; // Obtenir l'ID del nou propietari afegit
            
            // Actualitzar el dispositiu amb el nou propietari
            $sql_update_device = "UPDATE dispositius SET propietari_id = $propietari_id WHERE id = $dispositiu_id";
            if ($db->query($sql_update_device)) {
                $session->msg('s', "Propietari afegit al dispositiu amb èxit.");
                redirect('add_owner.php', false);
            } else {
                $session->msg('d', 'Ho sentim, no es va poder actualitzar el dispositiu.');
            }
        } else {
            $session->msg('d', 'Ho sentim, no es va poder afegir el propietari.');
        }
    } else {
        $session->msg('d', 'Se requereixen tots els camps.');
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Afegir Dispositiu</h4>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="dispositiu_id">Dispositiu:</label>
                        <select name="dispositiu_id" class="form-control" required>
                            <option value="">Selecciona un dispositiu</option>
                            <?php while ($dispositiu = $dispositius->fetch_assoc()): ?>
                                <option value="<?php echo (int) $dispositiu['id']; ?>">
                                    <?php echo remove_junk(ucwords($dispositiu['dispositiu'])); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom del Propietari:</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cognom">Cognom del Propietari:</label>
                        <input type="text" name="cognom" class="form-control" required>
                    </div>
                    <button type="submit" name="add_owner" class="btn btn-primary">Afegir Propietari</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<?php include_once('layouts/footer.php'); ?>