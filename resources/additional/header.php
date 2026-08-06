<?php
$phoneSupport = $helper->getSupportPhoneValue();
$sessionToken = $_COOKIE['session_token'] ?? null;
$isLoggedIn = !empty($sessionToken) && $user->sessionExists($sessionToken);
$isTeam = $isLoggedIn && $user->isInTeam($sessionToken);
$tagline = $helper->getHeaderTagline();
$navLinks = $helper->getFrontNavLinks();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
<header class="tf-site-header">
    <?php if ($phoneSupport !== '' || $tagline !== ''): ?>
    <div class="tf-util">
        <div class="container tf-util-inner">
            <div class="tf-util-left">
                <?php if ($phoneSupport !== ''): ?>
                <span><i class="fas fa-phone" aria-hidden="true"></i> <?= htmlspecialchars($phoneSupport); ?></span>
                <?php endif; ?>
                <?php if ($tagline !== ''): ?>
                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> <?= htmlspecialchars($tagline); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="tf-nav">
        <div class="container tf-nav-inner">
            <a class="tf-nav-brand" href="<?= $helper->url(); ?>">
                <?php if ($helper->hasLogoImage()): ?>
                <img src="<?= htmlspecialchars($helper->getLogoUrl()); ?>" alt="<?= htmlspecialchars($helper->getDisplayName()); ?>">
                <?php else: ?>
                <span><?= htmlspecialchars($helper->getDisplayName()); ?></span>
                <?php endif; ?>
            </a>

            <button type="button" class="tf-nav-toggle" id="tfNavToggle" aria-controls="tfNavMenu" aria-expanded="false" aria-label="Menü öffnen">
                <span></span><span></span><span></span>
            </button>

            <div class="tf-nav-menu" id="tfNavMenu">
                <nav class="tf-nav-links" aria-label="Hauptnavigation">
                    <?php foreach ($navLinks as $link):
                        $href = $link['url'];
                        $path = parse_url($href, PHP_URL_PATH) ?: '/';
                        $active = rtrim($currentPath, '/') === rtrim($path, '/')
                            || ($path !== '/' && strpos($currentPath, rtrim($path, '/')) === 0);
                    ?>
                    <a class="tf-nav-link<?= $active ? ' is-active' : ''; ?>" href="<?= htmlspecialchars($href); ?>">
                        <?= htmlspecialchars($link['label']); ?>
                    </a>
                    <?php endforeach; ?>
                </nav>

                <div class="tf-nav-actions">
                    <?php if ($isLoggedIn): ?>
                        <?php if ($isTeam): ?>
                        <a class="tf-nav-ghost" href="<?= $helper->url(); ?>team/dashboard">Admin</a>
                        <?php endif; ?>
                        <a class="tf-nav-ghost" href="<?= $helper->url(); ?>logout">Logout</a>
                        <a class="tf-nav-cta" href="<?= $helper->url(); ?>dashboard">Kundenbereich</a>
                    <?php else: ?>
                        <a class="tf-nav-ghost" href="<?= $helper->url(); ?>login">Anmelden</a>
                        <a class="tf-nav-cta" href="<?= $helper->url(); ?>teaspeak/order">Jetzt starten</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
(function () {
  var btn = document.getElementById('tfNavToggle');
  var menu = document.getElementById('tfNavMenu');
  if (!btn || !menu) return;
  btn.addEventListener('click', function () {
    var open = menu.classList.toggle('is-open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.classList.toggle('tf-nav-open', open);
  });
})();
</script>
