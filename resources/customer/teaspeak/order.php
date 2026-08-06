<?php
$currPage = 'front_Teaspeak';
include 'app/controller/PageController.php';
include 'app/manager/customer/teaspeak/order.php';
?>





<style>
	.slider {
  -webkit-appearance: none;
  width: 100%;
  height: 10px;
  border-radius: 50px;
  background: rgba(255,255,255,0.12);
  outline: none;
}
.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: #14b8a6;
  cursor: pointer;
}
.slider::-moz-range-thumb {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: #14b8a6;
  cursor: pointer;
  border: 0;
}
</style>




 <!--Cloud Slider-->
    <section class="section-spacing p-top80 tf-section">
        <section class="row pricing pricing2 p-bottom40">
            <div class="container">
                <div class="sectionTitle">
                    <h2 class="tf-section-title">TeaSpeak</h2>
                    <p class="tf-section-sub">Eigenständige Sprachkommunikation – unabhängig von Teamspeak. Wähle Slots und Laufzeit.</p>
                </div>
            </div>
        </section>
 
        <div class="container">
            <div class="row">
            	<!-- begain the Slider -->
                <div id="qsSlider" class="tf-order-panel">
                    <div class="col-sm-8">
                        <div id="QsControls">
							 <form method="post" id="orderForm">
                            <h4 class="title">Slots</h4>
                                        <input  id="slots" name="slots" type="range" min="10" max="1000" value="10" class="slider">
                            <h4 class="title">Laufzeit</h4>
                            <select id="duration" name="duration" class="form-control">
                                            <option value="30" data-factor="1">30 Tage</option>
                                            <option value="60" data-factor="2">60 Tage</option>
                                            <option value="90" data-factor="3">90 Tage</option>
                                        </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div id="QsPrice">
                            <div class="prices tf-order-summary">
                                <div id="pricetext" class="relative">
                                    <h5>Slots</h5>
                                    <span data-slots="" id="doller">0</span>
                                    <br>
									<h5>Preis</h5>
                                    <span id="doller"  data-amount="" class="doller">0.00€</span>
                                    <span id="btext">/mo</span>
                                </div>
                               <br>
                            	 <?php if($user->sessionExists($_COOKIE['session_token'] ?? '')){ ?>
                                            <div class="custom-control custom-checkbox mb-2">
                                                <label><input type="checkbox" id="agb" required> AGB akzeptieren</label>
                                            </div>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <label><input type="checkbox" id="wiederruf" required> Widerruf zur Kenntnis genommen</label>
                                            </div>
                                            <button onclick="orderNow();" id="orderBtn" type="submit" name="order" class="btn btn-primary btn-xlg btn-block">Kostenpflichtig bestellen</button>
                                            <script>
                                                function orderNow() {
                                                    if(document.getElementById("agb").checked){
                                                        if(document.getElementById("wiederruf").checked){
                                                            document.getElementById("orderForm").submit();
                                                            const button = document.getElementById('orderBtn');
                                                            button.disabled = true;
                                                            button.innerHTML = 'Bestellung wird ausgeführt...';
                                                        }
                                                    }
                                                }
                                            </script>
                                        <?php } else { ?>
                                            <a href="<?= $helper->url(); ?>register" class="btn btn-primary btn-xlg btn-block">Account erstellen</a>
                                        <?php } ?>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </section>
















<script>

    $('#slots').on('input', function() {update();});
    $("select, textarea").change(function() { update(); } ).trigger("change");

    function update(){
		
		var slots = $('#slots').val();
		
		if(slots > 1000){
			$('#slots').val(1000);
		}
		
        var sum = $("#slots").val() * <?= $site->getProductPrice('TEASPEAK'); ?>;
        var price = Number(sum * $("#duration").find("option:selected").data("factor"))
            .toLocaleString("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2});
        $('#price_post').val(price);
        $('#slots').val(slots);
        $("*[data-amount]").html(price );
        $("*[data-slots]").html(slots );
    }

    $(document).ready(function(){
        update();
    });
</script>