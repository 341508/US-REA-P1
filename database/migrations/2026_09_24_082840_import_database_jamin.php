<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // De featuretests gebruiken SQLite en maken hiervoor compacte testtabellen aan.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $createScript = database_path('create_script.sql');

        if (! is_file($createScript)) {
            throw new RuntimeException("Het create-script ontbreekt: {$createScript}");
        }

        DB::unprepared(file_get_contents($createScript));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Eerst de koppeltabellen verwijderen, daarna de stamtabellen.
            DB::statement('DROP TABLE IF EXISTS ProductPerAllergeen');
            DB::statement('DROP TABLE IF EXISTS ProductPerLeverancier');
            DB::statement('DROP TABLE IF EXISTS Magazijn');
            DB::statement('DROP TABLE IF EXISTS Leverancier');
            DB::statement('DROP TABLE IF EXISTS Product');
            DB::statement('DROP TABLE IF EXISTS Allergeen');
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
};
