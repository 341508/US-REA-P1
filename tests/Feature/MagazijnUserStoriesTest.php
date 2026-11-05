<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MagazijnUserStoriesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('Product', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam');
            $table->string('Barcode')->unique();
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
        });

        Schema::create('ProductPerLeverancier', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('LeverancierId');
            $table->unsignedInteger('ProductId');
            $table->date('DatumLevering');
            $table->unsignedInteger('Aantal');
            $table->date('DatumEerstVolgendeLevering')->nullable();
        });

        Schema::create('Allergeen', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam');
            $table->string('Omschrijving');
        });

        Schema::create('ProductPerAllergeen', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->unsignedInteger('AllergeenId');
        });

        DB::table('Product')->insert([
            ['Id' => 1, 'Naam' => 'Mintnopjes', 'Barcode' => '8719587231278'],
            ['Id' => 2, 'Naam' => 'Schoolkrijt', 'Barcode' => '8719587326713'],
            ['Id' => 5, 'Naam' => 'Cola Flesjes', 'Barcode' => '8719587321237'],
            ['Id' => 10, 'Naam' => 'Winegums', 'Barcode' => '8719587327527'],
            ['Id' => 13, 'Naam' => 'Zoute Ruitjes', 'Barcode' => '8719587323256'],
        ]);

        DB::table('Magazijn')->insert([
            ['Id' => 1, 'ProductId' => 1, 'VerpakkingsEenheid' => 5, 'AantalAanwezig' => 453],
            ['Id' => 2, 'ProductId' => 2, 'VerpakkingsEenheid' => 2.5, 'AantalAanwezig' => 400],
            ['Id' => 5, 'ProductId' => 5, 'VerpakkingsEenheid' => 3, 'AantalAanwezig' => 234],
            ['Id' => 10, 'ProductId' => 10, 'VerpakkingsEenheid' => 3, 'AantalAanwezig' => null],
            ['Id' => 13, 'ProductId' => 13, 'VerpakkingsEenheid' => 5, 'AantalAanwezig' => 20],
        ]);

        DB::table('Leverancier')->insert([
            'Id' => 1,
            'Naam' => 'Venco',
            'ContactPersoon' => 'Bert van Linge',
            'LeverancierNummer' => 'L1029384719',
            'Mobiel' => '06-28493827',
        ]);

        DB::table('ProductPerLeverancier')->insert([
            ['Id' => 1, 'LeverancierId' => 1, 'ProductId' => 1, 'DatumLevering' => '2024-10-09', 'Aantal' => 23, 'DatumEerstVolgendeLevering' => '2024-10-16'],
            ['Id' => 2, 'LeverancierId' => 1, 'ProductId' => 1, 'DatumLevering' => '2024-10-18', 'Aantal' => 21, 'DatumEerstVolgendeLevering' => '2024-10-25'],
        ]);

        DB::table('Allergeen')->insert([
            ['Id' => 1, 'Naam' => 'Gluten', 'Omschrijving' => 'Dit product bevat gluten'],
            ['Id' => 4, 'Naam' => 'Lactose', 'Omschrijving' => 'Dit product bevat lactose'],
            ['Id' => 5, 'Naam' => 'Soja', 'Omschrijving' => 'Dit product bevat soja'],
        ]);

        DB::table('ProductPerAllergeen')->insert([
            ['Id' => 10, 'ProductId' => 13, 'AllergeenId' => 1],
            ['Id' => 11, 'ProductId' => 13, 'AllergeenId' => 4],
            ['Id' => 12, 'ProductId' => 13, 'AllergeenId' => 5],
        ]);
    }

    public function test_magazijn_requires_an_authenticated_user(): void
    {
        $this->get(route('magazijn.index'))->assertRedirect(route('login'));
    }

    public function test_products_are_shown_in_barcode_order(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('magazijn.index'))
            ->assertOk()
            ->assertSee('Overzicht Magazijn Jamin');

        $html = $response->getContent();

        $this->assertLessThan(strpos($html, 'Winegums'), strpos($html, 'Mintnopjes'));
        $this->assertLessThan(strpos($html, 'Winegums'), strpos($html, 'Schoolkrijt'));
    }

    public function test_mintnopjes_shows_supplier_and_sorted_deliveries(): void
    {
        $product = Product::query()->where('Naam', 'Mintnopjes')->firstOrFail();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('magazijn.leveringen', $product))
            ->assertOk()
            ->assertSeeText('Venco')
            ->assertSeeText('Bert van Linge')
            ->assertSeeText('L1029384719')
            ->assertSeeText('06-28493827');

        $html = $response->getContent();
        $this->assertLessThan(strpos($html, '18-10-2024'), strpos($html, '09-10-2024'));
    }

    public function test_winegums_shows_no_stock_message_and_redirect_timer(): void
    {
        $product = Product::query()->where('Naam', 'Winegums')->firstOrFail();

        $this->actingAs(User::factory()->create())
            ->get(route('magazijn.leveringen', $product))
            ->assertOk()
            ->assertSeeText('Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: 30-04-2023')
            ->assertSee('4000');
    }

    public function test_zoute_ruitjes_shows_allergens_in_name_order(): void
    {
        $product = Product::query()->where('Naam', 'Zoute Ruitjes')->firstOrFail();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('magazijn.allergenen', $product))
            ->assertOk()
            ->assertSeeText('Overzicht Allergenen');

        $html = $response->getContent();
        $this->assertLessThan(strpos($html, 'Lactose'), strpos($html, 'Gluten'));
        $this->assertLessThan(strpos($html, 'Soja'), strpos($html, 'Lactose'));
    }

    public function test_cola_flesjes_shows_no_allergens_message_and_redirect_timer(): void
    {
        $product = Product::query()->where('Naam', 'Cola Flesjes')->firstOrFail();

        $this->actingAs(User::factory()->create())
            ->get(route('magazijn.allergenen', $product))
            ->assertOk()
            ->assertSeeText('In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken')
            ->assertSee('4000');
    }
}
