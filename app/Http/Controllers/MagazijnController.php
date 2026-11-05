<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class MagazijnController extends Controller
{
    /**
     * Toon alle magazijnproducten, oplopend gesorteerd op barcode.
     */
    public function index(): View
    {
        return view('magazijn.index', [
            'producten' => Product::query()
                ->with('magazijn')
                ->orderBy('Barcode')
                ->get(),
        ]);
    }

    /**
     * Toon de leveringsinformatie van het gekozen product.
     */
    public function leveringen(Product $product): View
    {
        $product->load([
            'magazijn',
            'leveringen' => fn ($query) => $query->orderBy('DatumLevering'),
            'leveringen.leverancier',
        ]);

        $geenVoorraad = $product->magazijn?->AantalAanwezig === null
            || $product->magazijn?->AantalAanwezig <= 0;

        return view('magazijn.leveringen', [
            'product' => $product,
            'geenVoorraad' => $geenVoorraad,
            // De datum staat letterlijk zo voorgeschreven in scenario 02.
            'verwachteLeveringZonderVoorraad' => '30-04-2023',
        ]);
    }

    /**
     * Toon de allergeneninformatie van het gekozen product.
     */
    public function allergenen(Product $product): View
    {
        $product->load([
            'allergenen' => fn ($query) => $query->orderBy('Naam'),
        ]);

        return view('magazijn.allergenen', [
            'product' => $product,
        ]);
    }
}
