<?php
$pageTitle = "Contact";
$rootPath = "../";
include '../includes/header.php';

$referents = [
    ["nom" => "Societ-e (021)", "email" => "referent1@etudiant.univ-smb.fr"],
    ["nom" => "Horizon (001)", "email" => "referent2@etudiant.univ-smb.fr"],
    ["nom" => "L'Envers du Décor (002)", "email" => "referent3@etudiant.univ-smb.fr"],
    ["nom" => "La Pépinière (005)", "email" => "referent4@etudiant.univ-smb.fr"],
];
?>

<!-- ── Hero ── -->
<section class="contact-hero">
    <p class="section-label">Contact</p>
    <h1 class="section-title">Une question ?</h1>
    <p class="contact-hero__sub">Contactez la responsable du projet ou le·la référent·e de chaque agence.</p>
</section>

<!-- ── Contenu contact ── -->
<section class="contact-page">

    <!-- Toutes les cartes sur une ligne -->
    <div class="contact-cards-row">
        <!-- Responsable du projet -->
        <div class="contact-card contact-card--main">
            <div class="contact-card__info">
                <p class="contact-card__role">Responsable du projet</p>
                <h2 class="contact-card__name">François Piranda</h2>
                <p class="contact-card__org">IUT de Chambéry · Département MMI</p>
                <a href="mailto:francois.piranda@univ-smb.fr" class="contact-card__email">francois.piranda@univ-smb.fr</a>
            </div>
        </div>

        <!-- Référent·e·s agences -->
        <?php foreach ($referents as $ref) : ?>
        <div class="contact-card contact-card--ref">
            <div class="contact-card__info">
                <p class="contact-card__role">Référent·e inscriptions</p>
                <h3 class="contact-card__name"><?php echo $ref['nom']; ?></h3>
                <p class="contact-card__org">Pour tout problème ou cas particulier</p>
                <a href="mailto:<?php echo $ref['email']; ?>" class="contact-card__email"><?php echo $ref['email']; ?></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Bandeau infos lieu / dates -->
    <div class="contact-infos">
        <div class="contact-infos__lieu">
            <p>IUT de Chambéry · Université Savoie Mont Blanc</p>
            <p>Boulevard du Lac · 73370 Le Bourget-du-Lac</p>
        </div>
        <div class="contact-infos__dates">
            <p>Jeudi 18 juin : 15h – 20h</p>
            <p>Vendredi 19 juin : 9h30 – 11h</p>
        </div>
    </div>

    <!-- CTA -->
    <div class="contact-cta">
        <div>
            <p class="contact-cta__title">Prêt·e à visiter E-Illusion ?</p>
            <p class="contact-cta__sub">Réservez votre créneau — places limitées</p>
        </div>
        <a href="inscription.php" class="btn-outline">S'inscrire →</a>
    </div>

</section>

<?php include '../includes/footer.php'; ?>
