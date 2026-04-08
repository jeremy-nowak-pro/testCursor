# API Contracts

## Implementation note (Laravel + Inertia)

Les chemins ci-dessous decrivent le **contrat fonctionnel** (URL + donnees). En implementation Inertia, le Backend renvoie des **props** depuis les controllers vers les pages React; les memes chemins `GET` s'appliquent sauf si tu exposes des alias documentes.

## Conventions
- Currency: EUR
- Response format:
  - `data`: payload principal
  - `meta`: pagination and extra metadata when needed
  - `error`: standardized error object (`code`, `message`)

## Catalog - Category Listing
- Method: `GET`
- Path: `/categories/{slug}/products`
- Query params:
  - `min_price` (number, optional)
  - `max_price` (number, optional)
  - `type` (string, optional)
  - `format` (string, optional)
  - `page` (int, optional)
- Response (200):
  - `data.items[]`: `id`, `slug`, `name`, `price`, `currency`, `thumbnail`, `type`, `format`
  - `meta.pagination`: `page`, `per_page`, `total`
  - `meta.available_filters`: ranges/options for UI
- Errors:
  - `404` category not found
  - `422` invalid filters

## Product - Detail
- Method: `GET`
- Path: `/products/{slug}`
- Response (200):
  - `data`: `id`, `slug`, `name`, `description`, `price`, `currency`, `images[]`, `category`, `type`, `format`, `in_stock`
- Errors:
  - `404` product not found

## Auth - Register
- Method: `POST`
- Path: `/register`
- Request:
  - `name`, `email`, `password`, `password_confirmation`
- Response:
  - `302` redirect on success (web flow) or `200` with auth state (if JSON mode)
- Errors:
  - `422` validation errors

## Auth - Login
- Method: `POST`
- Path: `/login`
- Request:
  - `email`, `password`, `remember` (optional bool)
- Response:
  - `302` redirect on success (web flow) or `200` with auth state (if JSON mode)
- Errors:
  - `422` invalid credentials/validation

## Cart - Add Item
- Method: `POST`
- Path: `/cart/items`
- Request:
  - `product_id` (int)
  - `quantity` (int, min 1)
- Response (200):
  - `data.items[]`, `data.subtotal`, `data.currency`
- Errors:
  - `422` invalid payload (`product_id` absent/inexistant, `quantity < 1`)

## Cart - State (Guest/Auth)
- Method: `GET`
- Path: `/cart/state`
- Behaviour:
  - Guest: panier resolu via `session_id` (table `carts`, `user_id = null`)
  - Auth: panier resolu via `user_id` (panier actif utilisateur)
- Response (200):
  - `data.items[]`: `id`, `product_id`, `quantity`, `unit_price`, `currency`
  - `data.subtotal`, `data.currency`

## Cart - Update Item
- Method: `PATCH`
- Path: `/cart/items/{item}`
- Request:
  - `quantity` (int, min 1)
- Response (200):
  - `data.items[]`, `data.subtotal`, `data.currency`
- Errors:
  - `404` item introuvable ou non possede (ownership strict)
  - `422` invalid quantity

## Cart - Delete Item
- Method: `DELETE`
- Path: `/cart/items/{item}`
- Response (200):
  - `data.items[]`, `data.subtotal`, `data.currency`
- Errors:
  - `404` item introuvable ou non possede (ownership strict)

## Cart - Merge on Login
- Trigger: `POST /login` succes (event `Login`)
- Merge rules:
  - Si panier guest existe, fusion dans panier utilisateur actif
  - Meme `product_id` => increment `quantity`
  - `unit_price`/`currency` conserves de facon coherente:
    - ligne existante utilisateur conserve ses valeurs
    - nouvelle ligne issue guest conserve ses valeurs
  - Nettoyage: suppression du panier guest apres fusion

## Cart - Show
- Method: `GET`
- Path: `/cart`
- Auth: required
- Response (200):
  - `data.id`
  - `data.items[]`: `id`, `product_id`, `product{name,slug}`, `unit_price`, `currency`, `quantity`, `line_total`
  - `data.subtotal`, `data.currency`

## Cart - Update Item Quantity
- Method: `PATCH`
- Path: `/cart/items/{item}`
- Auth: required
- Request:
  - `quantity` (int, min 1)
- Response:
  - `302` redirect on success (web flow) or `200` with cart payload (JSON flow)
- Errors:
  - `403` item ownership mismatch
  - `422` invalid quantity

## Cart - Delete Item
- Method: `DELETE`
- Path: `/cart/items/{item}`
- Auth: required
- Response:
  - `302` redirect on success (web flow) or `200` with cart payload (JSON flow)
- Errors:
  - `403` item ownership mismatch

## Versioning
- MVP strategy: web routes with stable contracts documented here
- Evolution path: introduce `/api/v1` when mobile/integrations require strict API versioning
