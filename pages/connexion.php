<?php
session_start();

// Si déjà connecté, rediriger
if (isset($_SESSION['connecte']) && $_SESSION['connecte'] == true) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: ../index.php');
    }
    exit();
}

$erreur = '';

// Traitement du formulaire
if (isset($_POST['email']) && isset($_POST['mdp'])) {
    $email = $_POST['email'];
    $mdp   = $_POST['mdp'];

    // Compte administrateur (en attendant la BDD)
    if ($email == 'admin' && $mdp == 'admin123') {
        $_SESSION['connecte'] = true;
        $_SESSION['role']     = 'admin';
        $_SESSION['prenom']   = 'Admin';
        header('Location: admin.php');
        exit();
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — E-LLUSION</title>
    <link rel="stylesheet" href="../ressources/css/reset.css">
    <link rel="stylesheet" href="../ressources/css/style.css">
    <link rel="stylesheet" href="../ressources/css/connexion.css">
</head>
<body class="auth-body">

<div class="auth-layout">

    <a href="../index.php" class="auth-back">← Retour à l'accueil</a>

    <div class="auth-right">
        <div class="auth-right__inner">
            <?php if ($erreur != '') : ?>
            <div class="auth-error"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <form method="post" action="connexion.php">

                <div class="auth-field">
                    <label class="auth-label" for="email">Identifiant</label>
                    <input type="text" id="email" name="email" class="auth-input" placeholder="Votre identifiant" required>
                </div>

                <div class="auth-field">
                    <div class="auth-field__row">
                        <label class="auth-label" for="mdp">Mot de passe</label>
                        <a href="#" class="auth-link">Mot de passe oublié ?</a>
                    </div>
                    <input type="password" id="mdp" name="mdp" class="auth-input" placeholder="••••••••" required>
                </div>

                <label class="auth-checkbox">
                    <input type="checkbox" name="remember"> Se souvenir de moi
                </label>

                <button type="submit" class="btn-auth">Se connecter →</button>

            </form>

        </div>
    </div>

</div>

</body>
</html>
