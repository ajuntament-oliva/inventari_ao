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

$successMessages = [];
$errors = [];

// Generar Departaments
$departaments = [
    'Alcaldía',
    'Activitats',
    'ADL',
    'Agència tributària',
    'Benestar social i Igualtat',
    'Biblioteca Tamarit',
    'Biblioteca l\'Envic',
    'Comerç',
    'Contratació',
    'Cultura',
    'EDAR',
    'Educació',
    'Esports',
    'Estadística i padró',
    'Intervenció',
    'Joventut',
    'Magatzem Municipal',
    'Modernització',
    'Museus',
    'OLAMA',
    'OMIC',
    'Participació ciutadana',
    'Prevenció de Riscos Laborals',
    'Promoció Lingüística',
    'RRHH',
    'Secretaría',
    'Serveis Públics',
    'Telefonia',
    'Tresoreria',
    'Turisme',
    'Urbanisme'
];

foreach ($departaments as $departament) {
    $departament_escaped = mysqli_real_escape_string($conn, $departament);
    $sql_departament = "INSERT INTO departaments (departament) VALUES ('$departament_escaped') 
                        ON DUPLICATE KEY UPDATE id=id";

    if ($conn->query($sql_departament) === TRUE) {
        $successMessages[] = "Departament '$departament' creat o actualitzat amb èxit.";
    } else {
        $errors[] = "Error en la inserció del departament '$departament': " . $conn->error;
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