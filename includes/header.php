<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' — E-LLUSION' : 'E-LLUSION'; ?></title>
    <link rel="stylesheet" href="<?php echo $rootPath ?? ''; ?>ressources/css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="<?php echo $rootPath ?? ''; ?>index.php" class="navbar__logo">E-LLUSION</a>
    <ul class="navbar__links">
        <li><a href="<?php echo $rootPath ?? ''; ?>index.php">Accueil</a></li>
        <li><a href="<?php echo $rootPath ?? ''; ?>pages/exposition.php">L'Exposition</a></li>
        <li><a href="<?php echo $rootPath ?? ''; ?>pages/salles.php">Les Salles</a></li>
        <li><a href="<?php echo $rootPath ?? ''; ?>pages/inscription.php">Inscription</a></li>
        <li><a href="<?php echo $rootPath ?? ''; ?>pages/contact.php">Contact</a></li>
    </ul>
    <a href="<?php echo $rootPath ?? ''; ?>pages/ma-reservation.php" class="btn-login">Ma réservation</a>
</nav>
