# Backlog

Priorite: haut vers bas. Chaque item lie a un **Task ID** (voir `TASK_IDS.md`).

## Phase socle (fait / en cours)

- [x] Finaliser le contexte projet e-commerce mono-vendeur B2C (EUR, Stripe) — contexte doc
- [x] Initialiser projet Laravel avec Inertia React — bootstrap merge dans `develop`
- [x] Configurer auth Laravel (register/login/logout) — Breeze
- [ ] Configurer CI applicative (PHPUnit, build front) quand le socle catalogue est pret

## Catalogue MVP (parallele possible apres CAT-001)

- [ ] **CAT-001** — Modeles + migrations Category/Product + factories/seeders
- [ ] **CAT-002** — Route + controller listing `/categories/{slug}` + filtres query + pagination
- [ ] **CAT-003** — Route + controller detail `/products/{slug}`
- [ ] **CAT-010** — UI Inertia: accueil, listing, filtres sidebar, fiche produit, layout header/footer
- [ ] **CAT-020** — Campagne tests feature + parcours critique catalogue

## Suite MVP

- [ ] Pages legales (mentions, confidentialite, cookies)
- [ ] Base panier (session ou DB)
- [ ] Preparation integration Stripe (structure checkout, hors prod live)
- [ ] Livraison: regles MVP (forfait / retrait)

## Notes orchestration

- Workflow parallele: `docs/ops/05_agents/PARALLEL_AGENTS_WORKFLOW.md`
- Registre Task IDs: `docs/ops/04_tasks/TASK_IDS.md`
