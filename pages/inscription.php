<?php
$pageTitle = "Inscription";
$rootPath = "../";
include '../includes/connexion_bdd.php';

$message = '';
$erreur  = '';

// Pré-remplissage si on vient de "Modifier ma réservation"
$prefill_nom     = isset($_GET['nom'])      ? htmlspecialchars($_GET['nom'])      : '';
$prefill_prenom  = isset($_GET['prenom'])   ? htmlspecialchars($_GET['prenom'])   : '';
$prefill_email   = isset($_GET['email'])    ? htmlspecialchars($_GET['email'])    : '';
$prefill_places  = isset($_GET['places'])   ? intval($_GET['places'])             : 1;
$prefill_ref     = isset($_GET['reference'])? htmlspecialchars($_GET['reference']): '';
$prefill_profil   = isset($_GET['profil'])    ? htmlspecialchars($_GET['profil'])  : '';
$prefill_buffet   = isset($_GET['buffet'])   ? intval($_GET['buffet'])             : 1;
$prefill_id_salle = isset($_GET['id_salle']) ? intval($_GET['id_salle'])           : 0;
$prefill_id_cren  = isset($_GET['id_creneau'])? intval($_GET['id_creneau'])        : 0;

// Correspondance id_salle → valeur du radio
$map_salle = [1 => 'plateau', 2 => 'salle001', 3 => 'salle002', 4 => 'salle005'];
// Correspondance id_creneau → valeur du radio
$map_creneau = [
    1 => 'jeudi-15h00', 2 => 'jeudi-15h30', 3 => 'jeudi-16h00', 4 => 'jeudi-16h30',
    5 => 'jeudi-17h00', 6 => 'jeudi-17h30', 7 => 'jeudi-18h00', 8 => 'jeudi-19h00',
    9 => 'jeudi-19h30', 10 => 'jeudi-20h00', 11 => 'vendredi-9h30', 12 => 'vendredi-10h00',
    13 => 'vendredi-10h30', 14 => 'vendredi-11h00'
];
$prefill_val_salle = isset($map_salle[$prefill_id_salle])  ? $map_salle[$prefill_id_salle]   : 'plateau';
$prefill_val_cren  = isset($map_creneau[$prefill_id_cren]) ? $map_creneau[$prefill_id_cren]  : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'])) {

    $nom          = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom       = mysqli_real_escape_string($conn, $_POST['prenom']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $profil       = mysqli_real_escape_string($conn, $_POST['categorie']);
    $nb_personnes = intval($_POST['nb_personnes']);
    $buffet       = isset($_POST['buffet']) ? 1 : 0;

    // Vérifier si l'utilisateur existe déjà
    $res = mysqli_query($conn, "SELECT id_user, code_unique FROM utilisateurs WHERE email = '$email'");
    $utilisateur = mysqli_fetch_assoc($res);

    if ($utilisateur) {
        $id_user     = $utilisateur['id_user'];
        $code_unique = $utilisateur['code_unique'];
    } else {
        // Créer le nouvel utilisateur (code_unique généré automatiquement par le trigger)
        mysqli_query($conn, "INSERT INTO utilisateurs (prenom, nom, email, code_unique, profil, role)
                             VALUES ('$prenom', '$nom', '$email', '', '$profil', 'util')");
        $id_user = mysqli_insert_id($conn);

        $res2 = mysqli_query($conn, "SELECT code_unique FROM utilisateurs WHERE id_user = $id_user");
        $row  = mysqli_fetch_assoc($res2);
        $code_unique = $row['code_unique'];
    }

    // Créer une inscription pour chaque salle ajoutée (max 4)
    $erreurs_places = [];

    for ($i = 0; $i < 4; $i++) {
        if (isset($_POST['id_salle_' . $i]) && isset($_POST['id_creneau_' . $i])) {
            $id_salle   = intval($_POST['id_salle_' . $i]);
            $id_creneau = intval($_POST['id_creneau_' . $i]);
            if ($id_salle > 0 && $id_creneau > 0) {

                // Vérifier combien de places sont déjà prises pour cette salle + créneau
                $res_check = mysqli_query($conn, "SELECT COALESCE(SUM(nb_personnes), 0) AS total
                                                  FROM inscriptions
                                                  WHERE salles_id_salles = $id_salle
                                                  AND creneaux_id_creneaux = $id_creneau");
                $check = mysqli_fetch_assoc($res_check);
                $places_prises = intval($check['total']);
                $places_restantes = 12 - $places_prises;

                if ($nb_personnes > $places_restantes) {
                    // Récupérer le nom de la salle pour le message d'erreur
                    $res_nom = mysqli_query($conn, "SELECT nom_salle FROM salles WHERE id_salles = $id_salle");
                    $nom_salle = mysqli_fetch_assoc($res_nom)['nom_salle'];
                    if ($places_restantes <= 0) {
                        $erreurs_places[] = "La salle <strong>$nom_salle</strong> est complète pour ce créneau.";
                    } else {
                        $erreurs_places[] = "La salle <strong>$nom_salle</strong> n'a plus que <strong>$places_restantes place(s)</strong> disponible(s) pour ce créneau (vous demandez $nb_personnes).";
                    }
                } else {
                    // Assez de places, on insère
                    mysqli_query($conn, "INSERT INTO inscriptions (nb_personnes, buffet, utilisateurs_id_user, salles_id_salles, creneaux_id_creneaux)
                                         VALUES ($nb_personnes, $buffet, $id_user, $id_salle, $id_creneau)");
                }
            }
        }
    }

    if (count($erreurs_places) > 0) {
        $erreur = implode('<br>', $erreurs_places);
    } else {
        $message = "Inscription confirmée ! Votre code de réservation : <strong>$code_unique</strong> — un email vous sera envoyé.";
    }
}

include '../includes/header.php';
?>

<!-- ── Hero ── -->
<section class="inscrip-hero">
    <p class="section-label">Inscription</p>
    <h1 class="section-title">Réservez votre visite</h1>
    <p class="inscrip-hero__sub">Choisissez vos salles, vos créneaux, et renseignez vos informations.</p>
</section>

<!-- ── Formulaire ── -->
<section class="inscrip-section">

<?php if ($message != '') : ?>
<div class="inscrip-form" style="max-width:540px;margin:0 auto;">
    <div class="admin-message admin-message--ok"><?php echo $message; ?></div>
    <a href="inscription.php" class="btn-outline" style="display:inline-block;margin-top:16px">Nouvelle réservation</a>
</div>
<?php elseif ($erreur != '') : ?>
<div class="inscrip-form" style="max-width:540px;margin:0 auto;">
    <div class="auth-error" style="margin-bottom:20px"><?php echo $erreur; ?></div>
    <a href="inscription.php" class="btn-outline" style="display:inline-block">← Retour au formulaire</a>
</div>
<?php else : ?>

<form class="inscrip-form" method="post" action="inscription.php">

    <!-- Étape 1 : Salles & créneaux -->
    <div class="form-step">
        <h2 class="form-step__title"><span class="form-step__num">1</span> Choisissez vos salles &amp; créneaux</h2>
        <p class="form-step__sub">Vous pouvez réserver plusieurs salles à des horaires différents en une seule fois.</p>

        <!-- Bloc salle 1 (toujours visible) -->
        <div class="resa-block" id="resa-0">
            <p class="resa-block__label">Salle 1</p>
            <div class="salle-grid">
                <label class="salle-radio <?php echo $prefill_val_salle == 'plateau' || $prefill_val_salle == '' ? 'salle-radio--active' : ''; ?>" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="plateau" <?php echo $prefill_val_salle == 'plateau' || $prefill_val_salle == '' ? 'checked' : ''; ?>> Societ-e (021)
                </label>
                <label class="salle-radio <?php echo $prefill_val_salle == 'salle001' ? 'salle-radio--active' : ''; ?>" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="salle001" <?php echo $prefill_val_salle == 'salle001' ? 'checked' : ''; ?>> Horizon (001)
                </label>
                <label class="salle-radio <?php echo $prefill_val_salle == 'salle002' ? 'salle-radio--active' : ''; ?>" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="salle002" <?php echo $prefill_val_salle == 'salle002' ? 'checked' : ''; ?>> L'Envers du Décor (002)
                </label>
                <label class="salle-radio <?php echo $prefill_val_salle == 'salle005' ? 'salle-radio--active' : ''; ?>" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="salle005" <?php echo $prefill_val_salle == 'salle005' ? 'checked' : ''; ?>> La Pépinière (005)
                </label>
            </div>
            <?php $est_vendredi = strpos($prefill_val_cren, 'vendredi') !== false; ?>
            <div class="jour-tabs">
                <button type="button" class="jour-tab <?php echo !$est_vendredi ? 'jour-tab--active' : ''; ?>" onclick="switchJour('jeudi-0', this)">Jeudi 18 juin</button>
                <button type="button" class="jour-tab <?php echo $est_vendredi ? 'jour-tab--active' : ''; ?>" onclick="switchJour('vendredi-0', this)">Vendredi 19 juin</button>
            </div>
            <div id="jeudi-0" <?php echo $est_vendredi ? 'style="display:none"' : ''; ?>>
                <p class="creneaux-date">Jeudi 18 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-15h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-15h00" <?php echo $prefill_val_cren == 'jeudi-15h00' ? 'checked' : ''; ?>> 15h00</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-15h30' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-15h30" <?php echo $prefill_val_cren == 'jeudi-15h30' ? 'checked' : ''; ?>> 15h30</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-16h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-16h00" <?php echo $prefill_val_cren == 'jeudi-16h00' ? 'checked' : ''; ?>> 16h00</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-16h30' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-16h30" <?php echo $prefill_val_cren == 'jeudi-16h30' ? 'checked' : ''; ?>> 16h30</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-17h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-17h00" <?php echo $prefill_val_cren == 'jeudi-17h00' ? 'checked' : ''; ?>> 17h00</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-17h30' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-17h30" <?php echo $prefill_val_cren == 'jeudi-17h30' ? 'checked' : ''; ?>> 17h30</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-18h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-18h00" <?php echo $prefill_val_cren == 'jeudi-18h00' ? 'checked' : ''; ?>> 18h00</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-19h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-19h00" <?php echo $prefill_val_cren == 'jeudi-19h00' ? 'checked' : ''; ?>> 19h00</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-19h30' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-19h30" <?php echo $prefill_val_cren == 'jeudi-19h30' ? 'checked' : ''; ?>> 19h30</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'jeudi-20h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-20h00" <?php echo $prefill_val_cren == 'jeudi-20h00' ? 'checked' : ''; ?>> 20h00</label>
                </div>
            </div>
            <div id="vendredi-0" <?php echo !$est_vendredi ? 'style="display:none"' : ''; ?>>
                <p class="creneaux-date">Vendredi 19 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau <?php echo $prefill_val_cren == 'vendredi-9h30' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-9h30" <?php echo $prefill_val_cren == 'vendredi-9h30' ? 'checked' : ''; ?>> 9h30</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'vendredi-10h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-10h00" <?php echo $prefill_val_cren == 'vendredi-10h00' ? 'checked' : ''; ?>> 10h00</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'vendredi-10h30' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-10h30" <?php echo $prefill_val_cren == 'vendredi-10h30' ? 'checked' : ''; ?>> 10h30</label>
                    <label class="creneau <?php echo $prefill_val_cren == 'vendredi-11h00' ? 'creneau--active' : ''; ?>" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-11h00" <?php echo $prefill_val_cren == 'vendredi-11h00' ? 'checked' : ''; ?>> 11h00</label>
                </div>
            </div>
            <div class="jauge-banner" id="jauge-0">Places restantes : <strong>… / 12</strong> — 12 personnes maximum par salle</div>
        </div>

        <!-- Bloc salle 2 (caché par défaut) -->
        <input type="hidden" name="id_salle_1" id="hidden_id_salle_1" value="0">
        <input type="hidden" name="id_creneau_1" id="hidden_id_creneau_1" value="0">
        <div class="resa-block" id="resa-1" style="display:none">
            <div class="resa-block__header">
                <p class="resa-block__label">Salle 2</p>
                <button type="button" class="resa-remove" onclick="supprimerSalle('resa-1', 'btn-add-1')">✕</button>
            </div>
            <div class="salle-grid">
                <label class="salle-radio salle-radio--active" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="plateau" checked> Societ-e (021)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="salle001"> Horizon (001)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="salle002"> L'Envers du Décor (002)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="salle005"> La Pépinière (005)
                </label>
            </div>
            <div class="jour-tabs">
                <button type="button" class="jour-tab jour-tab--active" onclick="switchJour('jeudi-1', this)">Jeudi 18 juin</button>
                <button type="button" class="jour-tab" onclick="switchJour('vendredi-1', this)">Vendredi 19 juin</button>
            </div>
            <div id="jeudi-1">
                <p class="creneaux-date">Jeudi 18 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau creneau--active" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-15h00" checked> 15h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-15h30"> 15h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-16h00"> 16h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-16h30"> 16h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-17h00"> 17h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-17h30"> 17h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-18h00"> 18h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-19h00"> 19h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-19h30"> 19h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="jeudi-20h00"> 20h00</label>
                </div>
            </div>
            <div id="vendredi-1" style="display:none">
                <p class="creneaux-date">Vendredi 19 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="vendredi-9h30"> 9h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="vendredi-10h00"> 10h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="vendredi-10h30"> 10h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[1]" value="vendredi-11h00"> 11h00</label>
                </div>
            </div>
            <div class="jauge-banner" id="jauge-1">Places restantes : <strong>… / 12</strong> — 12 personnes maximum par salle</div>
        </div>

        <!-- Bloc salle 3 (caché par défaut) -->
        <input type="hidden" name="id_salle_2" id="hidden_id_salle_2" value="0">
        <input type="hidden" name="id_creneau_2" id="hidden_id_creneau_2" value="0">
        <div class="resa-block" id="resa-2" style="display:none">
            <div class="resa-block__header">
                <p class="resa-block__label">Salle 3</p>
                <button type="button" class="resa-remove" onclick="supprimerSalle('resa-2', 'btn-add-2')">✕</button>
            </div>
            <div class="salle-grid">
                <label class="salle-radio salle-radio--active" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="plateau" checked> Societ-e (021)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="salle001"> Horizon (001)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="salle002"> L'Envers du Décor (002)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="salle005"> La Pépinière (005)
                </label>
            </div>
            <div class="jour-tabs">
                <button type="button" class="jour-tab jour-tab--active" onclick="switchJour('jeudi-2', this)">Jeudi 18 juin</button>
                <button type="button" class="jour-tab" onclick="switchJour('vendredi-2', this)">Vendredi 19 juin</button>
            </div>
            <div id="jeudi-2">
                <p class="creneaux-date">Jeudi 18 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau creneau--active" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-15h00" checked> 15h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-15h30"> 15h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-16h00"> 16h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-16h30"> 16h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-17h00"> 17h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-17h30"> 17h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-18h00"> 18h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-19h00"> 19h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-19h30"> 19h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="jeudi-20h00"> 20h00</label>
                </div>
            </div>
            <div id="vendredi-2" style="display:none">
                <p class="creneaux-date">Vendredi 19 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="vendredi-9h30"> 9h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="vendredi-10h00"> 10h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="vendredi-10h30"> 10h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[2]" value="vendredi-11h00"> 11h00</label>
                </div>
            </div>
            <div class="jauge-banner" id="jauge-2">Places restantes : <strong>… / 12</strong> — 12 personnes maximum par salle</div>
        </div>

        <!-- Bloc salle 4 (caché par défaut) -->
        <input type="hidden" name="id_salle_3" id="hidden_id_salle_3" value="0">
        <input type="hidden" name="id_creneau_3" id="hidden_id_creneau_3" value="0">
        <div class="resa-block" id="resa-3" style="display:none">
            <div class="resa-block__header">
                <p class="resa-block__label">Salle 4</p>
                <button type="button" class="resa-remove" onclick="supprimerSalle('resa-3', 'btn-add-3')">✕</button>
            </div>
            <div class="salle-grid">
                <label class="salle-radio salle-radio--active" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="plateau" checked> Societ-e (021)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="salle001"> Horizon (001)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="salle002"> L'Envers du Décor (002)
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="salle005"> La Pépinière (005)
                </label>
            </div>
            <div class="jour-tabs">
                <button type="button" class="jour-tab jour-tab--active" onclick="switchJour('jeudi-3', this)">Jeudi 18 juin</button>
                <button type="button" class="jour-tab" onclick="switchJour('vendredi-3', this)">Vendredi 19 juin</button>
            </div>
            <div id="jeudi-3">
                <p class="creneaux-date">Jeudi 18 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau creneau--active" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-15h00" checked> 15h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-15h30"> 15h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-16h00"> 16h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-16h30"> 16h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-17h00"> 17h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-17h30"> 17h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-18h00"> 18h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-19h00"> 19h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-19h30"> 19h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="jeudi-20h00"> 20h00</label>
                </div>
            </div>
            <div id="vendredi-3" style="display:none">
                <p class="creneaux-date">Vendredi 19 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="vendredi-9h30"> 9h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="vendredi-10h00"> 10h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="vendredi-10h30"> 10h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[3]" value="vendredi-11h00"> 11h00</label>
                </div>
            </div>
            <div class="jauge-banner" id="jauge-3">Places restantes : <strong>… / 12</strong> — 12 personnes maximum par salle</div>
        </div>

        <!-- Boutons ajouter -->
        <button type="button" class="btn-add-salle" id="btn-add-1" onclick="ajouterSalle('resa-1', 'btn-add-1', 'btn-add-2')">+ Ajouter une salle</button>
        <button type="button" class="btn-add-salle" id="btn-add-2" onclick="ajouterSalle('resa-2', 'btn-add-2', 'btn-add-3')" style="display:none">+ Ajouter une salle</button>
        <button type="button" class="btn-add-salle" id="btn-add-3" onclick="ajouterSalle('resa-3', 'btn-add-3', null)" style="display:none">+ Ajouter une salle</button>
    </div>

    <!-- Étape 2 : Nombre de personnes -->
    <div class="form-step">
        <h2 class="form-step__title"><span class="form-step__num">2</span> Nombre de personnes</h2>
        <p class="form-step__sub">Inscrivez plusieurs personnes (ex. famille) — max 12 par salle</p>
        <div class="stepper">
            <button type="button" class="stepper__btn" onclick="changerNombre(-1)">−</button>
            <input type="number" name="nb_personnes" id="nb_personnes" value="<?php echo $prefill_places; ?>" min="1" max="12" readonly class="stepper__input">
            <button type="button" class="stepper__btn" onclick="changerNombre(1)">+</button>
        </div>
    </div>

    <!-- Étape 3 : Informations -->
    <div class="form-step">
        <h2 class="form-step__title"><span class="form-step__num">3</span> Vos informations</h2>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" class="form-input" placeholder="Votre nom" value="<?php echo $prefill_nom; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" class="form-input" placeholder="Votre prénom" value="<?php echo $prefill_prenom; ?>" required>
            </div>
        </div>

        <div class="form-group form-group--full">
            <label class="form-label" for="email">Adresse email * <span class="form-hint-inline">(pour recevoir votre confirmation)</span></label>
            <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" value="<?php echo $prefill_email; ?>" required>
        </div>

        <fieldset class="form-fieldset">
            <legend class="form-label">Qui êtes-vous ? *</legend>
            <label class="radio-option <?php echo ($prefill_profil == 'enseignant' || $prefill_profil == '') ? 'radio-option--active' : ''; ?>" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="enseignant" <?php echo ($prefill_profil == 'enseignant' || $prefill_profil == '') ? 'checked' : ''; ?> onchange="afficherBuffet(this)"> Enseignant·e
            </label>
            <label class="radio-option <?php echo $prefill_profil == 'etudiant_mmi' ? 'radio-option--active' : ''; ?>" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="etudiant_mmi" <?php echo $prefill_profil == 'etudiant_mmi' ? 'checked' : ''; ?> onchange="afficherBuffet(this)"> Étudiant·e MMI 2 ou 3
            </label>
            <label class="radio-option <?php echo $prefill_profil == 'personnel_usmb' ? 'radio-option--active' : ''; ?>" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="personnel_usmb" <?php echo $prefill_profil == 'personnel_usmb' ? 'checked' : ''; ?> onchange="afficherBuffet(this)"> Personnel USMB
            </label>
            <label class="radio-option <?php echo $prefill_profil == 'professionnel' ? 'radio-option--active' : ''; ?>" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="professionnel" <?php echo $prefill_profil == 'professionnel' ? 'checked' : ''; ?> onchange="afficherBuffet(this)"> Professionnel·le / Partenaire
            </label>
            <label class="radio-option <?php echo $prefill_profil == 'visiteur' ? 'radio-option--active' : ''; ?>" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="visiteur" <?php echo $prefill_profil == 'visiteur' ? 'checked' : ''; ?> onchange="afficherBuffet(this)"> Visiteur·s extérieur·e
            </label>
        </fieldset>

        <label class="buffet-option" id="buffet-option">
            <input type="checkbox" name="buffet" value="1" <?php echo $prefill_buffet ? 'checked' : ''; ?>>
            <div>
                <span class="buffet-title">Je participe au buffet du jeudi à 18h30</span><br>
                <span class="buffet-sub">Disponible : Enseignant·e, Personnel USMB, Visiteur extérieur, Professionnels</span>
            </div>
        </label>

        <div class="form-group form-group--full">
            <label class="form-label" for="referent">Contact référent·e inscriptions</label>
            <p class="form-hint">Pour tout problème ou cas particulier, contactez le·la référent·e de votre agence / TP.</p>
            <input type="text" id="referent" name="referent" class="form-input" placeholder="Ex. nom.prénom@univ-smb.fr">
        </div>

        <!-- Champs cachés pour envoyer les IDs BDD -->
        <input type="hidden" name="id_salle_0" id="hidden_id_salle_0" value="<?php echo $prefill_id_salle > 0 ? $prefill_id_salle : 1; ?>">
        <input type="hidden" name="id_creneau_0" id="hidden_id_creneau_0" value="<?php echo $prefill_id_cren > 0 ? $prefill_id_cren : 1; ?>">

        <button type="submit" class="btn-inscrip">Confirmer mon inscription →</button>
        <p class="form-confirm-note">Un email de confirmation avec votre code de réservation vous sera envoyé.</p>
        <a href="ma-reservation.php" class="form-modify">Retrouver ma réservation →</a>
    </div>

</form>
<?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>

<script>
// Données des places occupées chargées depuis PHP
// Clé = "id_salle-id_creneau", valeur = nombre de personnes déjà inscrites
var placesOccupees = {
<?php
$res_places = mysqli_query($conn, "SELECT salles_id_salles, creneaux_id_creneaux, SUM(nb_personnes) AS total FROM inscriptions GROUP BY salles_id_salles, creneaux_id_creneaux");
while ($p = mysqli_fetch_assoc($res_places)) {
    echo '    "' . $p['salles_id_salles'] . '-' . $p['creneaux_id_creneaux'] . '": ' . $p['total'] . ',' . "\n";
}
?>
};
</script>

<script>
// Changer le jour (jeudi / vendredi)
function switchJour(idGroupe, bouton) {
    var bloc = bouton.closest('.resa-block');
    var groupes = bloc.querySelectorAll('[id^="jeudi-"], [id^="vendredi-"]');
    for (var i = 0; i < groupes.length; i++) {
        groupes[i].style.display = 'none';
    }
    document.getElementById(idGroupe).style.display = 'block';

    var tabs = bouton.closest('.jour-tabs').querySelectorAll('.jour-tab');
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove('jour-tab--active');
    }
    bouton.classList.add('jour-tab--active');

    // Cacher le buffet si vendredi est sélectionné
    var buffet = document.getElementById('buffet-option');
    if (buffet) {
        if (idGroupe.indexOf('vendredi') !== -1) {
            buffet.style.display = 'none';
        } else {
            var categorie = document.querySelector('input[name="categorie"]:checked');
            if (categorie && categorie.value != 'etudiant_mmi') {
                buffet.style.display = 'flex';
            }
        }
    }
}

// Correspondance valeur salle → id BDD
var idSalles = {
    'plateau':  1,
    'salle001': 2,
    'salle002': 3,
    'salle005': 4
};

// Correspondance valeur créneau → id BDD
var idCreneaux = {
    'jeudi-15h00':    1,
    'jeudi-15h30':    2,
    'jeudi-16h00':    3,
    'jeudi-16h30':    4,
    'jeudi-17h00':    5,
    'jeudi-17h30':    6,
    'jeudi-18h00':    7,
    'jeudi-19h00':    8,
    'jeudi-19h30':    9,
    'jeudi-20h00':    10,
    'vendredi-9h30':  11,
    'vendredi-10h00': 12,
    'vendredi-10h30': 13,
    'vendredi-11h00': 14
};

// Trouver le numéro du bloc (0, 1, 2, 3)
function getNumBloc(element) {
    var bloc = element.closest('.resa-block');
    if (!bloc) return 0;
    var id = bloc.id; // ex: "resa-0", "resa-1"
    return parseInt(id.replace('resa-', ''));
}

// Mettre à jour la jauge à partir des données déjà chargées
function mettreAJourJauge(num) {
    var idSalle   = document.getElementById('hidden_id_salle_' + num).value;
    var idCreneau = document.getElementById('hidden_id_creneau_' + num).value;
    var jauge     = document.getElementById('jauge-' + num);
    if (!jauge) return;

    if (idSalle == 0 || idCreneau == 0) {
        jauge.innerHTML = 'Places restantes : <strong>… / 12</strong> — 12 personnes maximum par salle';
        return;
    }

    var cle = idSalle + '-' + idCreneau;
    var prises = 0;
    if (placesOccupees[cle]) {
        prises = placesOccupees[cle];
    }
    var restantes = 12 - prises;

    if (restantes <= 0) {
        jauge.innerHTML = '<strong style="color:#e05;">Complet</strong> — Plus aucune place disponible pour ce créneau';
        jauge.style.borderColor = '#e05';
    } else {
        jauge.innerHTML = 'Places restantes : <strong>' + restantes + ' / 12</strong> — 12 personnes maximum par salle';
        jauge.style.borderColor = '';
    }
}

// Mettre en surbrillance la salle sélectionnée + mettre à jour le champ caché
function selectionnerSalle(label) {
    var grille = label.closest('.salle-grid');
    var labels = grille.querySelectorAll('.salle-radio');
    for (var i = 0; i < labels.length; i++) {
        labels[i].classList.remove('salle-radio--active');
    }
    label.classList.add('salle-radio--active');

    var valeur = label.querySelector('input').value;
    var num = getNumBloc(label);
    document.getElementById('hidden_id_salle_' + num).value = idSalles[valeur];
    mettreAJourJauge(num);
    ajusterStepper();
}

// Mettre en surbrillance le créneau sélectionné + mettre à jour le champ caché
function selectionnerCreneau(label) {
    var liste = label.closest('.creneaux-list');
    var labels = liste.querySelectorAll('.creneau');
    for (var i = 0; i < labels.length; i++) {
        labels[i].classList.remove('creneau--active');
    }
    label.classList.add('creneau--active');

    var valeur = label.querySelector('input').value;
    var num = getNumBloc(label);
    document.getElementById('hidden_id_creneau_' + num).value = idCreneaux[valeur];
    mettreAJourJauge(num);
    ajusterStepper();
}

// Ajuster la valeur du stepper selon les places restantes
function ajusterStepper() {
    var input = document.getElementById('nb_personnes');
    var maxPlaces = getMaxPlaces();
    input.max = maxPlaces;
    if (parseInt(input.value) > maxPlaces) {
        input.value = maxPlaces;
    }
}

// Mettre en surbrillance la catégorie sélectionnée
function selectionnerCategorie(label) {
    var labels = document.querySelectorAll('.radio-option');
    for (var i = 0; i < labels.length; i++) {
        labels[i].classList.remove('radio-option--active');
    }
    label.classList.add('radio-option--active');
}

// Afficher ou cacher le buffet selon la catégorie
function afficherBuffet(radio) {
    var buffet = document.getElementById('buffet-option');
    if (radio.value == 'etudiant_mmi') {
        buffet.style.display = 'none';
    } else {
        buffet.style.display = 'flex';
    }
}

// Ajouter une salle (montrer le bloc suivant)
function ajouterSalle(idBloc, idBoutonActuel, idBoutonSuivant) {
    document.getElementById(idBloc).style.display = 'block';
    document.getElementById(idBoutonActuel).style.display = 'none';
    if (idBoutonSuivant != null) {
        document.getElementById(idBoutonSuivant).style.display = 'inline-flex';
    }
}

// Supprimer une salle (cacher le bloc + remettre les champs cachés à 0)
function supprimerSalle(idBloc, idBoutonAfficher) {
    document.getElementById(idBloc).style.display = 'none';
    document.getElementById(idBoutonAfficher).style.display = 'inline-flex';
    var num = idBloc.replace('resa-', '');
    document.getElementById('hidden_id_salle_' + num).value = 0;
    document.getElementById('hidden_id_creneau_' + num).value = 0;
}

// Charger la jauge et ajuster le stepper au démarrage
window.onload = function() {
    mettreAJourJauge(0);
    ajusterStepper();
};

// Calculer le maximum de places disponibles parmi toutes les salles sélectionnées
function getMaxPlaces() {
    var max = 12;
    for (var num = 0; num < 4; num++) {
        var champSalle   = document.getElementById('hidden_id_salle_' + num);
        var champCreneau = document.getElementById('hidden_id_creneau_' + num);
        if (!champSalle || !champCreneau) continue;
        var idSalle   = champSalle.value;
        var idCreneau = champCreneau.value;
        if (idSalle == 0 || idCreneau == 0) continue;
        var cle = idSalle + '-' + idCreneau;
        var prises = placesOccupees[cle] ? placesOccupees[cle] : 0;
        var restantes = 12 - prises;
        if (restantes < max) {
            max = restantes;
        }
    }
    if (max < 0) max = 0;
    return max;
}

// Changer le nombre de personnes
function changerNombre(delta) {
    var input = document.getElementById('nb_personnes');
    var valeur = parseInt(input.value) + delta;
    var maxPlaces = getMaxPlaces();
    if (valeur >= 1 && valeur <= maxPlaces) {
        input.value = valeur;
    }
    // Mettre à jour l'attribut max de l'input
    input.max = maxPlaces;
}
</script>
