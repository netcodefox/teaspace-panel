<?php
$currPage = 'team_TeaSpeak Host_admin';
include 'app/controller/PageController.php';

$helper->ensureSettingsSchema();

if (!$user->isAdmin($_COOKIE['session_token'] ?? null)) {
    die(header('Location: ' . $helper->url() . 'team/dashboard'));
}

$id = (int) $helper->protect($_GET['id'] ?? 0);
$SQL = $db->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
$SQL->execute([':id' => $id]);
$host = $SQL->fetch(PDO::FETCH_ASSOC);

if (!$host) {
    die(header('Location: ' . $helper->url() . 'team/teaspeak-hosts'));
}

if (isset($_POST['saveHost'])) {
    $name = trim($_POST['name'] ?? '');
    $loginIp = trim($_POST['login_ip'] ?? '');
    $displayIp = trim($_POST['display_ip'] ?? '');
    $loginPort = trim($_POST['login_port'] ?? '');
    $loginName = trim($_POST['login_name'] ?? '');
    $loginPass = (string) ($_POST['login_passwort'] ?? '');
    $status = ($_POST['status'] ?? 'ACTIVE') === 'DISABLED' ? 'DISABLED' : 'ACTIVE';
    $notes = trim($_POST['notes'] ?? '');

    if ($name === '' || $loginIp === '' || $loginPort === '' || $loginName === '') {
        echo sendError('Bitte alle Pflichtfelder ausfüllen.');
    } else {
        if ($loginPass === '') {
            $loginPass = $host['login_passwort'];
        }
        $upd = $db->prepare("UPDATE `teaspeak_hosts` SET `name`=?, `login_ip`=?, `display_ip`=?, `login_port`=?, `login_name`=?, `login_passwort`=?, `status`=?, `notes`=? WHERE `id`=?");
        $upd->execute([$name, $loginIp, $displayIp !== '' ? $displayIp : $loginIp, $loginPort, $loginName, $loginPass, $status, $notes !== '' ? $notes : null, $id]);
        echo sendSuccess('Instanz gespeichert.');
        $SQL->execute([':id' => $id]);
        $host = $SQL->fetch(PDO::FETCH_ASSOC);
    }
}

$cnt = $db->prepare("SELECT COUNT(*) FROM `teaspeaks` WHERE `node_id` = :id AND `deleted_at` IS NULL");
$cnt->execute([':id' => $id]);
$activeServers = (int) $cnt->fetchColumn();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="ts-page-title">Instanz #<?= (int) $host['id']; ?></h1>
                <p class="ts-page-sub"><?= htmlspecialchars($host['name']); ?> · <?= $activeServers; ?> aktive Server</p>
            </div>
            <a class="btn btn-outline-secondary" href="<?= $helper->url(); ?>team/teaspeak-hosts">Zurück zur Liste</a>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card ts-panel">
                <div class="card-body">
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Anzeigename</label>
                                <input id="name" class="form-control" name="name" required value="<?= htmlspecialchars($host['name']); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" name="status">
                                    <option value="ACTIVE" <?= $host['status'] === 'ACTIVE' ? 'selected' : ''; ?>>Aktiv</option>
                                    <option value="DISABLED" <?= $host['status'] === 'DISABLED' ? 'selected' : ''; ?>>Deaktiviert</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="login_ip">Query-IP / Host</label>
                                <input id="login_ip" class="form-control" name="login_ip" required value="<?= htmlspecialchars($host['login_ip']); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="display_ip">Kunden-IP</label>
                                <input id="display_ip" class="form-control" name="display_ip" value="<?= htmlspecialchars($host['display_ip'] ?? $host['login_ip']); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="login_port">Query-Port</label>
                                <input id="login_port" class="form-control" name="login_port" required value="<?= htmlspecialchars($host['login_port']); ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="login_name">Query-User</label>
                                <input id="login_name" class="form-control" name="login_name" required value="<?= htmlspecialchars($host['login_name']); ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="login_passwort">Query-Passwort</label>
                                <input id="login_passwort" class="form-control" type="password" name="login_passwort" placeholder="Leer = unverändert" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notizen</label>
                            <textarea id="notes" class="form-control" name="notes" rows="3"><?= htmlspecialchars($host['notes'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" name="saveHost" class="btn btn-primary">Speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
