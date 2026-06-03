<?php
$pageTitle = "Ma réservation";
$rootPath = "../";
$pageCss = "ma-reservation.css";
include '../includes/connexion_bdd.php';
include '../includes/header.php';

$reservation        = null;
$reservations_salles = [];
$erreur             = '';
$message_ok         = '';

// Récupérer toutes les salles et créneaux pour le formulaire de modification
$toutes_salles   = [];
$tous_creneaux   = [];
$res_salles  = mysqli_query($conn, "SELECT id_salles, nom_salle FROM salles ORDER BY id_salles");
$nb_salles   = mysqli_num_rows($res_salles);
for ($i = 0; $i < $nb_salles; $i++) {
    $s = mysqli_fetch_row($res_salles);
    $toutes_salles[] = ['id_salles' => $s[0], 'nom_salle' => $s[1]];
}

$res_cren  = mysqli_query($conn, "SELECT id_creneaux, jour, heure FROM creneaux ORDER BY jour, heure");
$nb_cren   = mysqli_num_rows($res_cren);
for ($i = 0; $i < $nb_cren; $i++) {
    $c = mysqli_fetch_row($res_cren);
    $tous_creneaux[] = ['id_creneaux' => $c[0], 'jour' => $c[1], 'heure' => $c[2]];
}

// Fonction : charger les réservations d'un utilisateur
function chargerReservations($conn, $email, $reference) {
    $email     = mysqli_real_escape_string($conn, $email);
    $reference = mysqli_real_escape_string($conn, $reference);
    $sql = "SELECT u.prenom, u.nom, u.email, u.code_unique, u.profil,
                   i.nb_personnes, i.buffet, i.id_inscriptions, i.salles_id_salles, i.creneaux_id_creneaux,
                   s.nom_salle, c.jour, c.heure
            FROM utilisateurs u
            JOIN inscriptions i ON i.utilisateurs_id_user = u.id_user
            JOIN salles s ON s.id_salles = i.salles_id_salles
            JOIN creneaux c ON c.id_creneaux = i.creneaux_id_creneaux
            WHERE u.email = '$email' AND u.code_unique = '$reference'
            ORDER BY c.jour, c.heure";
    return mysqli_query($conn, $sql);
}

// ── Supprimer une inscription individuelle ──────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] == 'supprimer_inscription'
    && isset($_POST['id_inscription']) && isset($_POST['reference'])) {

    $id_inscription = intval($_POST['id_inscription']);
    $reference      = mysqli_real_escape_string($conn, $_POST['reference']);

    // Vérifier que cette inscription appartient bien à l'utilisateur
    $check = mysqli_query($conn, "SELECT i.id_inscriptions FROM inscriptions i
                JOIN utilisateurs u ON u.id_user = i.utilisateurs_id_user
                WHERE i.id_inscriptions = $id_inscription AND u.code_unique = '$reference'");

    if ($check && mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM inscriptions WHERE id_inscriptions = $id_inscription");
        $message_ok = "La réservation a bien été supprimée.";
    } else {
        $erreur = "Impossible de supprimer cette réservation.";
    }
}

// ── Modifier une inscription individuelle ──────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] == 'modifier_inscription'
    && isset($_POST['id_inscription']) && isset($_POST['id_salle']) && isset($_POST['id_creneau']) && isset($_POST['reference'])) {

    $id_inscription = intval($_POST['id_inscription']);
    $id_salle       = intval($_POST['id_salle']);
    $id_creneau     = intval($_POST['id_creneau']);
    $reference      = mysqli_real_escape_string($conn, $_POST['reference']);

    // Vérifier que cette inscription appartient bien à l'utilisateur
    $check = mysqli_query($conn, "SELECT i.id_inscriptions FROM inscriptions i
                JOIN utilisateurs u ON u.id_user = i.utilisateurs_id_user
                WHERE i.id_inscriptions = $id_inscription AND u.code_unique = '$reference'");

    if ($check && mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE inscriptions SET salles_id_salles = $id_salle, creneaux_id_creneaux = $id_creneau
                             WHERE id_inscriptions = $id_inscription");
        $message_ok = "La réservation a bien été modifiée.";
    } else {
        $erreur = "Impossible de modifier cette réservation.";
    }
}

// ── Annuler TOUTES les réservations ────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] == 'annuler' && isset($_POST['reference'])) {
    $reference = mysqli_real_escape_string($conn, $_POST['reference']);
    $res = mysqli_query($conn, "SELECT id_user FROM utilisateurs WHERE code_unique = '$reference'");
    $user = mysqli_fetch_assoc($res);
    if ($user) {
        mysqli_query($conn, "DELETE FROM inscriptions WHERE utilisateurs_id_user = " . $user['id_user']);
        $message_ok = "Toutes vos réservations ont été annulées.";
    } else {
        $erreur = "Réservation introuvable.";
    }
}

// ── Charger les réservations (après action ou après soumission du formulaire) ──
$email_session     = '';
$reference_session = '';

if (isset($_POST['email']) && isset($_POST['reference'])) {
    $email_session     = $_POST['email'];
    $reference_session = $_POST['reference'];
} elseif (isset($_POST['reference']) && isset($_POST['email_hidden'])) {
    $email_session     = $_POST['email_hidden'];
    $reference_session = $_POST['reference'];
}

if ($email_session != '' && $reference_session != '') {
    $res = chargerReservations($conn, $email_session, $reference_session);
    if ($res && mysqli_num_rows($res) > 0) {
        $first = true;
        while ($row = mysqli_fetch_assoc($res)) {
            if ($first) {
                $reservation = [
                    "reference" => $row['code_unique'],
                    "nom"       => $row['nom'],
                    "prenom"    => $row['prenom'],
                    "email"     => $row['email'],
                    "profil"    => $row['profil'],
                    "buffet"    => $row['buffet'],
                    "places"    => $row['nb_personnes'],
                    "statut"    => "Confirmée"
                ];
                $first = false;
            }
            $reservations_salles[] = [
                "id_inscription" => $row['id_inscriptions'],
                "id_salle"       => $row['salles_id_salles'],
                "id_creneau"     => $row['creneaux_id_creneaux'],
                "salle"          => $row['nom_salle'],
                "creneau"        => substr($row['heure'], 0, 5) . ' — ' . ($row['jour'] < '2026-06-19' ? 'Jeudi 18 juin' : 'Vendredi 19 juin')
            ];
        }
    } elseif ($erreur == '' && $message_ok == '') {
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

        <?php if ($reservation == null && $message_ok == '') : ?>

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
                    <p class="form-hint">Reçue par email lors de votre inscription (ex. 09e4cc84)</p>
                    <input type="text" id="reference" name="reference" class="form-input" placeholder="ex. 09e4cc84" required>
                </div>
                <button type="submit" class="btn-inscrip">Retrouver ma réservation →</button>
            </form>
        </div>

        <?php elseif ($message_ok != '' && $reservation == null) : ?>

        <!-- Message si toutes les réservations ont été supprimées -->
        <div class="form-step">
            <div class="admin-message admin-message--ok"><?php echo $message_ok; ?></div>
            <a href="inscription.php" class="btn-outline" style="display:inline-block;margin-top:16px">Faire une nouvelle réservation →</a>
        </div>

        <?php else : ?>

        <!-- Affichage de la réservation trouvée -->
        <div class="form-step">
            <h2 class="form-step__title"><span class="form-step__num">✓</span> Réservation trouvée</h2>

            <?php if ($message_ok != '') : ?>
            <div class="admin-message admin-message--ok" style="margin-bottom:20px"><?php echo $message_ok; ?></div>
            <?php endif; ?>
            <?php if ($erreur != '') : ?>
            <div class="auth-error" style="margin-bottom:20px"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <div class="resa-recap">
                <div class="resa-recap__ref">Référence : <strong><?php echo h($reservation['reference']); ?></strong></div>
                <div class="resa-recap__grid">
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Nom</span>
                        <span><?php echo h($reservation['prenom']); ?> <?php echo h($reservation['nom']); ?></span>
                    </div>
                    <div class="resa-recap__item">
                        <span class="resa-recap__label">Email</span>
                        <span><?php echo h($reservation['email']); ?></span>
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

            <!-- Ligne par réservation de salle -->
            <?php for ($i = 0; $i < count($reservations_salles); $i++) : ?>
            <?php $rs = $reservations_salles[$i]; ?>
            <div class="resa-ligne" id="resa-ligne-<?php echo $rs['id_inscription']; ?>">
                <div class="resa-ligne__infos">
                    <div class="resa-ligne__salle"><?php echo h($rs['salle']); ?></div>
                    <div class="resa-ligne__creneau"><?php echo h($rs['creneau']); ?></div>
                </div>
                <div class="resa-ligne__actions">
                    <button type="button" class="btn-outline btn-outline--small"
                        onclick="afficherModif(<?php echo $rs['id_inscription']; ?>)">
                        Modifier
                    </button>
                    <form method="post" action="ma-reservation.php" style="display:inline"
                        onsubmit="return confirm('Supprimer cette réservation ?')">
                        <input type="hidden" name="action" value="supprimer_inscription">
                        <input type="hidden" name="id_inscription" value="<?php echo $rs['id_inscription']; ?>">
                        <input type="hidden" name="reference" value="<?php echo h($reservation['reference']); ?>">
                        <input type="hidden" name="email_hidden" value="<?php echo h($reservation['email']); ?>">
                        <button type="submit" class="btn-supprimer-inline">Supprimer</button>
                    </form>
                </div>

                <!-- Formulaire de modification inline (caché par défaut) -->
                <div class="resa-modif-form" id="modif-<?php echo $rs['id_inscription']; ?>" style="display:none">
                    <form method="post" action="ma-reservation.php">
                        <input type="hidden" name="action" value="modifier_inscription">
                        <input type="hidden" name="id_inscription" value="<?php echo $rs['id_inscription']; ?>">
                        <input type="hidden" name="reference" value="<?php echo h($reservation['reference']); ?>">
                        <input type="hidden" name="email_hidden" value="<?php echo h($reservation['email']); ?>">
                        <div class="resa-modif-form__champs">
                            <div class="form-group">
                                <label class="form-label">Salle</label>
                                <select name="id_salle" class="form-input">
                                    <?php for ($j = 0; $j < count($toutes_salles); $j++) : ?>
                                    <option value="<?php echo $toutes_salles[$j]['id_salles']; ?>"
                                        <?php echo ($toutes_salles[$j]['id_salles'] == $rs['id_salle']) ? 'selected' : ''; ?>>
                                        <?php echo h($toutes_salles[$j]['nom_salle']); ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Créneau</label>
                                <select name="id_creneau" class="form-input">
                                    <?php for ($j = 0; $j < count($tous_creneaux); $j++) : ?>
                                    <?php
                                        $c = $tous_creneaux[$j];
                                        $label_jour = ($c['jour'] < '2026-06-19') ? 'Jeudi 18 juin' : 'Vendredi 19 juin';
                                        $label_heure = substr($c['heure'], 0, 5);
                                    ?>
                                    <option value="<?php echo $c['id_creneaux']; ?>"
                                        <?php echo ($c['id_creneaux'] == $rs['id_creneau']) ? 'selected' : ''; ?>>
                                        <?php echo $label_heure . ' — ' . $label_jour; ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div style="margin-top:12px;display:flex;gap:12px">
                            <button type="submit" class="btn-inscrip" style="padding:10px 20px;font-size:0.9rem">Enregistrer</button>
                            <button type="button" class="btn-outline btn-outline--small"
                                onclick="cacherModif(<?php echo $rs['id_inscription']; ?>)">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endfor; ?>

            <!-- Annuler toutes les réservations -->
            <div class="resa-recap__actions" style="margin-top:32px">
                <form method="post" action="ma-reservation.php" style="display:inline"
                    onsubmit="return confirm('Annuler TOUTES vos réservations ? Cette action est irréversible.')">
                    <input type="hidden" name="action" value="annuler">
                    <input type="hidden" name="reference" value="<?php echo h($reservation['reference']); ?>">
                    <input type="hidden" name="email_hidden" value="<?php echo h($reservation['email']); ?>">
                    <button type="submit" class="btn-supprimer-inline">Annuler toutes mes réservations</button>
                </form>
            </div>
        </div>

        <?php endif; ?>

    </div>
</section>

<script>
function afficherModif(id) {
    document.getElementById('modif-' + id).style.display = 'block';
}
function cacherModif(id) {
    document.getElementById('modif-' + id).style.display = 'none';
}
</script>

<?php include '../includes/footer.php'; ?>
