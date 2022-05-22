Zum Starten der Applikation in Terminal folgendes Schreiben: symfony serve
Xampp starten und Apache + MySQL starten

.env -> Zeile 17: APP_ENV=dev (DEVELOPEMENT MODE)
.env -> Zeile 17: APP_ENV=prod (PRODUCTION MODE)

COMPOSER INSTALL: installiert, Abhängigkeiten, die im composer.lock definiert wurden
COMPOSER UPDATE: updated composer.lock mit neuesten Versionen, installiert Abhängigkeiten

composer.json = hier stehen die Abhängigkeiten innerhalb der Applikation drin
vendor-Ordner: hier werden die Abhängigkeiten, die in der composer.json definiert werden gespeichert.


"php bin/console doctrine:schema:update --force" =  updated eine Datei, ohne Rücksicht auf Fehlermeldungen
"php bin/console make:entity" = erstelle/editiert eine Entity

Live-Voraussetzungen:
php8
composer
mysql-Datenbank

