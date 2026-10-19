# fundisc 🛒

> E-commerce généraliste — ventes en ligne et caisse marché.

[![PHP](https://img.shields.io/badge/PHP-8.1+-blue)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D)](https://vuejs.org)
[![Stripe](https://img.shields.io/badge/Paiement-Stripe-635BFF)](https://stripe.com)

## 🎯 À propos

fundisc est une boutique en ligne Laravel avec un **catalogue public**, un **tunnel de vente** complet, un **dashboard administrateur** et une caisse **mode marché** pour les ventes en direct (salons, marchés, événements).

Le projet est configuré et déployable sur un hébergement classique (Nginx + PHP-FPM).

## 🚀 Fonctionnalités principales

- **Catalogue public** — consultation des produits, fiches détaillées, avis clients.
- **Panier et commandes** — panier session + fusion à la connexion, adresses livraison/facturation, historique client.
- **Paiement Stripe** — checkout Stripe, webhooks, pages succès/annulation.
- **RBAC** — rôles `admin`, `employé` et `client` via middleware personnalisé.
- **Mode marché** — caisse emport avec historique des ventes, annulation et restock, export CSV.
- **Gestion produits** — CRUD vinyles, gestion des fonds (stock miroir/doré), galerie photos.
- **Alertes stock** — suivi des seuils et historique des alertes.
- **Rapports** — exports PDF/CSV et statistiques mensuelles.
- **Messagerie** — conversations clients et messages de contact côté admin.

## 🛠️ Stack technique

| Composant | Technologie |
|-----------|-------------|
| Backend | PHP 8.1+, Laravel 10.x |
| Frontend | Vue.js 3, Vite, Tailwind CSS, Bootstrap 5 |
| Base de données | MySQL 8.0+ (production), SQLite (tests) |
| Auth | Laravel Breeze + rôles custom |
| Paiement | Stripe |
| Médias | Spatie Laravel Media Library |
| Sauvegardes | Spatie Laravel Backup |
| Tests | PHPUnit |

## 📚 Documentation

La documentation technique et opérationnelle se trouve dans le dossier [`docs/`](docs/) :

| Document | Description |
|----------|-------------|
| [Architecture](docs/ARCHITECTURE.md) | Structure, flux de données, RBAC |
| [Déploiement](docs/DEPLOYMENT.md) | Guide Nginx + SSL + envoi en production |
| [Post-déploiement](docs/POST-DEPLOYMENT.md) | Checklist de validation |
| [Troubleshooting](docs/TROUBLESHOOTING.md) | Erreurs courantes |
| [Mode Marché](docs/MODE_MARCHE_SPECS.md) | Spécifications de la caisse emport |

## 🧪 Tests

```bash
# Tous les tests
php artisan test

# Par catégorie
php artisan test tests/Feature/Security/
php artisan test tests/Feature/ModeMarche/
php artisan test tests/Feature/Performance/
```

## 🚀 Installation locale

### Prérequis

- PHP 8.1+
- Composer 2.x
- Node.js 18+
- MySQL 8.0+ (ou SQLite pour tester)

### Installation

```bash
# 1. Cloner
git clone https://github.com/LittllMind/fundisc.git
cd fundisc

# 2. Dépendances PHP
composer install

# 3. Dépendances frontend
npm ci

# 4. Configuration
cp .env.example .env
php artisan key:generate

# 5. Éditer .env
# DB_DATABASE=fundisc
# DB_USERNAME=root
# DB_PASSWORD=secret
# STRIPE_KEY=sk_test_...
# STRIPE_WEBHOOK_SECRET=whsec_...

# 6. Base de données
php artisan migrate --seed

# 7. Assets
npm run build

# 8. Lancer
php artisan serve
```

### Vérification

```bash
./scripts/health-check.sh --verbose
```

## 🖥️ Utilisation

| Zone | URL locale | Accès |
|------|------------|-------|
| Accueil / catalogue | `http://localhost:8000/` | Public |
| Catalogue | `http://localhost:8000/kiosque` | Public |
| Panier | `http://localhost:8000/cart` | Public |
| Espace client | `http://localhost:8000/dashboard` | Connecté |
| Administration | `http://localhost:8000/admin/dashboard` | Admin / Employé |
| Mode marché | `http://localhost:8000/admin/marche` | Admin / Employé |

## 📦 Structure du projet

```
fundisc/
├── app/
│   ├── Http/Controllers/    # Contrôleurs web et admin
│   ├── Models/              # Eloquent + relations
│   ├── Services/            # Logique métier (panier, stock...)
│   └── Policies/            # Autorisations RBAC
├── database/
├── resources/js/            # Vue.js + Vite
├── routes/web.php           # Routes principales
├── tests/Feature/           # Tests fonctionnels
└── docs/                    # Documentation
```

## 📸 Démo / captures

Aucune démo publique ni capture d'écran n'est disponible actuellement.

## 📄 Licence

MIT License — voir [LICENSE](LICENSE)

---

*Dernière mise à jour : octobre 2026*
