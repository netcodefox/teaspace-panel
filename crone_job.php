<?php
include './vendor/autoload.php';
include 'app/controller/config.php';
include 'app/functions/autoload.php';
include 'app/notifications/sendMail.php';

if ($_GET['key'] != $cron_key) {
    die('no auth');
}

//Time now
$date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
$dateTimeNow = $date->format('Y-m-d H:i:s');

//Time minus 3 days
$dateMinus = new DateTime('now', new DateTimeZone('Europe/Berlin'));
$dateMinus->modify('-3 day');
$dateTimeMinus3Days = $dateMinus->format('Y-m-d H:i:s');


/* ======================================================================================================================================== */
$teaspeakEmail = $db->prepare("SELECT * FROM `teaspeaks` WHERE `deleted_at` IS NULL");
$teaspeakEmail->execute();
if ($teaspeakEmail->rowCount() != 0) {
    while ($row = $teaspeakEmail->fetch(PDO::FETCH_ASSOC)) {

        $username = $user->getDataById($row['user_id'],'username');

        if($user->getDataById($row['user_id'],'product_notify') == 1) {
            $diffInDays = $site->getDiffInDays($row['expire_at']);
            if ($diffInDays != $row['days']) {

                $product_name = 'Teaspeak';
                if ($diffInDays == 10) {
                    include 'app/notifications/mail_templates/soon_suspend.php';
                    $mail_state = sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $mailContent, $mailSubject, $emailAltBody);
                }

                if ($diffInDays == 5) {
                    include 'app/notifications/mail_templates/soon_suspend.php';
                    $mail_state = sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $mailContent, $mailSubject, $emailAltBody);
                }

                if ($diffInDays == 1) {
                    include 'app/notifications/mail_templates/soon_suspend.php';
                    $mail_state = sendMail($user->getDataById($row['user_id'], 'email'), $user->getDataById($row['user_id'], 'username'), $mailContent, $mailSubject, $emailAltBody);
                }

                $SQL = $db->prepare("UPDATE `teaspeaks` SET `days` = :days WHERE `id` = :id");
                $SQL->execute(array(":days" => $diffInDays, ":id" => $row['id']));
            }
        }

    }
}

$teaspeakDB = $db->prepare("SELECT * FROM `teaspeaks` WHERE `expire_at` < :dateTimeNow AND `state` = 'ACTIVE'");
$teaspeakDB->execute(array(":dateTimeNow" => $dateTimeNow));
if ($teaspeakDB->rowCount() != 0) {
    while ($row = $teaspeakDB->fetch(PDO::FETCH_ASSOC)) {

        $SQL = $db->prepare("UPDATE `teaspeaks` SET `state`='SUSPENDED' WHERE `id` = :id");
        $SQL->execute(array(":id" => $row['id']));

        try {
            $tea->stopServer($row['node_id'], $row['teaspeak_port'], $row['sid']);
        } catch (Exception $e){

        }

        echo 'Suspended Teaspeak #'.$row['id'];
        die();
    }
}

$teaspeakSuspendedDB = $db->prepare("SELECT * FROM `teaspeaks` WHERE `expire_at` < :dateTimeMinusDays AND `state` = 'SUSPENDED'");
$teaspeakSuspendedDB->execute(array(":dateTimeMinusDays" => $dateTimeMinus3Days));
if ($teaspeakSuspendedDB->rowCount() != 0) {
    while ($row = $teaspeakSuspendedDB->fetch(PDO::FETCH_ASSOC)) {

        $SQL = $db->prepare("UPDATE `teaspeaks` SET `state`='DELETED', `deleted_at` = :deleted_at WHERE `id` = :id");
        $SQL->execute(array(":deleted_at" => $dateTimeNow, ":id" => $row['id']));

        try {
            $tea->deleteServer($row['node_id'], $row['sid']);
        } catch (Exception $e){

        }

        echo 'Deleted Teaspeak #'.$row['id'];
        die();

    }
}
/* ======================================================================================================================================== */

===================================================================================================== */


die('<br>done');