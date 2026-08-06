<?php
$currPage = 'team_Benutzerverwaltung_team';
include 'app/controller/PageController.php';

$id = $helper->protect($_GET['id']);
$SQL = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
$SQL->execute(array(":id" => $id));
$userInfos = $SQL -> fetch(PDO::FETCH_ASSOC);

if(isset($_POST['updateUser'])){

    $SQL = $db->prepare("UPDATE `users` SET `username` = :username, `email` = :email, `state` = :state, `role` = :role WHERE `id` = :id");
    $SQL->execute(array(":username" => $_POST['username'], ":email" => $_POST['email'], ":state" => $_POST['state'], ":role" => $_POST['role'], ":id" => $id));

    echo sendSuccess('Benutzer wurde gespeichert');
}

if(isset($_POST['changePassword'])){
    $error = null;

    if(empty($_POST['password'])){
        $error = 'Bitte gebe ein Passwort ein';
    }

    if($_POST['password'] != $_POST['password_repeat']){
        $error = 'Die Passwörter sind nicht gleich';
    }

    if(empty($error)){

        $cost = 10;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => $cost]);

        $SQL = $db->prepare("UPDATE `users` SET `password` = :password WHERE `id` = :id");
        $SQL->execute(array(":password" => $password, ":id" => $id));
        echo sendSuccess('Password wurde geändert');

    } else {
        echo sendError($error);
    }
}

if(isset($_POST['addMoney'])){
    $error = null;

    if(empty($_POST['amount'])){
        $error = 'Bitte gebe einen Betrag an';
    }

    if(empty($_POST['desc'])){
        $desc = 'Keine Beschreibung angegeben';
    } else {
        $desc = $_POST['desc'];
    }

    if(empty($error)){

        $user->addMoney($_POST['amount'], $id);
        $user->addTransaction($id, $_POST['amount'], $desc);
        echo sendSuccess('Das Guthaben wurde hinzugefügt');

    } else {
        echo sendError($error);
    }
}

if(isset($_POST['remMoney'])){
    $error = null;

    if(empty($_POST['amount'])){
        $error = 'Bitte gebe einen Betrag an';
    }

    if(empty($_POST['desc'])){
        $desc = 'Keine Beschreibung angegeben';
    } else {
        $desc = $_POST['desc'];
    }

    if(empty($error)){

        $user->removeMoney($_POST['amount'], $id);
        $user->addTransaction($id, '-'.$_POST['amount'], $desc);
        echo sendSuccess('Das Guthaben wurde abgezogen');

    } else {
        echo sendError($error);
    }
}






if(isset($_POST['login'])){
    include 'app/notifications/telegram/log.php';
	if (!empty($telegram_token) && !empty($telegram_chat_id)) {
		sendlog($telegram_chat_id, $username, $_POST['username'], $telegram_token);
	}
	setcookie('old_session_token', $_COOKIE['session_token'],time()+864000,'/');
    setcookie('session_token', $userInfos['session_token'],time()+864000,'/');
	
	
	

    die(header('Location: '.$helper->url()));
}

if(isset($_POST['saveProfile'])){
    $error = null;

    if(empty($error)){

        $SQL = $db->prepare("UPDATE `users` SET `firstname` = :firstname, `lastname` = :lastname, `street` = :street, `postcode` = :postcode, `city` = :city, `country` = :country WHERE `id` = :id");
        $SQL->execute(array(":firstname" => $_POST['firstname'], ":lastname" => $_POST['lastname'], ":street" => $_POST['street'], ":postcode" => $_POST['postcode'], ":city" => $_POST['city'], ":country" => $_POST['country'], ":id" => $id));

        echo sendSuccess('Daten wurden gespeichert');

    } else {
        echo sendError($error);
    }

}

switch($userInfos['state']){
                  case "active":
                    $status = 'Aktiv';
                    break;
                  case "hv":
                    $status = 'Gesperrt';
                    break;
                  case "pending":
                    $status = 'Mail';
                    break;
                }


?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?= $currPageName; ?> ( KD - <?= $userid; ?> )</h1>
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
						<div class="feature-content">
							<form method="post">
								<div class="row">

									<div class="col-md-6">
										<label class="text-dark">Benutzername</label>
										<input  name="username" value="<?= $userInfos['username']; ?>" class="form-control">
									</div>

									<div class="col-md-6">
										<label class="text-dark">E-Mail</label>
										<input  name="email" value="<?= $userInfos['email']; ?>" class="form-control">
									</div>

									<div class="col-md-12"> <br> </div>

									<div class="col-md-6">
										<label class="text-dark">Status</label>
										<select  class="form-control" name="state">
											<option <?php if($userInfos['state'] == 'pending'){ echo 'selected'; } ?> value="pending">Inaktiv</option>
											<option <?php if($userInfos['state'] == 'hv'){ echo 'selected'; } ?> value="hv">Sperren</option>
											<option <?php if($userInfos['state'] == 'active'){ echo 'selected'; } ?> value="active">Aktiv</option>
											
										</select>
									</div>

									<div class="col-md-6">
										<label class="text-dark">Rolle</label>
										<select  class="form-control" name="role">
											<option <?php if($userInfos['role'] == 'customer'){ echo 'selected'; } ?> value="customer">Kunde</option>
											<option <?php if($userInfos['role'] == 'support'){ echo 'selected'; } ?> value="support">Supporter</option>
											<option <?php if($userInfos['role'] == 'p_support'){ echo 'selected'; } ?> value="support">Probe-Supporter</option>
											<option <?php if($userInfos['role'] == 'techniker'){ echo 'selected'; } ?> value="techniker">Techniker</option>
											<option <?php if($userInfos['role'] == 'content'){ echo 'selected'; } ?> value="content">Content</option>
											<option <?php if($userInfos['role'] == 'admin'){ echo 'selected'; } ?> value="admin">Admin</option>
										</select>
									</div>

									<div class="col-md-6">
										<br>
										<button class="btn btn-success" type="submit" name="updateUser">Speichern</button>
										<button class="btn btn-warning" type="submit" name="login">Als Kunde einloggen</button>
									</div>

								</div>
							</form>
						</div>
					</div>

					<br>
					
					
					<div class="card card-body">
						<div class="feature-content">
							
								<form method="post">
								<div class="row">
									<div class="col-md-6">
									<label class="text-dark">Vorname</label>
									<input class="form-control" name="firstname" value="<?= $helper->protect($user->getDataById($id,'firstname')); ?>">
									</div>
									
									<div class="col-md-6">
									<label class="text-dark">Nachname</label>
									<input class="form-control" name="lastname" value="<?= $helper->protect($user->getDataById($id,'lastname')); ?>">
									</div>
									
									<div class="col-md-6">
									<label class="text-dark">Straße, Hausnummer</label>
									<input class="form-control" name="street" value="<?= $helper->protect($user->getDataById($id,'street')); ?>">
									</div>
									
									<div class="col-md-6">
									<label class="text-dark">Postleitzahl</label>
									<input class="form-control" type="number" name="postcode" value="<?= $helper->protect($user->getDataById($id,'postcode')); ?>">
									</div>
									
									<div class="col-md-6">
									<label class="text-dark">Ort</label>
									<input class="form-control" name="city" value="<?= $helper->protect($user->getDataById($id,'city')); ?>">
									</div>
									
									<div class="col-md-6">
									<label class="text-dark">Land</label>
									<input class="form-control" name="country" value="<?= $helper->protect($user->getDataById($id,'country')); ?>">
									</div>
									
									<div class="col-md-6">
									<br>
									<button type="submit" name="saveProfile" class="btn btn-success"> Speichern</button>
									</div>
									</div>
								</form>

							
						</div>
					</div>
					
					<br>

					<div class="card card-body">
						<div class="feature-content">
							<h4 style="margin-top: 0px;">Passwort ändern</h4>
							<form method="post">
								<div class="row">

									<div class="col-md-6">
										<label class="text-dark">Passwort</label>
										<input style="color: black;" name="password" placeholder="Passwort eingeben" class="form-control">
									</div>

									<div class="col-md-6">
										<label class="text-dark">Passwort wiederholen</label>
										<input style="color: black;" name="password_repeat" placeholder="Passwort eingeben" class="form-control">
									</div>

									<div class="col-md-12">
										<br>
										<button class="btn btn-success" type="submit" name="changePassword">Passwort ändern</button>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-md-12"> <br> </div>

				<div class="col-md-6">
					<div class="card card-body">
						<div class="feature-content">
							<h4 style="margin-top: 0px;">Guthaben hinzufügen <?= $user->getDataById($id, 'amount'); ?>€</h4>
							<form method="post">
								<div class="row">

									<div class="col-md-6">
										<label class="text-dark">Betrag</label>
										<input name="amount" class="form-control" placeholder="0.00">
									</div>

									<div class="col-md-6">
										<label class="text-dark">Beschreibung</label>
										<input name="desc" class="form-control" placeholder="Keine Beschreibung angegeben">
									</div>

									<div class="col-md-12">
										<br>
										<?php if($user->isAdmin($_COOKIE['session_token'])){ ?>
										<button class="btn btn-success" type="submit" name="addMoney">Guthaben hinzufügen</button>
										<?php } ?>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="card card-body">
						<div class="feature-content">
							<h4 style="margin-top: 0px;">Guthaben abziehen</h4>
							<form method="post">
								<div class="row">

									<div class="col-md-6">
										<label class="text-dark">Betrag</label>
										<input name="amount" class="form-control" placeholder="0.00">
									</div>

									<div class="col-md-6">
										<label class="text-dark">Beschreibung</label>
										<input name="desc" class="form-control" placeholder="Keine Beschreibung angegeben">
									</div>

									<div class="col-md-12">
										<br>
										<?php if($user->isAdmin($_COOKIE['session_token'])){ ?>
										<button class="btn btn-success" type="submit" name="remMoney">Guthaben abziehen</button>
										<?php } ?>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div>

			</div>
		</div>
    </div>
</div>