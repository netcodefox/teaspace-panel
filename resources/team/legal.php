<?php
$currPage = 'team_Rechtstexte_admin';
include 'app/controller/PageController.php';

$helper->ensureSettingsSchema();

if (!$user->isAdmin($_COOKIE['session_token'] ?? null)) {
    die(header('Location: ' . $helper->url() . 'team/dashboard'));
}

$pages = $helper->legalPageKeys();
$active = $_GET['tab'] ?? 'impressum';
if (!isset($pages[$active])) {
    $active = 'impressum';
}

if (isset($_POST['saveLegal'])) {
    try {
        $key = $_POST['page_key'] ?? '';
        if (!isset($pages[$key])) {
            throw new RuntimeException('Ungültige Seite.');
        }
        $html = (string) ($_POST['page_html'] ?? '');
        if (isset($_POST['reset_default'])) {
            $html = $helper->getLegalPageDefault($key);
        }
        $helper->setLegalPage($key, $html);
        header('Location: ' . $helper->url() . 'team/legal/' . rawurlencode($key) . '?saved=1');
        exit;
    } catch (Throwable $e) {
        echo sendError($e->getMessage());
    }
}

if (!empty($_GET['saved'])) {
    echo sendSuccess(($pages[$active] ?? 'Seite') . ' gespeichert.');
}

$content = $helper->getLegalPage($active);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="ts-page-title">Rechtstexte</h1>
            <p class="ts-page-sub">Inhalte von Impressum, Datenschutz, AGB und Widerruf bearbeiten</p>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <ul class="nav nav-pills mb-3 flex-wrap">
                <?php foreach ($pages as $key => $label): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active === $key ? 'active' : ''; ?>" href="<?= $helper->url(); ?>team/legal/<?= rawurlencode($key); ?>">
                        <?= htmlspecialchars($label); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <form method="post" action="<?= $helper->url(); ?>team/legal/<?= rawurlencode($active); ?>">
                <input type="hidden" name="page_key" value="<?= htmlspecialchars($active); ?>">
                <div class="card ts-panel mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span><?= htmlspecialchars($pages[$active]); ?></span>
                        <a class="btn btn-sm btn-outline-secondary" href="<?= $helper->url() . rawurlencode($active); ?>" target="_blank" rel="noopener">Vorschau öffnen</a>
                    </div>
                    <div class="card-body">
                        <textarea id="page_html" name="page_html" class="form-control" rows="22"><?= htmlspecialchars($content); ?></textarea>
                        <small class="form-text text-muted mt-2">HTML ist erlaubt. Mit der Checkbox unten kannst du beim Speichern den Standard-Inhalt laden.</small>
                        <div class="custom-control custom-checkbox mt-3">
                            <input type="checkbox" class="custom-control-input" id="reset_default" name="reset_default" value="1">
                            <label class="custom-control-label" for="reset_default">Beim Speichern Standard-Inhalt aus Datei laden</label>
                        </div>
                    </div>
                </div>
                <button type="submit" name="saveLegal" class="btn btn-primary btn-lg mb-4">
                    <i class="fas fa-save mr-1"></i> <?= htmlspecialchars($pages[$active]); ?> speichern
                </button>
            </form>
        </div>
    </div>
</div>

<script>
$(function () {
  if ($.fn.summernote) {
    $('#page_html').summernote({
      height: 420,
      width: '100%',
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'hr']],
        ['view', ['codeview', 'fullscreen']]
      ]
    });
  }
});
</script>
