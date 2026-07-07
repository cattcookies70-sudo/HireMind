# 🧠 HireMind – Gestion intelligente des stagiaires avec extraction IA

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-13.18-red.svg)](https://laravel.com)
[![Groq API](https://img.shields.io/badge/Groq-LLaMA%203.1-orange.svg)](https://groq.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**HireMind** est une application web de gestion de stagiaires développée avec Laravel. Elle permet de créer, modifier, consulter et supprimer des fiches stagiaires, tout en intégrant une fonctionnalité d'**extraction automatique des données (prénom, nom, email, téléphone)** à partir d’un fichier PDF de CV, grâce à l’API **Groq** et son modèle de langage **LLaMA 3.1**.

> 🔥 L’extraction des données est réalisée en appelant une IA générative, sans apprentissage préalable : le modèle lit le texte du PDF et restitue un JSON structuré.

---

## 🎯 Fonctionnalités principales

- ✅ Authentification sécurisée (Laravel Breeze)
- ✅ CRUD complet des stagiaires
- ✅ Extraction automatique des données depuis un CV (PDF) via API Groq
- ✅ Remplissage instantané du formulaire (AJAX)
- ✅ Tableau de bord avec :
  - 🔍 Recherche textuelle globale
  - 🏷️ Filtres par école / filière
  - 🔁 Tri (date, nom, prénom, email) ascendant / descendant
  - 📄 Pagination Laravel
- ✅ Upload et stockage des CV (disque public / local)
- ✅ Suppression automatique des fichiers à la suppression du stagiaire
- ✅ Politiques d’autorisation (Policy) pour sécuriser l’accès aux données

---

## 🧠 Stack technique

| Composant       | Technologie                                     |
|-----------------|-------------------------------------------------|
| Framework       | Laravel 13.18                                   |
| Langage         | PHP 8.4                                         |
| Base de données | SQLite (intègre, configurable vers MySQL/PgSQL) |
| Frontend        | Blade + Tailwind CSS                            |
| IA / NLP        | Groq API (modèle `llama-3.1-8b-instant`)        |
| PDF parsing     | `smalot/pdfparser`                              |
| HTTP Client     | Guzzle (intégré à Laravel)                      |
| Assets          | Laravel Vite / NPM                              |

---

## 📦 Installation

### Prérequis

- PHP 8.4+
- Composer
- Node.js & NPM
- SQLite (ou MySQL / PostgreSQL)

### Étapes d’installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/votre_pseudo/HireMind.git
cd HireMind

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances frontend
npm install
npm run build

# 4. Configurer l’environnement
cp .env.example .env

# 5. Générer la clé de l’application
php artisan key:generate

# 6. Configurer la base de données SQLite
touch database/database.sqlite   # sur Linux/macOS
# ou
New-Item -Path database/database.sqlite -ItemType File   # sur Windows (PowerShell)

# 7. Configurer votre clé API Groq dans le .env
# GROQ_API_KEY=votre_cle_api_ici
# GROQ_MODEL=llama-3.1-8b-instant

# 8. Exécuter les migrations
php artisan migrate

# 9. Créer le lien symbolique pour les fichiers publics
php artisan storage:link

# 10. Lancer le serveur
php artisan serve --port=8002