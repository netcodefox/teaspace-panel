<?php
$currPage = 'back_Dashboard';
include 'app/controller/PageController.php';
$product_count = 0;
?>



 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text"><?= $currPageName; ?> ( KD - <?= $userid; ?> )</h1>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-euro-sign"></i></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Guthaben</span>
                <span class="info-box-number">
                  <?= $amount; ?> €
                  
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-ticket-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Offene Tickets</span>
                <span class="info-box-number"><?= $user->getOpenTickets($userid); ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-server"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Aktive Dienste</span>
                <span class="info-box-number"><?= $user->serviceCount($userid); ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-euro-sign"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Monatliche Kosten</span>
                <span class="info-box-number"><?= number_format($user->montlyCosts($userid),2); ?>€</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div> 
		  
		  <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Support Pin</span>
                <span class="info-box-number"><?= $suppin; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
		  <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fab fa-teamspeak"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?= htmlspecialchars($helper->getSupportTsLabel()); ?></span>
                <span class="info-box-number"><a href="ts3server://<?= htmlspecialchars($helper->getSupportTsValue()); ?>"><?= htmlspecialchars($helper->getSupportTsValue()); ?></a></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
		  <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-phone"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?= htmlspecialchars($helper->getSupportPhoneLabel()); ?></span>
                <span class="info-box-number"><?= htmlspecialchars($helper->getSupportPhoneValue()); ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
		  
		  		<?php if($site->userHaveValidProfile($userid) == FALSE){ ?>
<div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Account Info</span>
                <span class="info-box-number">Bitte fülle dein Profil aus</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        <?php } ?>
		  
		  
		  
		  
		  
          <!-- /.col -->
        </div>
        <!-- /.row -->





		<div class="row">
		<br>

            <?php

                $notes = $user->getDataById($userid,'notes');
                if(isset($_POST['saveNotes'])){
                    if(empty($_POST['notes'])){
                        $notes = null;
                    } else {
                        $notes = $_POST['notes'];
                    }

                    $SQL = $db->prepare("UPDATE `users` SET `notes`=:notes WHERE `id` = :user_id");
                    $SQL->execute(array(":notes" => $notes, ":user_id" => $userid));

                    echo sendSuccess('Notiz wurde gespeichert');
                }

            ?>

<div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  
                 Notizen
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                 <form method="post">
                            <textarea class="form-control" rows="10" name="notes"><?= $helper->xssFix($notes); ?></textarea>
                            <br>
                            <button type="submit" name="saveNotes" class="btn btn-primary">Speichern</button>
                        </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
            
			
			
			<div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                 Latest News
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                              


						<ul class="home-timeline">
							 <?php
                                $SQL = $db -> prepare("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 3;");
                                $SQL->execute();
                                if ($SQL->rowCount() != 0) {
                                while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){
                                ?>
							
							<li>
								<a ><?= $row['icon']; ?></a>
								<a class="float-right"><?=  $site->formatDateWithoutTime($row['created_at']); ?> | <?= $user->getDataById($row['user_id'], 'username'); ?> | <?= $user->getDataById($row['user_id'], 'role'); ?>
                                            </a>
								<p><?= $helper->nl2br2($row['message']); ?><br></p>
							</li>
							<?php } } ?>
						</ul>

 </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
			
			
			
			
			
			
			
			
			
			
			

							

</div>