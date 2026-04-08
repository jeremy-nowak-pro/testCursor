# Technical Specification

## Architecture
- Frontend: React via Inertia.js (pages SSR-like through Laravel responses)
- Backend: Laravel (controllers + services/actions + FormRequest + Policies)
- Database: MySQL (MVP), migrations and seeders managed by Laravel

## Application Layers
- Presentation: Inertia pages, reusable React components, shared layout (header/footer)
- API/Web Layer: Laravel routes and controllers
- Domain Layer: services/actions for catalog, filters, product details, cart prep
- Data Layer: Eloquent models, repositories optional if complexity grows

## Domain Modules
- Catalog: categories, listing, filtering
- Product: product details, availability, display data
- Auth: register/login/logout using Laravel auth stack
- Cart: cart state and totals (MVP simple implementation)
- Checkout Prep: Stripe-ready payload structure and validation
- Legal: legal pages and cookie policy exposure

## Non-functional Requirements
- Security:
  - FormRequest validation for all mutating endpoints
  - CSRF protection enabled for web forms
  - Authentication and authorization via Laravel guards/policies
  - Input sanitization and escaped output in frontend
- Performance:
  - Indexed columns for frequent filters (category_id, price, type, format)
  - Server-side pagination for listing pages
  - Avoid N+1 with eager loading on product/category relationships
- Observability:
  - Structured application logs
  - Error logging for auth and checkout preparation paths
  - Basic health visibility via CI and runtime logs

## Frontend Conventions
- Page-based structure aligned with Inertia routes
- Reusable filter and product card components
- Explicit loading and error states for listing/detail pages
- Consistent form validation feedback on auth pages

## Backend Conventions
- Thin controllers, business logic in services/actions
- API-like response consistency via Laravel Resources
- Named routes for maintainability
- Database transactions for cart/checkout critical writes

## Risks
- Delivery rules undefined: isolate shipping calculation in dedicated service interface
- Stripe integration drift: define strict request/response contract before implementation
- Filter complexity growth: keep query building centralized and test-covered
