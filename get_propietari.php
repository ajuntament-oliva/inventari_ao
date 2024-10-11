<?php
require_once('includes/load.php');

if (isset($_POST['dispositiu_id']) && !empty($_POST['dispositiu_id'])) {
    $dispositiu_id = (int) $_POST['dispositiu_id'];

    // Consulta obtindre el propietari asociat al dispositiu
    $dispositiu_assoc = $db->query("SELECT propietari_id FROM dispositiu_propietari WHERE dispositiu_id = $dispositiu_id");
    if ($dispositiu_assoc) {
        $propietari_id = $dispositiu_assoc->fetch_assoc()['propietari_id'] ?? null;

        if ($propietari_id) {
            // Consulta obtindre l'informació del propietari
            $propietari = $db->query("SELECT * FROM propietaris WHERE id = $propietari_id")->fetch_assoc();
            if ($propietari) {
                echo json_encode($propietari);
                exit;
            }
        }
    }
}

// Si no es troba el propietari
echo json_encode(['nom' => '', 'cognom' => '']);
exit;