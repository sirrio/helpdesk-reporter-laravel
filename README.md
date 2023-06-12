## Installationstutorial für Helpdesk Reporter:

Schritt 1: Neuen Projektordner erstellen
Erstellen Sie einen neuen Ordner für das Helpdesk Reporter-Projekt.

Schritt 2: Node.js LTS installieren
Besuchen Sie die offizielle Node.js-Website unter https://nodejs.org/de/download und laden Sie die LTS-Version von Node.js herunter. Führen Sie den Installationsprozess aus und stellen Sie sicher, dass Node.js erfolgreich installiert wurde.

Schritt 3: Chocolatey installieren
Installieren Sie Chocolatey, eine Paketverwaltungssoftware für Windows. Besuchen Sie die offizielle Chocolatey-Website und folgen Sie den Anweisungen zur Installation.

Schritt 4: GitHub Account anlegen
Falls Sie noch keinen GitHub-Account haben, erstellen Sie einen auf der offiziellen GitHub-Website.

Schritt 5: PHP 8.1 mit Chocolatey installieren
Öffnen Sie das Terminal oder die Eingabeaufforderung und führen Sie den folgenden Befehl aus, um PHP 8.1 mit Chocolatey zu installieren:
```
choco install php --version=8.1.20
```

Schritt 6: Composer mit Chocolatey installieren
Führen Sie den folgenden Befehl aus, um Composer mit Chocolatey zu installieren:
```
choco install composer
```

Schritt 7: MySQL mit Chocolatey installieren
Installieren Sie MySQL mit Chocolatey, indem Sie den folgenden Befehl ausführen:
```
choco install mysql
```

Schritt 8: Git 2.4 x64 per git-scm installieren
Besuchen Sie die offizielle Git-Website und laden Sie die Version 2.4 x64 herunter. Führen Sie den Installationsprozess aus und stellen Sie sicher, dass Git erfolgreich installiert wurde.

Schritt 9: Helpdesk Reporter-Projekt klonen
Öffnen Sie das Terminal oder die Eingabeaufforderung und führen Sie den folgenden Befehl aus, um das Helpdesk Reporter-Projekt von GitHub zu klonen:
```
git clone https://github.com/sirrio/helpdesk-reporter-laravel
```

Schritt 10: In das Projektverzeichnis wechseln
Navigieren Sie in das Verzeichnis des Helpdesk Reporter-Projekts:
```
cd helpdesk-reporter-laravel
```

Schritt 11: Abhängigkeiten mit npm install installieren
Führen Sie den folgenden Befehl aus, um die erforderlichen Node.js-Abhängigkeiten zu installieren:
```
npm install
```

Schritt 12: Abhängigkeiten mit composer install installieren
Führen Sie den folgenden Befehl aus, um die erforderlichen PHP-Abhängigkeiten mit Composer zu installieren:
```
composer install
```

Schritt 13: Datenbank migrieren mit Seeder
Führen Sie den folgenden Befehl aus, um die Datenbankmigrationen mit den Seed-Daten auszuführen:
```
php artisan migrate --seed
```

Schritt 14: .env-Datei anpassen
Öffnen Sie die .env-Datei im Projektverzeichnis und passen Sie die darin enthaltenen Konfigurationsvariablen an Ihre Umgebung an (z. B. Datenbankverbindungsinformationen).
