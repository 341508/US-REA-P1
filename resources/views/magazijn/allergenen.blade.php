<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Overzicht Allergenen</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-6 space-y-3 rounded-lg bg-white p-6 shadow-sm">
                <div><span class="font-semibold">Naam product:</span> {{ $product->Naam }}</div>
                <div><span class="font-semibold">Barcode:</span> {{ $product->Barcode }}</div>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="overflow-x-auto p-6">
                    <table class="jamin-table w-full border-collapse text-left">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-3">Naam</th>
                                <th class="px-4 py-3">Omschrijving</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if($product->allergenen->isEmpty())
                                <tr>
                                    <td colspan="2" class="bg-amber-100 px-4 py-6 text-amber-900" role="alert">
                                        In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken
                                    </td>
                                </tr>
                            @else
                                @foreach($product->allergenen as $allergeen)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $allergeen->Naam }}</td>
                                        <td class="px-4 py-3">{{ $allergeen->Omschrijving }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    @if($product->allergenen->isEmpty())
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
