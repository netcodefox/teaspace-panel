



<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Menü"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= $helper->url(); ?>" class="nav-link brand-inline">
          <img src="<?= htmlspecialchars($helper->getLogoUrl()); ?>" alt="" class="brand-inline-logo">
          <?php if ($helper->showBrandText()): ?>
          <span><?= htmlspecialchars($helper->getDisplayName()); ?></span>
          <?php endif; ?>
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
      <?php
      $sessionToken = $_COOKIE['session_token'] ?? null;
      $inAcp = isset($currPage) && strpos((string) $currPage, 'team_') === 0;
      if ($user->isInTeam($sessionToken)):
      ?>
      <li class="nav-item mr-2">
        <?php if ($inAcp): ?>
          <a class="btn btn-sm btn-outline-secondary" href="<?= $helper->url(); ?>dashboard">Zum Kundenbereich</a>
        <?php else: ?>
          <a class="btn btn-sm btn-primary" href="<?= $helper->url(); ?>team/dashboard">Zum Admin Panel</a>
        <?php endif; ?>
      </li>
      <?php endif; ?>

      <li class="nav-item dropdown">
        <a class="nav-link text" data-toggle="dropdown" href="#">
			<i class="fas fa-user"></i>
          <?= htmlspecialchars((string) $username); ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><?= htmlspecialchars((string) $username); ?></span>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="">KD - <?= (int) $userid; ?></a>
		  <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= $helper->url(); ?>account/profile">Mein Profil</a>
            <div class="dropdown-divider"></div>
           <a  class="dropdown-item" href="<?= $helper->url(); ?>account/donate">Spenden</a>
           <div class="dropdown-divider"></div>
            <a  class="dropdown-item" href="<?= $helper->url(); ?>account/affiliate">Affiliate</a>
            <?php if ($user->isInTeam($sessionToken)): ?>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= $helper->url(); ?>team/dashboard"><strong>Zum Admin Panel</strong></a>
            <?php endif; ?>
            <div class="dropdown-divider"></div>
            <form method="post">
                <?php if(($_COOKIE['mode'] ?? 'dark') == 'dark'){ ?>
                <button type="submit" name="changeMode" class="dropdown-item" >Light Mode</button>
                <?php } else { ?>
                <button type="submit" name="changeMode" class="dropdown-item" >Dark Mode</button>
                <?php } ?>
            </form>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= $helper->url(); ?>logout">Ausloggen</a>
          <div class="dropdown-divider"></div>
          
        </div>
      </li>
    </ul>
  </nav>
