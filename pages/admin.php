<?php
session_start();

// Protection : réservé aux administrateurs
if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] != true || $_SESSION['role'] != 'admin') {
    header('Location: connexion.php');
    exit();
}

// Traitement suppression
if (isset($_POST['action']) && $_POST['action'] == 'supprimer' && isset($_POST['id'])) {
    // Quand la BDD sera connectée : DELETE FROM reservations WHERE id = $_POST['id']
    header('Location: admin.php?message=supprime');
    exit();
}

// Traitement modification
if (isset($_POST['action']) && $_POST['action'] == 'modifier') {
    // Quand la BDD sera connectée : UPDATE reservations SET ...
    header('Location: admin.php?message=modifie');
    exit();
}

// Données fictives en attendant la BDD
$reservations = [
    ["id" => "01", "nom" => "Martin",  "prenom" => "Sophie", "salle" => "Plateau Régie", "creneau" => "18h00", "places" => 2, "statut" => "confirme"],
    ["id" => "02", "nom" => "Dupont",  "prenom" => "Lea",    "salle" => "Salle 001",     "creneau" => "19h00", "places" => 1, "statut" => "confirme"],
    ["id" => "03", "nom" => "Bernard", "prenom" => "Theo",   "salle" => "Salle 002",     "creneau" => "17h30", "places" => 4, "statut" => "confirme"],
    ["id" => "04", "nom" => "Moreau",  "prenom" => "Emma",   "salle" => "Salle 005",     "creneau" => "20h00", "places" => 3, "statut" => "confirme"],
    ["id" => "05", "nom" => "Laurent", "prenom" => "Hugo",   "salle" => "Plateau Régie", "creneau" => "19h30", "places" => 2, "statut" => "annule"],
    ["id" => "06", "nom" => "Simon",   "prenom" => "Jade",   "salle" => "Salle 001",     "creneau" => "10h00", "places" => 1, "statut" => "confirme"],
    ["id" => "07", "nom" => "Michel",  "prenom" => "Luca",   "salle" => "Salle 002",     "creneau" => "9h30",  "places" => 4, "statut" => "confirme"],
    ["id" => "08", "nom" => "Garcia",  "prenom" => "Ines",   "salle" => "Salle 005",     "creneau" => "10h30", "places" => 2, "statut" => "confirme"],
];

$places_salles = [
    "Plateau Régie" => ["oeuvres" => "Community · Distorsion", "dispo" => 8,  "couleur" => "cyan"],
    "Salle 001"     => ["oeuvres" => "Distorsion",             "dispo" => 3,  "couleur" => "cyan"],
    "Salle 002"     => ["oeuvres" => "Oeuvres 1-3",            "dispo" => 12, "couleur" => "cyan"],
    "Salle 005"     => ["oeuvres" => "Oeuvres 1-4",            "dispo" => 0,  "couleur" => "rouge"],
];
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
    <div class="admin-block">
        <div class="admin-creneaux-group">
            <p class="admin-creneaux-label">CRÉNEAUX JEUDI 18 JUIN</p>
            <div class="admin-creneaux">
                <button class="admin-creneau admin-creneau--active" type="button">17h30</button>
                <button class="admin-creneau" type="button">18h00</button>
                <button class="admin-creneau" type="button">19h00</button>
                <button class="admin-creneau" type="button">19h30</button>
                <button class="admin-creneau" type="button">20h00</button>
            </div>
        </div>
        <div class="admin-creneaux-group">
            <p class="admin-creneaux-label">CRÉNEAUX VENDREDI 19 JUIN</p>
            <div class="admin-creneaux">
                <button class="admin-creneau" type="button">9h30</button>
                <button class="admin-creneau" type="button">10h00</button>
                <button class="admin-creneau" type="button">10h30</button>
                <button class="admin-creneau" type="button">11h00</button>
            </div>
        </div>
    </div>

    <!-- ── Places disponibles ── -->
    <div class="admin-block">
        <p class="section-label">Temps réel</p>
        <h2 class="admin-section-title">Places disponibles par salle</h2>
        <div class="admin-salles-grid">
            <?php foreach ($places_salles as $nom => $info) : ?>
            <div class="admin-salle-card">
                <span class="admin-salle-badge"><?php echo $nom; ?></span>
                <p class="admin-salle-oeuvres"><?php echo $info['oeuvres']; ?></p>
                <p class="admin-salle-dispo">
                    <strong><?php echo $info['dispo']; ?></strong>
                    <span>/ 12 places</span>
                </p>
                <div class="admin-jauge-barre">
                    <div class="admin-jauge-fill" style="width: <?php echo (12 - $info['dispo']) / 12 * 100; ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
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
                <option value="plateau">Plateau Régie</option>
                <option value="salle001">Salle 001</option>
                <option value="salle002">Salle 002</option>
                <option value="salle005">Salle 005</option>
            </select>
            <select name="creneau" class="admin-input">
                <option value="">Tous les créneaux</option>
                <option value="jeudi-17h30">Jeudi 17h30</option>
                <option value="jeudi-18h00">Jeudi 18h00</option>
                <option value="jeudi-19h00">Jeudi 19h00</option>
                <option value="jeudi-19h30">Jeudi 19h30</option>
                <option value="jeudi-20h00">Jeudi 20h00</option>
                <option value="vendredi-9h30">Vendredi 9h30</option>
                <option value="vendredi-10h00">Vendredi 10h00</option>
                <option value="vendredi-10h30">Vendredi 10h30</option>
                <option value="vendredi-11h00">Vendredi 11h00</option>
            </select>
            <button type="submit" class="btn-primary">Rechercher</button>
            <button type="reset" class="btn-outline" onclick="window.location='admin.php'">Réinitialiser</button>
        </form>
    </div>

    <!-- ── Tableau réservations ── -->
    <div class="admin-block">
        <p class="section-label">Réservations</p>
        <h2 class="admin-section-title">Affichage global par salle et horaire</h2>
        <p class="admin-total">8 réservations · 30 personnes inscrites</p>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Salle</th>
                    <th>Créneau</th>
                    <th>Places</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r) : ?>
                <tr>
                    <td class="admin-table__id"><?php echo $r['id']; ?></td>
                    <td><?php echo $r['nom']; ?></td>
                    <td><?php echo $r['prenom']; ?></td>
                    <td><span class="admin-salle-tag"><?php echo $r['salle']; ?></span></td>
                    <td><?php echo $r['creneau']; ?></td>
                    <td><?php echo $r['places']; ?></td>
                    <td>
                        <?php if ($r['statut'] == 'confirme') : ?>
                            <span class="admin-statut admin-statut--ok">Confirmé</span>
                        <?php else : ?>
                            <span class="admin-statut admin-statut--annule">Annulé</span>
                        <?php endif; ?>
                    </td>
                    <td class="admin-table__actions">
                        <button class="btn-modifier" onclick="chargerEdition('<?php echo $r['id']; ?>', '<?php echo $r['nom']; ?>', '<?php echo $r['prenom']; ?>', '<?php echo $r['salle']; ?>', '<?php echo $r['creneau']; ?>', <?php echo $r['places']; ?>)">Modifier</button>
                        <form method="post" action="admin.php" style="display:inline" onsubmit="return confirm('Supprimer la réservation de <?php echo $r['nom']; ?> <?php echo $r['prenom']; ?> ? Cette action est irréversible.')">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                            <button type="submit" class="btn-supprimer-inline">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
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
                        <select name="salle" id="edit-salle" class="admin-input">
                            <option value="Plateau Régie">Plateau Régie</option>
                            <option value="Salle 001">Salle 001</option>
                            <option value="Salle 002">Salle 002</option>
                            <option value="Salle 005">Salle 005</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Créneau</label>
                        <select name="creneau" id="edit-creneau" class="admin-input">
                            <option>17h30</option><option>18h00</option><option>19h00</option>
                            <option>19h30</option><option>20h00</option><option>9h30</option>
                            <option>10h00</option><option>10h30</option><option>11h00</option>
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
                    <button type="button" class="btn-supprimer" onclick="confirmerSuppression()">Supprimer la réservation</button>
                    <form method="post" action="admin.php" id="form-supprimer" style="display:none">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id" id="form-supprimer-id">
                    </form>
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
function chargerEdition(id, nom, prenom, salle, creneau, places) {
    document.getElementById('edition-form').style.display = 'block';
    document.getElementById('edition-ref').textContent = '# Réservation ' + id + ' - ' + nom + ' ' + prenom + ' · ' + salle + ' · ' + creneau + ' · ' + places + ' personnes';
    document.getElementById('edit-id').value     = id;
    document.getElementById('edit-nom').value    = nom;
    document.getElementById('edit-prenom').value = prenom;
    document.getElementById('edit-places').value = places;

    var selectSalle = document.getElementById('edit-salle');
    for (var i = 0; i < selectSalle.options.length; i++) {
        if (selectSalle.options[i].value == salle) {
            selectSalle.selectedIndex = i;
        }
    }
    var selectCreneau = document.getElementById('edit-creneau');
    for (var i = 0; i < selectCreneau.options.length; i++) {
        if (selectCreneau.options[i].value == creneau) {
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

// Highlight créneau actif
var creneaux = document.querySelectorAll('.admin-creneau');
for (var i = 0; i < creneaux.length; i++) {
    creneaux[i].addEventListener('click', function() {
        for (var j = 0; j < creneaux.length; j++) {
            creneaux[j].classList.remove('admin-creneau--active');
        }
        this.classList.add('admin-creneau--active');
    });
}
</script>

</body>
</html>
