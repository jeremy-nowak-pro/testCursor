# Blockers Register

Centralise tous les blocages inter-agents.

## Blocker Template
- `Blocker ID`:
- `Task ID`:
- `Reported by`:
- `Date`:
- `Severity`: critical | high | medium | low
- `Description`:
- `Dependency`:
- `Owner`:
- `ETA`:
- `Status`: open | in_progress | resolved
- `Resolution`:

## Priorisation
- Critical: bloque integration ou release
- High: bloque une equipe complete
- Medium: ralentit un flux non critique
- Low: contournement possible

## Open Blockers
- `Blocker ID`: BLK-CAT-010-ROUTES-PROPS
- `Task ID`: CAT-010
- `Reported by`: Agent Frontend
- `Date`: 2026-04-08
- `Severity`: high
- `Description`: Les routes backend catalogue et le mapping Inertia associe ne sont pas encore exposes dans `routes/web.php` (`/categories/{slug}/products`, `/products/{slug}`) pour brancher le parcours home -> categorie -> detail de bout en bout.
- `Dependency`: Livraison backend CAT-002 et CAT-003 (routes + props conformes `API_CONTRACTS.md`).
- `Owner`: Agent Backend (CAT-002/CAT-003)
- `ETA`: 2026-04-09
- `Status`: open
- `Resolution`: En attente des routes et props backend; UI preparee cote frontend sur pages `Catalog/CategoryListing` et `Catalog/ProductShow`.
