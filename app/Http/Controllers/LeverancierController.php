<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeveringRequest;
use App\Models\Leverancier;
use App\Models\Product;
use App\Repositories\LeverancierRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeverancierController extends Controller
{
    public function __construct(private readonly LeverancierRepository $leveranciers) {}

    /**
     * Toon alle actieve leveranciers, gesorteerd op het aantal geleverde producten.
     */
    public function index(): View
    {
        return view('leveranciers.index', [
            'leveranciers' => $this->leveranciers->overzicht(),
        ]);
    }

    /**
     * Toon de unieke producten die door één leverancier zijn geleverd.
     */
    public function producten(Leverancier $leverancier): View
    {
        return view('leveranciers.producten', [
            'leverancier' => $leverancier,
            'producten' => $this->leveranciers->geleverdeProducten($leverancier),
        ]);
    }

    /**
     * Toon het formulier waarmee een magazijnmedewerker een levering registreert.
     */
    public function createLevering(Leverancier $leverancier, Product $product): View
    {
        $this->leveranciers->geleverdProduct($leverancier, $product);

        return view('leveranciers.levering', compact('leverancier', 'product'));
    }

    /**
     * Verwerk de levering via de stored procedure en toon het juiste scenario.
     */
    public function storeLevering(StoreLeveringRequest $request, Leverancier $leverancier, Product $product): RedirectResponse|View
    {
        $resultaat = $this->leveranciers->voegLeveringToe(
            $leverancier,
            $product,
            $request->integer('aantal'),
            $request->string('datum_eerstvolgende_levering')->toString(),
        );

        if (! $resultaat->Gelukt) {
            return view('leveranciers.levering', [
                'leverancier' => $leverancier,
                'product' => $product,
                'foutmelding' => $resultaat->Melding,
            ]);
        }

        return redirect()
            ->route('leveranciers.producten', $leverancier)
            ->with('status', $resultaat->Melding);
    }
}
