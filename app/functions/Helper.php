<?php

$helper = new Helper();

class Helper extends Controller
{

    public function protect($string)
    {
        $protection = htmlspecialchars(trim((string) $string), ENT_QUOTES);
        return $protection;
    }

    public function xssFix($string){
        $string = str_replace('<','', $string);
        $string = str_replace('>','', $string);
        $string = str_replace('´','', $string);
        $string = str_replace('[','(', $string);
        $string = str_replace(']',')', $string);
        $string = str_replace("'",'', $string);

        $string = $this->protect($string);

        return $string;
    }

    public function nl2br2($string)
    {
        $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
        return $string;
    }

    public function formatDate($date)
    {
        $date = new DateTime($date, new DateTimeZone('Europe/Berlin'));
        return $date->format('d.m.Y H:i:s');
    }

    public function formatDateWithoutTime($date)
    {
        $date = new DateTime($date, new DateTimeZone('Europe/Berlin'));
        return $date->format('d.m.Y');
    }

    function generateRandomString($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getSetting($data)
    {
        $this->ensureSettingsSchema();
        $SQL = self::db()->prepare("SELECT * FROM `settings` LIMIT 1");
        $SQL->execute();
        $response = $SQL->fetch(PDO::FETCH_ASSOC);
        if (!$response || !array_key_exists($data, $response)) {
            return null;
        }
        return $response[$data];
    }

    public function ensureSettingsSchema(): void
    {
        static $ready = false;
        if ($ready) {
            return;
        }
        $ready = true;

        try {
            $db = self::db();
            $cols = $db->query("SHOW COLUMNS FROM `settings`")->fetchAll(PDO::FETCH_COLUMN);
            $alters = [];
            if (!in_array('logo_path', $cols, true)) {
                $alters[] = "ADD COLUMN `logo_path` varchar(255) DEFAULT NULL";
            }
            if (!in_array('favicon_path', $cols, true)) {
                $alters[] = "ADD COLUMN `favicon_path` varchar(255) DEFAULT NULL";
            }
            if (!in_array('display_name', $cols, true)) {
                $alters[] = "ADD COLUMN `display_name` varchar(255) DEFAULT NULL";
            }
            if ($alters) {
                $db->exec('ALTER TABLE `settings` ' . implode(', ', $alters));
            }

            $hostCols = $db->query("SHOW COLUMNS FROM `teaspeak_hosts`")->fetchAll(PDO::FETCH_COLUMN);
            $hostAlters = [];
            if (!in_array('display_ip', $hostCols, true)) {
                $hostAlters[] = "ADD COLUMN `display_ip` varchar(255) DEFAULT NULL";
            }
            if (!in_array('notes', $hostCols, true)) {
                $hostAlters[] = "ADD COLUMN `notes` text DEFAULT NULL";
            }
            if (!in_array('created_at', $hostCols, true)) {
                $hostAlters[] = "ADD COLUMN `created_at` datetime NOT NULL DEFAULT current_timestamp()";
            }
            if ($hostAlters) {
                $db->exec('ALTER TABLE `teaspeak_hosts` ' . implode(', ', $hostAlters));
            }
        } catch (Throwable $e) {
            // Schema sync best-effort; pages still work with base columns
        }
    }

    public function getDisplayName(): string
    {
        $name = $this->getSetting('display_name');
        if (is_string($name) && trim($name) !== '') {
            return trim($name);
        }
        return (string) $this->siteName();
    }

    public function getLogoUrl(): string
    {
        $path = $this->getSetting('logo_path');
        if (is_string($path) && $path !== '' && is_file(__DIR__ . '/../../' . ltrim(str_replace('\\', '/', $path), '/'))) {
            return $this->url() . ltrim(str_replace('\\', '/', $path), '/');
        }
        return $this->url() . 'assets/tea/logo.png';
    }

    public function getFaviconUrl(): string
    {
        $path = $this->getSetting('favicon_path');
        if (is_string($path) && $path !== '' && is_file(__DIR__ . '/../../' . ltrim(str_replace('\\', '/', $path), '/'))) {
            return $this->url() . ltrim(str_replace('\\', '/', $path), '/');
        }
        return $this->getLogoUrl();
    }

    public function setSettings(array $fields): void
    {
        $this->ensureSettingsSchema();
        if (!$fields) {
            return;
        }
        $sets = [];
        $params = [];
        foreach ($fields as $key => $value) {
            $sets[] = '`' . str_replace('`', '', $key) . '` = :' . $key;
            $params[':' . $key] = $value;
        }
        $SQL = self::db()->prepare('UPDATE `settings` SET ' . implode(', ', $sets) . ' WHERE `id` = 1');
        $SQL->execute($params);
    }
	
	
	public function generateChar1($length = 4) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}

	public function generateChar2($length = 4) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}

	public function generateChar3($length = 4) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}

}
?>