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

    public function tableExists(string $table): bool
    {
        try {
            $db = self::db();
            $stmt = $db->prepare('SHOW TABLES LIKE :t');
            $stmt->execute([':t' => $table]);
            return $stmt->rowCount() > 0;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function ensureCoreSchema(): void
    {
        static $coreReady = false;
        if ($coreReady) {
            return;
        }
        $coreReady = true;

        try {
            $db = self::db();

            if (!$this->tableExists('product_prices')) {
                $db->exec("CREATE TABLE `product_prices` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL,
                  `price` decimal(12,2) NOT NULL,
                  `created_at` datetime DEFAULT current_timestamp(),
                  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
            }
            $priceCheck = $db->prepare("SELECT id FROM `product_prices` WHERE `name` = 'TEASPEAK' LIMIT 1");
            $priceCheck->execute();
            if ($priceCheck->rowCount() === 0) {
                $db->prepare("INSERT INTO `product_prices` (`name`, `price`) VALUES ('TEASPEAK', 0.12)")->execute();
            }

            if (!$this->tableExists('teaspeak_hosts')) {
                $db->exec("CREATE TABLE `teaspeak_hosts` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL,
                  `login_ip` varchar(255) NOT NULL,
                  `display_ip` varchar(255) DEFAULT NULL,
                  `login_port` varchar(255) NOT NULL,
                  `login_name` varchar(255) NOT NULL,
                  `login_passwort` varchar(255) NOT NULL,
                  `status` enum('ACTIVE','DISABLED') NOT NULL DEFAULT 'ACTIVE',
                  `notes` text DEFAULT NULL,
                  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
            }

            if (!$this->tableExists('teaspeaks')) {
                $db->exec("CREATE TABLE `teaspeaks` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) NOT NULL,
                  `slots` int(11) NOT NULL,
                  `node_id` int(11) NOT NULL,
                  `teaspeak_ip` varchar(255) NOT NULL,
                  `teaspeak_port` varchar(255) NOT NULL,
                  `sid` int(11) NOT NULL,
                  `expire_at` datetime NOT NULL,
                  `price` decimal(12,2) NOT NULL,
                  `state` enum('ACTIVE','SUSPENDED','DELETED') NOT NULL DEFAULT 'ACTIVE',
                  `days` varchar(255) DEFAULT NULL,
                  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                  `deleted_at` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
            }

            if (!$this->tableExists('settings')) {
                $db->exec("CREATE TABLE `settings` (
                  `id` int(11) NOT NULL,
                  `login` int(11) NOT NULL DEFAULT 1,
                  `register` int(11) NOT NULL DEFAULT 1,
                  `wartung` int(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
                $db->exec("INSERT INTO `settings` (`id`, `login`, `register`, `wartung`) VALUES (1, 1, 1, 0)");
            }
        } catch (Throwable $e) {
            // best-effort
        }
    }

    public function ensureSettingsSchema(): void
    {
        static $ready = false;
        if ($ready) {
            return;
        }
        $ready = true;

        try {
            $this->ensureCoreSchema();
            $db = self::db();
            if (!$this->tableExists('settings')) {
                return;
            }
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
            if (!in_array('support_ts_label', $cols, true)) {
                $alters[] = "ADD COLUMN `support_ts_label` varchar(255) DEFAULT NULL";
            }
            if (!in_array('support_ts_value', $cols, true)) {
                $alters[] = "ADD COLUMN `support_ts_value` varchar(255) DEFAULT NULL";
            }
            if (!in_array('support_phone_label', $cols, true)) {
                $alters[] = "ADD COLUMN `support_phone_label` varchar(255) DEFAULT NULL";
            }
            if (!in_array('support_phone_value', $cols, true)) {
                $alters[] = "ADD COLUMN `support_phone_value` varchar(255) DEFAULT NULL";
            }
            if (!in_array('site_content', $cols, true)) {
                $alters[] = "ADD COLUMN `site_content` longtext DEFAULT NULL";
            }
            foreach (['page_impressum', 'page_datenschutz', 'page_agb', 'page_widerruf'] as $pageCol) {
                if (!in_array($pageCol, $cols, true)) {
                    $alters[] = "ADD COLUMN `{$pageCol}` longtext DEFAULT NULL";
                }
            }
            if ($alters) {
                $db->exec('ALTER TABLE `settings` ' . implode(', ', $alters));
            }

            if ($this->tableExists('teaspeak_hosts')) {
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

    public function resolveLogoRelativePath(): ?string
    {
        $path = $this->getSetting('logo_path');
        if (is_string($path) && trim($path) !== '') {
            $rel = ltrim(str_replace('\\', '/', $path), '/');
            if (is_file(__DIR__ . '/../../' . $rel)) {
                return $rel;
            }
        }
        $fallbacks = [
            'assets/tea/logowhite.png',
            'assets/tea/logo.png',
            'assets/logonew.png',
            'assets/300px-Teaspeak_Logo.png',
        ];
        foreach ($fallbacks as $rel) {
            if (is_file(__DIR__ . '/../../' . $rel)) {
                return $rel;
            }
        }
        return null;
    }

    public function hasLogoImage(): bool
    {
        return $this->resolveLogoRelativePath() !== null;
    }

    public function getLogoUrl(): string
    {
        $rel = $this->resolveLogoRelativePath();
        if ($rel !== null) {
            return $this->url() . $rel;
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

    public function showBrandText(): bool
    {
        // Nie leeren Header: ohne Logo immer Text; mit Logo kein doppelter Name
        return !$this->hasLogoImage();
    }

    public function getSupportTsLabel(): string
    {
        $v = $this->getSetting('support_ts_label');
        return (is_string($v) && trim($v) !== '') ? trim($v) : 'Teamspeak Support';
    }

    public function getSupportTsValue(): string
    {
        $v = $this->getSetting('support_ts_value');
        return (is_string($v) && trim($v) !== '') ? trim($v) : 'ts.wino-space.de';
    }

    public function getSupportPhoneLabel(): string
    {
        $v = $this->getSetting('support_phone_label');
        return (is_string($v) && trim($v) !== '') ? trim($v) : 'Telefon & Whatsapp Support';
    }

    public function getSupportPhoneValue(): string
    {
        $v = $this->getSetting('support_phone_value');
        return (is_string($v) && trim($v) !== '') ? trim($v) : '+49 (0) 2452 860729';
    }

    public function defaultSiteContent(): array
    {
        $base = $this->url();
        return [
            'contact' => [
                'office' => ['title' => 'Büro', 'line1' => 'Kommt', 'line2' => 'Noch'],
                'phone' => ['title' => 'Ruf Uns An', 'line1' => '', 'line2' => ''],
                'message' => [
                    'title' => 'Send Message',
                    'line1' => '',
                    'line2' => 'Oder',
                    'link_label' => 'Ticket',
                    'link_url' => $base . 'support',
                ],
                'whatsapp' => ['title' => '24 / 7 Whatsapp', 'line1' => '', 'line2' => ''],
            ],
            'social' => [
                'facebook' => '',
                'twitter' => '',
                'instagram' => '',
                'teamspeak' => '',
                'discord' => '',
            ],
            'footer' => [
                'about' => '',
                'extra_link_label' => '',
                'extra_link_url' => '',
                'legal' => [
                    ['key' => 'impressum', 'label' => 'Impressum', 'url' => $base . 'impressum', 'external' => false],
                    ['key' => 'datenschutz', 'label' => 'Datenschutz', 'url' => $base . 'datenschutz', 'external' => false],
                    ['key' => 'agb', 'label' => 'AGB', 'url' => $base . 'agb', 'external' => false],
                    ['key' => 'widerruf', 'label' => 'Widerruf', 'url' => $base . 'widerruf', 'external' => false],
                    ['key' => 'hoster', 'label' => 'Teaspeak Hoster', 'url' => '', 'external' => true],
                ],
            ],
            'header' => [
                'show_brand_text' => false,
                'tagline' => 'Hosting aus Deutschland',
            ],
            'nav' => [
                ['label' => 'Start', 'url' => $base],
                ['label' => 'TeaSpeak', 'url' => $base . 'teaspeak/order'],
                ['label' => 'Kontakt', 'url' => $base . 'contact'],
            ],
        ];
    }

    public function getSiteContent(): array
    {
        $defaults = $this->defaultSiteContent();
        $raw = $this->getSetting('site_content');
        if (!is_string($raw) || trim($raw) === '') {
            return $defaults;
        }
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $defaults;
        }
        $merged = array_replace_recursive($defaults, $decoded);
        if (isset($decoded['nav']) && is_array($decoded['nav'])) {
            $merged['nav'] = $decoded['nav'];
        }
        return $merged;
    }

    public function getFrontNavLinks(): array
    {
        $nav = $this->getSiteContent()['nav'] ?? [];
        $out = [];
        if (!is_array($nav)) {
            return $out;
        }
        foreach ($nav as $item) {
            if (!is_array($item)) {
                continue;
            }
            $label = trim((string) ($item['label'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));
            if ($label === '' || $url === '' || $url === '#') {
                continue;
            }
            $out[] = ['label' => $label, 'url' => $url];
        }
        return $out;
    }

    public function getHeaderTagline(): string
    {
        $header = $this->getSiteContent()['header'] ?? [];
        $tag = trim((string) ($header['tagline'] ?? ''));
        return $tag !== '' ? $tag : 'Hosting aus Deutschland';
    }

    public function saveSiteContent(array $content): void
    {
        $merged = array_replace_recursive($this->defaultSiteContent(), $content);
        if (isset($content['nav']) && is_array($content['nav'])) {
            $merged['nav'] = $content['nav'];
        }
        $this->setSettings(['site_content' => json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
    }

    public function getContactCard(string $key): array
    {
        $content = $this->getSiteContent();
        $card = $content['contact'][$key] ?? [];
        return is_array($card) ? $card : [];
    }

    public function getSocialLinks(): array
    {
        $social = $this->getSiteContent()['social'] ?? [];
        $icons = [
            'facebook' => 'fa fa-facebook',
            'twitter' => 'fa fa-twitter',
            'instagram' => 'fa fa-instagram',
            'teamspeak' => 'fab fa-teamspeak',
            'discord' => 'fab fa-discord',
        ];
        $out = [];
        foreach ($icons as $key => $icon) {
            $url = trim((string) ($social[$key] ?? ''));
            if ($url === '' || $url === '#') {
                continue;
            }
            $out[] = ['key' => $key, 'url' => $url, 'icon' => $icon];
        }
        return $out;
    }

    public function getFooterLegal(): array
    {
        $legal = $this->getSiteContent()['footer']['legal'] ?? [];
        return is_array($legal) ? $legal : [];
    }

    public function legalPageKeys(): array
    {
        return [
            'impressum' => 'Impressum',
            'datenschutz' => 'Datenschutz',
            'agb' => 'AGB',
            'widerruf' => 'Widerruf',
        ];
    }

    public function getLegalPageDefault(string $key): string
    {
        $file = __DIR__ . '/../../resources/content/legal/' . $key . '.html';
        if (is_file($file)) {
            $html = file_get_contents($file);
            if (!is_string($html)) {
                return '';
            }
            return preg_replace('/^\xEF\xBB\xBF/', '', $html) ?? $html;
        }
        return '';
    }

    public function getLegalPage(string $key): string
    {
        $keys = $this->legalPageKeys();
        if (!isset($keys[$key])) {
            return '';
        }
        $col = 'page_' . $key;
        $stored = $this->getSetting($col);
        if (is_string($stored) && trim($stored) !== '') {
            return $stored;
        }
        return $this->getLegalPageDefault($key);
    }

    public function setLegalPage(string $key, string $html): void
    {
        $keys = $this->legalPageKeys();
        if (!isset($keys[$key])) {
            throw new InvalidArgumentException('Unbekannte Rechtsseite.');
        }
        $this->setSettings(['page_' . $key => $html]);
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