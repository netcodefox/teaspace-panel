<?php
$currPage = 'front_Kontakt';
include 'app/controller/PageController.php';

$office = $helper->getContactCard('office');
$phone = $helper->getContactCard('phone');
$message = $helper->getContactCard('message');
$whatsapp = $helper->getContactCard('whatsapp');
?>
<section class="row">
    <div class="row m0 sub_banner overly relative">
        <div class="container overly-content text-center">
            <div class="col-sm-12">
                <h4>Kontakt</h4>
            </div>
        </div>
    </div>
</section>

<section class="row contact_content section-spacing tf-section">
    <div class="container">
        <div class="row form_row">
            <div class="col-sm-3 cause2choose">
                <div class="media">
                    <div class="media-left"><i class="ti-map-alt fa-3x text-gredient1"></i></div>
                    <div class="media-body">
                        <h4><?= htmlspecialchars((string) ($office['title'] ?? 'Büro')); ?></h4>
                        <?php if (($office['line1'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $office['line1']); ?></p><?php endif; ?>
                        <?php if (($office['line2'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $office['line2']); ?></p><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 cause2choose">
                <div class="media">
                    <div class="media-left"><i class="ti-mobile fa-3x text-gredient1"></i></div>
                    <div class="media-body">
                        <h4><?= htmlspecialchars((string) ($phone['title'] ?? 'Ruf uns an')); ?></h4>
                        <?php if (($phone['line1'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $phone['line1']); ?></p><?php endif; ?>
                        <?php if (($phone['line2'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $phone['line2']); ?></p><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 cause2choose">
                <div class="media">
                    <div class="media-left"><i class="ti-email fa-3x text-gredient1"></i></div>
                    <div class="media-body">
                        <h4><?= htmlspecialchars((string) ($message['title'] ?? 'Nachricht')); ?></h4>
                        <?php if (($message['line1'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $message['line1']); ?></p><?php endif; ?>
                        <?php
                        $line2 = trim((string) ($message['line2'] ?? ''));
                        $linkLabel = trim((string) ($message['link_label'] ?? ''));
                        $linkUrl = trim((string) ($message['link_url'] ?? ''));
                        if ($line2 !== '' || ($linkLabel !== '' && $linkUrl !== '')):
                        ?>
                        <p>
                            <?php if ($line2 !== ''): ?><?= htmlspecialchars($line2); ?> <?php endif; ?>
                            <?php if ($linkLabel !== '' && $linkUrl !== ''): ?>
                                <a href="<?= htmlspecialchars($linkUrl); ?>"><?= htmlspecialchars($linkLabel); ?></a>
                            <?php endif; ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 cause2choose">
                <div class="media">
                    <div class="media-left"><i class="ti-headphone-alt fa-3x text-gredient1"></i></div>
                    <div class="media-body">
                        <h4><?= htmlspecialchars((string) ($whatsapp['title'] ?? 'WhatsApp')); ?></h4>
                        <?php if (($whatsapp['line1'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $whatsapp['line1']); ?></p><?php endif; ?>
                        <?php if (($whatsapp['line2'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $whatsapp['line2']); ?></p><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row contact_content tf-section tf-section-alt">
    <div class="container">
        <h2 class="tf-section-title text-center">Support</h2>
        <p class="tf-section-sub text-center" style="margin-left:auto;margin-right:auto;">So erreichst du uns.</p>
        <div class="row we_support">
            <div class="col-sm-4">
                <div class="media">
                    <div class="media-left"><i class="icon-chat fa-3x text-gredient1"></i></div>
                    <div class="media-body">
                        <h4>Live-Chat</h4>
                        <p>Montag bis Samstag, 16:00–21:00 Uhr.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="media">
                    <div class="media-left"><i class="icon-clock fa-3x text-gredient1"></i></div>
                    <div class="media-body">
                        <h4>Tickets</h4>
                        <p>24/7 erreichbar – Antworten innerhalb unserer Öffnungszeiten.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="media">
                    <div class="media-left"><i class="icon-envelope fa-3x text-gredient1"></i></div>
                    <div class="media-body">
                        <h4>E-Mail</h4>
                        <p>Rund um die Uhr erreichbar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
