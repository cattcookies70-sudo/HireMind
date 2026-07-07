@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Mes stagiaires</h1>
        <a href="{{ route('stagiaires.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
            + Ajouter un stagiaire
        </a>
    </div>

    {{-- Formulaire de recherche et filtres --}}
    <form method="GET" action="{{ route('stagiaires.index') }}" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Recherche globale --}}
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Rechercher</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Nom, prénom, email..."
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Filtre école --}}
            <div>
                <label for="school_filter" class="block text-sm font-medium text-gray-700">École</label>
                <select name="school_filter" id="school_filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Toutes</option>
                    @foreach($schools as $school)
                        <option value="{{ $school }}" {{ request('school_filter') == $school ? 'selected' : '' }}>
                            {{ $school }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre filière --}}
            <div>
                <label for="major_filter" class="block text-sm font-medium text-gray-700">Filière</label>
                <select name="major_filter" id="major_filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Toutes</option>
                    @foreach($majors as $major)
                        <option value="{{ $major }}" {{ request('major_filter') == $major ? 'selected' : '' }}>
                            {{ $major }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tri --}}
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Trier par</label>
                <select name="sort" id="sort" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date d'ajout</option>
                    <option value="first_name" {{ request('sort') == 'first_name' ? 'selected' : '' }}>Prénom</option>
                    <option value="last_name" {{ request('sort') == 'last_name' ? 'selected' : '' }}>Nom</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                </select>
                <div class="mt-1 flex gap-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="direction" value="asc" {{ request('direction', 'desc') == 'asc' ? 'checked' : '' }}>
                        <span class="ml-1 text-sm">Croissant</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="direction" value="desc" {{ request('direction', 'desc') == 'desc' ? 'checked' : '' }}>
                        <span class="ml-1 text-sm">Décroissant</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Appliquer les filtres
            </button>
            <a href="{{ route('stagiaires.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                Réinitialiser
            </a>
        </div>
    </form>

    {{-- Tableau des stagiaires --}}
    @if($stagiaires->count())
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CV</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stagiaires as $stagiaire)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stagiaire->first_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stagiaire->last_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stagiaire->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stagiaire->phone ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stagiaire->school ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $stagiaire->major ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($stagiaire->cv_path)
                                    <a href="{{ Storage::url($stagiaire->cv_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900">Voir</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('stagiaires.show', $stagiaire) }}" class="text-blue-600 hover:text-blue-900 mr-3">Détails</a>
                                <a href="{{ route('stagiaires.edit', $stagiaire) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                                <form action="{{ route('stagiaires.destroy', $stagiaire) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce stagiaire ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $stagiaires->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500">Aucun stagiaire trouvé.</p>
            <a href="{{ route('stagiaires.create') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Ajouter votre premier stagiaire
            </a>
        </div>
    @endif
</div>
@endsection