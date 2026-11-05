<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Maak de stored procedures die opdracht 2 gebruikt.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::unprepared(<<<'SQL'
            CREATE PROCEDURE sp_GetLeveranciersOverzicht()
            BEGIN
                SELECT
                    l.Id,
                    l.Naam,
                    l.ContactPersoon,
                    l.LeverancierNummer,
                    l.Mobiel,
                    COUNT(DISTINCT ppl.ProductId) AS AantalVerschillendeProducten
                FROM Leverancier AS l
                LEFT JOIN ProductPerLeverancier AS ppl ON ppl.LeverancierId = l.Id
                WHERE l.IsActief = 1
                GROUP BY l.Id, l.Naam, l.ContactPersoon, l.LeverancierNummer, l.Mobiel
                ORDER BY AantalVerschillendeProducten DESC, l.Naam ASC;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE PROCEDURE sp_GetGeleverdeProducten(IN p_LeverancierId SMALLINT UNSIGNED)
            BEGIN
                SELECT
                    p.Id AS ProductId,
                    p.Naam AS ProductNaam,
                    p.IsActief,
                    m.AantalAanwezig,
                    m.VerpakkingsEenheid,
                    MAX(ppl.DatumLevering) AS LaatsteLevering
                FROM ProductPerLeverancier AS ppl
                INNER JOIN Product AS p ON p.Id = ppl.ProductId
                INNER JOIN Magazijn AS m ON m.ProductId = p.Id
                WHERE ppl.LeverancierId = p_LeverancierId
                GROUP BY p.Id, p.Naam, p.IsActief, m.AantalAanwezig, m.VerpakkingsEenheid
                ORDER BY m.AantalAanwezig DESC, p.Naam ASC;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE PROCEDURE sp_VoegProductLeveringToe(
                IN p_LeverancierId SMALLINT UNSIGNED,
                IN p_ProductId SMALLINT UNSIGNED,
                IN p_Aantal INT UNSIGNED,
                IN p_DatumEerstVolgendeLevering DATE
            )
            procedure_body: BEGIN
                DECLARE v_IsActief BIT DEFAULT 0;
                DECLARE v_ProductNaam VARCHAR(50);
                DECLARE v_LeverancierNaam VARCHAR(50);

                SELECT IsActief, Naam INTO v_IsActief, v_ProductNaam
                FROM Product
                WHERE Id = p_ProductId;

                SELECT Naam INTO v_LeverancierNaam
                FROM Leverancier
                WHERE Id = p_LeverancierId;

                IF v_IsActief = 0 THEN
                    SELECT
                        0 AS Gelukt,
                        CONCAT('Het product ', v_ProductNaam, ' van de leverancier ', v_LeverancierNaam, ' wordt niet meer geproduceerd') AS Melding;
                    LEAVE procedure_body;
                END IF;

                START TRANSACTION;

                INSERT INTO ProductPerLeverancier
                    (LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering)
                VALUES
                    (p_LeverancierId, p_ProductId, CURRENT_DATE(), p_Aantal, p_DatumEerstVolgendeLevering);

                UPDATE Magazijn
                SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + p_Aantal
                WHERE ProductId = p_ProductId;

                COMMIT;

                SELECT 1 AS Gelukt, 'De levering is opgeslagen' AS Melding;
            END
            SQL);
    }

    /**
     * Verwijder de stored procedures van opdracht 2.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_VoegProductLeveringToe');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetGeleverdeProducten');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeveranciersOverzicht');
    }
};
