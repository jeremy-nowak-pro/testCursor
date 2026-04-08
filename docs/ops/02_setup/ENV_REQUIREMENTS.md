# Environment Requirements

- OS: macOS, Linux or Windows (WSL2 recommended)
- Runtime versions:
  - PHP: 8.3+
  - Composer: 2.x
  - Node.js: 20 LTS+
  - npm: 10+ (or pnpm 9+ if team standardizes on pnpm)
- Package manager:
  - Backend: Composer
  - Frontend: npm
- Database: MySQL 8+
- Optional services:
  - Redis (cache/queue/session optional for MVP)
  - Mailpit or Mailhog (local email testing)

## Environment Variables
- `APP_NAME=EcommerceMVP`
- `APP_ENV=local`
- `APP_DEBUG=true`
- `APP_URL=http://localhost:8000`

- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=ecommerce_mvp`
- `DB_USERNAME=root`
- `DB_PASSWORD=`

- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`

- `MAIL_MAILER=smtp`
- `MAIL_HOST=127.0.0.1`
- `MAIL_PORT=1025`
- `MAIL_FROM_ADDRESS="no-reply@example.test"`
- `MAIL_FROM_NAME="${APP_NAME}"`

- `STRIPE_KEY=`
- `STRIPE_SECRET=`
- `STRIPE_WEBHOOK_SECRET=`
