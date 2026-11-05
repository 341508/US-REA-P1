<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Overzicht Magazijn Jamin</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="overflow-x-auto p-6">
                    <table class="jamin-table w-full border-collapse text-left">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-3">Barcode</th>
                                <th class="px-4 py-3">Naam</th>
                                <th class="px-4 py-3 text-right">Verpakkingseenheid</th>
                                <th class="px-4 py-3 text-right">AantalAanwezig</th>
                                <th class="px-4 py-3 text-center">Allergenen Info</th>
                                <th class="px-4 py-3 text-center">Leverantie Info</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($producten as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono">{{ $product->Barcode }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $product->Naam }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($product->magazijn->VerpakkingsEenheid, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right">{{ $product->magazijn->AantalAanwezig ?? 'Geen voorraad' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('magazijn.allergenen', $product) }}"
                                           aria-label="Allergeneninformatie van {{ $product->Naam }}"
                                           title="Allergeneninformatie"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-red-600 text-lg font-bold text-white hover:bg-red-700">×</a>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('magazijn.leveringen', $product) }}"
                                           aria-label="Leveringsinformatie van {{ $product->Naam }}"
                                           title="Leveringsinformatie"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-lg font-bold text-white hover:bg-blue-700">?</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
