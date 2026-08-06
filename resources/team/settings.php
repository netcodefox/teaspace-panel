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

        $helper->setSettings($fields);
        echo sendSuccess('Einstellungen gespeichert.');
    } catch (Throwable $e) {
        echo sendError($e->getMessage());
    }
}

$displayName = $helper->getSetting('display_name') ?: $helper->siteName();
$logoUrl = $helper->getLogoUrl();
$faviconUrl = $helper->getFaviconUrl();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="ts-page-title">Einstellungen &amp; Branding</h1>
            <p class="ts-page-sub">Logo, Support-Kontakte, Seitenname und System-Schalter</p>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <form method="post" enctype="multipart/form-data" class="row">
                <div class="col-lg-7">
                    <div class="card ts-panel mb-3">
                        <div class="card-header">Markenauftritt</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="display_name">Anzeigename</label>
                                <input id="display_name" class="form-control" name="display_name" value="<?= htmlspecialchars((string) $displayName); ?>" placeholder="Tea-Space">
                                <small class="form-text text-muted">Wird in Sidebar, Titel und Footer genutzt.</small>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="logo">Logo (PNG/JPG/WEBP/SVG)</label>
                                    <input id="logo" class="form-control-file" type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="reset_logo" name="reset_logo" value="1">
                                        <label class="custom-control-label" for="reset_logo">Standard-Logo wiederherstellen</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="favicon">Favicon</label>
                                    <input id="favicon" class="form-control-file" type="file" name="favicon" accept="image/png,image/jpeg,image/webp,image/x-icon,image/vnd.microsoft.icon,image/svg+xml">
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="reset_favicon" name="reset_favicon" value="1">
                                        <label class="custom-control-label" for="reset_favicon">Standard-Favicon wiederherstellen</label>
                                    </div>
                                </div>
                            </div>
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
                                    <small class="form-text text-muted">Wird als Anzeige und als <code>ts3server://…</code>-Link genutzt.</small>
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

                    <button type="submit" name="saveBranding" class="btn btn-primary btn-lg">
                        <i class="fas fa-save mr-1"></i> Speichern
                    </button>
                </div>

                <div class="col-lg-5">
                    <div class="card ts-panel ts-preview">
                        <div class="card-header">Vorschau</div>
                        <div class="card-body text-center">
                            <div class="ts-preview-brand">
                                <img src="<?= htmlspecialchars($logoUrl); ?>" alt="Logo" class="ts-preview-logo">
                                <div class="ts-preview-name"><?= htmlspecialchars((string) $displayName); ?></div>
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
