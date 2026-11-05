<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-semibold">Welkom, {{ auth()->user()->name }}!</p>
                    <p class="mt-2">Je bent ingelogd met de rol
                        <span class="font-semibold">{{ auth()->user()->role }}</span>.
                    </p>
                    <p class="mt-4">
                        <a class="inline-flex rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700" href="{{ route('magazijn.index') }}">
                            Naar Overzicht Magazijn Jamin
                        </a>
                    </p>
                    <p class="mt-4">
                        <a class="inline-flex rounded-md bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700" href="{{ route('leveranciers.index') }}">
                            Naar Overzicht Leveranciers
                        </a>
                    </p>
                    @if(auth()->user()->isAdmin())
                        <p class="mt-4">
                            <a class="text-indigo-600 underline hover:text-indigo-900" href="{{ route('admin.users.index') }}">
                                Beheer gebruikersrollen
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
