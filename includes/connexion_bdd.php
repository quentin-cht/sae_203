<?php
define('SERVEUR_BD', 'localhost');
define('PORT_BD',    8889);
define('LOGIN_BD',   'root');
define('PASSE_BD',   'root');
define('NOM_BD',     'sae-203');

$conn = mysqli_connect(SERVEUR_BD, LOGIN_BD, PASSE_BD, NOM_BD, PORT_BD);

if (mysqli_connect_errno()) {
    echo 'Il y a un souci avec la connexion : ' . mysqli_connect_error();
    exit();
}

if (!mysqli_set_charset($conn, 'UTF8')) {
    echo 'Il y a un souci d\'encodage';
}

// Protection XSS : échappe les caractères HTML dans les données utilisateur
function h($texte) {
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}
?>
