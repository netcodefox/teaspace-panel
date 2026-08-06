<?php

include './vendor/autoload.php';
include 'app/controller/config.php';
include 'app/functions/autoload.php';


ob_start();
session_start();

if(isset($_POST['id'])){

    $transactionId = $_POST['id'];
    //$status = $_POST['status'];

    $SQL = $db->prepare("SELECT * FROM `transactions` WHERE `tid` = :tid");
    $SQL->execute(array(":tid" => $transactionId));
    if($SQL->rowCount() == 1) {

        include 'app/manager/customer/payment/mollie/initialize.php';
        include 'app/manager/customer/payment/mollie/api_manager.php';

        $status = $mollie_payment->isPayed($transactionId);

        if ($status == 'paid' || $status == 'authorized') {

            $state = 'success';

            $updatePayment = $db->prepare("UPDATE `transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));

            $SQLGetInfos = $db->prepare("SELECT * FROM `transactions` WHERE `tid` = :tid");
            $SQLGetInfos->execute(array(":tid" => $transactionId));
            $infos = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $SQLGetInfos = $db->prepare("SELECT * FROM `users` WHERE `id` = :user_id");
            $SQLGetInfos->execute(array(":user_id" => $infos['user_id']));
            $userInfo = $SQLGetInfos->fetch(PDO::FETCH_ASSOC);

            $percent = '15';
            $userGet = round($infos['amount'] / 100 * (100 - $percent),2);

            $user->addMoney($userGet, $infos['user_id']);
            $user->addTransaction($infos['user_id'], $userGet,'Guthabenaufladung mit Mollie');

        } elseif ($status == 'expired') {

            $state = 'abort';

            $updatePayment = $db->prepare("UPDATE `transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));


        } elseif ($status == 'failed') {

            $state = 'abort';

            $updatePayment = $db->prepare("UPDATE `transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));

        } elseif ($status == 'canceled') {

            $state = 'abort';

            $updatePayment = $db->prepare("UPDATE `transactions` SET `state` = :state WHERE `tid` = :tid");
            $updatePayment->execute(array(":state" => $state, ":tid" => $transactionId));

        }

    }

} else {
    echo 'Something went wrong...';
}
