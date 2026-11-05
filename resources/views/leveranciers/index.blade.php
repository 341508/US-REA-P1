<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Overzicht leveranciers</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="overflow-x-auto p-6">
                    <table class="jamin-table w-full border-collapse text-left">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-3">Naam</th>
                                <th class="px-4 py-3">Contactpersoon</th>
                                <th class="px-4 py-3">Leveranciernummer</th>
                                <th class="px-4 py-3">Mobiel</th>
                                <th class="px-4 py-3 text-right">Aantal verschillende producten</th>
                                <th class="px-4 py-3 text-center">Toon producten</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($leveranciers as $leverancier)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium">{{ $leverancier->Naam }}</td>
                                    <td class="px-4 py-3">{{ $leverancier->ContactPersoon }}</td>
                                    <td class="px-4 py-3">{{ $leverancier->LeverancierNummer }}</td>
                                    <td class="px-4 py-3">{{ $leverancier->Mobiel }}</td>
                                    <td class="px-4 py-3 text-right">{{ $leverancier->AantalVerschillendeProducten }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('leveranciers.producten', $leverancier->Id) }}"
                                           aria-label="Toon producten van {{ $leverancier->Naam }}"
                                           title="Toon producten"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7 12 3 4 7m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 text-right">
                        <a class="inline-flex rounded-md bg-gray-700 px-4 py-2 text-white hover:bg-gray-800" href="{{ route('dashboard') }}">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
