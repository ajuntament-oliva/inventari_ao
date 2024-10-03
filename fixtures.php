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
    $dispositius = ["Torre", "Portàtil", "Tablet", "Telèfon Mòbil", "Monitor", "Teclat"];
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
for ($i = 1; $i <= 5; $i++) {
    $departament = generarDepartament();
    $dispositiu = generarDispositiu();
    $nom = generarNom();
    $cognom = generarCognom();

    $sql = "INSERT INTO departaments (departament, dispositiu, nom, cognom) VALUES ('$departament', '$dispositiu', '$nom', '$cognom')";

    if ($conn->query($sql) === TRUE) {
        echo "Dades afegides!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Tancar conexió
$conn->close();
