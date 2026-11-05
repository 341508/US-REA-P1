<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use App\Repositories\LeverancierRepository;
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
}
