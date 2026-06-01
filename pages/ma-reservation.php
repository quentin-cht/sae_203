<?php
$pageTitle = "Ma réservation";
$rootPath = "../";
include '../includes/header.php';

$reservation = null;
$erreur = '';

if (isset($_GET['demo']) && $_GET['demo'] == '1') {
    $reservation = [
        "reference" => "ELL-2024-01",
        "nom"       => "Martin",
        "prenom"    => "Sophie",
        "email"     => "test.1@email.fr",
        "salle"     => "Plateau Régie",
        "creneau"   => "18h00 — Jeudi 18 juin",
        "places"    => 2,
        "statut"    => "Confirmée"
    ];
} elseif (isset($_POST['email']) && isset($_POST['reference'])) {
    $email     = $_POST['email'];
    $reference = $_POST['reference'];

    // Quand la BDD sera connectée :
    // SELECT * FROM reservations WHERE email = $email AND reference = $reference
    // Pour l'instant, simulation avec des données fictives
    if ($email == 'test.1@email.fr' && $reference == 'ELL-2024-01') {
        $reservation = [
            "reference" => "ELL-2024-01",
            "nom"       => "Martin",
            "prenom"    => "Sophie",
            "email"     => "test.1@email.fr",
            "salle"     => "Plateau Régie",
            "creneau"   => "18h00 — Jeudi 18 juin",
            "places"    => 2,
            "statut"    => "Confirmée"
        ];
    } else {
        $erreur = 'Aucune réservation trouvée avec ces informations.';
    }
}
?>

<section class="inscrip-hero">
    <p class="section-label">Ma réservation</p>
    <h1 class="section-title">Retrouvez votre réservation</h1>
    <p class="inscrip-hero__sub">Entrez votre adresse email et votre référence de réservation.</p>
</section>

<section class="inscrip-section">
    <div class="inscrip-form">

        <?php if ($reservation == null) : ?>

        <!-- Formulaire de recherche -->
        <div class="form-step">
            <h2 class="form-step__title"><span class="form-step__num">1</span> Vos informations</h2>

            <?php if ($erreur != '') : ?>
            <div class="auth-error"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <form method="post" action="ma-reservation.php">
                <div class="form-group form-group--full" style="margin-bottom:16px">
                    <label class="form-label" for="email">Adresse email *</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" required>
                </div>
                <div class="form-group form-group--full" style="margin-bottom:24px">
                    <label class="form-label" for="reference">Référence de réservation *</label>
                    <p class="form-hint">Reçue par email lors de votre inscription (ex. ELL-2024-01)</p>
                    <input type="text" id="reference" name="reference" class="form-input" placeholder="ELL-XXXX-XX" required>
                </div>
                <button type="submit" class="btn-inscrip">Retrouver ma réservation →</button>
            </form>
        </div>

        <?php else : ?>

        <!-- Affichage de la réservation trouvée -->
        <div class="form-step">
            <h2 class="form-step__title"><span class="form-step__num">✓</span> Réservation trouvée</h2>

            <div class="resa-recap">
                <div class="resa-recap__ref">Référence : <strong><?php echo $reservation['reference']; ?></strong></div>
                <div class="resa-recap__grid">
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Nom</span>
                        <span><?php echo $reservation['prenom']; ?> <?php echo $reservation['nom']; ?></span>
                    </div>
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Email</span>
                        <span><?php echo $reservation['email']; ?></span>
                    </div>
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Salle</span>
                        <span><?php echo $reservation['salle']; ?></span>
                    </div>
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Créneau</span>
                        <span><?php echo $reservation['creneau']; ?></span>
                    </div>
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Personnes</span>
                        <span><?php echo $reservation['places']; ?></span>
                    </div>
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Statut</span>
                        <span class="admin-statut admin-statut--ok"><?php echo $reservation['statut']; ?></span>
                    </div>
                </div>
            </div>

            <div class="resa-recap__actions">
                <a href="inscription.php" class="btn-outline">Modifier ma réservation →</a>
                <form method="post" action="ma-reservation.php" style="display:inline" onsubmit="return confirm('Annuler votre réservation ? Cette action est irréversible.')">
                    <input type="hidden" name="action" value="annuler">
                    <input type="hidden" name="reference" value="<?php echo $reservation['reference']; ?>">
                    <button type="submit" class="btn-supprimer-inline">Annuler la réservation</button>
                </form>
            </div>
        </div>

        <?php endif; ?>

    </div>
</section>

<?php include '../includes/footer.php'; ?>
