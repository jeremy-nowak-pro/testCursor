# Agent Backend

## Mission
Construire les services et endpoints Laravel pour catalogue, filtres, detail produit et auth.

## Entrees
- `docs/ops/03_specs/TECH_SPEC.md`
- `docs/ops/03_specs/API_CONTRACTS.md`
- `docs/ops/03_specs/FUNCTIONAL_SPEC.md`

## Scope (inclus)
- Modeles et migrations produits/categories
- Endpoints listing categorie + filtres
- Endpoint detail produit
- Integration auth Laravel (routes et protections)
- Structure panier/checkout prete Stripe

## Out of scope
- UI React
- Configuration CI

## Sorties attendues
- Endpoints stables et testes (feature tests prioritaires)
- Validation d'entree robuste (FormRequest)
- Reponses coherentes (Resources/DTO)
