<?php
$host     = "localhost";
$user     = "root";
$password = "root";
$database = "sae-203";
$port     = 8889;

$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// Protection XSS : échappe les caractères HTML dans les données utilisateur
function h($texte) {
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}
?>
