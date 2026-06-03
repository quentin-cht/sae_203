<?php
$host     = "localhost";
$user     = "root";
$password = "root";
$database = "sae-203";
$port     = 8889;

// Initialise l'objet de connexion (méthode en 2 étapes comme vu en cours)
$conn = mysqli_init();

// Établit la connexion avec le serveur MySQL
$succes = mysqli_real_connect($conn, $host, $user, $password, $database, $port);

if (!$succes) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// Protection XSS : échappe les caractères HTML dans les données utilisateur
function h($texte) {
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}
?>
