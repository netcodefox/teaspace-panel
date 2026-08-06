<?php
$currPage = 'back_Guthaben aufladen';
include 'app/controller/PageController.php';
include 'app/manager/customer/payment/init.php';

$methods = [
    'paypal' => ['label' => 'PayPal', 'hint' => 'Schnell & sicher'],
    'BANKTRANSFER' => ['label' => 'Überweisung', 'hint' => 'Manuelle Freigabe'],
    'PAYSAFECARD' => ['label' => 'Paysafecard', 'hint' => '+15 % Gebühr'],
    'GIROPAY' => ['label' => 'GiroPay', 'hint' => 'Online-Banking'],
    'SOFORT' => ['label' => 'Sofortüberweisung', 'hint' => 'Klarna Sofort'],
    'EPS' => ['label' => 'EPS', 'hint' => 'Österreich'],
    'IDEAL' => ['label' => 'iDEAL', 'hint' => 'Niederlande'],
];
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="ts-page-title">Guthaben aufladen</h1>
            <p class="ts-page-sub">Kundennummer KD-<?= (int) $userid; ?> · Aktuelles Guthaben: <strong><?= htmlspecialchars((string) $amount); ?> €</strong></p>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="card ts-panel h-100">
                        <div class="card-header">Aufladung starten</div>
                        <div class="card-body">
                            <form method="post" class="ts-charge-form">
                                <div class="form-group">
                                    <label for="amount">Betrag in Euro</label>
                                    <div class="input-group ts-amount-group">
                                        <input id="amount" class="form-control" name="amount" type="number" min="1" step="0.01" value="10.00" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                    <div class="ts-quick-amounts mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary ts-amount-chip" data-amount="5">5 €</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ts-amount-chip" data-amount="10">10 €</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ts-amount-chip" data-amount="25">25 €</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ts-amount-chip" data-amount="50">50 €</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Zahlungsmethode</label>
                                    <select class="form-control custom-select" id="payment_method" name="payment_method" required>
                                        <?php foreach ($methods as $value => $meta): ?>
                                            <option value="<?= htmlspecialchars($value); ?>">
                                                <?= htmlspecialchars($meta['label']); ?><?= $meta['hint'] !== '' ? ' — ' . htmlspecialchars($meta['hint']) : ''; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <button type="submit" name="startPayment" class="btn btn-primary btn-block btn-lg">
                                    <i class="fas fa-wallet mr-1"></i> Jetzt aufladen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 mb-3">
                    <div class="card ts-panel h-100">
                        <div class="card-header">Meine Zahlungen</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table ts-table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Beschreibung</th>
                                        <th>Betrag</th>
                                        <th>Datum</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $SQL = $db->prepare("SELECT * FROM `transactions` WHERE `user_id` = :user_id AND `state` = :state ORDER BY `id` DESC");
                                    $SQL->execute([":user_id" => $userid, ":state" => 'success']);
                                    if ($SQL->rowCount() != 0) {
                                        while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <tr>
                                                <td><?= (int) $row['id']; ?></td>
                                                <td><?= htmlspecialchars((string) $row['desc']); ?></td>
                                                <td><?= htmlspecialchars((string) $row['amount']); ?> €</td>
                                                <td><?= htmlspecialchars($helper->formatDate($row['created_at'])); ?></td>
                                                <td class="text-right">
                                                    <a class="btn btn-primary btn-sm" href="<?= $helper->url(); ?>accounting/invoice/<?= (int) $row['id']; ?>">Rechnung</a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Noch keine erfolgreichen Zahlungen.</td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card ts-panel ts-note">
                        <div class="card-body">
                            <div class="ts-note-label">Hinweis</div>
                            <p class="mb-1">Es ist kein Abo. Der Betrag wird nur einmalig fällig – es entstehen <strong>keine</strong> weiteren Kosten. Keine Kündigung notwendig.</p>
                            <p class="mb-0">Mit dieser Zahlung wird nur das Guthaben des Kundenkontos aufgeladen. Guthaben kann <strong>nicht</strong> wieder ausgezahlt werden.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
(function () {
  var input = document.getElementById('amount');
  document.querySelectorAll('.ts-amount-chip').forEach(function (btn) {
    btn.addEventListener('click', function () {
      input.value = Number(btn.getAttribute('data-amount')).toFixed(2);
      input.focus();
    });
  });
})();
</script>
