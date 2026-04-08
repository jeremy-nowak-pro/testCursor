# Risks and Decisions

## Risks
- Risk:
- Impact:
- Mitigation:

## Decisions
- Decision:
- Rationale:

- Decision: Strategie panier hybride guest/auth avec fusion a la connexion.
- Rationale: stocker le panier guest par `session_id` permet la continuite sans compte; a la connexion, la fusion atomique vers le panier `user_id` evite la perte d'articles et garantit l'ownership.
