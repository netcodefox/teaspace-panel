<?php
$currPage = 'front_Startseite';
include 'app/controller/PageController.php';



?>

 <!--Why Section -->
    <section class="row why_choose bg-gray section-spacing">
        <div class="container">
            <div class="row sectionTitle p-bottom40">
                
                <h2>Warum Wir</h2>
            </div>
            <div class="row">
                <div class="col-sm-4 cause2choose m-top40">
                    <div class="media bg_white relative border shadow">

                    	

                        <div class="media-left">
						
                        	<i class="fas fa-star text-gredient1 text-shadow1 fa-5x"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="text-gredient1">Sofort Online</h4>
                            <p>Jedes Produkt wird sofort eingerichtet und steht nur wenige Sekunden nach der Bestellung schon zur Verfügung.(Ausgenommen Service)</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4 cause2choose m-top40">
                    <div class="media bg_white border shadow">
                        <div class="media-left">
                        	<i class="fas fa-microchip text-gredient1 text-shadow1 fa-5x"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="text-gredient1">Hochwertige Hardware</h4>
                            <p>Alle Server sind mit leistungsstarken Intel/AMD Prozessoren, Nvme SSD-Festplatten und ausfallsicherem Arbeitsspeicher ausgestattet.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 cause2choose m-top40">
                    <div class="media bg_white border shadow">
                        <div class="media-left">
                        	<i class="fas fa-ticket-alt text-gredient1 text-shadow1 fa-5x"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="text-gredient1 ">Schneller Support</h4>
                            <p>Unser Kundensupport steht dir über zahlreiche Kontaktmöglichkeiten zur Verfügung, wie zum Beispiel der Livechat, Ticket System oder via TeamSpeak und Telefon. Wann immer du dein Produkt bei uns nutzen möchtest, wir stehen dir bei allen Fragen zur Seite.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 cause2choose m-top40">
                    <div class="media bg_white border shadow">
                        <div class="media-left">
                        	<i class="fas fa-plug text-gredient1 text-shadow1 fa-5x"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="text-gredient1 ">95,9% Ökostrom</h4>
                            <p>Ein verantwortlicher Umgang mit der Umwelt ist uns sehr wichtig. Aus diesem Grund laufen alle Server mit 95,9% Ökostrom.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 cause2choose m-top40">
                    <div class="media bg_white border shadow">
                        <div class="media-left">
                        	<i class="fas fa-shield-alt text-gredient1 text-shadow1 fa-5x"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="text-gredient1">DDoS-Protection </h4>
                            <p>Unsere Server sind mit einem erweiterten DDoS-Schutz ausgestattet, um dein Produkt auch bei einem Angriff aufrecht erhalten zu können.</p>
                        </div>
                    </div>
                </div>
				<div class="col-sm-4 cause2choose m-top40">
                    <div class="media bg_white relative border shadow">
                        <div class="media-left">
                        	<i class="fas fa-euro-sign text-gredient1 text-shadow1 fa-5x"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="text-gredient1">Prepaidsystem</h4>
                            <p>Bei uns gibt es keine Mahnungen. Wenn du dein Produkt nicht mehr möchtest reicht es aus, wenn du es einfach nicht mehr verlängerst.(Ausgenommen sind vertrags Kunden)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
	<!--Facts-->
    <section class="row facts about_us_facts section-spacing bg-pattern">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 fact"><i class="fas fa-users fa-5x text-gredient1 text-shadow1"></i><strong class="counter text-gredient1"><?= $user->getAllUser($userid); ?></strong>Registrierte Kunden</div>
                <div class="col-sm-3 fact"><i class="fas fa-server fa-5x text-gredient9 text-shadow1"></i><strong class="counter text-gredient9"><?= $user->adminserviceCount($userid); ?></strong>Aktive Services</div>
                <div class="col-sm-3 fact"><i class="fas fa-ticket-alt fa-5x text-gredient4 text-shadow1"></i><strong class="counter text-gredient4"><?= $user->getcloseTicketsAdmin($userid); ?></strong>Gelöste Tickets</div>
                <div class="col-sm-3 fact"><i class="fas fa-users-cog fa-5x text-gredient7 text-shadow1"></i><strong class="counter text-gredient7"><?= $user->adminsCount($userid); ?></strong>Mitabeiter</div>
            </div>
        </div>
    </section>
    <!--Facts End-->
	<!--Testimonials-->
<!-- Blog Posts -->
    <section class="section-spacing bg-gray">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="row sectionTitle text-center p-bottom40">
                <h2>Letzte News</h2>
               
              </div>

              <div class="latest_news row m0">
<?php
                        $SQL = $db -> prepare("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 3;");
                        $SQL->execute();
                        if ($SQL->rowCount() != 0) {
                        while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){
                        ?>
                <div class="col-sm-4  m-top40">
                  <div class="post media border shadow radius">
                    
                    <div class="media-body">
                      <h5><?= $row['icon']; ?></h5>
                      <p><?= $helper->nl2br2($row['message']); ?></p>
                     	<div class="sr-blog-meta">
                            <ul class="list-inline">
							
                                <li><a href="#"><i class="fa fa-user-circle" aria-hidden="true"></i> <?= $user->getDataById($row['user_id'], 'username'); ?> </a></li>
                                <li><a href="#"><i class="fa fa-calendar" aria-hidden="true"></i> <?=  $site->formatDateWithoutTime($row['created_at']); ?> </a></li>
                                <li><a href="#"><i class="fas fa-user-tag" aria-hidden="true"></i> <?= $user->getDataById($row['user_id'], 'role'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                  </div>
                </div>
   <?php } } ?>
                
                
              </div>
            </div>
          </div>
        </div>
    </section>
	