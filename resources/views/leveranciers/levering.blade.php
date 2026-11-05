<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Levering product</h2>
    </x-slot>

    @isset($foutmelding)
        <meta http-equiv="refresh" content="4;url={{ route('leveranciers.producten', $leverancier) }}">
    @endisset

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="p-6">
                    <dl class="mb-6 grid gap-2 text-gray-800 sm:grid-cols-[12rem_1fr]">
                        <dt class="font-semibold">Product:</dt><dd>{{ $product->Naam }}</dd>
                        <dt class="font-semibold">Leverancier:</dt><dd>{{ $leverancier->Naam }}</dd>
                        <dt class="font-semibold">Contactpersoon:</dt><dd>{{ $leverancier->ContactPersoon }}</dd>
                        <dt class="font-semibold">Mobiel:</dt><dd>{{ $leverancier->Mobiel }}</dd>
                    </dl>

                    @isset($foutmelding)
                        <div class="mb-6 rounded-md bg-red-100 p-4 font-semibold text-red-800" role="alert">
                            {{ $foutmelding }}
                        </div>
                    @endisset

                    <form method="POST" action="{{ route('leveranciers.leveringen.store', [$leverancier, $product]) }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="aantal" value="Aantal producteenheden" />
                            <x-text-input id="aantal" name="aantal" type="number" min="1" class="mt-1 block w-full" :value="old('aantal')" required />
                            <x-input-error :messages="$errors->get('aantal')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="datum_eerstvolgende_levering" value="Datum eerstvolgende levering" />
                            <x-text-input id="datum_eerstvolgende_levering" name="datum_eerstvolgende_levering" type="date" class="mt-1 block w-full" :value="old('datum_eerstvolgende_levering')" required />
                            <x-input-error :messages="$errors->get('datum_eerstvolgende_levering')" class="mt-2" />
                        </div>

                        <div class="flex flex-wrap justify-between gap-3 pt-2">
                            <x-primary-button>Sla op</x-primary-button>
                            <div class="flex gap-3">
                                <a class="inline-flex rounded-md bg-gray-200 px-4 py-2 text-gray-800 hover:bg-gray-300" href="{{ route('leveranciers.producten', $leverancier) }}">Terug</a>
                                <a class="inline-flex rounded-md bg-gray-700 px-4 py-2 text-white hover:bg-gray-800" href="{{ route('dashboard') }}">Home</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
