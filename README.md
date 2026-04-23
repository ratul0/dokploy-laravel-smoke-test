# Dokploy Laravel Smoke Test

Laravel 13 smoke-test application for validating a shared Dokploy MariaDB service and phpMyAdmin import/export workflow.

## Production Flow

1. Create a database named `laravel_smoke_test` and a scoped user named `laravel_smoke_test_user` in the shared Dokploy MariaDB service.
2. Configure the app with the variables from `.env.dokploy.example`.
3. Deploy the app through Dokploy using the repository `Dockerfile`.
4. Run migrations and seeders manually:

```bash
php artisan migrate --force
php artisan db:seed --force
```

The app keeps `/up` enabled for Laravel's built-in health check. The `/` route displays the active database connection and latest seeded users.

## Seed Login

The seeder creates:

- Email: `admin@dokploy-smoke.test`
- Password: `DokployTest123!` unless `SMOKE_ADMIN_PASSWORD` is set.

This app is only for infrastructure validation. Do not reuse the dummy credentials for a real application.
