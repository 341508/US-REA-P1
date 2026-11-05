<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Levering Informatie</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 space-y-3 rounded-lg bg-white p-6 shadow-sm">
                @if($product->leveringen->isNotEmpty())
                    <div><span class="font-semibold">Naam leverancier:</span> {{ $product->leveringen->first()->leverancier->Naam }}</div>
                    <div><span class="font-semibold">Contactpersoon leverancier:</span> {{ $product->leveringen->first()->leverancier->ContactPersoon }}</div>
                    <div><span class="font-semibold">Leveranciernummer:</span> {{ $product->leveringen->first()->leverancier->LeverancierNummer }}</div>
                    <div><span class="font-semibold">Mobiel:</span> {{ $product->leveringen->first()->leverancier->Mobiel }}</div>
                @endif
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="overflow-x-auto p-6">
                    <table class="jamin-table w-full border-collapse text-left">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-3">Naam Product</th>
                                <th class="px-4 py-3">Datum laatste levering</th>
                                <th class="px-4 py-3 text-right">Aantal</th>
                                <th class="px-4 py-3">Eerstvolgende levering</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @if($geenVoorraad)
                            <tr>
                                <td colspan="4" class="bg-amber-100 px-4 py-6 text-amber-900" role="alert">
                                    Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: {{ $verwachteLeveringZonderVoorraad }}
                                </td>
                            </tr>
                        @else
                            @forelse($product->leveringen as $levering)
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $product->Naam }}</td>
                                    <td class="px-4 py-3">{{ $levering->DatumLevering->format('d-m-Y') }}</td>
                                    <td class="px-4 py-3 text-right">{{ $levering->Aantal }}</td>
                                    <td class="px-4 py-3">{{ $levering->DatumEerstVolgendeLevering?->format('d-m-Y') ?? 'Onbekend' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-6 text-center">Geen leveringen gevonden.</td></tr>
                            @endforelse
                        @endif
                        </tbody>
                    </table>

                    @if($geenVoorraad)
                        <p class="mt-3 text-sm text-gray-500">Je wordt over 4 seconden teruggestuurd.</p>
                        <script>
                            window.setTimeout(() => window.location.href = @json(route('magazijn.index')), 4000);
                        </script>
                    @endif
                </div>
            </div>

            <a href="{{ route('magazijn.index') }}" class="mt-6 inline-block text-indigo-600 underline">Terug naar het magazijnoverzicht</a>
        </div>
    </div>
</x-app-layout>
