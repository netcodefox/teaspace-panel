<?php
include './vendor/autoload.php';
include 'app/controller/config.php';
include 'app/functions/autoload.php';

require 'app/manager/customer/payment/functions.php';

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

if (isset($_POST["txn_id"]) && isset($_POST["txn_type"])) {

    $data = [
        'item_name' => $_POST['item_name'],
        'item_number' => $_POST['item_number'],
        'payment_status' => $_POST['payment_status'],
        'payment_amount' => $_POST['mc_gross'],
        'payment_currency' => $_POST['mc_currency'],
        'txn_id' => $_POST['txn_id'],
        'receiver_email' => $_POST['receiver_email'],
        'payer_email' => $_POST['payer_email'],
        'custom' => $_POST['custom'],
    ];

    if (verifyTransaction($_POST)){
        $SQL = $db->prepare("SELECT * FROM `transactions` WHERE `tid` = :tid");
        $SQL->execute(array(":tid" => $data['txn_id']));
        if ($SQL->rowCount() == 0) {
            $user_id = $user->getDataBySession($data['custom'],'id');
            $money = $data['payment_amount'];

            $site->addTransaction($user_id,'paypal','success', $data['payment_amount'],'Guthabenaufladung mit Paypal', $data['txn_id']);
            $user->addMoney($money, $user_id);

            if($user->getDataById($user_id,'transaction_notify') == 1) {
                include 'app/notifications/mail_templates/transaction/new_amount.php';
                sendMail($user->getDataById($user_id, 'email'), $user->getDataById($user_id, 'username'), $mailContent, $mailSubject);
            }

            /*
            * Affiliate System
            */
            $SQL = $db->prepare("SELECT * FROM `affiliates` WHERE `affiliated_id` = :affiliated_id AND `state` = 'pending'");
            $SQL->execute(array(":affiliated_id" => $user_id));
            if ($SQL->rowCount() == 1) {
                $details = $SQL->fetch(PDO::FETCH_ASSOC);

                $new_amount = $details['amount'] + $money;
                if($new_amount >= 5){
                    $affiliater_id = $user->getDataByAffiliateId($details['affiliate_id'],'id');
                    $user->addTransaction($affiliater_id,'1.00','Affiliate Zahlung');
                    $user->addMoney(1, $affiliater_id);

                    $SQL = $db->prepare("UPDATE `affiliates` SET `amount` = :amount, `state` = :state WHERE `affiliated_id` = :affiliated_id");
                    $SQL->execute(array(":money_added" => $new_amount, ":state" => 'done', ":affiliated_id" => $user_id));
                } else {
                    $SQL = $db->prepare("UPDATE `affiliates` SET `amount` = :amount WHERE `affiliated_id` = :affiliated_id");
                    $SQL->execute(array(":money_added" => $new_amount, ":affiliated_id" => $user_id));
                }

            }
        }
    }
} else {
    die();
}