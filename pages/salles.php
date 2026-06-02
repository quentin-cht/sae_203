<?php
$pageTitle = "Les Salles";
$rootPath = "../";
include '../includes/header.php';

$salles = [
    [
        "num"     => "Salle 021",
        "nom"     => "Societ-e",
        "concept" => "Comment le monde numérique modifie-t-il et crée-t-il une nouvelle réalité ? Notre salle questionne l'influence de la société sur nos comportements et la construction de notre identité à travers le regard des autres. Deux œuvres montrent comment le numérique transforme notre rapport au réel et renforce cette pression.",
        "oeuvres" => [
            [
                "titre" => "Community",
                "desc"  => "Et si une simple image suffisait à dicter la vérité ? Explorez comment la décontextualisation transforme un geste aléatoire en une intention partagée. Dans cette société miniature, chaque personnage ajuste son comportement en fonction de ce qui est montré. Une expérience interactive sur le pouvoir de l'image et la construction des émotions collectives.",
                "img"   => "",
                "cote"  => "gauche"
            ],
            [
                "titre" => "Distorsion",
                "desc"  => "Votre identité est-elle à vendre ? Explorez la métamorphose de votre image sous l'influence du collectif. Ici, le visage devient un objet marchand que chacun peut modifier et s'approprier. Une immersion interactive qui questionne la valeur de notre double numérique dans une société de consommation où le regard de l'autre dicte notre prix.",
                "img"   => "",
                "cote"  => "droite"
            ],
        ]
    ],
    [
        "num"     => "Salle 001",
        "nom"     => "Horizon",
        "concept" => "Comment les objets numériques altèrent-ils notre perception du réel ? À travers trois œuvres interactives, nous explorons comment les technologies influencent notre rapport au corps, à l'image et à l'environnement — transformant ce que nous croyons voir et ressentir.",
        "oeuvres" => [
            [
                "titre" => "Bon Profil",
                "desc"  => "Une installation qui interroge la construction de l'image de soi en ligne. Comment façonnons-nous notre profil numérique pour correspondre aux attentes des autres ? Une expérience où apparence et identité se confrontent.",
                "img"   => "",
                "cote"  => "gauche"
            ],
            [
                "titre" => "Antithèse",
                "desc"  => "Deux visions de la réalité s'affrontent : une vision déformée par les réseaux et les médias biaisés, et une vision objective basée sur les faits. Selon votre distance à l'installation, votre perception évolue. Une invitation à questionner la manière dont les médias influencent notre compréhension du réel.",
                "img"   => "",
                "cote"  => "droite"
            ],
            [
                "titre" => "Beauté hors du cadre",
                "desc"  => "Un écran géant imite un smartphone et capture votre reflet. Au fil des défilements, les contenus familiers deviennent angoissants, la lumière s'assombrit, les sons se déforment. Votre reflet s'efface peu à peu — métaphore d'une absorption totale par le numérique.",
                "img"   => "",
                "cote"  => "gauche"
            ],
        ]
    ],
    [
        "num"     => "Salle 002",
        "nom"     => "L'Envers du Décor",
        "concept" => "Comment l'illusion d'une société parfaite révèle-t-elle l'état de la nôtre ? Notre salle questionne les façades que la société se construit pour masquer ses contradictions — regard social sur les réseaux, glamour de la mode, mécanique de la consommation.",
        "oeuvres" => [
            [
                "titre" => "Tapis Rouge",
                "desc"  => "Une installation immersive qui détourne les codes du prestige. Un tapis rouge vous invite à avancer : votre mouvement contrôle une vidéo qui glisse du luxe aux coulisses de la production industrielle — entrepôts, pénibilité du travail, épuisement des corps. Chaque pas vers le succès repose sur une réalité humaine sacrifiée.",
                "img"   => "",
                "cote"  => "droite"
            ],
            [
                "titre" => "En Direct",
                "desc"  => "Une caméra vous filme comme sur TikTok. Des commentaires automatiques apparaissent selon votre distance et vos expressions. Trop proche, trop loin, souriant ou neutre — quoi que vous fassiez, vous serez jugé. Une mise en scène du jugement social permanent que produisent les réseaux sociaux.",
                "img"   => "",
                "cote"  => "gauche"
            ],
            [
                "titre" => "AD-HD",
                "desc"  => "Face à une interface inspirée de TikTok, faites défiler un flux de contenus promotionnels à l'aide d'un grand scroll physique. Des pop-ups publicitaires s'imposent à vous. L'œuvre révèle l'illusion du contrôle : nos gestes, notre temps et notre attention sont constamment captés par la publicité.",
                "img"   => "",
                "cote"  => "droite"
            ],
        ]
    ],
    [
        "num"     => "Salle 005",
        "nom"     => "La Pépinière",
        "concept" => "Comment les illusions numériques façonnent-elles notre rapport à l'identité et à la beauté ? À travers quatre œuvres interactives, notre salle explore la frontière entre ce que l'on est et ce que l'on projette en ligne.",
        "oeuvres" => [
            [
                "titre" => "Lotus",
                "desc"  => "Une installation qui explore le renouveau identitaire à l'ère numérique. Comment se réinvente-t-on en ligne ? Une expérience sensorielle autour de la transformation et de la renaissance de l'image de soi.",
                "img"   => "",
                "cote"  => "gauche"
            ],
            [
                "titre" => "E-biscus",
                "desc"  => "Un jeune homme s'endort après avoir liké la photo d'une inconnue. Dans son rêve, il a rendez-vous avec elle et doit préparer son apparence. Les spectateurs modifient son look — muscles, cheveux, barbe. La rencontre a lieu, mais ni lui ni elle ne ressemblent à leurs photos. E-biscus interroge l'écart entre l'image projetée en ligne et la réalité.",
                "img"   => "",
                "cote"  => "droite"
            ],
            [
                "titre" => "Datura",
                "desc"  => "En jouant du synthétiseur, transformez en temps réel la chute d'Alice au pays des merveilles. Les notes modifient les formes et couleurs de la chute. Une deuxième personne peut déformer la sonorité tandis qu'une pédale de sustain ralentit ou étire la scène. Une performance collective où la technologie devient le pinceau d'une chute onirique.",
                "img"   => "",
                "cote"  => "gauche"
            ],
        ]
    ],
];
?>

<!-- ── Hero ── -->
<section class="salles-hero">
    <p class="section-label">Les Salles</p>
    <h1 class="section-title">4 espaces interactifs à explorer</h1>
    <p class="salles-hero__sub">Chaque salle propose une expérience unique. Choisissez votre visite et réservez votre créneau.</p>
</section>

<!-- ── Accordéon des salles ── -->
<div class="salles-accordion">
<?php foreach ($salles as $index => $salle) : ?>

    <!-- En-tête de salle (cliquable) -->
    <div class="accordion-header <?php echo $index === 0 ? 'active' : ''; ?>" onclick="toggleSalle(<?php echo $index; ?>)">
        <span class="accordion-badge"><?php echo $salle['num']; ?></span>
        <span class="accordion-arrow"><?php echo $index === 0 ? '∧' : '∨'; ?></span>
    </div>

    <!-- Contenu de la salle -->
    <div class="accordion-content" id="salle-<?php echo $index; ?>" <?php echo $index === 0 ? 'style="display:block"' : ''; ?>>

        <!-- Concept de la salle -->
        <div class="salle-concept">
            <h2 class="salle-concept__nom"><?php echo $salle['nom']; ?><br>Le concept</h2>
            <p class="salle-concept__desc"><?php echo $salle['concept']; ?></p>
        </div>

        <!-- Œuvres -->
        <?php foreach ($salle['oeuvres'] as $oeuvre) : ?>
        <div class="oeuvre <?php echo $oeuvre['cote'] === 'droite' ? 'oeuvre--reverse' : ''; ?>">
            <div class="oeuvre__media">
                <?php if ($oeuvre['img']) : ?>
                    <img src="<?php echo $oeuvre['img']; ?>" alt="<?php echo $oeuvre['titre']; ?>">
                <?php else : ?>
                    <div class="oeuvre__placeholder">[ Photo ]</div>
                <?php endif; ?>
            </div>
            <div class="oeuvre__content">
                <span class="oeuvre__badge"><?php echo $salle['num']; ?></span>
                <h3 class="oeuvre__titre"><?php echo $oeuvre['titre']; ?></h3>
                <p class="oeuvre__desc"><?php echo $oeuvre['desc']; ?></p>
                <div class="oeuvre__jauge">
                    12 personnes maximum
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div><!-- /accordion-content -->

<?php endforeach; ?>
</div><!-- /salles-accordion -->

<?php include '../includes/footer.php'; ?>

<script>
function toggleSalle(index) {
    var contenu = document.getElementById('salle-' + index);
    var header = contenu.previousElementSibling;
    var arrow = header.querySelector('.accordion-arrow');

    if (contenu.style.display === 'block') {
        contenu.style.display = 'none';
        header.classList.remove('active');
        arrow.textContent = '∨';
    } else {
        contenu.style.display = 'block';
        header.classList.add('active');
        arrow.textContent = '∧';
    }
}
</script>
