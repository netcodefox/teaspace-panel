<?php
$phoneSupport = $helper->getSupportPhoneValue();
$isLoggedIn = !empty($_COOKIE['session_token']) && $user->sessionExists($_COOKIE['session_token']);
?>
<section class="row top_header">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <ul class="nav nav-pills pull-left">
                    <?php if ($phoneSupport !== ''): ?>
                    <li><i class="fas fa-phone"></i> <?= htmlspecialchars($phoneSupport); ?></li>
                    <?php endif; ?>
                    <li><i class="fas fa-map-marker-alt"></i> Hosting aus Deutschland</li>
                </ul>
            </div>
            <div class="col-sm-6">
                <ul class="nav nav-pills pull-right">
                    <?php if ($isLoggedIn): ?>
                        <li><a href="<?= $helper->url(); ?>dashboard"><i class="fa fa-user"></i> Kundenbereich</a></li>
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
            <a class="logo" href="<?= $helper->url(); ?>">
                <img src="<?= htmlspecialchars($helper->getLogoUrl()); ?>" alt="<?= htmlspecialchars($helper->getDisplayName()); ?>">
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
                <li><a href="<?= $helper->url(); ?>">Start</a></li>
                <li><a href="<?= $helper->url(); ?>teaspeak/order">TeaSpeak</a></li>
                <li><a href="<?= $helper->url(); ?>contact">Kontakt</a></li>
                <?php if ($isLoggedIn): ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <?= htmlspecialchars((string) $username); ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= $helper->url(); ?>dashboard">Dashboard</a></li>
                        <li><a href="<?= $helper->url(); ?>account/profile">Mein Profil</a></li>
                        <li><a href="<?= $helper->url(); ?>account/affiliate">Affiliate</a></li>
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
