# Installation Plan

## Step 1: Prerequisites
- Install and verify:
  - PHP (`php -v`)
  - Composer (`composer -V`)
  - Node.js (`node -v`)
  - npm (`npm -v`)
  - MySQL (`mysql --version`)

## Step 2: Project bootstrap
- Initialize Laravel project (if not already initialized)
- Install PHP dependencies with Composer
- Install JS dependencies with npm
- Copy env file (`.env`) from template and fill DB + Stripe keys
- Generate application key
- Run migrations (and seeders if available)

## Step 3: Quality tooling
- Backend:
  - Configure Laravel Pint (formatting)
  - Configure PHPUnit/Pest (tests)
- Frontend:
  - Configure ESLint + Prettier
  - Configure React test tooling (at least baseline)
- CI:
  - Add pipeline for `lint`, `test`, and `build`

## Step 4: Verification
- Run backend lint/format check
- Run frontend lint check
- Run backend test suite
- Run frontend tests (if configured in MVP)
- Start dev servers and verify:
  - Home page
  - Category listing
  - Product detail
  - Login/Register

## Step 5: MVP Readiness Gate
- Validate routes and contracts from `docs/ops/03_specs/API_CONTRACTS.md`
- Validate Sprint 01 done criteria from `docs/ops/04_tasks/SPRINT_01.md`
- Ensure no critical blockers remain in QA
