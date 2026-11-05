<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gebruikersrollen beheren</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded bg-red-100 p-4 text-red-800">{{ $errors->first() }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3">Naam</th>
                                <th class="py-3">E-mail</th>
                                <th class="py-3">Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="border-b last:border-0">
                                    <td class="py-3">{{ $user->name }}</td>
                                    <td class="py-3">{{ $user->email }}</td>
                                    <td class="py-3">
                                        <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="rounded border-gray-300">
                                                <option value="gebruiker" @selected($user->role === 'gebruiker')>Gebruiker</option>
                                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                            </select>
                                            <button class="rounded bg-indigo-600 px-3 py-2 text-white hover:bg-indigo-700">Opslaan</button>
                                        </form>
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
