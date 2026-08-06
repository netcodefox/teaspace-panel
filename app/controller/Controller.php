<?php

global $url;

abstract class Controller
{

    public function db()
    {
        include __DIR__ . '/config.php';

        try {
            $db = new PDO(
                'mysql:host=' . $db_host . ';charset=utf8mb4;dbname=' . $db_name,
                $db_username,
                $db_password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            $hint = '';
            if (str_contains($e->getMessage(), '1698') || str_contains($e->getMessage(), 'Access denied')) {
                $hint = ' Auf MariaDB/Plesk bitte keinen System-User „root“ nutzen – im Panel eine eigene Datenbank + DB-User anlegen und in config.php bzw. im Installer eintragen. Bei Fehler 1698 oft „127.0.0.1“ statt „localhost“ testen.';
            }
            throw new PDOException(
                'Datenbankverbindung fehlgeschlagen: ' . $e->getMessage() . '.' . $hint,
                (int) $e->getCode(),
                $e
            );
        }

        return $db;
    }

    public function url()
    {
        include __DIR__ . '/config.php';

        return $url;
    }

    public function cdnUrl()
    {
        include __DIR__ . '/config.php';

        return $cdnUrl;
    }

    public function siteName()
    {
        include __DIR__ . '/config.php';

        return $siteName;
    }

    public function grecaptchaSiteKey()
    {
        include __DIR__ . '/config.php';

        return $grecaptchaSiteKey;
    }

    public function grecaptchaSecret()
    {
        include __DIR__ . '/config.php';

        return $grecaptchaSecret;
    }

    public function getDateTime()
    {
        $date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
        $datetime = $date->format('Y-m-d H:i:s');

        return $datetime;
    }

    public function nl2br2($string)
    {
        $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
        return $string;
    }

    public function setCookie($name, $variable, $time = '777600', $path = '/', $domain = null, $secure = 0)
    {
        setcookie($name, $variable, time() + (int) $time, $path, $domain, (bool) $secure);
    }

}
