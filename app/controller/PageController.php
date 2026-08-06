<?php

$sessionToken = $_COOKIE['session_token'] ?? null;

if (!empty($sessionToken) && $user->sessionExists($sessionToken)) {
    /*
     * set static values
     */

    $username = $user->getDataBySession($sessionToken,'username');
    $rang = $user->getDataBySession($sessionToken,'role');
    $mail = $user->getDataBySession($sessionToken,'email');
    $amount = $user->getDataBySession($sessionToken,'amount');
    $street = $user->getDataBySession($sessionToken,'street');
    $city = $user->getDataBySession($sessionToken,'city');
    $postcode = $user->getDataBySession($sessionToken,'postcode');
    $userid = $user->getDataBySession($sessionToken,'id');
    $affiliate_id = $user->getDataBySession($sessionToken,'affiliate_id');
	$suppin = $user->getDataBySession($sessionToken,'support_pin');
	

    $user_addr = $user->getDataBySession($sessionToken,'user_addr');
    if(is_null($user_addr)){
        $SQL = $db->prepare("UPDATE `users` SET `user_addr` = :user_addr WHERE `id` = :id");
        $SQL->execute(array(":user_addr" => $user->getIP(), ":id" => $userid));
        $user_addr = $user->getIP();
    }
    if($user->getIP() != $user_addr){
        if(isset($_COOKIE['old_session_token'])){
            if($user->isInTeam($_COOKIE['old_session_token'])){

            } else {
                $_SESSION['info_msg'] = 'Something went wrong';
                setcookie('session_token', '', time() - 3600, '/'); header('Location: '.$helper->url().'login');
                die();
            }
        } else {
            $_SESSION['info_msg'] = 'Something went wrong';
            setcookie('session_token', '', time() - 3600, '/'); header('Location: '.$helper->url().'login');
            die();
        }
    }

}

if (strpos($currPage,'back_') !== false || strpos($currPage,'team_') !== false) {

    /*
     * check if user is logged in
     */
    if(!($user->loggedIn($sessionToken))){
        die(header('Location: '.$helper->url().'login'));
    }

    /*
     * check if user is on team page and is in team
     */
    if(strpos($currPage,'team_') !== false) {
        if(!$user->isInTeam($sessionToken)){
            die(header('Location: '.$url.'dashboard'));
        }
    }

    /*
     * check if user is on admin page and is admin
     */
    if(strpos($currPage,'_admin') !== false) {
        if(!$user->isAdmin($sessionToken)){
            die(header('Location: '.$url.'team/tickets'));
        }
    }

}

$currPageName = explode('_',$currPage)[1] ?? $currPage;

if(strpos($currPage,'system_') !== false) {

} else {
    if (strpos($currPage, 'back_') !== false || strpos($currPage, 'team_') !== false) {
        include 'resources/additional/BACK/head.php';
        include 'resources/additional/BACK/sidebar.php';
        include 'resources/additional/BACK/header.php';
    } elseif (strpos($currPage, 'auth_') !== false) {
        include 'resources/additional/AUTH/head.php';
    } else {
        include 'resources/additional/head.php';
        include 'resources/additional/header.php';
    }
}

/*
 * manage cookies
 */
include 'app/notifications/sendAlert.php';
if(isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])){
    echo sendError($_SESSION['error_msg']);
    $_SESSION['error_msg'] = '';
    unset($_SESSION['error_msg']);
}

if(isset($_SESSION['info_msg']) && !empty($_SESSION['info_msg'])){
    echo sendInfo($_SESSION['info_msg']);
    $_SESSION['info_msg'] = '';
    unset($_SESSION['info_msg']);
}

if(isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])){
    echo sendSuccess($_SESSION['success_msg']);
    $_SESSION['success_msg'] = '';
    unset($_SESSION['success_msg']);
}
