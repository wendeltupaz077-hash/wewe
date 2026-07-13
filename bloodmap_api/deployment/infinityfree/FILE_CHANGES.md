# Required deployment changes

The following changes are required for InfinityFree deployment and are isolated in this deployment package so local development remains untouched.

## Files to use for production

- deployment/infinityfree/public/index.php  -> use as the production web entrypoint
- deployment/infinityfree/public/.htaccess -> use as the production rewrite rules
- deployment/infinityfree/.env.production.example -> copy values into your hosting .env file

## Files not changed for local development

- public/index.php
- public/.htaccess

## Environment values to configure

- APP_ENV=production
- APP_DEBUG=false
- APP_URL=https://your-domain.com
- DB_* values from your hosting database

## Storage preparation

- Keep storage/app/public writable.
- Ensure public/storage is available for uploaded files and public assets.

## Rollback

If you need to roll back, restore the original local Laravel files from your local copy or Git repository.
