# Laravel 12 met gebruikersrollen

## BE-opdracht 01

Dit project realiseert de twee voorgeschreven userstories voor Jamin:

- leveringsinformatie van een gekozen product bekijken;
- allergeneninformatie van een gekozen product bekijken.

De officiële opdracht staat in `docs`, het create-script en voorbeeldscript staan
in `database`, en de zelfstandig importeerbare database-export staat in `db`.
De demonstratievideo moet vóór het inleveren in `vids` worden geplaatst.

De uiterste inleverdatum op Canvas is **zondag 4 oktober 2026**. Na deze datum
is inleveren volgens de opdracht niet meer mogelijk.

Dit project bevat registratie, inloggen en twee gebruikersrollen: `admin` en `gebruiker`.
Nieuwe registraties krijgen automatisch de rol `gebruiker`. Alleen admins kunnen het
gebruikersoverzicht openen en rollen wijzigen.

## Vereisten

- PHP 8.2 of hoger (in deze Wamp-installatie is PHP 8.3.14 beschikbaar)
- MySQL via Wamp, met de storage engine InnoDB
- Composer en Node.js

## Database en starten

Maak in phpMyAdmin een lege database met de naam `laravel`. De `.env` staat al op:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Voer daarna vanuit deze projectmap uit:

```powershell
C:\wamp64\bin\php\php8.3.14\php.exe artisan migrate --seed
npm install
npm run build
C:\wamp64\bin\php\php8.3.14\php.exe artisan serve
```

Open vervolgens http://localhost:8000.

Demo-accounts na `php artisan migrate --seed`:

| Rol | E-mail | Wachtwoord |
|---|---|---|
| Admin | admin@example.com | Password123! |
| Gebruiker | gebruiker@example.com | Password123! |

Gebruik deze accounts alleen lokaal voor de demonstratie.

## Wat je in de video laat zien

1. Toon in de terminal `php artisan --version` en leg uit dat dit Laravel 12 is.
2. Toon `.env` en wijs de MySQL-instellingen en database `laravel` aan.
3. Start de app, registreer een nieuw account en log in.
4. Toon op het dashboard dat het nieuwe account rol `gebruiker` heeft.
5. Log uit en log in als `admin@example.com`.
6. Open **Gebruikersrollen**, verander de rol van het nieuwe account en sla op.
7. Toon in phpMyAdmin database `laravel`, tabel `users`, en de kolom `role`.
8. Leg kort uit dat `/admin/gebruikers` voor gewone gebruikers een 403 geeft.

Voor de officiële opdracht moet de video maximaal 60 seconden duren. Laat daarin
ook de zes Jamin-tabellen, hun relaties en de gegevens in phpMyAdmin zien. Film
de browser en phpMyAdmin, niet de broncode.

## Inlevercontrole

- Plaats de video in `vids`.
- Controleer of `db/jamin.sql` in phpMyAdmin kan worden geïmporteerd.
- Controleer of de repository minimaal 20 inhoudelijke commits bevat.
- Merge de featurebranches naar `main`.
- Push `main` naar de openbare GitHub-repository
  `BE-opdracht-1-studentnummer`.
- Lever op Canvas de video en de GitHub-link in.

## Tests

De tests gebruiken een tijdelijke SQLite-database in het geheugen en wijzigen MySQL niet:

```powershell
C:\wamp64\bin\php\php8.3.14\php.exe artisan test
```
