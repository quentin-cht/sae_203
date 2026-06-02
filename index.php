<?php
$pageTitle = "Accueil";
$rootPath = "";
include 'includes/header.php';
?>

<!-- ── Hero ── -->
<section class="hero">
    <div class="hero__content">
        <h1 class="hero__title">E-LLUSION</h1>
        <h2 class="hero__subtitle">L'impact du numérique sur notre perception du réel.</h2>
        <p class="hero__desc">Exposition interactive — IUT de Chambéry · MMI-1</p>
        <p class="hero__dates">18 &amp; 19 juin 2026</p>
        <div class="hero__buttons">
            <a href="pages/inscription.php" class="btn-primary">Réserver ma visite →</a>
            <a href="pages/exposition.php" class="btn-outline">Découvrir →</a>
        </div>
    </div>
    <div class="hero__carousel">
        <span>Carrousel des groupes</span>
    </div>
</section>

<!-- ── Exposition ── -->
<section class="exposition">
    <div class="exposition__inner">
        <p class="section-label">L'Exposition</p>
        <h2 class="section-title">L'illusion technologique au service de l'art</h2>
        <p class="exposition__text">
            4 espaces. 4 problématiques. Plus de 10 œuvres interactives. E-Llusion est une plongée
            au cœur de l'innovation numérique. En combinant différents points de vue et technologies,
            chaque salle a été conçue pour captiver le regard et transformer la perception.
            Une immersion totale où l'émerveillement rencontre la réflexion technique.
        </p>
        <a href="pages/salles.php" class="btn-outline">Voir les salles →</a>
    </div>
</section>

<!-- ── Salles ── -->
<section class="salles">
    <p class="section-label">Les Salles</p>
    <h2 class="section-title">4 espaces à explorer</h2>

    <div class="salles__grid">

        <?php
        $salles = [
            [
                "num"     => "Salle 021",
                "nom"     => "Societ-e",
                "oeuvres" => ["Community", "Distorsion"],
                "img"     => ""
            ],
            [
                "num"     => "Salle 001",
                "nom"     => "Horizon",
                "oeuvres" => ["Bon Profil", "Antithèse", "Beauté hors du cadre"],
                "img"     => ""
            ],
            [
                "num"     => "Salle 002",
                "nom"     => "L'Envers du Décor",
                "oeuvres" => ["Tapis Rouge", "En Direct", "AD-HD"],
                "img"     => ""
            ],
            [
                "num"     => "Salle 005",
                "nom"     => "La Pépinière",
                "oeuvres" => ["Lotus", "E-biscus", "La chute d'Alice", "Œuvre 4"],
                "img"     => ""
            ],
        ];

        foreach ($salles as $salle) :
        ?>
        <div class="salle-card">
            <div class="salle-card__badge"><?php echo $salle['num']; ?></div>
            <div class="salle-card__photo">
                <?php if ($salle['img']) : ?>
                    <img src="<?php echo $salle['img']; ?>" alt="<?php echo $salle['nom']; ?>">
                <?php else : ?>
                    <span>[ Photo ]</span>
                <?php endif; ?>
            </div>
            <div class="salle-card__body">
                <div class="salle-card__name"><?php echo $salle['nom']; ?></div>
                <div class="salle-card__oeuvres"><?php echo implode(' · ', $salle['oeuvres']); ?></div>
                <a href="pages/inscription.php?salle=<?php echo urlencode($salle['num']); ?>" class="btn-outline">S'inscrire →</a>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</section>

<!-- ── CTA Banner ── -->
<div class="cta-banner">
    <div>
        <div class="cta-banner__title">Places limitées — Réservez votre créneau dès maintenant</div>
        <div class="cta-banner__sub">12 personnes max par salle &nbsp;·&nbsp; Jeudi 18 &amp; Vendredi 19 juin 2026</div>
    </div>
    <a href="pages/inscription.php" class="btn-dark">S'inscrire →</a>
</div>

<!-- ── Contact ── -->
<section class="contact">
    <p class="section-label">Contact</p>
    <h2 class="section-title">Une question ?</h2>

    <div class="contact__card">
        <div class="contact__card-label">Responsable du projet</div>
        <div class="contact__card-name">François Piranda</div>
        <div class="contact__card-role">IUT de Chambéry · Département MMI</div>
        <a href="mailto:francois.piranda@univ-smb.fr" class="contact__card-email">francois.piranda@univ-smb.fr</a>
    </div>

    <div class="contact__info">
        <p>IUT de Chambéry · USMB · Le Bourget-du-Lac</p>
        <p>Jeudi 18 juin : 15h–20h &nbsp;·&nbsp; Vendredi 19 juin : 9h30–11h</p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
