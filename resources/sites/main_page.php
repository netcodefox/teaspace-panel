<?php
$currPage = 'front_Startseite';
include 'app/controller/PageController.php';
$brand = $helper->getDisplayName();
$logoUrl = $helper->getLogoUrl();
?>

<section class="tf-hero">
    <div class="tf-hero-glow" aria-hidden="true"></div>
    <div class="container">
        <div class="tf-hero-inner tf-hero-motion">
            <img class="tf-hero-logo" src="<?= htmlspecialchars($logoUrl); ?>" alt="<?= htmlspecialchars($brand); ?>" width="280" height="80">
            <h1 class="tf-hero-title">TeaSpeak Hosting</h1>
            <p class="tf-hero-lead">In Sekunden online, prepaid und mit Support – Hosting aus Deutschland.</p>
            <div class="tf-hero-actions">
                <a class="btn btn-primary btn-xlg" href="<?= $helper->url(); ?>teaspeak/order">TeaSpeak bestellen</a>
                <a class="tf-btn-ghost" href="<?= $helper->url(); ?>contact">Kontakt</a>
            </div>
        </div>
    </div>
</section>

<section class="tf-section">
    <div class="container">
        <h2 class="tf-section-title">Warum <?= htmlspecialchars($brand); ?></h2>
        <p class="tf-section-sub">Klarer Fokus auf stabile Voice-Server – ohne Ballast.</p>
        <div class="tf-features">
            <article class="tf-feature">
                <i class="fas fa-bolt" aria-hidden="true"></i>
                <h3>Sofort online</h3>
                <p>Produkte werden direkt eingerichtet und stehen kurz nach der Bestellung bereit.</p>
            </article>
            <article class="tf-feature">
                <i class="fas fa-microchip" aria-hidden="true"></i>
                <h3>Starke Hardware</h3>
                <p>Leistungsfähige CPUs, NVMe-SSDs und ausfallsicherer Speicher.</p>
            </article>
            <article class="tf-feature">
                <i class="fas fa-headset" aria-hidden="true"></i>
                <h3>Schneller Support</h3>
                <p>Ticket, Telefon und TeamSpeak – wir helfen, wenn etwas hakt.</p>
            </article>
            <article class="tf-feature">
                <i class="fas fa-leaf" aria-hidden="true"></i>
                <h3>Ökostrom</h3>
                <p>Unsere Server laufen mit einem hohen Anteil an Ökostrom.</p>
            </article>
            <article class="tf-feature">
                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                <h3>DDoS-Schutz</h3>
                <p>Erweiterter Schutz hält deine Instanz auch unter Last erreichbar.</p>
            </article>
            <article class="tf-feature">
                <i class="fas fa-wallet" aria-hidden="true"></i>
                <h3>Prepaid</h3>
                <p>Keine Mahnungen: nicht verlängern heißt automatisch beenden.</p>
            </article>
        </div>
    </div>
</section>

<section class="tf-section tf-section-alt">
    <div class="container">
        <div class="tf-stats">
            <div class="tf-stat">
                <strong class="counter"><?= (int) $user->getAllUser($userid ?? 0); ?></strong>
                <span>Registrierte Kunden</span>
            </div>
            <div class="tf-stat">
                <strong class="counter"><?= (int) $user->adminserviceCount($userid ?? 0); ?></strong>
                <span>Aktive Services</span>
            </div>
            <div class="tf-stat">
                <strong class="counter"><?= (int) $user->getcloseTicketsAdmin($userid ?? 0); ?></strong>
                <span>Gelöste Tickets</span>
            </div>
            <div class="tf-stat">
                <strong class="counter"><?= (int) $user->adminsCount($userid ?? 0); ?></strong>
                <span>Mitarbeiter</span>
            </div>
        </div>
    </div>
</section>

<section class="tf-section">
    <div class="container">
        <h2 class="tf-section-title">Letzte News</h2>
        <p class="tf-section-sub">Kurze Updates aus dem Panel.</p>
        <div class="tf-news">
            <?php
            $SQL = $db->prepare('SELECT * FROM `news` ORDER BY `id` DESC LIMIT 3');
            $SQL->execute();
            if ($SQL->rowCount() != 0) {
                while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <article class="tf-news-item">
                        <h3><?= htmlspecialchars((string) $row['icon']); ?></h3>
                        <p><?= $helper->nl2br2(htmlspecialchars((string) $row['message'])); ?></p>
                        <div class="tf-news-meta">
                            <span><i class="fa fa-user-circle" aria-hidden="true"></i> <?= htmlspecialchars((string) $user->getDataById($row['user_id'], 'username')); ?></span>
                            <span><i class="fa fa-calendar" aria-hidden="true"></i> <?= htmlspecialchars($site->formatDateWithoutTime($row['created_at'])); ?></span>
                        </div>
                    </article>
                    <?php
                }
            } else {
                echo '<p class="tf-section-sub">Aktuell keine News.</p>';
            }
            ?>
        </div>
    </div>
</section>
