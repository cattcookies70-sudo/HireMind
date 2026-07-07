@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Détails du stagiaire</h1>
        <div>
            <a href="{{ route('stagiaires.edit', $stagiaire) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md mr-2">Modifier</a>
            <a href="{{ route('stagiaires.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Retour à la liste</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Prénom</dt>
                    <dd class="text-lg">{{ $stagiaire->first_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nom</dt>
                    <dd class="text-lg">{{ $stagiaire->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-lg">{{ $stagiaire->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                    <dd class="text-lg">{{ $stagiaire->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">École / Université</dt>
                    <dd class="text-lg">{{ $stagiaire->school ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Filière / Spécialité</dt>
                    <dd class="text-lg">{{ $stagiaire->major ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">CV</dt>
                    <dd class="text-lg">
                        @if($stagiaire->cv_path)
                            <a href="{{ Storage::url($stagiaire->cv_path) }}" target="_blank" class="text-blue-600 hover:underline">Télécharger / Voir le CV</a>
                        @else
                            Aucun CV
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ajouté le</dt>
                    <dd class="text-lg">{{ $stagiaire->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection