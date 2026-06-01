<?php
$pageTitle = "Inscription";
$rootPath = "../";
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
<form class="inscrip-form" method="post" action="#">

    <!-- Étape 1 : Salles & créneaux -->
    <div class="form-step">
        <h2 class="form-step__title"><span class="form-step__num">1</span> Choisissez vos salles &amp; créneaux</h2>
        <p class="form-step__sub">Vous pouvez réserver plusieurs salles à des horaires différents en une seule fois.</p>

        <!-- Bloc salle 1 (toujours visible) -->
        <div class="resa-block" id="resa-0">
            <p class="resa-block__label">Salle 1</p>
            <div class="salle-grid">
                <label class="salle-radio salle-radio--active" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="plateau" checked> PLATEAU Régie
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="salle001"> Salle 001
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="salle002"> Salle 002
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[0]" value="salle005"> Salle 005
                </label>
            </div>
            <div class="jour-tabs">
                <button type="button" class="jour-tab jour-tab--active" onclick="switchJour('jeudi-0', this)">Jeudi 18 juin</button>
                <button type="button" class="jour-tab" onclick="switchJour('vendredi-0', this)">Vendredi 19 juin</button>
            </div>
            <div id="jeudi-0">
                <p class="creneaux-date">Jeudi 18 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau creneau--active" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-15h00" checked> 15h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-15h30"> 15h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-16h00"> 16h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-16h30"> 16h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-17h00"> 17h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-17h30"> 17h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-18h00"> 18h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-19h00"> 19h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-19h30"> 19h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="jeudi-20h00"> 20h00</label>
                </div>
            </div>
            <div id="vendredi-0" style="display:none">
                <p class="creneaux-date">Vendredi 19 juin 2026</p>
                <div class="creneaux-list">
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-9h30"> 9h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-10h00"> 10h00</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-10h30"> 10h30</label>
                    <label class="creneau" onclick="selectionnerCreneau(this)"><input type="radio" name="creneau[0]" value="vendredi-11h00"> 11h00</label>
                </div>
            </div>
            <div class="jauge-banner">Places restantes : <strong>8 / 12</strong> — 12 personnes maximum par salle</div>
        </div>

        <!-- Bloc salle 2 (caché par défaut) -->
        <div class="resa-block" id="resa-1" style="display:none">
            <div class="resa-block__header">
                <p class="resa-block__label">Salle 2</p>
                <button type="button" class="resa-remove" onclick="supprimerSalle('resa-1', 'btn-add-1')">✕</button>
            </div>
            <div class="salle-grid">
                <label class="salle-radio salle-radio--active" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="plateau" checked> PLATEAU Régie
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="salle001"> Salle 001
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="salle002"> Salle 002
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[1]" value="salle005"> Salle 005
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
            <div class="jauge-banner">Places restantes : <strong>8 / 12</strong> — 12 personnes maximum par salle</div>
        </div>

        <!-- Bloc salle 3 (caché par défaut) -->
        <div class="resa-block" id="resa-2" style="display:none">
            <div class="resa-block__header">
                <p class="resa-block__label">Salle 3</p>
                <button type="button" class="resa-remove" onclick="supprimerSalle('resa-2', 'btn-add-2')">✕</button>
            </div>
            <div class="salle-grid">
                <label class="salle-radio salle-radio--active" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="plateau" checked> PLATEAU Régie
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="salle001"> Salle 001
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="salle002"> Salle 002
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[2]" value="salle005"> Salle 005
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
            <div class="jauge-banner">Places restantes : <strong>8 / 12</strong> — 12 personnes maximum par salle</div>
        </div>

        <!-- Bloc salle 4 (caché par défaut) -->
        <div class="resa-block" id="resa-3" style="display:none">
            <div class="resa-block__header">
                <p class="resa-block__label">Salle 4</p>
                <button type="button" class="resa-remove" onclick="supprimerSalle('resa-3', 'btn-add-3')">✕</button>
            </div>
            <div class="salle-grid">
                <label class="salle-radio salle-radio--active" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="plateau" checked> PLATEAU Régie
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="salle001"> Salle 001
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="salle002"> Salle 002
                </label>
                <label class="salle-radio" onclick="selectionnerSalle(this)">
                    <input type="radio" name="salle[3]" value="salle005"> Salle 005
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
            <div class="jauge-banner">Places restantes : <strong>8 / 12</strong> — 12 personnes maximum par salle</div>
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
            <input type="number" name="nb_personnes" id="nb_personnes" value="1" min="1" max="12" readonly class="stepper__input">
            <button type="button" class="stepper__btn" onclick="changerNombre(1)">+</button>
        </div>
    </div>

    <!-- Étape 3 : Informations -->
    <div class="form-step">
        <h2 class="form-step__title"><span class="form-step__num">3</span> Vos informations</h2>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" class="form-input" placeholder="Votre nom" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" class="form-input" placeholder="Votre prénom" required>
            </div>
        </div>

        <div class="form-group form-group--full">
            <label class="form-label" for="email">Adresse email * <span class="form-hint-inline">(pour recevoir votre confirmation)</span></label>
            <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" required>
        </div>

        <fieldset class="form-fieldset">
            <legend class="form-label">Qui êtes-vous ? *</legend>
            <label class="radio-option radio-option--active" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="enseignant" checked onchange="afficherBuffet(this)"> Enseignant·e
            </label>
            <label class="radio-option" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="etudiant_mmi" onchange="afficherBuffet(this)"> Étudiant·e MMI 2 ou 3
            </label>
            <label class="radio-option" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="personnel_usmb" onchange="afficherBuffet(this)"> Personnel USMB
            </label>
            <label class="radio-option" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="professionnel" onchange="afficherBuffet(this)"> Professionnel·le / Partenaire
            </label>
            <label class="radio-option" onclick="selectionnerCategorie(this)">
                <input type="radio" name="categorie" value="visiteur" onchange="afficherBuffet(this)"> Visiteur·s extérieur·e
            </label>
        </fieldset>

        <label class="buffet-option" id="buffet-option">
            <input type="checkbox" name="buffet" value="1" checked>
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

        <button type="submit" class="btn-inscrip">Confirmer mon inscription →</button>
        <p class="form-confirm-note">Un email de confirmation sera envoyé au référent de l'inscription.</p>
        <a href="#" class="form-modify">Modifier ou supprimer ma réservation →</a>
    </div>

</form>
</section>

<?php include '../includes/footer.php'; ?>

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

// Mettre en surbrillance la salle sélectionnée
function selectionnerSalle(label) {
    var grille = label.closest('.salle-grid');
    var labels = grille.querySelectorAll('.salle-radio');
    for (var i = 0; i < labels.length; i++) {
        labels[i].classList.remove('salle-radio--active');
    }
    label.classList.add('salle-radio--active');
}

// Mettre en surbrillance le créneau sélectionné
function selectionnerCreneau(label) {
    var liste = label.closest('.creneaux-list');
    var labels = liste.querySelectorAll('.creneau');
    for (var i = 0; i < labels.length; i++) {
        labels[i].classList.remove('creneau--active');
    }
    label.classList.add('creneau--active');
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

// Supprimer une salle (cacher le bloc)
function supprimerSalle(idBloc, idBoutonAfficher) {
    document.getElementById(idBloc).style.display = 'none';
    document.getElementById(idBoutonAfficher).style.display = 'inline-flex';
}

// Changer le nombre de personnes
function changerNombre(delta) {
    var input = document.getElementById('nb_personnes');
    var valeur = parseInt(input.value) + delta;
    if (valeur >= 1 && valeur <= 12) {
        input.value = valeur;
    }
}
</script>
