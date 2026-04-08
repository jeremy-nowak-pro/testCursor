# Functional Specification

## Personas
- Visiteur: consulte la boutique, categories et fiches produit
- Client: s'inscrit, se connecte et suit son parcours d'achat
- Admin: gere produits, categories et commandes (scope minimal MVP)

## User Stories
- As a visitor, I want to browse categories so that I can find products quickly.
- As a visitor, I want to filter products by price, type and format so that I can narrow results.
- As a visitor, I want to open a product detail page so that I can view complete information.
- As a customer, I want to register and log in so that I can access my account.
- As a customer, I want a clear legal and cookie information area so that I can trust the store.

## MVP Features
- Home page with hero and category highlights
- Global header with category navigation
- Global footer with legal links (mentions legales, politique cookies, confidentialite)
- Category listing page with sidebar filters (prix, type, format)
- Product detail page
- Authentication pages (register/login/logout) using Laravel auth
- Basic cart and checkout readiness for Stripe integration

## Acceptance Criteria
- Given a user visits home page, when page loads, then header, footer and category entry points are visible.
- Given a user opens a category, when filters are applied, then product list updates by selected filters.
- Given a user opens a product page, when content loads, then title, price, format, description and image are displayed.
- Given a new user submits register form with valid data, when request succeeds, then account is created and user is authenticated.
- Given an existing user submits login form with valid credentials, when request succeeds, then user is redirected to authenticated area.
- Given a user clicks legal links in footer, when target page opens, then legal and cookie information is accessible.
