<?php
$currPage = 'back_Tickets';
include 'app/controller/PageController.php';

if(isset($_POST['createTicket'])){
	 $error = null;
$google_response = $site->validateCaptcha($_POST['g-recaptcha-response']);
    if(!($google_response['success'])){
        $error = 'Ungueltige Anfrage bitte versuche es erneut (Captcha)';
    }
	
	if(empty($error)){
        if (isset($_POST['title']) && !empty($_POST['title'])) {
            if (isset($_POST['category']) && !empty($_POST['category'])) {
                if (isset($_POST['priority']) && !empty($_POST['priority'])) {
                    if (isset($_POST['message']) && !empty($_POST['message'])) {

                        $DB_SQL = $db;
                        $SQL = $DB_SQL->prepare("INSERT INTO `tickets`(`user_id`, `categorie`, `priority`,`produkt`, `title`, `state`, `last_msg`) VALUES (:user_id,:categorie,:priority,:produkt,:title,:status,:last_msg)");
                        $SQL->execute(array(":user_id" => $userid, ":categorie" => $_POST['category'], ":priority" => $_POST['priority'],":produkt" => $_POST['produkt'], ":title" => $_POST['title'], ":status" => 'OPEN', ":last_msg" => 'CUSTOMER'));
                        $ticket_id = $DB_SQL->lastInsertId();

                        $SQL = $db->prepare("INSERT INTO `ticket_message`(`ticket_id`, `writer_id`, `message`) VALUES (:ticket_id,:writer_id,:message)");
                        $SQL->execute(array(":ticket_id" => $ticket_id, ":writer_id" => $userid, ":message" => $_POST['message']));

                        echo sendSuccess('Deine Anfrage wurde an das Team übermittelt');
                    }
                }
            }
        }
	} else {
        echo sendError($error);
    }
}

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
<section class="content">
      <div class="container-fluid">
<div class="row">
            <div class="col-md-12">
                <div class="card card-body">
                    <form method="post">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Titel:</label>
                                <input name="title" class="form-control" required="required" style="width: 100%;">
                            </div>

                            <div class="col-md-12">
                                <br>
                            </div>

                            <div class="col-md-6">
                                <label>Kategorie:</label>
                                <select name="category" class="form-control"  required="required">
                                    <option value="ALLGEMEIN">Allgemein</option>
                                    <option value="TECHNIK">Technik</option>
                                    <option value="BUCHHALTUNG">Buchhaltung</option>
                                    <option value="PARTNER">Partnerschaft</option>
                                    <option value="PERSONAL">Personal</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Priorität:</label>
                                <select name="priority" class="form-control"  required="required">
                                    <option value="NORMAL">Normal</option>
                                    <option value="MITTEL">Mittel</option>
                                    <option value="HOCH">Hoch (Ein Missbrauch wird mit 40,00€ netto berechnet.)</option>
                                </select>
                            </div>
							<div class="col-md-12">
                                <label>Produkt:</label>
                                <select name="produkt" class="form-control"  >
                                    <option value="">Kein Produkt / Allgemein</option>
								 <?php
                                $SQL = $db -> prepare("SELECT * FROM `teaspeaks` WHERE `user_id` = :user_id AND `deleted_at` IS NULL AND UPPER(`state`) = 'ACTIVE'");
                                $SQL->execute(array(":user_id" => $userid));
                                if ($SQL->rowCount() != 0) {
                                while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){
                                ?>
								<option value="Teaspeak #<?= $row['id']; ?>">Teaspeak #<?= $row['id']; ?></option>
							<?php } } ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <label>Beschreibung:</label>
                                <textarea style="width: 100%;" class="form-control" name="message" rows="5" required="required"></textarea>
                            </div>

                            <div class="col-md-12">
                                <br>
                            </div>
							<div class="col-md-12">
<div class="g-recaptcha" data-sitekey="<?= $helper->grecaptchaSiteKey(); ?>"></div>
</div>
<div class="col-md-12">
                                <br>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-block btn-primary" name="createTicket">Ticket erstellen</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

    </div>
</div>