<?php
$currPage = 'team_Einstellungen_admin';
include 'app/controller/PageController.php';

$helper->ensureSettingsSchema();

if (!$user->isAdmin($_COOKIE['session_token'] ?? null)) {
    die(header('Location: ' . $helper->url() . 'team/dashboard'));
}

$uploadDirRel = 'assets/uploads/branding';
$uploadDirAbs = __DIR__ . '/../../' . $uploadDirRel;
if (!is_dir($uploadDirAbs)) {
    @mkdir($uploadDirAbs, 0755, true);
}

function ts_store_upload(array $file, string $absDir, string $relDir, string $prefix): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp', 'image/x-icon' => 'ico', 'image/vnd.microsoft.icon' => 'ico', 'image/svg+xml' => 'svg'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']) ?: '';
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Ungültiges Bildformat. Erlaubt: PNG, JPG, WEBP, SVG, ICO.');
    }
    if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        throw new RuntimeException('Datei zu groß (max. 2 MB).');
    }
    $name = $prefix . '_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];
    $dest = rtrim($absDir, '/\\') . DIRECTORY_SEPARATOR . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Upload fehlgeschlagen.');
    }
    return $relDir . '/' . $name;
}

if (isset($_POST['saveBranding'])) {
    try {
        $fields = [
            'display_name' => trim($_POST['display_name'] ?? '') ?: null,
            'login' => (int) ($_POST['login'] ?? 1),
            'register' => (int) ($_POST['register'] ?? 1),
            'wartung' => (int) ($_POST['wartung'] ?? 0),
            'support_ts_label' => trim($_POST['support_ts_label'] ?? '') ?: null,
            'support_ts_value' => trim($_POST['support_ts_value'] ?? '') ?: null,
            'support_phone_label' => trim($_POST['support_phone_label'] ?? '') ?: null,
            'support_phone_value' => trim($_POST['support_phone_value'] ?? '') ?: null,
        ];

        if (!empty($_FILES['logo']['name'])) {
            $logoPath = ts_store_upload($_FILES['logo'], $uploadDirAbs, $uploadDirRel, 'logo');
            if ($logoPath) {
                $fields['logo_path'] = $logoPath;
            }
        }
        if (!empty($_FILES['favicon']['name'])) {
            $favPath = ts_store_upload($_FILES['favicon'], $uploadDirAbs, $uploadDirRel, 'favicon');
            if ($favPath) {
                $fields['favicon_path'] = $favPath;
            }
        }

        if (isset($_POST['reset_logo'])) {
            $fields['logo_path'] = null;
        }
        if (isset($_POST['reset_favicon'])) {
            $fields['favicon_path'] = null;
        }

        $legalKeys = ['impressum', 'datenschutz', 'agb', 'widerruf', 'hoster'];
        $legal = [];
        foreach ($legalKeys as $key) {
            $legal[] = [
                'key' => $key,
                'label' => trim($_POST['legal_label_' . $key] ?? ''),
                'url' => trim($_POST['legal_url_' . $key] ?? ''),
                'external' => $key === 'hoster' || !empty($_POST['legal_external_' . $key]),
            ];
        }

        $navItems = [];
        $navLabels = $_POST['nav_label'] ?? [];
        $navUrls = $_POST['nav_url'] ?? [];
        if (is_array($navLabels) && is_array($navUrls)) {
            $count = max(count($navLabels), count($navUrls));
            for ($i = 0; $i < $count && $i < 8; $i++) {
                $label = trim((string) ($navLabels[$i] ?? ''));
                $url = trim((string) ($navUrls[$i] ?? ''));
                if ($label === '' && $url === '') {
                    continue;
                }
                $navItems[] = ['label' => $label, 'url' => $url];
            }
        }

        $siteContent = [
            'contact' => [
                'office' => [
                    'title' => trim($_POST['contact_office_title'] ?? ''),
                    'line1' => trim($_POST['contact_office_line1'] ?? ''),
                    'line2' => trim($_POST['contact_office_line2'] ?? ''),
                ],
                'phone' => [
                    'title' => trim($_POST['contact_phone_title'] ?? ''),
                    'line1' => trim($_POST['contact_phone_line1'] ?? ''),
                    'line2' => trim($_POST['contact_phone_line2'] ?? ''),
                ],
                'message' => [
                    'title' => trim($_POST['contact_message_title'] ?? ''),
                    'line1' => trim($_POST['contact_message_line1'] ?? ''),
                    'line2' => trim($_POST['contact_message_line2'] ?? ''),
                    'link_label' => trim($_POST['contact_message_link_label'] ?? ''),
                    'link_url' => trim($_POST['contact_message_link_url'] ?? ''),
                ],
                'whatsapp' => [
                    'title' => trim($_POST['contact_whatsapp_title'] ?? ''),
                    'line1' => trim($_POST['contact_whatsapp_line1'] ?? ''),
                    'line2' => trim($_POST['contact_whatsapp_line2'] ?? ''),
                ],
            ],
            'social' => [
                'facebook' => trim($_POST['social_facebook'] ?? ''),
                'twitter' => trim($_POST['social_twitter'] ?? ''),
                'instagram' => trim($_POST['social_instagram'] ?? ''),
                'teamspeak' => trim($_POST['social_teamspeak'] ?? ''),
                'discord' => trim($_POST['social_discord'] ?? ''),
            ],
            'footer' => [
                'about' => trim($_POST['footer_about'] ?? ''),
                'extra_link_label' => trim($_POST['footer_extra_link_label'] ?? ''),
                'extra_link_url' => trim($_POST['footer_extra_link_url'] ?? ''),
                'legal' => $legal,
            ],
            'header' => [
                'show_brand_text' => isset($_POST['show_brand_text']),
                'tagline' => trim($_POST['header_tagline'] ?? ''),
            ],
            'nav' => $navItems,
        ];

        $merged = array_replace_recursive($helper->defaultSiteContent(), $siteContent);
        $merged['nav'] = $navItems;
        $fields['site_content'] = json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $helper->setSettings($fields);
        echo sendSuccess('Einstellungen gespeichert.');
    } catch (Throwable $e) {
        echo sendError($e->getMessage());
    }
}

$displayName = $helper->getSetting('display_name') ?: $helper->siteName();
$logoUrl = $helper->getLogoUrl();
$faviconUrl = $helper->getFaviconUrl();
$siteContent = $helper->getSiteContent();
$contact = $siteContent['contact'];
$social = $siteContent['social'];
$footer = $siteContent['footer'];
$headerCfg = $siteContent['header'] ?? [];
$navItems = $siteContent['nav'] ?? [];
while (count($navItems) < 5) {
    $navItems[] = ['label' => '', 'url' => ''];
}
$legalByKey = [];
foreach ($footer['legal'] as $item) {
    $legalByKey[$item['key']] = $item;
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="ts-page-title">Einstellungen &amp; Branding</h1>
            <p class="ts-page-sub">Logo, Kontakt, Footer, Social-Links und System-Schalter</p>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <form method="post" enctype="multipart/form-data" class="row">
                <div class="col-lg-8">
                    <div class="card ts-panel mb-3">
                        <div class="card-header">Markenauftritt</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="display_name">Anzeigename</label>
                                <input id="display_name" class="form-control" name="display_name" value="<?= htmlspecialchars((string) $displayName); ?>" placeholder="TEA-Space">
                                <small class="form-text text-muted">Für Seitentitel, Footer und Systemtexte. Neben dem Logo wird er nicht angezeigt, sobald ein Logo vorhanden ist.</small>
                            </div>
                            <div class="form-group">
                                <label for="header_tagline">Header-Tagline (Top-Leiste)</label>
                                <input id="header_tagline" class="form-control" name="header_tagline" value="<?= htmlspecialchars((string) ($headerCfg['tagline'] ?? '')); ?>" placeholder="Hosting aus Deutschland">
                            </div>
                            <?php if (!$helper->hasLogoImage()): ?>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="show_brand_text" name="show_brand_text" value="1" <?= !empty($headerCfg['show_brand_text']) ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="show_brand_text">Anzeigenamen statt Logo anzeigen (nur ohne Logo-Datei)</label>
                            </div>
                            <?php else: ?>
                            <input type="hidden" name="show_brand_text" value="0">
                            <p class="text-muted small mb-3">Logo ist gesetzt – der Anzeigename erscheint nicht neben dem Logo.</p>
                            <?php endif; ?>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="logo">Logo (PNG/JPG/WEBP/SVG)</label>
                                    <div class="ts-file">
                                        <input id="logo" class="ts-file-input" type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                                        <label for="logo" class="btn btn-outline-secondary ts-file-btn">
                                            <i class="fas fa-upload mr-1"></i> Datei auswählen
                                        </label>
                                        <span class="ts-file-name" data-for="logo">Keine Datei ausgewählt</span>
                                    </div>
                                    <div class="custom-control custom-checkbox mt-3">
                                        <input type="checkbox" class="custom-control-input" id="reset_logo" name="reset_logo" value="1">
                                        <label class="custom-control-label" for="reset_logo">Standard-Logo wiederherstellen</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="favicon">Favicon</label>
                                    <div class="ts-file">
                                        <input id="favicon" class="ts-file-input" type="file" name="favicon" accept="image/png,image/jpeg,image/webp,image/x-icon,image/vnd.microsoft.icon,image/svg+xml">
                                        <label for="favicon" class="btn btn-outline-secondary ts-file-btn">
                                            <i class="fas fa-upload mr-1"></i> Datei auswählen
                                        </label>
                                        <span class="ts-file-name" data-for="favicon">Keine Datei ausgewählt</span>
                                    </div>
                                    <div class="custom-control custom-checkbox mt-3">
                                        <input type="checkbox" class="custom-control-input" id="reset_favicon" name="reset_favicon" value="1">
                                        <label class="custom-control-label" for="reset_favicon">Standard-Favicon wiederherstellen</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card ts-panel mb-3">
                        <div class="card-header">Website-Navigation (Header)</div>
                        <div class="card-body">
                            <p class="text-muted small">Leere Zeilen werden ignoriert. Max. 8 Einträge.</p>
                            <?php foreach ($navItems as $i => $nav): ?>
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <label>Label <?= (int) $i + 1; ?></label>
                                    <input class="form-control" name="nav_label[]" value="<?= htmlspecialchars((string) ($nav['label'] ?? '')); ?>" placeholder="z. B. Start">
                                </div>
                                <div class="form-group col-md-7">
                                    <label>URL <?= (int) $i + 1; ?></label>
                                    <input class="form-control" name="nav_url[]" value="<?= htmlspecialchars((string) ($nav['url'] ?? '')); ?>" placeholder="<?= htmlspecialchars($helper->url()); ?>">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="card ts-panel mb-3">
                        <div class="card-header">Support-Karten (Dashboard)</div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="support_ts_label">Teamspeak – Titel</label>
                                    <input id="support_ts_label" class="form-control" name="support_ts_label" value="<?= htmlspecialchars($helper->getSupportTsLabel()); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="support_ts_value">Teamspeak – Adresse</label>
                                    <input id="support_ts_value" class="form-control" name="support_ts_value" value="<?= htmlspecialchars($helper->getSupportTsValue()); ?>" placeholder="ts.example.de">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="support_phone_label">Telefon – Titel</label>
                                    <input id="support_phone_label" class="form-control" name="support_phone_label" value="<?= htmlspecialchars($helper->getSupportPhoneLabel()); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="support_phone_value">Telefon / WhatsApp – Nummer</label>
                                    <input id="support_phone_value" class="form-control" name="support_phone_value" value="<?= htmlspecialchars($helper->getSupportPhoneValue()); ?>" placeholder="+49 …">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card ts-panel mb-3">
                        <div class="card-header">Kontaktseite – Info-Karten</div>
                        <div class="card-body">
                            <?php
                            $cardDefs = [
                                'office' => 'Büro / Standort',
                                'phone' => 'Telefon',
                                'message' => 'Nachricht / E-Mail',
                                'whatsapp' => 'WhatsApp',
                            ];
                            foreach ($cardDefs as $key => $label):
                                $c = $contact[$key] ?? [];
                            ?>
                            <h6 class="text-muted mb-2"><?= htmlspecialchars($label); ?></h6>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Titel</label>
                                    <input class="form-control" name="contact_<?= $key; ?>_title" value="<?= htmlspecialchars((string) ($c['title'] ?? '')); ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Zeile 1</label>
                                    <input class="form-control" name="contact_<?= $key; ?>_line1" value="<?= htmlspecialchars((string) ($c['line1'] ?? '')); ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Zeile 2</label>
                                    <input class="form-control" name="contact_<?= $key; ?>_line2" value="<?= htmlspecialchars((string) ($c['line2'] ?? '')); ?>">
                                </div>
                            </div>
                            <?php if ($key === 'message'): ?>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Link-Text (z. B. Ticket)</label>
                                    <input class="form-control" name="contact_message_link_label" value="<?= htmlspecialchars((string) ($c['link_label'] ?? '')); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Link-URL</label>
                                    <input class="form-control" name="contact_message_link_url" value="<?= htmlspecialchars((string) ($c['link_url'] ?? '')); ?>" placeholder="<?= htmlspecialchars($helper->url() . 'support'); ?>">
                                </div>
                            </div>
                            <?php endif; ?>
                            <hr>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="card ts-panel mb-3">
                        <div class="card-header">Social Media (Footer)</div>
                        <div class="card-body">
                            <p class="text-muted small">Leere URLs werden ausgeblendet.</p>
                            <div class="form-row">
                                <?php foreach (['facebook' => 'Facebook', 'twitter' => 'Twitter / X', 'instagram' => 'Instagram', 'teamspeak' => 'Teamspeak', 'discord' => 'Discord'] as $key => $label): ?>
                                <div class="form-group col-md-6">
                                    <label for="social_<?= $key; ?>"><?= htmlspecialchars($label); ?></label>
                                    <input id="social_<?= $key; ?>" class="form-control" name="social_<?= $key; ?>" value="<?= htmlspecialchars((string) ($social[$key] ?? '')); ?>" placeholder="https://…">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card ts-panel mb-3">
                        <div class="card-header">Footer – Text &amp; Extra-Link</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="footer_about">Kurztext neben Logo</label>
                                <textarea id="footer_about" class="form-control" name="footer_about" rows="3" placeholder="Kurze Beschreibung …"><?= htmlspecialchars((string) ($footer['about'] ?? '')); ?></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="footer_extra_link_label">Extra-Link (Spalte „Links“) – Text</label>
                                    <input id="footer_extra_link_label" class="form-control" name="footer_extra_link_label" value="<?= htmlspecialchars((string) ($footer['extra_link_label'] ?? '')); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="footer_extra_link_url">Extra-Link – URL</label>
                                    <input id="footer_extra_link_url" class="form-control" name="footer_extra_link_url" value="<?= htmlspecialchars((string) ($footer['extra_link_url'] ?? '')); ?>" placeholder="https://…">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card ts-panel mb-3">
                        <div class="card-header">Footer – Legal-Links</div>
                        <div class="card-body">
                            <?php
                            $legalMeta = [
                                'impressum' => 'Impressum',
                                'datenschutz' => 'Datenschutz',
                                'agb' => 'AGB',
                                'widerruf' => 'Widerruf',
                                'hoster' => 'Teaspeak Hoster (extern)',
                            ];
                            foreach ($legalMeta as $key => $hint):
                                $item = $legalByKey[$key] ?? ['label' => '', 'url' => ''];
                            ?>
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-4">
                                    <label><?= htmlspecialchars($hint); ?> – Label</label>
                                    <input class="form-control" name="legal_label_<?= $key; ?>" value="<?= htmlspecialchars((string) ($item['label'] ?? '')); ?>">
                                </div>
                                <div class="form-group col-md-8">
                                    <label>URL</label>
                                    <input class="form-control" name="legal_url_<?= $key; ?>" value="<?= htmlspecialchars((string) ($item['url'] ?? '')); ?>" placeholder="https://… oder interne Route">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="card ts-panel mb-3">
                        <div class="card-header">System</div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="login">Login</label>
                                    <select id="login" class="form-control" name="login">
                                        <option value="1" <?= (int) $helper->getSetting('login') === 1 ? 'selected' : ''; ?>>Aktiviert</option>
                                        <option value="0" <?= (int) $helper->getSetting('login') === 0 ? 'selected' : ''; ?>>Deaktiviert</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="register">Registrierung</label>
                                    <select id="register" class="form-control" name="register">
                                        <option value="1" <?= (int) $helper->getSetting('register') === 1 ? 'selected' : ''; ?>>Aktiviert</option>
                                        <option value="0" <?= (int) $helper->getSetting('register') === 0 ? 'selected' : ''; ?>>Deaktiviert</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="wartung">Wartungsmodus</label>
                                    <select id="wartung" class="form-control" name="wartung">
                                        <option value="0" <?= (int) $helper->getSetting('wartung') === 0 ? 'selected' : ''; ?>>Aus</option>
                                        <option value="1" <?= (int) $helper->getSetting('wartung') === 1 ? 'selected' : ''; ?>>An</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="saveBranding" class="btn btn-primary btn-lg mb-4">
                        <i class="fas fa-save mr-1"></i> Speichern
                    </button>
                </div>

                <div class="col-lg-4">
                    <div class="card ts-panel ts-preview sticky-top" style="top:1rem;">
                        <div class="card-header">Vorschau</div>
                        <div class="card-body text-center">
                            <div class="ts-preview-brand">
                                <img src="<?= htmlspecialchars($logoUrl); ?>" alt="Logo" class="ts-preview-logo">
                                <?php if ($helper->showBrandText()): ?>
                                <div class="ts-preview-name"><?= htmlspecialchars((string) $displayName); ?></div>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted mb-2">Favicon</p>
                            <img src="<?= htmlspecialchars($faviconUrl); ?>" alt="Favicon" class="ts-preview-fav">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(function () {
  document.querySelectorAll('.ts-file-input').forEach(function (input) {
    input.addEventListener('change', function () {
      var nameEl = document.querySelector('.ts-file-name[data-for="' + input.id + '"]');
      if (!nameEl) return;
      if (input.files && input.files[0]) {
        nameEl.textContent = input.files[0].name;
        nameEl.classList.add('has-file');
      } else {
        nameEl.textContent = 'Keine Datei ausgewählt';
        nameEl.classList.remove('has-file');
      }
    });
  });
})();
</script>
