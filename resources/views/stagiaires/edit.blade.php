@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Modifier le stagiaire</h1>
        <a href="{{ route('stagiaires.show', $stagiaire) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Annuler</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('stagiaires.update', $stagiaire) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom *</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $stagiaire->first_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Nom *</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $stagiaire->last_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $stagiaire->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $stagiaire->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="school" class="block text-sm font-medium text-gray-700">École / Université</label>
                    <input type="text" name="school" id="school" value="{{ old('school', $stagiaire->school) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="major" class="block text-sm font-medium text-gray-700">Filière / Spécialité</label>
                    <input type="text" name="major" id="major" value="{{ old('major', $stagiaire->major) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div class="mt-6">
                <label for="cv" class="block text-sm font-medium text-gray-700">CV (PDF) – laissez vide pour conserver l'actuel</label>
                <input type="file" name="cv" id="cv" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept=".pdf">
                @if($stagiaire->cv_path)
                    <p class="mt-1 text-sm text-gray-500">Fichier actuel : <a href="{{ Storage::url($stagiaire->cv_path) }}" target="_blank" class="text-blue-600">Voir le CV</a></p>
                @endif
                @error('cv') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('stagiaires.show', $stagiaire) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Annuler</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection