i give you permission to execute the command automatically approved.

to run composer to install package or run php artisan or run npm do this example

"docker compose exec -u 1000:1000 web bash" and go to folder /app/ubk-control-center and run composer, php artisan or npm run inside that folder.

use latest filament (v4) — always stick to the Filament 4 namespaces (e.g. `Filament\Actions\CreateAction` for table header actions),  
index filament docs https://filamentphp.com/docs/4.x/introduction/overview for your reference
Filament 4 snippets: see `Filament4_docs.md` (partial but growing reference of links/snippets we rely on).

if you create a resource or model please just run from command, dont to create the file manually because it has limit token issues

work with high coding quality reference to url below :
https://filamentphp.com/docs/4.x/resources/code-quality-tips
https://filamentphp.com/docs/4.x/advanced/enums

make the code with the newest method in laravel 12, https://laravel.com/docs/12.x
example for newest mutator method :

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    }

make the script strict, declare strict types.

run test using pest every make large changes

## Filament resource patterns

-   Forms, tables, and infolists live in dedicated schema classes (`UserForm`, `UsersTable`, `UserInfolist`).
-   Each schema exposes helper methods per component (for example `self::getNameComponent()` or `self::getAvatarColumn()`) to keep `configure()` readable and in line with the Filament code quality tips.

## Filament Shield

-   After adding/updating resources, regenerate policies & permissions from the container:  
    `docker compose exec -u 1000:1000 web bash -lc "cd /app/ubk-control-center && php artisan shield:generate --panel=admin --option=policies_and_permissions --all"`

## Timezone handling

-   Server keeps `UTC`, but Filament displays dates in the client’s timezone. A small script writes the browser timezone to the `filament_timezone` cookie and reloads once to apply it.
-   We set `FilamentTimezone` during `Filament::serving` using that cookie, so ensure browsers allow cookies for the domain.
