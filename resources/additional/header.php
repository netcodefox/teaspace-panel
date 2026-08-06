<?php
$phoneSupport = $helper->getSupportPhoneValue();
$sessionToken = $_COOKIE['session_token'] ?? null;
$isLoggedIn = !empty($sessionToken) && $user->sessionExists($sessionToken);
$isTeam = $isLoggedIn && $user->isInTeam($sessionToken);
$showBrandText = $helper->showBrandText();
$tagline = $helper->getHeaderTagline();
?>
<section class="row top_header">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <ul class="nav nav-pills pull-left">
                    <?php if ($phoneSupport !== ''): ?>
                    <li><i class="fas fa-phone"></i> <?= htmlspecialchars($phoneSupport); ?></li>
                    <?php endif; ?>
                    <?php if ($tagline !== ''): ?>
                    <li><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($tagline); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-sm-6">
                <ul class="nav nav-pills pull-right">
                    <?php if ($isLoggedIn): ?>
                        <li><a href="<?= $helper->url(); ?>dashboard"><i class="fa fa-user"></i> Kundenbereich</a></li>
                        <?php if ($isTeam): ?>
                        <li><a href="<?= $helper->url(); ?>team/dashboard"><i class="fas fa-shield-alt"></i> Zum Admin Panel</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="<?= $helper->url(); ?>login">Anmelden</a></li>
                        <li><a href="<?= $helper->url(); ?>register">Registrieren</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<nav class="navbar navbar-default navbar-static-top fluid_header centered">
    <div class="container">
        <div class="navbar-header">
            <a class="logo tf-brand-link" href="<?= $helper->url(); ?>">
                <img src="<?= htmlspecialchars($helper->getLogoUrl()); ?>" alt="<?= htmlspecialchars($helper->getDisplayName()); ?>">
                <?php if ($showBrandText): ?>
                <span class="tf-brand-text"><?= htmlspecialchars($helper->getDisplayName()); ?></span>
                <?php endif; ?>
            </a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_navigation" aria-expanded="false" aria-label="Menü öffnen">
                <span class="sr-only">Navigation umschalten</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="main_navigation">
            <ul class="nav navbar-nav navbar-right">
                <?php foreach ($helper->getFrontNavLinks() as $link): ?>
                <li><a href="<?= htmlspecialchars($link['url']); ?>"><?= htmlspecialchars($link['label']); ?></a></li>
                <?php endforeach; ?>

                <?php if ($isLoggedIn): ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <?= htmlspecialchars((string) ($username ?? 'Konto')); ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= $helper->url(); ?>dashboard">Kundenbereich</a></li>
                        <?php if ($isTeam): ?>
                        <li><a href="<?= $helper->url(); ?>team/dashboard"><strong>Zum Admin Panel</strong></a></li>
                        <?php endif; ?>
                        <li><a href="<?= $helper->url(); ?>account/profile">Mein Profil</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?= $helper->url(); ?>logout">Ausloggen</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li><a class="tf-nav-cta" href="<?= $helper->url(); ?>teaspeak/order">Jetzt starten</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
