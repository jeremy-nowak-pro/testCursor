# Task ID Registry

Convention: `XXX-NNN` avec prefixe domaine et numero (ex. `CAT-001`).

## Prefixes actifs

| Prefixe | Domaine |
|---------|---------|
| CAT | Catalogue (categories, produits, filtres) |
| CART | Panier |
| CHK | Checkout / Stripe |
| LEG | Pages legales / cookies |
| AUTH | Auth (deja couvert par Breeze; reserver extensions) |
| OPS | Operations, CI, docs repo |

## Regles

- Un **Task ID** unique par lot livrable (une PR principale).
- Le nom de branche recommande: `feat/<TASK-ID>-kebab-description`.
- Mettre le Task ID dans le **titre de commit** ou la **description de PR**.

## Catalogue MVP (registre initial)

| ID | Titre | Agent principal | Statut |
|----|-------|-----------------|--------|
| CAT-001 | Modeles + migrations Category/Product + seed minimal | Backend | planned |
| CAT-002 | Listing categorie + filtres (Inertia props alignes contrat) | Backend | planned |
| CAT-003 | Page detail produit (Inertia props alignes contrat) | Backend | planned |
| CAT-010 | Pages Inertia: home, listing, detail, layout header/footer filtres | Frontend | planned |
| CAT-020 | Tests feature + parcours critique (auth deja existant) | QA | planned |
| OPS-010 | CI applicative (PHPUnit + build front) | Setup/DevOps | in_progress |

Mettre a jour la colonne **Statut** au fil des merges: `planned` -> `in_progress` -> `done`.

## Zones de fichiers (eviter les conflits)

**Backend (typique)**

- `app/Http/Controllers/**` (sauf si page-only)
- `app/Models/**`
- `database/migrations/**`
- `routes/web.php` (routes catalogue)
- `tests/Feature/**`

**Frontend (typique)**

- `resources/js/Pages/**`
- `resources/js/Components/**` (catalogue)
- `resources/css/**` (si scope UI)

Ne pas melanger sans coordination: `routes/web.php` (souvent backend d'abord).
