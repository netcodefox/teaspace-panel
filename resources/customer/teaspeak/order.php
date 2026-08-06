<?php
$currPage = 'front_Teaspeak';
include 'app/controller/PageController.php';
include 'app/manager/customer/teaspeak/order.php';

$unitPrice = (float) $site->getProductPrice('TEASPEAK');
$loggedIn = $user->sessionExists($_COOKIE['session_token'] ?? '');
?>

<section class="tf-page-hero">
    <div class="container">
        <h1 class="tf-section-title">TeaSpeak</h1>
        <p class="tf-section-sub">Eigenständige Sprachkommunikation – unabhängig von Teamspeak. Wähle Slots und Laufzeit.</p>
    </div>
</section>

<section class="tf-section" style="padding-top:0;">
    <div class="container">
        <form method="post" id="orderForm" class="tf-order-grid">
            <div class="tf-order-config">
                <div class="tf-order-field">
                    <div class="tf-order-label-row">
                        <label for="slots">Slots</label>
                        <span class="tf-order-live" data-slots>10</span>
                    </div>
                    <input id="slots" name="slots" type="range" min="10" max="1000" value="10" class="tf-range" aria-valuemin="10" aria-valuemax="1000">
                    <div class="tf-range-hints"><span>10</span><span>1000</span></div>
                </div>

                <div class="tf-order-field">
                    <label for="duration">Laufzeit</label>
                    <select id="duration" name="duration" class="form-control">
                        <option value="30" data-factor="1">30 Tage</option>
                        <option value="60" data-factor="2">60 Tage</option>
                        <option value="90" data-factor="3">90 Tage</option>
                    </select>
                </div>
            </div>

            <aside class="tf-order-summary-card">
                <h2>Zusammenfassung</h2>
                <div class="tf-order-row">
                    <span>Slots</span>
                    <strong data-slots>10</strong>
                </div>
                <div class="tf-order-row">
                    <span>Preis</span>
                    <strong><span data-amount>0,00</span> € <small>/ Monat</small></strong>
                </div>
                <p class="tf-order-note"><?= number_format($unitPrice, 2, ',', '.'); ?> € pro Slot / 30 Tage</p>

                <?php if ($loggedIn): ?>
                    <label class="tf-check">
                        <input type="checkbox" id="agb" required>
                        <span>AGB akzeptieren</span>
                    </label>
                    <label class="tf-check">
                        <input type="checkbox" id="wiederruf" required>
                        <span>Widerruf zur Kenntnis genommen</span>
                    </label>
                    <button type="submit" name="order" id="orderBtn" class="btn btn-primary btn-xlg btn-block">Kostenpflichtig bestellen</button>
                <?php else: ?>
                    <a href="<?= $helper->url(); ?>register" class="btn btn-primary btn-xlg btn-block">Account erstellen</a>
                <?php endif; ?>
            </aside>
        </form>
    </div>
</section>

<script>
(function () {
  var unit = <?= json_encode($unitPrice); ?>;
  var slotsEl = document.getElementById('slots');
  var durationEl = document.getElementById('duration');
  var form = document.getElementById('orderForm');

  function update() {
    var slots = Math.min(1000, Math.max(10, parseInt(slotsEl.value, 10) || 10));
    slotsEl.value = slots;
    var factor = parseFloat(durationEl.options[durationEl.selectedIndex].getAttribute('data-factor')) || 1;
    var price = (slots * unit * factor).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.querySelectorAll('[data-slots]').forEach(function (n) { n.textContent = String(slots); });
    document.querySelectorAll('[data-amount]').forEach(function (n) { n.textContent = price; });
  }

  slotsEl.addEventListener('input', update);
  durationEl.addEventListener('change', update);
  update();

  if (form) {
    form.addEventListener('submit', function (e) {
      var agb = document.getElementById('agb');
      var wid = document.getElementById('wiederruf');
      if (agb && wid && (!agb.checked || !wid.checked)) {
        e.preventDefault();
        return;
      }
      var btn = document.getElementById('orderBtn');
      if (btn) {
        btn.disabled = true;
        btn.textContent = 'Bestellung wird ausgeführt…';
      }
    });
  }
})();
</script>
