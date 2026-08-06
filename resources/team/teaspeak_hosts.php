<?php
$currPage = 'team_TeaSpeak Hosts_admin';
include 'app/controller/PageController.php';

$helper->ensureSettingsSchema();

if (!$user->isAdmin($_COOKIE['session_token'] ?? null)) {
    die(header('Location: ' . $helper->url() . 'team/dashboard'));
}

if (isset($_POST['createHost'])) {
    $error = null;
    $name = trim($_POST['name'] ?? '');
    $loginIp = trim($_POST['login_ip'] ?? '');
    $displayIp = trim($_POST['display_ip'] ?? '');
    $loginPort = trim($_POST['login_port'] ?? '');
    $loginName = trim($_POST['login_name'] ?? '');
    $loginPass = (string) ($_POST['login_passwort'] ?? '');
    $status = ($_POST['status'] ?? 'ACTIVE') === 'DISABLED' ? 'DISABLED' : 'ACTIVE';
    $notes = trim($_POST['notes'] ?? '');

    if ($name === '' || $loginIp === '' || $loginPort === '' || $loginName === '' || $loginPass === '') {
        $error = 'Bitte alle Pflichtfelder ausfüllen.';
    }

    if (empty($error)) {
        $SQL = $db->prepare("INSERT INTO `teaspeak_hosts` (`name`, `login_ip`, `display_ip`, `login_port`, `login_name`, `login_passwort`, `status`, `notes`) VALUES (?,?,?,?,?,?,?,?)");
        $SQL->execute([$name, $loginIp, $displayIp !== '' ? $displayIp : $loginIp, $loginPort, $loginName, $loginPass, $status, $notes !== '' ? $notes : null]);
        echo sendSuccess('TeaSpeak-Instanz wurde angelegt.');
    } else {
        echo sendError($error);
    }
}

if (isset($_POST['deleteHost'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $check = $db->prepare("SELECT COUNT(*) FROM `teaspeaks` WHERE `node_id` = :id AND `deleted_at` IS NULL");
    $check->execute([':id' => $id]);
    if ((int) $check->fetchColumn() > 0) {
        echo sendError('Instanz hat noch aktive Server und kann nicht gelöscht werden.');
    } else {
        $SQL = $db->prepare("DELETE FROM `teaspeak_hosts` WHERE `id` = :id");
        $SQL->execute([':id' => $id]);
        echo sendSuccess('Instanz gelöscht.');
    }
}
?>

<div class="modal fade" id="createHostModal" tabindex="-1" role="dialog" aria-labelledby="createHostLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" class="modal-content ts-panel">
            <div class="modal-header">
                <h5 class="modal-title" id="createHostLabel">Neue TeaSpeak-Instanz</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="host_name">Anzeigename</label>
                        <input id="host_name" class="form-control" name="name" required placeholder="z.B. Node Frankfurt">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="host_status">Status</label>
                        <select id="host_status" class="form-control" name="status">
                            <option value="ACTIVE">Aktiv</option>
                            <option value="DISABLED">Deaktiviert</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="login_ip">Query-IP / Host</label>
                        <input id="login_ip" class="form-control" name="login_ip" required placeholder="127.0.0.1 oder hostname">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="display_ip">Kunden-IP (optional)</label>
                        <input id="display_ip" class="form-control" name="display_ip" placeholder="Öffentliche IP für Kunden">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="login_port">Query-Port</label>
                        <input id="login_port" class="form-control" name="login_port" value="10101" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="login_name">Query-User</label>
                        <input id="login_name" class="form-control" name="login_name" value="serveradmin" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="login_passwort">Query-Passwort</label>
                        <input id="login_passwort" class="form-control" type="password" name="login_passwort" required autocomplete="new-password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">Notizen</label>
                    <textarea id="notes" class="form-control" name="notes" rows="2" placeholder="Optional"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Abbrechen</button>
                <button type="submit" class="btn btn-primary" name="createHost">Instanz anlegen</button>
            </div>
        </form>
    </div>
</div>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="ts-page-title">TeaSpeak-Instanzen</h1>
                <p class="ts-page-sub">ServerQuery-Hosts für Bestellungen und Verwaltung</p>
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createHostModal">
                <i class="fas fa-plus mr-1"></i> Neue Instanz
            </button>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card ts-panel">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table ts-table mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Query</th>
                                <th>Kunden-IP</th>
                                <th>Status</th>
                                <th>Server</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $SQL = $db->prepare("SELECT * FROM `teaspeak_hosts` ORDER BY `id` ASC");
                            $SQL->execute();
                            if ($SQL->rowCount() === 0) {
                                echo '<tr><td colspan="7" class="text-center text-muted py-4">Noch keine Instanzen – lege die erste an.</td></tr>';
                            }
                            while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                                $cnt = $db->prepare("SELECT COUNT(*) FROM `teaspeaks` WHERE `node_id` = :id AND `deleted_at` IS NULL");
                                $cnt->execute([':id' => $row['id']]);
                                $active = (int) $cnt->fetchColumn();
                                $badge = ($row['status'] ?? '') === 'ACTIVE' ? 'ts-badge-ok' : 'ts-badge-off';
                                ?>
                                <tr>
                                    <td><?= (int) $row['id']; ?></td>
                                    <td><strong><?= htmlspecialchars($row['name']); ?></strong></td>
                                    <td><code><?= htmlspecialchars($row['login_ip'] . ':' . $row['login_port']); ?></code></td>
                                    <td><?= htmlspecialchars($row['display_ip'] ?? $row['login_ip']); ?></td>
                                    <td><span class="ts-badge <?= $badge; ?>"><?= htmlspecialchars($row['status']); ?></span></td>
                                    <td><?= $active; ?></td>
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-primary" href="<?= $helper->url(); ?>team/teaspeak-host/<?= (int) $row['id']; ?>">Bearbeiten</a>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Instanz wirklich löschen?');">
                                            <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">
                                            <button type="submit" name="deleteHost" class="btn btn-sm btn-outline-danger" <?= $active > 0 ? 'disabled title="Hat aktive Server"' : ''; ?>>Löschen</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
