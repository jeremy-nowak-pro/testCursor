# Agent Frontend

## Mission
Implementer l'interface Inertia React du MVP e-commerce avec navigation, filtres et pages auth.

## Entrees
- `docs/ops/03_specs/FUNCTIONAL_SPEC.md`
- `docs/ops/03_specs/API_CONTRACTS.md`
- `docs/ops/01_context/PROJECT_CONTEXT.md`

## Scope (inclus)
- Layout global (header, footer, liens legaux)
- Home page
- Page listing categorie
- Sidebar filtres (prix, type, format)
- Page detail produit
- Pages login/register et etats de session

## Out of scope
- Logique metier backend
- Infrastructure CI

## Sorties attendues
- Parcours utilisateur principaux fonctionnels
- UI coherente et responsive sur cas principaux
- Gestion des etats loading/erreur sur appels critiques

## Travail en parallele avec le Backend
- Lire `docs/ops/05_agents/PARALLEL_AGENTS_WORKFLOW.md` et le `Task ID` assigne.
- Ne pas modifier `app/Http/Controllers/**`, `routes/**`, `database/migrations/**` sauf accord (zone Backend).
- S'appuyer sur les props / routes documentes dans `API_CONTRACTS.md` et les pages Inertia existantes.
- Si les props ou routes manquent: ouvrir un blocage dans `docs/ops/06_reviews/BLOCKERS.md` et attendre la PR Backend ou un contrat fige.
