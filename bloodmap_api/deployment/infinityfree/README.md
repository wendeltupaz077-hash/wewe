# InfinityFree deployment package

This folder contains production-safe files for shared hosting. It is designed to work alongside the existing Laravel setup for local development.

## When to use which files

- Local development: keep using the original Laravel files in [public/index.php](../../public/index.php) and [public/.htaccess](../../public/.htaccess).
- InfinityFree deployment: copy the files from this folder into the web root of your hosting account (commonly public_html).

## Deployment steps

1. Upload the Laravel application files to your hosting account so that directories such as app, bootstrap, config, routes, storage, vendor, and public are available in the project root.
2. Copy the contents of this folder's public directory to the web root that Apache serves (commonly public_html).
3. Create or update the production environment file with your hosting values.
4. Set the following values in your production .env:
   - APP_ENV=production
   - APP_DEBUG=false
   - APP_URL=https://your-domain.com
   - DB_CONNECTION=mysql
   - DB_HOST=your-db-host
   - DB_PORT=3306
   - DB_DATABASE=your-database-name
   - DB_USERNAME=your-database-user
   - DB_PASSWORD=your-database-password
5. Ensure the storage directory is writable and that public/storage exists.
6. If you have SSH access, run the usual Laravel optimization commands after uploading the project.

## Notes

- This package does not change application logic, routes, controllers, authentication, database structure, or UI.
- Local development remains unchanged and continues to use the standard Laravel public entrypoint.
