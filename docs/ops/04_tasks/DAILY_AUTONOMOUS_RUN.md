# Daily Autonomous Run

## Objectif
Executer un cycle quotidien multi-agents stable, mesurable et reproductible.

## Timeboxes recommandes
- T0 (debut): planification et assignation
- T1 (milieu): sync rapide et gestion blocages
- T2 (fin): integration, QA, decision

## Cycle
1. L'orchestrateur lit backlog + sprint + blockers.
2. Il assigne les `Task ID` aux agents selon dependances.
3. Chaque agent execute son scope et produit un handoff standard.
4. QA valide les taches `done` et classe les anomalies.
5. L'orchestrateur decide:
   - Go integration si aucun blocant
   - Relance corrective sinon
6. Docs/release met a jour l'etat du sprint.

## KPIs de pilotage
- Nombre de taches `done` par jour
- Taux de blocages ouverts/fermes
- Lead time par tache
- Taux de rework apres QA

## Regles
- Pas de nouvelle tache si bloqueur critical non adresse.
- Toute tache integree doit avoir tests et checks qualite valides.
