# Git Workflow (Agent-safe)

## Branching model
- Branch principale: `main` (release, protegee)
- Branch relais: `develop` (integration, protegee)
- Branches de travail:
  - `feat/<task-id>-<short-name>`
  - `fix/<task-id>-<short-name>`
  - `chore/<task-id>-<short-name>`

## Regles de contribution
- Aucun push direct sur `main` ni `develop`
- 1 tache = 1 branche
- PR obligatoire pour toute integration
- Commits atomiques, scopes clairs

## Flux officiel
1. Creer la branche depuis `develop`.
2. Ouvrir une PR vers `develop`.
3. Valider checks CI et review.
4. Merger vers `develop`.
5. Ouvrir une PR `develop` vers `main` pour release.
6. Tagger la release sur `main`.

## Cibles de PR
- `feat/*`, `fix/*`, `chore/*` -> `develop`
- `develop` -> `main` (release uniquement)

## Commit convention (Conventional Commits)
- `feat:`
- `fix:`
- `chore:`
- `docs:`
- `test:`
- `refactor:`

Exemple:
- `feat(catalog): add category filters [TASK-CAT-003]`

## PR gates obligatoires
- Lint vert
- Tests verts
- Build vert
- Au moins 1 review (ou 0 en mode solo si la ruleset est assouplie)

## Merge policy
- Preferer `squash merge` pour garder un historique propre
- Interdire merge si checks rouges
- Tag de version pour chaque milestone sprint (`v0.x.0`)

## Regleset GitHub attendues
- `main`:
  - PR obligatoire
  - Checks obligatoires: `lint`, `test`, `build`
  - Force push interdit, suppression interdite
- `develop`:
  - PR obligatoire
  - Checks obligatoires: `lint`, `test`, `build`
  - Force push interdit, suppression interdite
