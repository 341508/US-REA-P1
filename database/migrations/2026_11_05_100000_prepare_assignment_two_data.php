<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Voeg de gegevens toe die specifiek voor opdracht 2 nodig zijn.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::table('Leverancier')->updateOrInsert(
            ['Id' => 6],
            [
                'Naam' => 'Quality Street',
                'ContactPersoon' => 'Johan Nooij',
                'LeverancierNummer' => 'L1029234586',
                'Mobiel' => '06-23458456',
                'IsActief' => 1,
            ]
        );

        // Opdracht 2 gebruikt dezelfde leveringen één maand later dan opdracht 1.
        DB::statement("UPDATE ProductPerLeverancier SET DatumLevering = DATE_ADD(DatumLevering, INTERVAL 1 MONTH) WHERE YEAR(DatumLevering) = 2024 AND MONTH(DatumLevering) = 10");
        DB::statement("UPDATE ProductPerLeverancier SET DatumEerstVolgendeLevering = DATE_ADD(DatumEerstVolgendeLevering, INTERVAL 1 MONTH) WHERE YEAR(DatumEerstVolgendeLevering) = 2024 AND MONTH(DatumEerstVolgendeLevering) = 10");

        // Winegums wordt volgens scenario 02 niet meer geproduceerd.
        DB::table('Product')->where('Id', 10)->update(['IsActief' => 0]);
    }

    /**
     * Draai uitsluitend de gegevenswijzigingen van opdracht 2 terug.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::table('Product')->where('Id', 10)->update(['IsActief' => 1]);
        DB::statement("UPDATE ProductPerLeverancier SET DatumLevering = DATE_SUB(DatumLevering, INTERVAL 1 MONTH) WHERE YEAR(DatumLevering) = 2024 AND MONTH(DatumLevering) = 11");
        DB::statement("UPDATE ProductPerLeverancier SET DatumEerstVolgendeLevering = DATE_SUB(DatumEerstVolgendeLevering, INTERVAL 1 MONTH) WHERE YEAR(DatumEerstVolgendeLevering) = 2024 AND MONTH(DatumEerstVolgendeLevering) = 11");
        DB::table('Leverancier')->where('Id', 6)->delete();
    }
};
