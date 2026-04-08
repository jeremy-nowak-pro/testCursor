# API Contracts

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
  - `404` product not found
  - `422` invalid quantity

## Versioning
- MVP strategy: web routes with stable contracts documented here
- Evolution path: introduce `/api/v1` when mobile/integrations require strict API versioning
