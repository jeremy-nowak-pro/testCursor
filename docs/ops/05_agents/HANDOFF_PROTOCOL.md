# Handoff Protocol (Standard)

Tous les agents doivent produire ce format en fin de tache.

## Header
- `Task ID`:
- `Agent`:
- `Date`:
- `Status`: done | blocked | partial

## Scope execute
- Objectif de la tache:
- Fichiers concernes:
- Hors scope confirme:

## Verification
- Tests executes:
- Resultats:
- Checks qualite (lint/build):

## Risques
- Risque technique:
- Impact:
- Mitigation proposee:

## Next handoff
- Agent destinataire:
- Prerequis de reprise:
- ETA recommandee:

## Regles
- Pas de handoff sans `Task ID`.
- Pas de status `done` sans verification explicite.
- Tout blocage va aussi dans `docs/ops/06_reviews/BLOCKERS.md`.
