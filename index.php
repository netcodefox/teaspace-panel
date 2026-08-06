<?php

ob_start();
session_start();

/*
 * Installer gate – redirect until setup is complete
 */
$installLock = __DIR__ . '/install/install.lock';
if (!is_file($installLock)) {
    header('Location: install/');
    exit;
}

$date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
$datetime = $date->format('Y-m-d H:i:s');

/*
 * composer
 */
include_once './vendor/autoload.php';

/*
 * config
 */
include 'app/controller/config.php';
include_once 'app/functions/autoload.php';

include_once 'app/notifications/sendMail.php';

/*
 * page manager
 */
$resources = 'resources/';
$sites = $resources.'sites/';
$auth = $resources.'auth/';
$customer = $resources.'customer/';
$team = $resources.'team/';

$page = isset($_GET['page']) ? $helper->protect($_GET['page']) : null;
$currPage = 'front_404';

/**
 * Safe include – fall back to 404 if a resource file is missing
 */
$includePage = static function (string $file) use ($sites): void {
    if (is_file($file)) {
        include $file;
        return;
    }
    include $sites . '404.php';
};

if (isset($_GET['page'])) {
    switch ($page) {

        default: $includePage($sites."404.php");  break;

        //auth
        case "auth_login": $includePage($auth."login.php");  break;
        case "auth_register": $includePage($auth."register.php"); break;
        case "auth_logout": setcookie('session_token', '', time() - 3600, '/'); header('Location: '.$helper->url().'login'); exit;
        case "auth_activate": $includePage($auth."activate.php"); break;
        case "auth_forgot_password": $includePage($auth."forgot_password.php"); break;

        //index
        case "main_page": $includePage($sites."main_page.php");  break;
        case "dashboard": $includePage($customer."dashboard.php");  break;

        //webspace
        case "webspace_order": $includePage($customer."webspace/order.php");  break;
        case "webspace": $includePage($customer."webspace/index.php");  break;
        case "webspace_manage": $includePage($customer."webspace/manage.php");  break;
        case "webspace_renew": $includePage($customer."webspace/renew.php");  break;

        //teamspeak
        case "teamspeak_order": $includePage($customer."teamspeak/order.php");  break;
        case "teamspeak": $includePage($customer."teamspeak/index.php");  break;
        case "teamspeak_manage": $includePage($customer."teamspeak/manage.php");  break;
        case "teamspeak_renew": $includePage($customer."teamspeak/renew.php");  break;
        case "teamspeak_reconfigure": $includePage($customer."teamspeak/reconfigure.php");  break;

        //teaspeak
        case "teaspeak_order": $includePage($customer."teaspeak/order.php");  break;
        case "teaspeak": $includePage($customer."teaspeak/index.php");  break;
        case "teaspeak_manage": $includePage($customer."teaspeak/manage.php");  break;
        case "teaspeak_renew": $includePage($customer."teaspeak/renew.php");  break;
        case "teaspeak_reconfigure": $includePage($customer."teaspeak/reconfigure.php");  break;

        //service
        case "service_order": $includePage($customer."service/order.php");  break;
        case "service": $includePage($customer."service/index.php");  break;
        case "service_manage": $includePage($customer."service/manage.php");  break;
        case "service_renew": $includePage($customer."service/renew.php");  break;

        //ts3audiobot
        case "ts3audiobot_order": $includePage($customer."ts3audiobot/order.php");  break;
        case "ts3audiobot": $includePage($customer."ts3audiobot/index.php");  break;
        case "ts3audiobot_manage": $includePage($customer."ts3audiobot/manage.php");  break;
        case "ts3audiobot_renew": $includePage($customer."ts3audiobot/renew.php");  break;

        //
        case "impressum": $includePage($sites."impressum.php");  break;
        case "datenschutz": $includePage($sites."datenschutz.php");  break;
        case "agb": $includePage($sites."agb.php");  break;
		case "widerruf": $includePage($sites."widerruf.php");  break;
		case "wartung": $includePage($sites."wartung.php");  break;
		case "vlst": $includePage($sites."lst.php");  break;
		case "contact": $includePage($sites."contact.php");  break;

        //accounting
        case "charge": $includePage($customer."accounting/charge.php");  break;
        case "transactions": $includePage($customer."accounting/transactions.php");  break;
		case "invoice": $includePage($customer. "accounting/invoice.php"); break;

        //tickets
        case "tickets": $includePage($customer."support/tickets.php");  break;
        case "ticket_create": $includePage($customer."support/create.php");  break;
        case "ticket": $includePage($customer."support/ticket.php");  break;
        case "support": $includePage($customer."support/ticket.php");  break;

        //tickets
        case "profile": $includePage($customer."profile.php");  break;
        case "donate": $includePage($customer."donate.php");  break;
        case "affiliate": $includePage($customer."affiliate.php");  break;
        case "a": $includePage($customer."affiliate/index.php");  break;

        //team
        case "team_tickets": $includePage($team."tickets.php");  break;
        case "team_ticket": $includePage($team."ticket.php");  break;
        case "team_users": $includePage($team."users.php");  break;
        case "team_user": $includePage($team."user.php");  break;
        case "team_transactions": $includePage($team."transactions.php");  break;
        case "team_login_back": $includePage($team."login_back.php");  break;
        case "team_service": $includePage($team."service/index.php");  break;
        case "team_service_manage": $includePage($team."service/manage.php");  break;
        case "team_news": $includePage($team."news.php");  break;
        case "team_dashboard": $includePage($team."dashboard.php");  break;
        case "team_gutscheine": $includePage($team."gutscheine.php");  break;
        case "team_emailblack": $includePage($team."emailblack.php");  break;
		case "team_nodes": $includePage($team."nodes.php");  break;
        case "team_node": $includePage($team."node.php");  break;
		case "team_bots": $includePage($team."bots.php");  break;
        case "team_bot": $includePage($team."bot.php");  break;
		case "team_invoice": $includePage($team. "invoice.php"); break;
		case "team_streams": $includePage($team."streams.php");  break;
    }

    if (strpos((string) $currPage, 'system_') !== false) {

    } else {
        if (strpos((string) $currPage, 'back_') !== false || strpos((string) $currPage, 'team_') !== false) {
            include 'resources/additional/BACK/footer.php';
        } elseif (strpos((string) $currPage, 'auth_') !== false) {
            include 'resources/additional/AUTH/footer.php';
        } else {
            include 'resources/additional/footer.php';
        }
    }

} else {
    die('please enable .htaccess on your server');
}
