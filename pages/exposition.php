<?php
$pageTitle = "L'Exposition";
$rootPath = "../";
include '../includes/header.php';
?>

<!-- ── Hero Exposition ── -->
<section class="expo-hero">
    <p class="section-label">L'Exposition</p>
    <h1 class="expo-hero__title">E-LLUSION</h1>
    <h2 class="expo-hero__subtitle">Expérimentations interactives sur<br>la métamorphose digitale.</h2>
    <p class="expo-hero__desc">
        Une exposition immersive conçue et réalisée par les étudiant·es de première année du
        département MMI de l'IUT de Chambéry dans le cadre de la SAE 202.
    </p>
    <div class="expo-hero__buttons">
        <a href="inscription.php" class="btn-outline">S'inscrire →</a>
        <a href="salles.php" class="btn-outline">Voir les salles →</a>
    </div>
</section>

<!-- ── Le Concept ── -->
<section class="concept">
    <div class="concept__inner">
        <div class="concept__left">
            <p class="section-label">Le Concept</p>
            <h2 class="section-title">Une plongée dans la réalité numérique</h2>
            <p class="concept__text">
                E-Llusion est une exposition interactive imaginée et réalisée par les étudiants de
                première année du département MMI de l'IUT de Chambéry. À travers quatre espaces
                distincts, elle interroge notre rapport au monde numérique : comment les outils digitaux
                transforment notre identité, notre perception de la réalité et nos interactions sociales.
            </p>
            <p class="concept__text concept__text--light">
                Chaque espace a été imaginé par une agence étudiante pour offrir une vision unique et technologique de notre
                double digital. Entre émerveillement et réflexion, devenez l'acteur de cette métamorphose.
            </p>
        </div>
        <div class="concept__media">
            <div class="concept__placeholder"></div>
        </div>
    </div>
</section>

<!-- ── Infos Pratiques ── -->
<section class="infos">
    <p class="section-label">Infos Pratiques</p>
    <h2 class="section-title">Tout ce qu'il faut savoir</h2>

    <div class="infos__grid">
        <div class="info-card">
            <div class="info-card__title">Dates</div>
            <p class="info-card__text">Jeudi 18 juin : 15h – 20h<br>Vendredi 19 juin : 9h30 – 11h</p>
        </div>
        <div class="info-card">
            <div class="info-card__title">Lieu</div>
            <p class="info-card__text">IUT de Chambéry<br>Boulevard du Lac<br>73370 Le Bourget-du-Lac</p>
        </div>
        <div class="info-card">
            <div class="info-card__title">Accès</div>
            <p class="info-card__text">Entrée libre<br>Inscription obligatoire<br>12 personnes max par salle</p>
        </div>
        <div class="info-card">
            <div class="info-card__title">Buffet</div>
            <p class="info-card__text">Jeudi 18 juin à 18h30<br>Pour enseignants, personnel<br>et professionnels</p>
        </div>
    </div>
</section>

<!-- ── CTA Banner ── -->
<div class="cta-banner">
    <div>
        <div class="cta-banner__title">Venez vivre l'expérience E-Llusion</div>
        <div class="cta-banner__sub">Places limitées — Réservez dès maintenant</div>
    </div>
    <a href="inscription.php" class="btn-dark">S'inscrire →</a>
</div>

<!-- ── Les Salles ── -->
<section class="salles">
    <p class="section-label">Les Salles</p>
    <h2 class="section-title">4 espaces à explorer</h2>

    <div class="salles__grid">
        <?php
        $salles = [
            ["num" => "Salle 021", "nom" => "Societ-e", "oeuvres" => ["Community", "Distorsion"], "img" => ""],
            ["num" => "Salle 001", "nom" => "Horizon", "oeuvres" => ["Bon Profil", "Antithèse", "Beauté hors du cadre"], "img" => ""],
            ["num" => "Salle 002", "nom" => "L'Envers du Décor", "oeuvres" => ["Tapis Rouge", "En Direct", "AD-HD"], "img" => ""],
            ["num" => "Salle 005", "nom" => "La Pépinière", "oeuvres" => ["Lotus", "E-biscus", "Datura"], "img" => ""],
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
                <a href="salle.php?id=<?php echo urlencode($salle['num']); ?>" class="btn-outline">Voir la salle →</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
