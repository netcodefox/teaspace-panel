<?php

$id = $helper->protect($_GET['id']);

$SQLGetServerInfos = $db->prepare("SELECT * FROM `teaspeaks` WHERE `id` = :id");
$SQLGetServerInfos -> execute(array(":id" => $id));
$serverInfos = $SQLGetServerInfos -> fetch(PDO::FETCH_ASSOC);

if(!($serverInfos['deleted_at'] == NULL)){
    header('Location: '.$helper->url().'teaspeak/order');
}

if($userid != $serverInfos['user_id']){
    die(header('Location: '.$helper->url().'teaspeak'));
}

if(isset($_POST['reconfigure'])){

    $error = null;

    if(empty($_POST['slots'])){
        $error = 'Bitte wähle Slots aus';
    }

    if(!is_numeric($_POST['slots'])){
        $error = 'Bitte wähle Slots aus (Zahl)';
    }

    if($_POST['slots'] > 1000){
        $error = 'Bitte wähle weniger als 1000 Slots';
    }

    if($_POST['slots'] < 2){
        $error = 'Bitte wähle mehr als 2 Slots';
    }

    $sum = ($_POST['slots'] * $site->getProductPrice('TEASPEAK')) - ($serverInfos['slots'] * $site->getProductPrice('TEASPEAK'));
    $price = round($sum * $site->getDiffInDays($serverInfos['expire_at']) / 30,2);

    if($site->getDiffInDays($serverInfos['expire_at']) < 5){
        $error = 'Bitte verlängere zuerst deinen Dienst';
    }

    if($amount < $price){
        $error = 'Du hast leider nicht genügend Guthaben';
    }

    if($_POST['slots'] == $serverInfos['slots']){
        $error = 'Bitte verändere die Slotanzahl';
    }

    try{
        $SQL = $db->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
        $SQL -> execute(array(":id" => $serverInfos['node_id']));
        $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);
        $uri = "serverquery://".$nodeInfos['login_name'].":".$nodeInfos['login_passwort']."@".$nodeInfos['login_ip'].":".$nodeInfos['login_port']."/?server_port=".$serverInfos['teaspeak_port'];
        $tea_query = \PlanetTeamSpeak\TeamSpeak3Framework\TeamSpeak3::factory($uri);

        $serverStatus = 'ONLINE';
    }catch(\PlanetTeamSpeak\TeamSpeak3Framework\Exception\TeamSpeak3Exception $e){
        $serverStatus = 'OFFLINE';
    }

    if($serverStatus == 'OFFLINE'){
        $error = 'Dein Server muss gestartet sein';
    }

    if(empty($error)){

//        $date = new DateTime($serverInfos['expire_at'], new DateTimeZone('Europe/Berlin'));
//        $date->modify('+' . $_POST['duration'] . ' day');
//        $expire_at = $date->format('Y-m-d H:i:s');

//        $SQLGetServerInfos = $db->prepare("UPDATE `teaspeaks` SET `expire_at` = :expire_at, `slots` = :slots WHERE `id` = :id");
//        $SQLGetServerInfos -> execute(array(":expire_at" => $expire_at, ":slots" => $_POST['slots'], ":id" => $id));

        $SQLGetServerInfos = $db->prepare("UPDATE `teaspeaks` SET `slots` = :slots WHERE `id` = :id");
        $SQLGetServerInfos -> execute(array(":slots" => $_POST['slots'], ":id" => $id));

        $tea->changeSlots($serverInfos['node_id'], $serverInfos['teaspeak_port'], $_POST['slots']);

        if($price > 0){
            $user->removeMoney($price, $userid);
            $user->addTransaction($userid,'-'.$price,'Teaspeak Server Upgrade');
            echo sendSuccess('Dein Teaspeak Server wurde geupgraded');
        } else {
            $price = str_replace('-','', $price);
            $user->addMoney($price, $userid);
            $user->addTransaction($userid, $price,'Teaspeak Server Downgrade');
            echo sendSuccess('Dein Teaspeak Server wurde downgraded');
        }

        header('refresh:3;url='.$helper->url().'teaspeak/manage/'.$serverInfos['id']);

    } else {
        echo sendError($error);
    }

}