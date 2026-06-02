<?php
session_start();

// Protection : réservé aux administrateurs
if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] != true || $_SESSION['role'] != 'admin') {
    header('Location: connexion.php');
    exit();
}

include '../includes/connexion_bdd.php';

// Oeuvres par salle (non stockées en BDD)
$oeuvres_salles = [
    1 => "Community · Distorsion",
    2 => "Bon Profil · Antithèse · Beauté hors du cadre",
    3 => "Tapis Rouge · En Direct · AD-HD",
    4 => "Lotus · E-biscus · Datura"
];

// Traitement suppression
if (isset($_POST['action']) && $_POST['action'] == 'supprimer' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM inscriptions WHERE id_inscriptions = $id");
    header('Location: admin.php?message=supprime');
    exit();
}

// Traitement modification
if (isset($_POST['action']) && $_POST['action'] == 'modifier') {
    $id         = intval($_POST['id']);
    $id_salle   = intval($_POST['id_salle']);
    $id_creneau = intval($_POST['id_creneau']);
    $places     = intval($_POST['places']);
    mysqli_query($conn, "UPDATE inscriptions SET salles_id_salles = $id_salle, creneaux_id_creneaux = $id_creneau, nb_personnes = $places WHERE id_inscriptions = $id");
    header('Location: admin.php?message=modifie');
    exit();
}

// Filtres de recherche
$where = "1=1";
if (isset($_GET['nom'])    && $_GET['nom']    != '') $where .= " AND u.nom    LIKE '%" . mysqli_real_escape_string($conn, $_GET['nom'])    . "%'";
if (isset($_GET['prenom']) && $_GET['prenom'] != '') $where .= " AND u.prenom LIKE '%" . mysqli_real_escape_string($conn, $_GET['prenom']) . "%'";
if (isset($_GET['email'])  && $_GET['email']  != '') $where .= " AND u.email  LIKE '%" . mysqli_real_escape_string($conn, $_GET['email'])  . "%'";
if (isset($_GET['salle'])  && $_GET['salle']  != '') $where .= " AND i.salles_id_salles = " . intval($_GET['salle']);
if (isset($_GET['creneau'])&& $_GET['creneau']!= '') $where .= " AND i.creneaux_id_creneaux = " . intval($_GET['creneau']);

// Réservations depuis la BDD
$reservations = [];
$res_resa = mysqli_query($conn, "SELECT i.id_inscriptions, u.nom, u.prenom, u.email, u.code_unique,
                                        s.nom_salle, s.id_salles,
                                        c.heure, c.jour, c.id_creneaux,
                                        i.nb_personnes
                                 FROM inscriptions i
                                 JOIN utilisateurs u ON u.id_user = i.utilisateurs_id_user
                                 JOIN salles s       ON s.id_salles = i.salles_id_salles
                                 JOIN creneaux c     ON c.id_creneaux = i.creneaux_id_creneaux
                                 WHERE $where
                                 ORDER BY c.jour, c.heure");
while ($row = mysqli_fetch_assoc($res_resa)) {
    $reservations[] = $row;
}

// Total personnes inscrites
$total_personnes = 0;
for ($i = 0; $i < count($reservations); $i++) {
    $total_personnes += $reservations[$i]['nb_personnes'];
}

// Charger salles et créneaux pour les selects du formulaire de modification
$toutes_salles  = [];
$tous_creneaux  = [];
$res_s = mysqli_query($conn, "SELECT id_salles, nom_salle FROM salles ORDER BY id_salles");
while ($s = mysqli_fetch_assoc($res_s)) { $toutes_salles[] = $s; }
$res_c = mysqli_query($conn, "SELECT id_creneaux, jour, heure FROM creneaux ORDER BY jour, heure");
while ($c = mysqli_fetch_assoc($res_c)) { $tous_creneaux[] = $c; }

// Créneau sélectionné via les boutons (GET)
$creneau_filtre = isset($_GET['creneau']) ? intval($_GET['creneau']) : 0;

// Places par salle pour le créneau sélectionné (ou total si aucun créneau)
$places_salles = [];
if ($creneau_filtre > 0) {
    $res_salles = mysqli_query($conn, "SELECT s.id_salles, s.nom_salle, COALESCE(SUM(i.nb_personnes), 0) AS total
                                       FROM salles s
                                       LEFT JOIN inscriptions i ON i.salles_id_salles = s.id_salles
                                           AND i.creneaux_id_creneaux = $creneau_filtre
                                       GROUP BY s.id_salles
                                       ORDER BY s.id_salles");
} else {
    $res_salles = mysqli_query($conn, "SELECT s.id_salles, s.nom_salle, COALESCE(SUM(i.nb_personnes), 0) AS total
                                       FROM salles s
                                       LEFT JOIN inscriptions i ON i.salles_id_salles = s.id_salles
                                       GROUP BY s.id_salles
                                       ORDER BY s.id_salles");
}
while ($row = mysqli_fetch_assoc($res_salles)) {
    $places_salles[] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — E-LLUSION</title>
    <link rel="stylesheet" href="../ressources/css/style.css">
</head>
<body class="admin-body">

<!-- ── Navbar admin ── -->
<nav class="admin-nav">
    <a href="../index.php" class="admin-nav__logo">E-LLUSION</a>
    <span class="admin-nav__badge">ADMIN</span>
    <a href="deconnexion.php" class="btn-login">Se déconnecter</a>
</nav>

<div class="admin-page">

    <?php if (isset($_GET['message']) && $_GET['message'] == 'supprime') : ?>
    <div class="admin-message admin-message--ok">✓ Réservation supprimée avec succès.</div>
    <?php endif; ?>
    <?php if (isset($_GET['message']) && $_GET['message'] == 'modifie') : ?>
    <div class="admin-message admin-message--ok">✓ Réservation modifiée avec succès.</div>
    <?php endif; ?>

    <!-- ── En-tête ── -->
    <div class="admin-hero">
        <p class="section-label">Administration</p>
        <h1 class="admin-title">Tableau de bord</h1>
        <p class="admin-subtitle">Gestion des réservations — E-LLUSION · IUT de Chambéry · SAE 202</p>
    </div>

    <!-- ── Filtres créneaux ── -->
    <div class="admin-block" id="creneaux">
        <a href="admin.php#creneaux" class="admin-creneau <?php echo $creneau_filtre == 0 ? 'admin-creneau--active' : ''; ?>" style="margin-bottom:16px;display:inline-block">Tous les créneaux</a>
        <?php for ($i = 0; $i < count($tous_creneaux); $i++) : ?>
        <?php
            $c = $tous_creneaux[$i];
            $lj = strtotime($c['jour']) < strtotime('2026-06-19') ? 'CRÉNEAUX JEUDI 18 JUIN' : 'CRÉNEAUX VENDREDI 19 JUIN';
            $lh = date('H\hi', strtotime($c['heure']));
            // Afficher le label du jour avant le premier créneau de chaque journée
            if ($i == 0 || $lj != (strtotime($tous_creneaux[$i-1]['jour']) < strtotime('2026-06-19') ? 'CRÉNEAUX JEUDI 18 JUIN' : 'CRÉNEAUX VENDREDI 19 JUIN')) :
        ?>
        <div class="admin-creneaux-group">
            <p class="admin-creneaux-label"><?php echo $lj; ?></p>
            <div class="admin-creneaux">
        <?php endif; ?>
                <a href="admin.php?creneau=<?php echo $c['id_creneaux']; ?>#creneaux" class="admin-creneau <?php echo $creneau_filtre == $c['id_creneaux'] ? 'admin-creneau--active' : ''; ?>"><?php echo $lh; ?></a>
        <?php
            $next_lj = ($i + 1 < count($tous_creneaux)) ? (strtotime($tous_creneaux[$i+1]['jour']) < strtotime('2026-06-19') ? 'CRÉNEAUX JEUDI 18 JUIN' : 'CRÉNEAUX VENDREDI 19 JUIN') : '';
            if ($i == count($tous_creneaux) - 1 || $next_lj != $lj) :
        ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endfor; ?>
    </div>

    <!-- ── Places disponibles ── -->
    <div class="admin-block">
        <p class="section-label">Temps réel</p>
        <?php if ($creneau_filtre > 0) : ?>
            <?php
                // Trouver le label du créneau sélectionné
                $label_cren = '';
                for ($i = 0; $i < count($tous_creneaux); $i++) {
                    if ($tous_creneaux[$i]['id_creneaux'] == $creneau_filtre) {
                        $lj = strtotime($tous_creneaux[$i]['jour']) < strtotime('2026-06-19') ? 'Jeudi 18 juin' : 'Vendredi 19 juin';
                        $label_cren = date('H\hi', strtotime($tous_creneaux[$i]['heure'])) . ' — ' . $lj;
                    }
                }
            ?>
            <h2 class="admin-section-title">Places pour le créneau : <?php echo $label_cren; ?></h2>
        <?php else : ?>
            <h2 class="admin-section-title">Total des inscriptions par salle</h2>
        <?php endif; ?>
        <div class="admin-salles-grid">
            <?php for ($i = 0; $i < count($places_salles); $i++) : ?>
            <?php
                $salle   = $places_salles[$i];
                $id      = $salle['id_salles'];
                $total   = intval($salle['total']);
                $oeuvres = isset($oeuvres_salles[$id]) ? $oeuvres_salles[$id] : '';
                $max     = $creneau_filtre > 0 ? 12 : 12 * count($tous_creneaux);
                $pct     = $max > 0 ? min(($total / $max * 100), 100) : 0;
                $plein   = $creneau_filtre > 0 && $total >= 12;
            ?>
            <div class="admin-salle-card <?php echo $plein ? 'admin-salle-card--plein' : ''; ?>">
                <span class="admin-salle-badge <?php echo $plein ? 'admin-salle-badge--plein' : ''; ?>"><?php echo h($salle['nom_salle']); ?></span>
                <p class="admin-salle-oeuvres"><?php echo h($oeuvres); ?></p>
                <p class="admin-salle-dispo">
                    <strong><?php echo $total; ?></strong>
                    <?php if ($creneau_filtre > 0) : ?>
                    <span>/ 12 places</span>
                    <?php else : ?>
                    <span>personnes inscrites</span>
                    <?php endif; ?>
                </p>
                <div class="admin-jauge-barre">
                    <div class="admin-jauge-fill" style="width: <?php echo $pct; ?>%"></div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- ── Recherche ── -->
    <div class="admin-block">
        <p class="section-label">Filtrage</p>
        <h2 class="admin-section-title">Rechercher une inscription</h2>
        <form class="admin-search" method="get" action="admin.php">
            <input type="text"   name="nom"    class="admin-input" placeholder="Nom">
            <input type="text"   name="prenom" class="admin-input" placeholder="Prénom">
            <input type="email"  name="email"  class="admin-input" placeholder="Adresse email">
            <select name="salle" class="admin-input">
                <option value="">Toutes les salles</option>
                <?php for ($i = 0; $i < count($toutes_salles); $i++) : ?>
                <option value="<?php echo $toutes_salles[$i]['id_salles']; ?>" <?php echo (isset($_GET['salle']) && $_GET['salle'] == $toutes_salles[$i]['id_salles']) ? 'selected' : ''; ?>><?php echo $toutes_salles[$i]['nom_salle']; ?></option>
                <?php endfor; ?>
            </select>
            <select name="creneau" class="admin-input">
                <option value="">Tous les créneaux</option>
                <?php for ($i = 0; $i < count($tous_creneaux); $i++) : ?>
                <?php
                    $c = $tous_creneaux[$i];
                    $lj = strtotime($c['jour']) < strtotime('2026-06-19') ? 'Jeudi 18 juin' : 'Vendredi 19 juin';
                    $lh = date('H\hi', strtotime($c['heure']));
                ?>
                <option value="<?php echo $c['id_creneaux']; ?>" <?php echo (isset($_GET['creneau']) && $_GET['creneau'] == $c['id_creneaux']) ? 'selected' : ''; ?>><?php echo $lh . ' — ' . $lj; ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn-primary">Rechercher</button>
            <button type="reset" class="btn-outline" onclick="window.location='admin.php'">Réinitialiser</button>
        </form>
    </div>

    <!-- ── Tableau réservations ── -->
    <div class="admin-block">
        <p class="section-label">Réservations</p>
        <h2 class="admin-section-title">Affichage global par salle et horaire</h2>
        <p class="admin-total"><?php echo count($reservations); ?> réservations · <?php echo $total_personnes; ?> personnes inscrites</p>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Salle</th>
                    <th>Créneau</th>
                    <th>Places</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($reservations); $i++) : ?>
                <?php $r = $reservations[$i]; ?>
                <?php
                    $label_jour   = strtotime($r['jour']) < strtotime('2026-06-19') ? 'Jeudi 18 juin' : 'Vendredi 19 juin';
                    $label_heure  = date('H\hi', strtotime($r['heure']));
                ?>
                <tr>
                    <td class="admin-table__id"><?php echo $r['id_inscriptions']; ?></td>
                    <td><?php echo h($r['nom']); ?></td>
                    <td><?php echo h($r['prenom']); ?></td>
                    <td style="font-size:11px;color:#666"><?php echo h($r['email']); ?></td>
                    <td><span class="admin-salle-tag"><?php echo h($r['nom_salle']); ?></span></td>
                    <td><?php echo $label_heure . ' — ' . $label_jour; ?></td>
                    <td><?php echo $r['nb_personnes']; ?></td>
                    <td class="admin-table__actions">
                        <button class="btn-modifier" onclick="chargerEdition(<?php echo $r['id_inscriptions']; ?>, '<?php echo addslashes(h($r['nom'])); ?>', '<?php echo addslashes(h($r['prenom'])); ?>', <?php echo $r['id_salles']; ?>, <?php echo $r['id_creneaux']; ?>, <?php echo $r['nb_personnes']; ?>)">Modifier</button>
                        <form method="post" action="admin.php" style="display:inline" onsubmit="return confirm('Supprimer la réservation de <?php echo $r['nom']; ?> <?php echo $r['prenom']; ?> ?')">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="id" value="<?php echo $r['id_inscriptions']; ?>">
                            <button type="submit" class="btn-supprimer-inline">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div class="admin-pagination">
            <span class="admin-pagination__info">Page 1 / 3 · 24 résultats</span>
            <button class="btn-outline" type="button">Précédent</button>
            <button class="btn-outline" type="button">Suivant</button>
        </div>
    </div>

    <!-- ── Édition ── -->
    <div class="admin-block" id="edition-block">
        <p class="section-label">Édition</p>
        <h2 class="admin-section-title">Modifier ou supprimer une réservation</h2>
        <p class="admin-edition-hint">Sélectionnez une ligne dans le tableau ci-dessus pour la modifier.</p>

        <div class="admin-edition-form" id="edition-form" style="display:none">
            <p class="admin-edition-ref" id="edition-ref"></p>
            <form method="post" action="admin.php">
                <input type="hidden" name="action" value="modifier">
                <input type="hidden" name="id" id="edit-id">
                <div class="admin-edition-champs">
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nom" id="edit-nom" class="admin-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="prenom" id="edit-prenom" class="admin-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salle</label>
                        <select name="id_salle" id="edit-salle" class="admin-input">
                            <?php for ($i = 0; $i < count($toutes_salles); $i++) : ?>
                            <option value="<?php echo $toutes_salles[$i]['id_salles']; ?>"><?php echo h($toutes_salles[$i]['nom_salle']); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Créneau</label>
                        <select name="id_creneau" id="edit-creneau" class="admin-input">
                            <?php for ($i = 0; $i < count($tous_creneaux); $i++) : ?>
                            <?php
                                $c = $tous_creneaux[$i];
                                $lj = strtotime($c['jour']) < strtotime('2026-06-19') ? 'Jeudi 18 juin' : 'Vendredi 19 juin';
                                $lh = date('H\hi', strtotime($c['heure']));
                            ?>
                            <option value="<?php echo $c['id_creneaux']; ?>"><?php echo $lh . ' — ' . $lj; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nb personnes</label>
                        <div class="stepper">
                            <button type="button" class="stepper__btn" onclick="changerNombre(-1)">−</button>
                            <input type="number" name="places" id="edit-places" class="stepper__input" min="1" max="12" readonly>
                            <button type="button" class="stepper__btn" onclick="changerNombre(1)">+</button>
                        </div>
                    </div>
                </div>
                <div class="admin-edition-actions">
                    <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                    <button type="button" class="btn-outline" onclick="fermerEdition()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

</div><!-- /admin-page -->

<!-- ── Footer ── -->
<footer class="footer">
    <div class="footer__left">
        <div class="footer__logo">E-LLUSION</div>
        <p class="footer__desc">Exposition interactive MMI-1 · IUT de Chambéry · Université Savoie Mont Blanc</p>
        <div class="footer__links">
            <a href="#">Instagram @mmi_chambery</a>
            <a href="#">Site MMI</a>
        </div>
    </div>
    <div class="footer__copy">© 2025 MMI-1 IUT de Chambéry</div>
</footer>

<script>
function chargerEdition(id, nom, prenom, idSalle, idCreneau, places) {
    document.getElementById('edition-form').style.display = 'block';
    document.getElementById('edition-ref').textContent = '# Réservation ' + id + ' — ' + nom + ' ' + prenom + ' · ' + places + ' personnes';
    document.getElementById('edit-id').value     = id;
    document.getElementById('edit-nom').value    = nom;
    document.getElementById('edit-prenom').value = prenom;
    document.getElementById('edit-places').value = places;

    var selectSalle = document.getElementById('edit-salle');
    for (var i = 0; i < selectSalle.options.length; i++) {
        if (selectSalle.options[i].value == idSalle) {
            selectSalle.selectedIndex = i;
        }
    }
    var selectCreneau = document.getElementById('edit-creneau');
    for (var i = 0; i < selectCreneau.options.length; i++) {
        if (selectCreneau.options[i].value == idCreneau) {
            selectCreneau.selectedIndex = i;
        }
    }

    document.getElementById('edition-block').scrollIntoView({ behavior: 'smooth' });
}

function fermerEdition() {
    document.getElementById('edition-form').style.display = 'none';
}

function confirmerSuppression() {
    var id = document.getElementById('edit-id').value;
    var ok = confirm('Supprimer la réservation n°' + id + ' ? Cette action est irréversible.');
    if (ok) {
        document.getElementById('form-supprimer-id').value = id;
        document.getElementById('form-supprimer').submit();
    }
}

function changerNombre(delta) {
    var input = document.getElementById('edit-places');
    var valeur = parseInt(input.value) + delta;
    if (valeur >= 1 && valeur <= 12) {
        input.value = valeur;
    }
}

</script>

</body>
</html>
