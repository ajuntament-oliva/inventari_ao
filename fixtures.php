<?php
// Configuració de connexió a la BDA
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inv";

// Crear connexió
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar connexió
if ($conn->connect_error) {
    die("Connexió fallida: " . $conn->connect_error);
}

// Generar Propietaris
$propietaris = [
    ['Minho', 'Lee'],
    ['Richard', 'Grayson'],
    ['Sebastian', 'Stan'],
    ['Bruce', 'Wayne'],
    ['Clark', 'Kent'],
    ['Diana', 'Prince'],
    ['Tony', 'Stark'],
    ['Natasha', 'Romanoff'],
];

$successMessages = [];
$errors = [];

// Afegir propietaris
foreach ($propietaris as $propietari) {
    $nom = mysqli_real_escape_string($conn, $propietari[0]);
    $cognom = mysqli_real_escape_string($conn, $propietari[1]);
    
    // Intentar inserir o actualitzar el propietari
    $sql_propietari = "INSERT INTO propietaris (nom, cognom) VALUES ('$nom', '$cognom') 
                       ON DUPLICATE KEY UPDATE id=id"; // Mantenim el mateix ID

    if ($conn->query($sql_propietari) === TRUE) {
        $successMessages[] = "Propietari '$nom $cognom' creat o actualitzat amb èxit.";
    } else {
        $errors[] = "Error en la inserció del propietari '$nom $cognom': " . $conn->error;
    }
}

// Generar Departaments
$departaments = [
    'Alcaldía', 'Activitats', 'ADL', 'Agència tributària', 
    'Benestar social i Igualtat', 'Biblioteca Tamarit', 'Biblioteca l\'Envic', 
    'Comerç', 'Contratació', 'Cultura', 'EDAR', 'Educació', 
    'Esports', 'Estadística i padró', 'Intervenció', 'Joventut', 
    'Magatzem Municipal', 'Modernització', 'Museus', 'OLAMA', 
    'OMIC', 'Participació ciutadana', 'Prevenció de Riscos Laborals', 
    'Promoció Lingüística', 'RRHH', 'Secretaría', 'Serveis Públics', 
    'Telefonia', 'Tresoreria', 'Turisme', 'Urbanisme'
];

foreach ($departaments as $departament) {
    $departament_escaped = mysqli_real_escape_string($conn, $departament);
    // Intentar inserir o actualitzar el departament
    $sql_departament = "INSERT INTO departaments (departament) VALUES ('$departament_escaped') 
                        ON DUPLICATE KEY UPDATE id=id"; // Mantenim el mateix ID

    if ($conn->query($sql_departament) === TRUE) {
        $successMessages[] = "Departament '$departament' creat o actualitzat amb èxit.";
    } else {
        $errors[] = "Error en la inserció del departament '$departament': " . $conn->error;
    }
}

// Generar Dispositius
$dispositius = ['Torre', 'Portàtil', 'Telèfon Mòbil', 'Monitor', 'Teclat'];

$dispositiu_ids = []; // Array per emmagatzemar IDs dels dispositius
foreach ($dispositius as $dispositiu) {
    $dispositiu_escaped = mysqli_real_escape_string($conn, $dispositiu);
    
    // Assignar un departament aleatoriament
    $departament_id = rand(1, count($departaments)); 

    // Intentar inserir el dispositiu
    $sql_dispositiu = "INSERT INTO dispositius (dispositiu, departament_id) VALUES ('$dispositiu_escaped', '$departament_id') 
                       ON DUPLICATE KEY UPDATE id=id"; // Mantenim el mateix ID

    if ($conn->query($sql_dispositiu) === TRUE) {
        $dispositiu_ids[] = $conn->insert_id; // Guardem l'ID generat
        $successMessages[] = "Dispositiu '$dispositiu' creat o actualitzat amb èxit.";
    } else {
        $errors[] = "Error en la inserció del dispositiu '$dispositiu': " . $conn->error;
    }
}

// Associar Propietaris amb Dispositius
foreach ($dispositiu_ids as $dispositiu_id) {
    // Seleccionar 2 propietaris aleatoris per cada dispositiu
    $random_propietaris_ids = array_rand($propietaris, 2);
    
    foreach ($random_propietaris_ids as $index) {
        $propietari_id = $index + 1; // Ajustem l'índex per l'ID
        $sql_assoc = "INSERT INTO dispositiu_propietari (dispositiu_id, propietari_id) VALUES ('$dispositiu_id', '$propietari_id') 
                      ON DUPLICATE KEY UPDATE dispositiu_id=dispositiu_id"; // Evitar duplicats

        if ($conn->query($sql_assoc) === TRUE) {
            $successMessages[] = "Dispositiu ID '$dispositiu_id' associat amb propietari ID '$propietari_id'.";
        } else {
            $errors[] = "Error en la inserció de la associació entre dispositiu ID '$dispositiu_id' i propietari ID '$propietari_id': " . $conn->error;
        }
    }
}

// Generar Característiques
$caracteristiques = ["Intel Core i7", "Intel Core i5", "16GB RAM", "8GB RAM", "512GB SSD", "256GB SSD", "4K Monitor", "Full HD", "Teclat mecànic", "Teclat membrana"];

// Inserir Característiques per a cada Dispositiu
foreach ($dispositiu_ids as $dispositiu_id) {
    $numCaracteristiques = rand(2, 4); // Generar entre 2 i 4 característiques per dispositiu
    $selected_caracteristiques = array_rand($caracteristiques, $numCaracteristiques);
    
    foreach ((array)$selected_caracteristiques as $index) {
        $caracteristica = mysqli_real_escape_string($conn, $caracteristiques[$index]);
        $sql_caracteristica = "INSERT INTO caracteristiques_dispositiu (dispositiu_id, caracteristica) VALUES ('$dispositiu_id', '$caracteristica') 
                              ON DUPLICATE KEY UPDATE caracteristica=caracteristica"; // Mantenim la mateixa característica

        if ($conn->query($sql_caracteristica) === TRUE) {
            $successMessages[] = "Característica '$caracteristica' afegida al dispositiu ID '$dispositiu_id'.";
        } else {
            $errors[] = "Error en la inserció de la característica '$caracteristica' per al dispositiu ID '$dispositiu_id': " . $conn->error;
        }
    }
}

// Mostrar missatges de resultats
if (empty($errors)) {
    echo "Totes les dades s'han afegit o actualitzat correctament!<br>";
} else {
    echo "S'han produït errors en les següents insercions:<br>";
    echo implode("<br>", $errors);
}

foreach ($successMessages as $message) {
    echo $message . "<br>";
}

// Tancar connexió
$conn->close();