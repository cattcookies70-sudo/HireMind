<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

class GroqCvParserService
{
    /**
     * Extrait les informations d'un fichier PDF via l'API Groq.
     *
     * @param string $filePath Chemin absolu du fichier PDF
     * @return array
     * @throws \Exception
     */
    public function parseCvFile(string $filePath): array
    {
        // 1. Lecture du PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        // Nettoyage du texte (supprime les sauts de ligne superflus)
        $text = preg_replace('/\s+/', ' ', $text);

        if (empty(trim($text))) {
            throw new \Exception('Le PDF ne contient pas de texte lisible. (Peut-être un scan ?)');
        }

        // 2. Appel à l'API Groq
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.groq.api_key'),
            'Content-Type'  => 'application/json',
        ])
        // ⚠️ Désactiver la vérification SSL UNIQUEMENT en développement local
        // → À SUPPRIMER ou remplacer par 'verify' => true en production
        ->withOptions([
            'verify' => false,
        ])
        ->post(config('services.groq.api_url'), [
            'model' => config('services.groq.model', 'llama3-8b-8192'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un assistant spécialisé dans l\'extraction de données de CV. 
                    Extrais le prénom (first_name), le nom (last_name), l\'email (email) et le téléphone (phone). 
                    Réponds UNIQUEMENT en JSON valide, sans texte supplémentaire. 
                    Format: {"first_name": "...", "last_name": "...", "email": "...", "phone": "..."}. 
                    Si un champ est absent, mets "Non renseigné".'
                ],
                [
                    'role' => 'user',
                    'content' => "Voici le texte du CV :\n\n" . $text
                ]
            ],
            'temperature' => 0.1,
            'response_format' => ['type' => 'json_object']
        ]);

        // 3. Gestion des erreurs de l'API
        if ($response->failed()) {
            throw new \Exception('Erreur API Groq : ' . $response->body());
        }

        // 4. Extraction du contenu JSON
        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '{}';
        $extracted = json_decode($content, true);

        // 5. Retour des champs (avec valeurs par défaut)
        return [
            'first_name' => $extracted['first_name'] ?? '',
            'last_name'  => $extracted['last_name'] ?? '',
            'email'      => $extracted['email'] ?? '',
            'phone'      => $extracted['phone'] ?? '',
        ];
    }
}