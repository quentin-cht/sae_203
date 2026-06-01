<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte — E-LLUSION</title>
    <link rel="stylesheet" href="../ressources/css/style.css">
</head>
<body class="auth-body">

<div class="auth-layout">

    <a href="../index.php" class="auth-back">← Retour à l'accueil</a>

    <div class="auth-right">
        <div class="auth-right__inner">
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-intro">Rejoignez E-Llusion et réservez votre visite.</p>

            <form method="post" action="#">

                <div class="auth-row">
                    <div class="auth-field">
                        <label class="auth-label" for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" class="auth-input" placeholder="Votre prénom" required>
                    </div>
                    <div class="auth-field">
                        <label class="auth-label" for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" class="auth-input" placeholder="Votre nom" required>
                    </div>
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="email">Adresse email *</label>
                    <input type="email" id="email" name="email" class="auth-input" placeholder="sp@gmail.com" required>
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="tel">Numéro de téléphone</label>
                    <input type="tel" id="tel" name="tel" class="auth-input" placeholder="+33 6 67 67 67 67">
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="mdp">Mot de passe *</label>
                    <input type="password" id="mdp" name="mdp" class="auth-input" placeholder="Minimum 8 caractères" required>
                </div>

                <div class="auth-field">
                    <label class="auth-label" for="mdp2">Confirmer le mot de passe *</label>
                    <input type="password" id="mdp2" name="mdp2" class="auth-input" placeholder="Répétez votre mot de passe" required>
                </div>

                <label class="auth-checkbox">
                    <input type="checkbox" name="cgu" required> J'accepte les conditions générales d'utilisation
                </label>

                <button type="submit" class="btn-auth">Créer mon compte →</button>

            </form>

            <p class="auth-switch">Vous avez déjà un compte ? <a href="connexion.php" class="auth-link">Se connecter</a></p>
        </div>
    </div>

</div>


</body>
</html>
