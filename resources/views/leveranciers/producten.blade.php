<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Geleverde producten</h2>
    </x-slot>

    @if($producten->isEmpty())
        <meta http-equiv="refresh" content="3;url={{ route('leveranciers.index') }}">
    @endif

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="p-6">
                    <dl class="mb-6 grid gap-2 text-gray-800 sm:grid-cols-[14rem_1fr]">
                        <dt class="font-semibold">Naam leverancier:</dt><dd>{{ $leverancier->Naam }}</dd>
                        <dt class="font-semibold">Contactpersoon:</dt><dd>{{ $leverancier->ContactPersoon }}</dd>
                        <dt class="font-semibold">Leveranciernummer:</dt><dd>{{ $leverancier->LeverancierNummer }}</dd>
                        <dt class="font-semibold">Mobiel:</dt><dd>{{ $leverancier->Mobiel }}</dd>
                    </dl>

                    <div class="overflow-x-auto">
                        <table class="jamin-table w-full border-collapse text-left">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="px-4 py-3">Naam product</th>
                                    <th class="px-4 py-3 text-right">Aantal in Magazijn</th>
                                    <th class="px-4 py-3 text-right">Verpakkingseenheid</th>
                                    <th class="px-4 py-3">Laatste levering</th>
                                    <th class="px-4 py-3 text-center">Nieuwe levering</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($producten as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium">{{ $product->ProductNaam }}</td>
                                        <td class="px-4 py-3 text-right">{{ $product->AantalAanwezig ?? 'Geen voorraad' }}</td>
                                        <td class="px-4 py-3 text-right">{{ number_format($product->VerpakkingsEenheid, 1, ',', '.') }} kg</td>
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($product->LaatsteLevering)->format('d-m-Y') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-xl font-bold text-gray-500" title="Nieuwe levering wordt in userstory 2 toegevoegd">+</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center font-semibold text-gray-700">
                                            Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a class="inline-flex rounded-md bg-gray-200 px-4 py-2 text-gray-800 hover:bg-gray-300" href="{{ route('leveranciers.index') }}">Terug</a>
                        <a class="inline-flex rounded-md bg-gray-700 px-4 py-2 text-white hover:bg-gray-800" href="{{ route('dashboard') }}">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
