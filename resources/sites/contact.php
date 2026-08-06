<?php
$currPage = 'front_Impressum';
include 'app/controller/PageController.php';

$office = $helper->getContactCard('office');
$phone = $helper->getContactCard('phone');
$message = $helper->getContactCard('message');
$whatsapp = $helper->getContactCard('whatsapp');
?>
<!--Contact Banner-->
    <section class="row">
        <div class="row m0 sub_banner overly relative">
            <div class="container overly-content text-center">
            	<div class="col-sm-12">
                    <h4>Contact Us</h4>
                </div>
            </div>
        </div>
    </section>

    <!--Contact Form-->
    <section class="row contact_content section-spacing bg-pattern">
        <div class="container">
            <div class="row form_row">
            	<div class="col-sm-3 cause2choose">
                    <div class="media border shadow bg-pattern">
                        <div class="media-left"><i class="ti-map-alt fa-5x text-gredient1"></i></div>
                        <div class="media-body">
                            <h4><?= htmlspecialchars((string) ($office['title'] ?? 'Büro')); ?></h4>
                            <?php if (($office['line1'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $office['line1']); ?></p><?php endif; ?>
                            <?php if (($office['line2'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $office['line2']); ?></p><?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 cause2choose">
                    <div class="media border shadow bg-pattern">
                        <div class="media-left"><i class="ti-mobile fa-5x text-gredient1"></i></div>
                        <div class="media-body">
                            <h4><?= htmlspecialchars((string) ($phone['title'] ?? 'Ruf Uns An')); ?></h4>
                            <?php if (($phone['line1'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $phone['line1']); ?></p><?php endif; ?>
                            <?php if (($phone['line2'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $phone['line2']); ?></p><?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 cause2choose">
                    <div class="media border shadow bg-pattern">
                        <div class="media-left"><i class="ti-email fa-5x text-gredient1"></i></div>
                        <div class="media-body">
                            <h4><?= htmlspecialchars((string) ($message['title'] ?? 'Send Message')); ?></h4>
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
                    <div class="media border shadow bg-pattern">
                        <div class="media-left"><i class="ti-headphone-alt fa-5x text-gredient1"></i></div>
                        <div class="media-body">
                            <h4><?= htmlspecialchars((string) ($whatsapp['title'] ?? '24 / 7 Whatsapp')); ?></h4>
                            <?php if (($whatsapp['line1'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $whatsapp['line1']); ?></p><?php endif; ?>
                            <?php if (($whatsapp['line2'] ?? '') !== ''): ?><p><?= htmlspecialchars((string) $whatsapp['line2']); ?></p><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

  

    <!--Contact Features-->
    <section class="row contact_content section-spacing bg-gray">
        <div class="container">
            <div class="sectionTitle p-bottom80">
                <h5>We are</h5>
                <h2>Support</h2>
            </div>
            <div class="row we_support">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="media">
                                <div class="media-left">
                                    <i class="icon-chat fa-5x text-gredient1"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="text-gredient1">Live-Chat Support</h4>
                                    <p>Der Live-Chat ist für Sie von Montag bis Samstag zwischen 16:00 Uhr und 21:00 Uhr erreichbar.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="media">
                                <div class="media-left">
                                    <i class="icon-clock fa-5x text-gredient1"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="text-gredient1">Submit Ticket</h4>
                                    <p>Das Ticket System steht für Sie 24/7 bereit, da Antworten wir innerhalb unserer Öffnungszeiten.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="media">
                                <div class="media-left">
                                    <i class="icon-envelope fa-5x text-gredient1"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="text-gredient1">Email Support</h4>
                                    <p>Per E-Mail sind wir 24/7 in 365 Tagen im Jahr erreichbar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
