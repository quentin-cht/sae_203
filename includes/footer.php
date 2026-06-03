</main>

<footer class="footer">
    <div class="footer__left">
        <div class="footer__logo">E-LLUSION</div>
        <p class="footer__desc">Exposition interactive MMI-1 · IUT de Chambéry · Université Savoie Mont Blanc</p>
        <div class="footer__links">
            <a href="#">Instagram @mmi_chambery</a>
            <a href="#">Site MMI</a>
            <a href="<?php echo isset($rootPath) ? $rootPath : ''; ?>pages/mentions-legales.php">Mentions légales</a>
        </div>
    </div>
    <div class="footer__logos">
        <img src="<?php echo isset($rootPath) ? $rootPath : ''; ?>ressources/images/iut.png" alt="IUT de Chambéry">
        <img src="<?php echo isset($rootPath) ? $rootPath : ''; ?>ressources/images/mmi.png" alt="MMI Chambéry">
    </div>
    <div class="footer__copy">
        © 2025 MMI-1 IUT de Chambéry
        <a href="<?php echo isset($rootPath) ? $rootPath : ''; ?>pages/connexion.php" class="footer__admin">Administration</a>
    </div>
</footer>

</body>
</html>
