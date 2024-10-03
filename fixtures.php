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

// Generar Departaments aleatoris
function generarDepartament()
{
    $departaments = ["Alcaldía", "Activitats", "ADL", "Agència tributària", "Benestar social i Igualtat", "Biblioteca Tamarit", "Biblioteca l'Envic", "Comerç", "Contratació", "Cultura", "EDAR", "Educació", "Esports", "Estadística i padró", "Intervenció", "Joventut", "Magatzem Municipal", "Modernització", "Museus", "OLAMA", "OMIC", "Participació ciutadana", "Prevenció de Riscos Laborals", "Promoció Lingüística", "RRHH", "Secretaría", "Serveis Públics", "Telefonia", "Tresoreria", "Turisme", "Urbanisme"];
    return $departaments[array_rand($departaments)];
}

// Generar Dispositius aleatoris
function generarDispositiu()
{
    $dispositius = ["Torre", "Portàtil", "Telèfon Mòbil", "Monitor", "Teclat"];
    return $dispositius[array_rand($dispositius)];
}

// Generar Característiques aleatòries
function generarCaracteristiques()
{
    $caracteristiques = ["Intel Core i7", "Intel Core i5", "16GB RAM", "8GB RAM", "512GB SSD", "256GB SSD", "4K Monitor", "Full HD", "Teclat mecànic", "Teclat membrana"];
    $numCaracteristiques = rand(2, 4); // Generar entre 2 i 4 característiques per dispositiu
    return array_rand(array_flip($caracteristiques), $numCaracteristiques);
}

// Obtenir propietari aleatori
function obtenirPropietariAleatori($conn)
{
    $result = $conn->query("SELECT id, nom, cognom FROM propietaris ORDER BY RAND() LIMIT 1");
    return $result->fetch_assoc(); // Retorna un array associatiu amb les dades del propietari
}

// Registres aleatoris
$errors = [];

for ($i = 1; $i <= 50; $i++) {
    // Inserció de departament
    $departament = mysqli_real_escape_string($conn, generarDepartament());
    $sql_departament = "INSERT INTO departaments (departament) VALUES ('$departament')";

    if ($conn->query($sql_departament) !== TRUE) {
        $errors[] = "Error en la fila $i (departament): " . $conn->error;
        continue;
    }

    // Obtenir un propietari aleatori
    $propietari = obtenirPropietariAleatori($conn); // Obtenir un propietari aleatori
    $propietari_id = $propietari['id']; // Obtenir l'ID del propietari
    $propietari_nom = mysqli_real_escape_string($conn, $propietari['nom']);
    $propietari_cognom = mysqli_real_escape_string($conn, $propietari['cognom']);
    
    // Inserció de dispositiu
    $dispositiu = mysqli_real_escape_string($conn, generarDispositiu());
    $sql_dispositiu = "INSERT INTO dispositius (dispositiu, propietari_id) VALUES ('$dispositiu', '$propietari_id')";

    if ($conn->query($sql_dispositiu) !== TRUE) {
        $errors[] = "Error en la fila $i (dispositiu): " . $conn->error;
        continue;
    }

    // Obtenir l'ID del dispositiu acabat d'inserir
    $dispositiu_id = $conn->insert_id;

    // Inserció de característiques per al dispositiu
    $caracteristiques = generarCaracteristiques();
    foreach ($caracteristiques as $caracteristica) {
        $caracteristica = mysqli_real_escape_string($conn, $caracteristica);
        $sql_caracteristica = "INSERT INTO caracteristiques_dispositiu (dispositiu_id, caracteristica) VALUES ('$dispositiu_id', '$caracteristica')";
        
        if ($conn->query($sql_caracteristica) !== TRUE) {
            $errors[] = "Error en la fila $i (característica): " . $conn->error;
        }
    }
}

if (empty($errors)) {
    echo "Totes les dades s'han afegit correctament!";
} else {
    echo "S'han produït errors en les següents insercions:<br>";
    echo implode("<br>", $errors);
}

// Tancar connexió
$conn->close();