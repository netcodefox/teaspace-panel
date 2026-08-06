# TeaSpace Panel

Kundenpanel für TeaSpeak-/Hosting-Produkte mit Login, Zahlungen, Tickets und Team-Bereich.

**Voraussetzung: PHP 8.1+** (getestet mit PHP 8.3)

## Features

- Login / Registrierung mit E-Mail-Bestätigung
- PayPal- & Mollie-Zahlungen
- Support-Ticket-System
- E-Mail-Blacklist
- Spenden- & Affiliate-System
- Benutzerverwaltung (Team/Admin)
- TeaSpeak-Server-Verwaltung (GreenTeaSpeak TS3 PHP Framework 1.3.1)

## Screenshots

- Screen 1: https://pic.nico-bary.de/img/futcwjwj.png
- Screen 2: https://pic.nico-bary.de/img/lx54oojb.png

## Voraussetzungen

- PHP **8.1 oder neuer**
- Extensions: `pdo`, `pdo_mysql`, `curl`, `mbstring`, `json`, `openssl`, `ctype`
- MySQL / MariaDB
- Apache mit `mod_rewrite` (oder vergleichbare Rewrite-Regeln)
- Composer-Abhängigkeiten (`vendor/` – im Repo enthalten oder via `composer install`)

## Installation (empfohlen)

1. Dateien auf den Webserver legen (Document Root oder Unterordner).
2. Im Browser `https://deine-domain.tld/pfad/install/` öffnen.
3. Die 4 Schritte durchlaufen:
   - Systemprüfung
   - Datenbankverbindung (DB wird bei Bedarf angelegt, Schema aus `teaspace.sql` importiert)
   - Site-/SMTP-/Zahlungs-Einstellungen
   - Admin-Konto anlegen
4. Nach Erfolg erscheint `install/install.lock` – der Installer ist gesperrt.
5. Aus Sicherheitsgründen den Ordner `install/` löschen oder den Webzugriff darauf sperren.
6. Login unter `/login` mit dem angelegten Admin-Konto.

Beim ersten Aufruf von `index.php` ohne Lock wird automatisch zum Installer weitergeleitet.

## Manuelle Installation

1. `composer install` (falls `vendor/` fehlt)
2. `app/controller/config.sample.php` nach `app/controller/config.php` kopieren und ausfüllen
3. Datenbank anlegen und `teaspace.sql` importieren
4. Admin-Benutzer manuell in der Tabelle `users` anlegen (`role = admin`, `state = active`, Passwort als `password_hash`)
5. In `.htaccess` die `RewriteBase` an den Installationspfad anpassen (z. B. `/` oder `/teaspace/`)
6. Datei `install/install.lock` anlegen (beliebiger Inhalt), damit das Panel startet

### Wichtige Config-Werte

| Einstellung | Datei / Variable |
|---|---|
| Datenbank & URL | `app/controller/config.php` |
| SMTP | `$mail_*` in `config.php` |
| Mollie | `$mollie_api_key` |
| PayPal | `$paypal_email`, `$paypal_sandbox` |
| reCAPTCHA | `$grecaptchaSiteKey`, `$grecaptchaSecret` |
| Telegram | `$telegram_token`, `$telegram_chat_id` |
| Cron-Secret | `$cron_key` → Aufruf `crone_job.php?key=DEIN_SECRET` |

## Nach dem Setup

- TeaSpeak-Nodes unter Team → Nodes hinterlegen
- Zahlungsmethoden (Mollie/PayPal) in der Config prüfen
- Cron-Job einrichten, z. B. täglich:  
  `https://deine-domain.tld/crone_job.php?key=DEIN_SECRET`

## PHP 8 – Hinweise zu diesem Release

- TeaSpeak-Integration über [GreenTeaSpeak/ts3phpframework 1.3.1](https://github.com/GreenTeaSpeak/ts3phpframework/releases/tag/1.3.1) (GreenTeaSpeak-Protokoll-Support)
- `DateTime(null, …)` durch `DateTime('now', …)` ersetzt
- Optionale Parameter vor Pflichtparametern in den TS-Admin-Klassen korrigiert
- Undefined-Index-Zugriffe und fehlende Resource-Dateien abgefangen
- Klartext-Secrets aus dem Repo entfernt; Konfiguration über Installer/`config.php`

## Lizenz

Siehe `LICENSE`.
