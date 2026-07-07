@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Ajouter un stagiaire</h1>

    {{-- Bloc d'extraction --}}
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Extraire automatiquement depuis un CV (PDF)</h2>
        <form id="extract-form" enctype="multipart/form-data" class="flex items-end gap-4 flex-wrap">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label for="cv_file" class="block text-sm font-medium text-gray-700">Choisir un fichier PDF</label>
                <input type="file" name="cv" id="cv_file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept=".pdf" required>
            </div>
            <button type="button" id="extract-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Extraire les informations
            </button>
        </form>
        <div id="extract-result" class="mt-4"></div>
    </div>

    {{-- Formulaire de création --}}
    <form method="POST" action="{{ route('stagiaires.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="school" class="block text-sm font-medium text-gray-700">École / Université</label>
                <input type="text" name="school" id="school" value="{{ old('school') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
                <label for="major" class="block text-sm font-medium text-gray-700">Filière / Spécialité</label>
                <input type="text" name="major" id="major" value="{{ old('major') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>

        <div class="mt-6">
            <label for="cv" class="block text-sm font-medium text-gray-700">CV (PDF) - optionnel</label>
            <input type="file" name="cv" id="cv" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept=".pdf">
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md">
                Créer le stagiaire
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const extractBtn = document.getElementById('extract-btn');
    const fileInput = document.getElementById('cv_file');
    const resultDiv = document.getElementById('extract-result');

    if (extractBtn) {
        extractBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!fileInput.files.length) {
                resultDiv.innerHTML = '<div class="text-yellow-600 bg-yellow-100 p-3 rounded">Veuillez sélectionner un fichier PDF.</div>';
                return;
            }

            const formData = new FormData();
            formData.append('cv', fileInput.files[0]);

            extractBtn.disabled = true;
            extractBtn.textContent = 'Extraction en cours...';
            resultDiv.innerHTML = '<div class="text-blue-600 bg-blue-100 p-3 rounded">Traitement en cours...</div>';

            fetch('{{ route("stagiaires.parse-cv") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('first_name').value = data.data.first_name || '';
                    document.getElementById('last_name').value = data.data.last_name || '';
                    document.getElementById('email').value = data.data.email || '';
                    document.getElementById('phone').value = data.data.phone || '';
                    resultDiv.innerHTML = '<div class="text-green-600 bg-green-100 p-3 rounded">Informations extraites avec succès !</div>';
                } else {
                    resultDiv.innerHTML = '<div class="text-red-600 bg-red-100 p-3 rounded">Erreur : ' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                resultDiv.innerHTML = '<div class="text-red-600 bg-red-100 p-3 rounded">Une erreur réseau est survenue.</div>';
            })
            .finally(() => {
                extractBtn.disabled = false;
                extractBtn.textContent = 'Extraire les informations';
            });
        });
    }
});
</script>
@endpush