<?php

namespace App\Repositories;

use App\Models\Leverancier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
}
