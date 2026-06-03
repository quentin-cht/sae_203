<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' — E-LLUSION' : 'E-LLUSION'; ?></title>
    <link rel="stylesheet" href="<?php echo $rootPath ?? ''; ?>ressources/css/reset.css">
    <link rel="stylesheet" href="<?php echo $rootPath ?? ''; ?>ressources/css/style.css">
    <?php if (isset($pageCss)) echo '<link rel="stylesheet" href="' . ($rootPath ?? '') . 'ressources/css/' . $pageCss . '">'; ?>
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
    <a href="<?php echo $rootPath ?? ''; ?>pages/ma-reservation.php" class="btn-login navbar__resa">Ma réservation</a>
    <button class="navbar__burger" id="burger" onclick="toggleMenu()" aria-label="Menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

<!-- Menu mobile -->
<div class="navbar__mobile" id="mobileMenu">
    <a href="<?php echo $rootPath ?? ''; ?>index.php">Accueil</a>
    <a href="<?php echo $rootPath ?? ''; ?>pages/exposition.php">L'Exposition</a>
    <a href="<?php echo $rootPath ?? ''; ?>pages/salles.php">Les Salles</a>
    <a href="<?php echo $rootPath ?? ''; ?>pages/inscription.php">Inscription</a>
    <a href="<?php echo $rootPath ?? ''; ?>pages/contact.php">Contact</a>
    <a href="<?php echo $rootPath ?? ''; ?>pages/ma-reservation.php" class="navbar__mobile-resa">Ma réservation</a>
</div>

<script>
var menuOuvert = false;

function toggleMenu() {
    var menu = document.getElementById('mobileMenu');
    if (menuOuvert == false) {
        menu.style.display = 'flex';
        menuOuvert = true;
    } else {
        menu.style.display = 'none';
        menuOuvert = false;
    }
}
</script>

<main>
