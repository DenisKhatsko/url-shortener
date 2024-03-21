### Requirements
1. PHP >= 8.2
### How to install this project?
1. Download or clone this project.
2. Copy file .env.example and rename to .env
    fill `APP_URL`
3. Run `composer install`
4. Run `php artisan migrate`
5. Run `php artisan key:generate`

### Usage
1. If you don't have running server, run `php artisan serve`
2. The API documentation is accessible via `{APP_URL}/docs/`
3. Users can access stored via API links by `{APP_URL}/short/{short_url}`
