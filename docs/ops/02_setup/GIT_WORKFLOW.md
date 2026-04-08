# Git Workflow (Agent-safe)

## Branching model
- Branch principale: `main` (protegee)
- Branches de travail:
  - `feat/<task-id>-<short-name>`
  - `fix/<task-id>-<short-name>`
  - `chore/<task-id>-<short-name>`

## Regles de contribution
- Aucun push direct sur `main`
- 1 tache = 1 branche
- PR obligatoire pour toute integration
- Commits atomiques, scopes clairs

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
- Au moins 1 review

## Merge policy
- Preferer `squash merge` pour garder un historique propre
- Interdire merge si checks rouges
- Tag de version pour chaque milestone sprint (`v0.x.0`)
