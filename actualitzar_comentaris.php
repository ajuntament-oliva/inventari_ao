<?php
require_once('includes/load.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $comentaris = $db->escape($_POST['comentaris']);

    $query = "UPDATE propietaris SET comentaris = '{$comentaris}' WHERE id = {$id}";
    if ($db->query($query)) {
        $_SESSION['message'] = "Afegit el propietari antic.";
    } else {
        $_SESSION['message'] = "No s'ha pogut afegir el propietari.";
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}