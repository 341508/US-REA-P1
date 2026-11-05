<?php

namespace App\Repositories;

use App\Models\Leverancier;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LeverancierRepository
{
    /**
     * Haal het leveranciersoverzicht op via de MySQL stored procedure.
     *
     * @return Collection<int, object>
     */
    public function overzicht(): Collection
    {
        if (DB::getDriverName() === 'mysql') {
            return collect(DB::select('CALL sp_GetLeveranciersOverzicht()'));
        }

        return DB::table('Leverancier as l')
            ->leftJoin('ProductPerLeverancier as ppl', 'ppl.LeverancierId', '=', 'l.Id')
            ->where('l.IsActief', 1)
            ->groupBy('l.Id', 'l.Naam', 'l.ContactPersoon', 'l.LeverancierNummer', 'l.Mobiel')
            ->orderByDesc(DB::raw('COUNT(DISTINCT ppl.ProductId)'))
            ->orderBy('l.Naam')
            ->select([
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel',
                DB::raw('COUNT(DISTINCT ppl.ProductId) as AantalVerschillendeProducten'),
            ])
            ->get();
    }

    /**
     * Haal de unieke producten van één leverancier op via een stored procedure.
     *
     * @return Collection<int, object>
     */
    public function geleverdeProducten(Leverancier $leverancier): Collection
    {
        if (DB::getDriverName() === 'mysql') {
            return collect(DB::select('CALL sp_GetGeleverdeProducten(?)', [$leverancier->Id]));
        }

        return DB::table('ProductPerLeverancier as ppl')
            ->join('Product as p', 'p.Id', '=', 'ppl.ProductId')
            ->join('Magazijn as m', 'm.ProductId', '=', 'p.Id')
            ->where('ppl.LeverancierId', $leverancier->Id)
            ->groupBy('p.Id', 'p.Naam', 'p.IsActief', 'm.AantalAanwezig', 'm.VerpakkingsEenheid')
            ->orderByDesc('m.AantalAanwezig')
            ->orderBy('p.Naam')
            ->select([
                'p.Id as ProductId',
                'p.Naam as ProductNaam',
                'p.IsActief',
                'm.AantalAanwezig',
                'm.VerpakkingsEenheid',
                DB::raw('MAX(ppl.DatumLevering) as LaatsteLevering'),
            ])
            ->get();
    }

    /**
     * Controleer dat het gekozen product door deze leverancier geleverd wordt.
     */
    public function geleverdProduct(Leverancier $leverancier, Product $product): object
    {
        $geleverdProduct = $this->geleverdeProducten($leverancier)
            ->firstWhere('ProductId', $product->Id);

        if ($geleverdProduct === null) {
            throw new RuntimeException('Dit product hoort niet bij de gekozen leverancier.');
        }

        return $geleverdProduct;
    }

    /**
     * Voeg een levering toe en verhoog de magazijnvoorraad.
     *
     * @return object{Gelukt: int, Melding: string}
     */
    public function voegLeveringToe(Leverancier $leverancier, Product $product, int $aantal, string $volgendeLevering): object
    {
        $this->geleverdProduct($leverancier, $product);

        if (DB::getDriverName() === 'mysql') {
            return DB::selectOne('CALL sp_VoegProductLeveringToe(?, ?, ?, ?)', [
                $leverancier->Id,
                $product->Id,
                $aantal,
                $volgendeLevering,
            ]);
        }

        if (! $product->IsActief) {
            return (object) [
                'Gelukt' => 0,
                'Melding' => "Het product {$product->Naam} van de leverancier {$leverancier->Naam} wordt niet meer geproduceerd",
            ];
        }

        DB::transaction(function () use ($leverancier, $product, $aantal, $volgendeLevering): void {
            DB::table('ProductPerLeverancier')->insert([
                'LeverancierId' => $leverancier->Id,
                'ProductId' => $product->Id,
                'DatumLevering' => now()->toDateString(),
                'Aantal' => $aantal,
                'DatumEerstVolgendeLevering' => $volgendeLevering,
            ]);

            DB::table('Magazijn')
                ->where('ProductId', $product->Id)
                ->update(['AantalAanwezig' => DB::raw('COALESCE(AantalAanwezig, 0) + '.(int) $aantal)]);
        });

        return (object) ['Gelukt' => 1, 'Melding' => 'De levering is opgeslagen'];
    }
}
