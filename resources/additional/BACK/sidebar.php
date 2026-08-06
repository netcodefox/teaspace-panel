<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= $helper->url(); ?>dashboard" class="brand-link">
      <img src="<?= htmlspecialchars($helper->getLogoUrl()); ?>" alt="<?= htmlspecialchars($helper->getDisplayName()); ?>" class="brand-image">
      <span class="brand-text"><?= htmlspecialchars($helper->getDisplayName()); ?></span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">Kundenbereich</li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>dashboard">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>tickets">
              <i class="nav-icon fas fa-life-ring"></i>
              <p>Support</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>teaspeak/">
              <i class="nav-icon fas fa-server"></i>
              <p>TeaSpeak</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>teaspeak/order">
              <i class="nav-icon fas fa-plus-circle"></i>
              <p>TeaSpeak bestellen</p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a class="nav-link" href="#">
              <i class="nav-icon fas fa-user"></i>
              <p>Mein Account <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a class="nav-link" href="<?= $helper->url(); ?>account/profile"><i class="far fa-circle nav-icon"></i><p>Profil</p></a></li>
              <li class="nav-item"><a class="nav-link" href="<?= $helper->url(); ?>account/donate"><i class="far fa-circle nav-icon"></i><p>Spenden</p></a></li>
              <li class="nav-item"><a class="nav-link" href="<?= $helper->url(); ?>account/affiliate"><i class="far fa-circle nav-icon"></i><p>Affiliate</p></a></li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a class="nav-link" href="#">
              <i class="nav-icon fas fa-wallet"></i>
              <p>Buchhaltung <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a class="nav-link" href="<?= $helper->url(); ?>accounting/charge"><i class="far fa-circle nav-icon"></i><p>Guthaben aufladen</p></a></li>
              <li class="nav-item"><a class="nav-link" href="<?= $helper->url(); ?>accounting/transactions"><i class="far fa-circle nav-icon"></i><p>Transaktionen</p></a></li>
            </ul>
          </li>

          <?php if (isset($_COOKIE['old_session_token'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/login_back">
              <i class="nav-icon fas fa-undo"></i>
              <p>Zurück zum ACP</p>
            </a>
          </li>
          <?php } ?>

          <?php if ($user->isInTeam($_COOKIE['session_token'] ?? null)) { ?>
          <li class="nav-header">Team</li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/dashboard">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>ACP Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/tickets">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <p>Tickets</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/users">
              <i class="nav-icon fas fa-users"></i>
              <p>Benutzer</p>
            </a>
          </li>

          <?php if ($user->isAdmin($_COOKIE['session_token'] ?? null)) { ?>
          <li class="nav-header">Admin</li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/teaspeak-hosts">
              <i class="nav-icon fas fa-network-wired"></i>
              <p>TeaSpeak-Instanzen</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/settings">
              <i class="nav-icon fas fa-palette"></i>
              <p>Branding &amp; Settings</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/transactions">
              <i class="nav-icon fas fa-euro-sign"></i>
              <p>Transaktionen</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/news">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>News</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/gutscheine">
              <i class="nav-icon fas fa-tags"></i>
              <p>Gutscheine</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $helper->url(); ?>team/emailblack">
              <i class="nav-icon fas fa-ban"></i>
              <p>E-Mail Blacklist</p>
            </a>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </nav>
    </div>
  </aside>
