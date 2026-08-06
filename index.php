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

try {
    include_once 'app/functions/autoload.php';
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8"><title>Datenbankfehler</title></head><body style="font-family:sans-serif;max-width:640px;margin:3rem auto;line-height:1.5">';
    echo '<h1>Datenbankverbindung fehlgeschlagen</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p>Bitte <code>app/controller/config.php</code> prüfen oder <code>install/install.lock</code> löschen und den Installer unter <a href="install/">/install/</a> erneut ausführen.</p>';
    echo '</body></html>';
    exit;
}

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
$pageFile = null;

if (isset($_GET['page'])) {
    switch ($page) {

        default: $pageFile = $sites."404.php";  break;

        //auth
        case "auth_login": $pageFile = $auth."login.php";  break;
        case "auth_register": $pageFile = $auth."register.php"; break;
        case "auth_logout": setcookie('session_token', '', time() - 3600, '/'); header('Location: '.$helper->url().'login'); exit;
        case "auth_activate": $pageFile = $auth."activate.php"; break;
        case "auth_forgot_password": $pageFile = $auth."forgot_password.php"; break;

        //index
        case "main_page": $pageFile = $sites."main_page.php";  break;
        case "dashboard": $pageFile = $customer."dashboard.php";  break;

        //webspace
        case "webspace_order": $pageFile = $customer."webspace/order.php";  break;
        case "webspace": $pageFile = $customer."webspace/index.php";  break;
        case "webspace_manage": $pageFile = $customer."webspace/manage.php";  break;
        case "webspace_renew": $pageFile = $customer."webspace/renew.php";  break;

        //teamspeak
        case "teamspeak_order": $pageFile = $customer."teamspeak/order.php";  break;
        case "teamspeak": $pageFile = $customer."teamspeak/index.php";  break;
        case "teamspeak_manage": $pageFile = $customer."teamspeak/manage.php";  break;
        case "teamspeak_renew": $pageFile = $customer."teamspeak/renew.php";  break;
        case "teamspeak_reconfigure": $pageFile = $customer."teamspeak/reconfigure.php";  break;

        //teaspeak
        case "teaspeak_order": $pageFile = $customer."teaspeak/order.php";  break;
        case "teaspeak": $pageFile = $customer."teaspeak/index.php";  break;
        case "teaspeak_manage": $pageFile = $customer."teaspeak/manage.php";  break;
        case "teaspeak_renew": $pageFile = $customer."teaspeak/renew.php";  break;
        case "teaspeak_reconfigure": $pageFile = $customer."teaspeak/reconfigure.php";  break;

        //service
        case "service_order": $pageFile = $customer."service/order.php";  break;
        case "service": $pageFile = $customer."service/index.php";  break;
        case "service_manage": $pageFile = $customer."service/manage.php";  break;
        case "service_renew": $pageFile = $customer."service/renew.php";  break;

        //ts3audiobot
        case "ts3audiobot_order": $pageFile = $customer."ts3audiobot/order.php";  break;
        case "ts3audiobot": $pageFile = $customer."ts3audiobot/index.php";  break;
        case "ts3audiobot_manage": $pageFile = $customer."ts3audiobot/manage.php";  break;
        case "ts3audiobot_renew": $pageFile = $customer."ts3audiobot/renew.php";  break;

        //
        case "impressum": $pageFile = $sites."impressum.php";  break;
        case "datenschutz": $pageFile = $sites."datenschutz.php";  break;
        case "agb": $pageFile = $sites."agb.php";  break;
		case "widerruf": $pageFile = $sites."widerruf.php";  break;
		case "wartung": $pageFile = $sites."wartung.php";  break;
		case "vlst": $pageFile = $sites."lst.php";  break;
		case "contact": $pageFile = $sites."contact.php";  break;

        //accounting
        case "charge": $pageFile = $customer."accounting/charge.php";  break;
        case "transactions": $pageFile = $customer."accounting/transactions.php";  break;
		case "invoice": $pageFile = $customer. "accounting/invoice.php"; break;

        //tickets
        case "tickets": $pageFile = $customer."support/tickets.php";  break;
        case "ticket_create": $pageFile = $customer."support/create.php";  break;
        case "ticket": $pageFile = $customer."support/ticket.php";  break;
        case "support": $pageFile = $customer."support/ticket.php";  break;

        //tickets
        case "profile": $pageFile = $customer."profile.php";  break;
        case "donate": $pageFile = $customer."donate.php";  break;
        case "affiliate": $pageFile = $customer."affiliate.php";  break;
        case "a": $pageFile = $customer."affiliate/index.php";  break;

        //team
        case "team_tickets": $pageFile = $team."tickets.php";  break;
        case "team_ticket": $pageFile = $team."ticket.php";  break;
        case "team_users": $pageFile = $team."users.php";  break;
        case "team_user": $pageFile = $team."user.php";  break;
        case "team_transactions": $pageFile = $team."transactions.php";  break;
        case "team_login_back": $pageFile = $team."login_back.php";  break;
        case "team_service": $pageFile = $team."service/index.php";  break;
        case "team_service_manage": $pageFile = $team."service/manage.php";  break;
        case "team_news": $pageFile = $team."news.php";  break;
        case "team_dashboard": $pageFile = $team."dashboard.php";  break;
        case "team_gutscheine": $pageFile = $team."gutscheine.php";  break;
        case "team_emailblack": $pageFile = $team."emailblack.php";  break;
		case "team_nodes": $pageFile = $team."nodes.php";  break;
        case "team_node": $pageFile = $team."node.php";  break;
		case "team_bots": $pageFile = $team."bots.php";  break;
        case "team_bot": $pageFile = $team."bot.php";  break;
		case "team_invoice": $pageFile = $team. "invoice.php"; break;
		case "team_streams": $pageFile = $team."streams.php";  break;
        case "team_teaspeak_hosts": $pageFile = $team."teaspeak_hosts.php"; break;
        case "team_teaspeak_host": $pageFile = $team."teaspeak_host.php"; break;
        case "team_settings": $pageFile = $team."settings.php"; break;
    }

    // Include in global scope so $user / $helper / $db stay available
    if (!is_string($pageFile) || !is_file($pageFile)) {
        $pageFile = $sites . '404.php';
    }
    include $pageFile;

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
