<?php

namespace Tests\Feature;

use App\Models\Leverancier;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LeverancierUserStoriesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('Product', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam');
            $table->boolean('IsActief')->default(true);
        });

        Schema::create('Magazijn', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->decimal('VerpakkingsEenheid', 5, 2);
            $table->unsignedInteger('AantalAanwezig')->nullable();
        });

        Schema::create('Leverancier', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam');
            $table->string('ContactPersoon');
            $table->string('LeverancierNummer');
            $table->string('Mobiel');
            $table->boolean('IsActief')->default(true);
        });

        Schema::create('ProductPerLeverancier', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('LeverancierId');
            $table->unsignedInteger('ProductId');
            $table->date('DatumLevering');
            $table->unsignedInteger('Aantal');
            $table->date('DatumEerstVolgendeLevering')->nullable();
        });

        DB::table('Product')->insert([
            ['Id' => 1, 'Naam' => 'Mintnopjes', 'IsActief' => 1],
            ['Id' => 2, 'Naam' => 'Schoolkrijt', 'IsActief' => 1],
        ]);

        DB::table('Magazijn')->insert([
            ['Id' => 1, 'ProductId' => 1, 'VerpakkingsEenheid' => 5, 'AantalAanwezig' => 453],
            ['Id' => 2, 'ProductId' => 2, 'VerpakkingsEenheid' => 2.5, 'AantalAanwezig' => 400],
        ]);

        DB::table('Leverancier')->insert([
            ['Id' => 1, 'Naam' => 'Venco', 'ContactPersoon' => 'Bert van Linge', 'LeverancierNummer' => 'L1029384719', 'Mobiel' => '06-28493827', 'IsActief' => 1],
            ['Id' => 6, 'Naam' => 'Quality Street', 'ContactPersoon' => 'Johan Nooij', 'LeverancierNummer' => 'L1029234586', 'Mobiel' => '06-23458456', 'IsActief' => 1],
        ]);

        DB::table('ProductPerLeverancier')->insert([
            ['Id' => 1, 'LeverancierId' => 1, 'ProductId' => 1, 'DatumLevering' => '2024-11-09', 'Aantal' => 23, 'DatumEerstVolgendeLevering' => '2024-11-16'],
            ['Id' => 2, 'LeverancierId' => 1, 'ProductId' => 1, 'DatumLevering' => '2024-11-18', 'Aantal' => 21, 'DatumEerstVolgendeLevering' => '2024-11-25'],
            ['Id' => 3, 'LeverancierId' => 1, 'ProductId' => 2, 'DatumLevering' => '2024-11-09', 'Aantal' => 12, 'DatumEerstVolgendeLevering' => '2024-11-16'],
        ]);
    }

    public function test_leveranciersoverzicht_requires_an_authenticated_user(): void
    {
        $this->get(route('leveranciers.index'))->assertRedirect(route('login'));
    }

    public function test_overview_shows_product_counts_in_descending_order(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('leveranciers.index'))
            ->assertOk()
            ->assertSeeText('Overzicht leveranciers')
            ->assertSeeText('Quality Street');

        $html = $response->getContent();
        $this->assertLessThan(strpos($html, 'Quality Street'), strpos($html, 'Venco'));
    }

    public function test_supplier_detail_follows_wireframe_and_product_sorting(): void
    {
        $leverancier = Leverancier::query()->findOrFail(1);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('leveranciers.producten', $leverancier))
            ->assertOk()
            ->assertSeeText('Geleverde producten')
            ->assertSeeText('Bert van Linge')
            ->assertSeeText('Aantal in Magazijn')
            ->assertSeeText('Nieuwe levering');

        $html = $response->getContent();
        $this->assertLessThan(strpos($html, 'Schoolkrijt'), strpos($html, 'Mintnopjes'));
    }

    public function test_empty_supplier_shows_exact_message_and_three_second_redirect(): void
    {
        $leverancier = Leverancier::query()->findOrFail(6);

        $this->actingAs(User::factory()->create())
            ->get(route('leveranciers.producten', $leverancier))
            ->assertOk()
            ->assertSeeText('Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin')
            ->assertSee('content="3;url=', false);
    }
}
