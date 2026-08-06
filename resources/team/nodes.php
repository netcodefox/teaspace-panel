<?php
$currPage = 'back_Nodeverwaltung_team_admin';
include 'app/controller/PageController.php';

if (!$helper->tableExists('bot_nodes')) {
    echo sendError('Die alte Bot-Node-Verwaltung ist nicht mehr Teil dieses Panels. Nutze unter Admin → TeaSpeak-Instanzen die Host-Verwaltung.');
    echo '<div class="content-wrapper"><div class="content"><div class="container-fluid">
        <a class="btn btn-primary" href="' . htmlspecialchars($helper->url()) . 'team/teaspeak-hosts">Zu TeaSpeak-Instanzen</a>
    </div></div></div>';
    return;
}

if(isset($_POST['createNode'])){
    $SQL = $db->prepare("INSERT INTO `bot_nodes`(`name`, `node_ip`, `unique_id`, `token`, `port`, `state`, `limit`) VALUES (?,?,?,?,?,?,?)");
    $SQL->execute(array($_POST['name'], $_POST['node_ip'], $_POST['unique_id'], $_POST['token'], $_POST['port'], 'active', $_POST['limit']));

    echo sendSuccess('Die Node wurde angelegt');
}
?>
<form method="post">
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Neue Node erstellen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <label>Name:</label>
                    <input class="form-control" name="name" required="required">
                    <br>

                    <label>Node IP:</label>
                    <input class="form-control" name="node_ip" required="required">
                    <br>

                    <label>Unique ID:</label>
                    <input class="form-control" name="unique_id" required="required">
                    <br>

                    <label>Token:</label>
                    <input class="form-control" name="token" required="required">
                    <br>

                    <label>Port:</label>
                    <input class="form-control" name="port" required="required">
                    <br>

                    <label>Limit:</label>
                    <input class="form-control" name="limit" type="number" required="required">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary" name="createNode">Node anlegen</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $currPageName; ?></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Neue Node</button>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">IP</th>
                                <th scope="col">Limit</th>
                                <th scope="col">Status</th>
                                <th scope="col">Erstellt am</th>
                                <th scope="col">Aktive Bots</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $SQL = $db->prepare("SELECT * FROM `bot_nodes`");
                            $SQL->execute();
                            if ($SQL->rowCount() != 0) {
                                while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= htmlspecialchars($row['name']); ?></td>
                                        <td><?= htmlspecialchars($row['node_ip']); ?></td>
                                        <td><?= htmlspecialchars((string) $row['limit']); ?></td>
                                        <td><?= htmlspecialchars($row['state']); ?></td>
                                        <td><?= $site->formatDate($row['created_at']); ?></td>
                                        <td><?= isset($node) ? $node->getBotCountFromNode($row['id']) : '0'; ?></td>
                                        <td><a href="<?= $helper->url(); ?>team/node/<?= $row['id']; ?>" class="btn btn-primary btn-sm">Bearbeiten</a></td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
