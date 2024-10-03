

<?php
// Configuració de connexió a la BDA
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inv";

// Crear conexió
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexió
if ($conn->connect_error) {
    die("Conexió fallida: " . $conn->connect_error);
}

// Generar Departaments aleatoris
function generarDepartament()
{
    $departaments = ["Alcaldía", "Activitats", "ADL", "Agència tributària", "Benestar social i Igualtat", "Biblioteca Tamarit", "Biblioteca l'Envic", "Comerç", "Contratació", "Cultura", "EDAR", "Educació", "Esports", "Estadística i padró", "Intervenció", "Joventut", "Magatzem Munucipal", "Modernització", "Museus", "OLAMA", "OMIC", "Participació ciutadana", "Prevenció de Riscos Laborals", "Promoció Lingüística", "RRHH", "Secretaría", "Serveis Públics", "Telefonia", "Tresoreria", "Turisme", "Urbanisme"];
    return $departaments[array_rand($departaments)];
}

// Generar Dispositius aleatoris
function generarDispositiu()
{
    $dispositius = ["Torre", "Portàtil", "Monitor", "Teclat"];
    return $dispositius[array_rand($dispositius)];
}

// Generar noms aleatoris
function generarNom()
{
    $noms = ["Josep", "Maria", "Pere", "Laura", "Anna", "Jordi", "Montserrat", "Marc", "Clara", "Sergi"];
    return $noms[array_rand($noms)];
}

// Generar cognoms aleatoris
function generarCognom()
{
    $cognoms = ["García", "Martínez", "López", "Sánchez", "Ferrer", "Soler", "Riera", "Vidal", "Roca", "Domènech"];
    return $cognoms[array_rand($cognoms)];
}

// Registres aleatoris
$errors = [];

for ($i = 1; $i <= 50; $i++) {
    $departament = mysqli_real_escape_string($conn, generarDepartament());
    $dispositiu = mysqli_real_escape_string($conn, generarDispositiu());
    $nom = mysqli_real_escape_string($conn, generarNom());
    $cognom = mysqli_real_escape_string($conn, generarCognom());

    $sql = "INSERT INTO departaments (departament, dispositiu, nom, cognom) VALUES ('$departament', '$dispositiu', '$nom', '$cognom')";

    if ($conn->query($sql) !== TRUE) {
        $errors[] = "Error en la fila $i: " . $conn->error;
    }
}

if (empty($errors)) {
    echo "Totes les dades s'han afegit correctament!";
} else {
    echo "S'han produït errors en les següents insercions:<br>";
    echo implode("<br>", $errors);
}

// Tancar conexió
$conn->close();

