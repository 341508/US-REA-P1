# Inleverchecklist

## Opdracht 2.3 - Laravel met gebruikersrollen

Klaar in het project:

- Laravel 12 met registratie en inloggen.
- MySQL-database `laravel` via Wamp/MariaDB en InnoDB.
- Rollen `admin` en `gebruiker`.
- Alleen een administrator kan het scherm Gebruikersrollen openen.

Video van maximaal 60 seconden:

1. Open de loginpagina en log in als administrator.
2. Open **Gebruikersrollen** en laat de twee rollen zien.
3. Open phpMyAdmin en toon database `laravel`, tabel `users` en kolom `role`.
4. Zeg dat `.env` MySQL gebruikt en dat de tabellen InnoDB zijn.

## Opdracht 2.4 - Createscript

In te leveren bestand: `database/create_script.sql`.

Het script bevat eerst de stamtabellen `Product`, `Allergeen` en `Leverancier`. Daarna volgen de koppeltabellen `Magazijn`, `ProductPerAllergeen` en `ProductPerLeverancier`. Alle tabellen bevatten een primary key, de juiste foreign keys en de systeemvelden `IsActief`, `Opmerking`, `DatumAangemaakt` en `DatumGewijzigd`.

## Opdracht 3.3 - Opdracht 1

Video van maximaal 60 seconden:

1. Log in en open **Overzicht Magazijn Jamin**.
2. Laat zien dat de producten op barcode zijn gesorteerd.
3. Open bij Mintnopjes de leveringsinformatie.
4. Open bij Winegums het scenario zonder voorraad.
5. Open bij Zoute Ruitjes de allergeneninformatie.
6. Open bij Cola Flesjes het scenario zonder allergenen.
7. Toon kort de zes tabellen en relaties in phpMyAdmin.

Verder inleveren: de URL van de openbare GitHub-repository.

## Opdracht 4.3 - Opdracht 2

Video van maximaal 60 seconden:

1. Open **Overzicht Leveranciers**.
2. Toon dat leveranciers op aantal verschillende producten zijn gesorteerd.
3. Open Venco en laat de geleverde producten zien.
4. Voeg bij Mintnopjes een levering van 25 toe en sla deze op.
5. Laat zien dat de voorraad met 25 is verhoogd.
6. Open Quality Street en toon de melding dat er nog geen producten zijn geleverd.
7. Open Basset, probeer Winegums toe te voegen en toon de melding dat dit product niet meer wordt geproduceerd.

Verder inleveren: de URL van de repository en, als de repository privé is, GitHub-gebruiker `EstebanVihaio` als collaborator.

## MVC en routes uitleggen

- **Model:** classes in `app/Models` beschrijven de databasetabellen en relaties.
- **View:** Blade-bestanden in `resources/views/leveranciers` bouwen de HTML-schermen.
- **Controller:** `app/Http/Controllers/LeverancierController.php` ontvangt het verzoek en kiest de view.
- **Repository/PDO-laag:** `app/Repositories/LeverancierRepository.php` voert databasequeries en stored procedures uit.
- **Routes:** `routes/web.php` koppelt een URL en HTTP-methode aan een controllermethode.

Voorbeeld: `GET /leveranciers` gaat naar `LeverancierController::index()`. Deze methode vraagt het overzicht op bij `LeverancierRepository::overzicht()` en geeft de resultaten door aan `leveranciers.index`.

## Startcommando's

```powershell
php artisan migrate
composer run dev
```

Open daarna de URL die de terminal toont. Wanneer poort 8000 al bezet is:

```powershell
php artisan serve --port=8001
npm run dev
```

Open dan `http://127.0.0.1:8001`.
