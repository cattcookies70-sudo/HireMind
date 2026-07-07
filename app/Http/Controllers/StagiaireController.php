<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Services\GroqCvParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StagiaireController extends Controller
{
    use AuthorizesRequests;

    protected GroqCvParserService $cvParser;

    public function __construct(GroqCvParserService $cvParser)
    {
        $this->cvParser = $cvParser;
    }

    /**
     * Affiche la liste des stagiaires avec recherche, filtres et tri.
     */
    public function index(Request $request)
    {
        $query = Stagiaire::where('user_id', Auth::id());

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('school', 'LIKE', "%{$search}%")
                  ->orWhere('major', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par école
        if ($request->filled('school_filter')) {
            $query->where('school', $request->input('school_filter'));
        }

        // Filtre par filière
        if ($request->filled('major_filter')) {
            $query->where('major', $request->input('major_filter'));
        }

        // Tri
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);

        // Pagination (10 par page)
        $stagiaires = $query->paginate(10)->withQueryString();

        // Récupérer les valeurs distinctes pour les filtres déroulants
        $schools = Stagiaire::where('user_id', Auth::id())->distinct()->pluck('school');
        $majors  = Stagiaire::where('user_id', Auth::id())->distinct()->pluck('major');

        return view('stagiaires.index', compact('stagiaires', 'schools', 'majors'));
    }

    /**
     * Affiche le formulaire de création d'un stagiaire.
     */
    public function create()
    {
        return view('stagiaires.create');
    }

    /**
     * Enregistre un nouveau stagiaire.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:stagiaires,email',
            'phone'      => 'nullable|string|max:50',
            'school'     => 'nullable|string|max:255',
            'major'      => 'nullable|string|max:255',
            'cv'         => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = collect($validated)->except('cv')->toArray();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('cvs', 'public');
            $data['cv_path'] = $path;
        }

        $stagiaire = Stagiaire::create($data);

        return redirect()
            ->route('stagiaires.show', $stagiaire)
            ->with('success', 'Stagiaire créé avec succès.');
    }

    /**
     * Affiche les détails d'un stagiaire.
     */
    public function show(Stagiaire $stagiaire)
    {
        $this->authorize('view', $stagiaire);
        return view('stagiaires.show', compact('stagiaire'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(Stagiaire $stagiaire)
    {
        $this->authorize('update', $stagiaire);
        return view('stagiaires.edit', compact('stagiaire'));
    }

    /**
     * Met à jour un stagiaire existant.
     */
    public function update(Request $request, Stagiaire $stagiaire)
    {
        $this->authorize('update', $stagiaire);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:stagiaires,email,' . $stagiaire->id,
            'phone'      => 'nullable|string|max:50',
            'school'     => 'nullable|string|max:255',
            'major'      => 'nullable|string|max:255',
            'cv'         => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = collect($validated)->except('cv')->toArray();

        if ($request->hasFile('cv')) {
            // Supprimer l'ancien fichier si présent
            if ($stagiaire->cv_path) {
                Storage::disk('public')->delete($stagiaire->cv_path);
            }
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        $stagiaire->update($data);

        return redirect()
            ->route('stagiaires.show', $stagiaire)
            ->with('success', 'Stagiaire mis à jour avec succès.');
    }

    /**
     * Supprime un stagiaire.
     */
    public function destroy(Stagiaire $stagiaire)
    {
        $this->authorize('delete', $stagiaire);

        if ($stagiaire->cv_path) {
            Storage::disk('public')->delete($stagiaire->cv_path);
        }

        $stagiaire->delete();

        return redirect()
            ->route('stagiaires.index')
            ->with('success', 'Stagiaire supprimé avec succès.');
    }

    /**
     * Endpoint AJAX pour extraire les informations d'un CV (PDF) via Groq.
     */
    public function parseCv(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf|max:20480', // 20 Mo max
        ]);

        // Stockage temporaire
        $tempPath = $request->file('cv')->store('cvs/temp', 'local');
        $absolutePath = Storage::disk('local')->path($tempPath);

        try {
            $extracted = $this->cvParser->parseCvFile($absolutePath);
            return response()->json([
                'success' => true,
                'data'    => $extracted,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de l'extraction : " . $e->getMessage(),
            ], 500);
        } finally {
            Storage::disk('local')->delete($tempPath);
        }
    }
}