<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Toon alle gebruikers zodat een administrator hun rollen kan beheren.
     */
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Werk de rol van een gebruiker bij met bescherming van de eigen adminrol.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'gebruiker'])],
        ]);

        if ($request->user()->is($user) && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Je kunt je eigen adminrol niet verwijderen.']);
        }

        $user->update($validated);

        return back()->with('status', "De rol van {$user->name} is bijgewerkt.");
    }
}
