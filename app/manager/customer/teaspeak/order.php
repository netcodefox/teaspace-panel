<?php

if(isset($_POST['order'])){

    $error = null;

    if(!$user->sessionExists($_COOKIE['session_token'])){
        $error = 'Bitte logge dich erst ein';
    }

    if(empty($_POST['slots'])){
        $error = 'Bitte wähle Slots aus';
    }

    if(!is_numeric($_POST['slots'])){
        $error = 'Bitte wähle Slots aus (Zahl)';
    }

	if($site->userHaveValidProfile($userid) == FALSE) {
		 $error = 'Bitte fülle erst dein Profil aus.';
	} 

	
    if($_POST['slots'] > 1000){
        $error = 'Bitte wähle weniger als 1000 Slots';
    }

    if($_POST['slots'] < 10){
        $error = 'Bitte wähle mehr als 10 Slots';
    }

    if(empty($_POST['duration'])){
        $error = 'Bitte wähle eine Laufzeit aus';
    }

    if($validate->duration($_POST['duration']) != true){
        $error = 'Bitte gebe eine gültige Laufzeit an';
    }

    $price = ($_POST['slots'] * $site->getProductPrice('TEASPEAK')) * ($_POST['duration'] / 30);
    $db_price = $site->getProductPrice('TEASPEAK');

    $price = number_format($price,2);

    if($price == 0){
        $error = 'Ungültige Anfrage bitte versuche es erneut (1001)';
    }

    if($amount < $price){
        $error = 'Du hast leider nicht genügend Guthaben';
    }

    $node_id = '1';
    $port = rand(9000, 12000);
    if(!$site->isTS3PortAviable($node_id, $port)){
        $error = 'Bitte versuche es erneut';
    }

    if(empty($error)){

        $getNodeInfos = $db->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
        $getNodeInfos->execute(array(":id" => $node_id));
        $nodeInfos = $getNodeInfos->fetch(PDO::FETCH_ASSOC);

        $sid_converter = json_encode($tea->createServer($node_id, $_POST['slots'], $port));
        $get_sid = json_decode($sid_converter);
        $sid = $get_sid->sid;

        $date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
        $date->modify('+' . $_POST['duration'] . ' day');
        $new_date = $date->format('Y-m-d H:i:s');

        $SQLInsertBot = $db->prepare("INSERT INTO `teaspeaks`(`slots`, `user_id`, `node_id`, `teaspeak_ip`, `teaspeak_port`, `sid`, `expire_at`, `price`) VALUES (:slots,:user_id,:node_id,:teaspeak_ip,:teaspeak_port,:sid,:expire_at,:price)");
        $SQLInsertBot->execute(array(":slots" => $_POST['slots'], ":user_id" => $userid, ":node_id" => $node_id, ":teaspeak_ip" => $nodeInfos['login_ip'], ":teaspeak_port" => $port, ":sid" => $sid, ":expire_at" => $new_date, ":price" => $db_price));

        $user->removeMoney($price, $userid);
        $user->addTransaction($userid,'-'.$price,'Teaspeak Server Bestellung');

        $_SESSION['success_msg'] = 'Vielen Dank! Dein Teaspeak Server wird nun eingerichtet';

        header('Location: '.$helper->url().'dashboard');
    } else {
        echo sendError($error);
    }

}