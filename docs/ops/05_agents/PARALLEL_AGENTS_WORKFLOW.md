# Parallel Agents Workflow

Objectif: permettre a des agents **backend**, **frontend**, **QA**, etc. de travailler **en parallele** avec un minimum de conflits, jusqu'a ce que le responsable ne fasse plus que **reviewer les PR** vers `develop`.

## Principes

1. **Un Task ID = une branche = une PR** (sauf exception documentee).
2. **Contrat d'abord**: `docs/ops/03_specs/API_CONTRACTS.md` et pages Inertia attendues doivent etre alignes avant merge des lots dependants.
3. **Zones de fichiers**: chaque agent a un perimetre de chemins (voir `docs/ops/04_tasks/TASK_IDS.md`).
4. **Dependances explicites**: toute attente entre agents va dans `docs/ops/06_reviews/BLOCKERS.md` avec owner et ETA.
5. **Integration sur `develop` uniquement** via PR; pas de push direct.

## Mode d'execution recommande

### Option A — API puis UI (moins de friction)

1. Agent Backend merge la PR du Task ID catalogue API (`develop` a les routes + donnees).
2. Agent Frontend ouvre sa PR sur la meme base `develop` a jour et branche les pages Inertia.

### Option B — Parallele avec contrat fige

1. Mini-PR ou commit de **contrat seul** (routes nommees, signatures Inertia documentees, seed minimal).
2. Backend et Frontend en parallele sur des branches differentes; le Front peut utiliser des **donnees de dev** coherentes avec le contrat.
3. Merge **API avant UI** si conflit, ou resolution de blocage dans `BLOCKERS.md`.

## Roles et livrables

| Role | Branche type | Livrable PR |
|------|----------------|-------------|
| Backend | `feat/<TASK-ID>-slug-api` | Migrations, controllers, FormRequest, tests Feature |
| Frontend | `feat/<TASK-ID>-slug-ui` | Pages Inertia, composants, pas de logique metier serveur |
| QA | branche de test ou review sur PR | Rapport, go/no-go |
| Docs/Release | `chore/<TASK-ID>-docs` | Specs, changelog si besoin |

## Definition of Done (par PR)

- [ ] `Task ID` dans le titre ou la description de la PR
- [ ] Scope respecte (fichiers dans la zone assignee)
- [ ] `HANDOFF_PROTOCOL` rempli en commentaire PR ou fichier lie
- [ ] CI verte sur la PR vers `develop`
- [ ] Pas de modification non review des fichiers hors scope sans accord

## Ce qui reste humain

- Arbitrage si deux PR touchent le meme fichier
- Merge `develop` -> `main` (release)
- Decisions produit / UX non couvertes par les specs

## Liens utiles

- Registre des Task IDs: `docs/ops/04_tasks/TASK_IDS.md`
- Backlog priorise: `docs/ops/04_tasks/BACKLOG.md`
- Handoff: `docs/ops/05_agents/HANDOFF_PROTOCOL.md`
- Orchestrateur: `docs/ops/05_agents/ORCHESTRATOR.md`

## Exemple catalogue MVP (decoupage)

| Ordre | Task ID | Agent | Branche suggeree | Depend de |
|-------|---------|-------|------------------|-----------|
| 1 | CAT-001 | Backend | `feat/CAT-001-catalog-models` | — |
| 2 | CAT-002 | Backend | `feat/CAT-002-category-listing-api` | CAT-001 |
| 3 | CAT-003 | Backend | `feat/CAT-003-product-show-api` | CAT-001 |
| 4 | CAT-010 | Frontend | `feat/CAT-010-catalog-pages-ui` | CAT-002, CAT-003 (ou contrat + seed) |
| 5 | CAT-020 | QA | review sur PR | CAT-010 merge |

En parallele strict: CAT-002 et CAT-003 peuvent etre deux agents backend **si** CAT-001 est merge et qu'ils ne modifient pas les memes fichiers (ex. un fichier service filtres partage = un seul agent ou ordre sequentiel).
