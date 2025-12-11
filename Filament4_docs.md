### Install Filament Assets in Existing Projects

Source: https://filamentphp.com/docs/4.x/introduction/installation

Install Filament frontend assets into an existing Laravel project without scaffolding. Use this approach to preserve existing application functionality and configuration.

```bash
php artisan filament:install
```

--------------------------------

### Check Filament Infolists Installation with Composer

Source: https://filamentphp.com/docs/4.x/components/infolist

Before proceeding, verify if the `filament/infolists` package is installed in your project using the Composer command. If not installed, consult the installation guide.

```bash
composer show filament/infolists
```

--------------------------------

### Scaffold New Laravel Project with Filament

Source: https://filamentphp.com/docs/4.x/introduction/installation

Quickly set up Filament in a new Laravel project by installing Livewire, Alpine.js, Tailwind CSS, and frontend assets. This command overwrites existing files and includes optional Notifications component installation. Only use in new projects to avoid data loss.

```bash
php artisan filament:install --scaffold

npm install

npm run dev
```

--------------------------------

### Use setUp() method for default configuration in PHP

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Override the setUp() method to apply default configuration to objects after instantiation, rather than overriding make(). This method is called immediately after object instantiation and provides a cleaner alternative for setting default properties like labels.

```php
protected function setUp(): void
{
    parent::setUp();

    $this->label('Default label');
}
```

--------------------------------

### Install Individual Filament Components

Source: https://filamentphp.com/docs/4.x/introduction/installation

Install specific Filament packages for use with Blade views instead of the full panel builder. Includes tables, schemas, forms, infolists, actions, notifications, and widgets packages. Can be installed incrementally in existing projects without disruption.

```bash
composer require
    filament/tables:"^4.0"
    filament/schemas:"^4.0"
    filament/forms:"^4.0"
    filament/infolists:"^4.0"
    filament/actions:"^4.0"
    filament/notifications:"^4.0"
    filament/widgets:"^4.0"
```

```bash
composer require
    filament/tables:"~4.0"
    filament/schemas:"~4.0"
    filament/forms:"~4.0"
    filament/infolists:"~4.0"
    filament/actions:"~4.0"
    filament/notifications:"~4.0"
    filament/widgets:"~4.0"
```

--------------------------------

### Filament Live Field Reactivity Example (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This example demonstrates the default behavior of a `live()` Filament `TextInput`, where updating the 'name' field triggers a full Livewire component re-render. It shows how the 'email' field's label dynamically updates based on the 'name' field's value, highlighting potential inefficiencies for large forms.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

TextInput::make('name')
    ->live()
    
TextInput::make('email')
    ->label(fn (Get $get): string => filled($get('name')) ? "Email address for {$get('name')}" : 'Email address')
```

--------------------------------

### Field Configuration with Dynamic Functions

Source: https://filamentphp.com/docs/4.x/forms/overview

Shows how to use callback functions in field configuration methods to dynamically calculate values based on context. Includes examples for DatePicker displayFormat, Select options, and TextInput required validation. Functions allow runtime customization without hardcoded values.

```PHP
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

DatePicker::make('date_of_birth')
    ->displayFormat(function (): string {
        if (auth()->user()->country_id === 'us') {
            return 'm/d/Y';
        }

        return 'd/m/Y';
    })

Select::make('user_id')
    ->options(function (): array {
        return User::query()->pluck('name', 'id')->all();
    })

TextInput::make('middle_name')
    ->required(fn (): bool => auth()->user()->hasMiddleName())
```

--------------------------------

### Install Filament Panel Builder with Composer

Source: https://filamentphp.com/docs/4.x/introduction/installation

Install the Filament panel builder package and register it in your Laravel project. This command creates the AdminPanelProvider service provider and sets up the admin panel framework. For Windows PowerShell users, use the alternative command with ~4.0 version constraint.

```bash
composer require filament/filament:"^4.0"

php artisan filament:install --panels
```

```bash
composer require filament/filament:"~4.0"

php artisan filament:install --panels
```

--------------------------------

### Install Tailwind CSS for Filament

Source: https://filamentphp.com/docs/4.x/introduction/installation

Install Tailwind CSS v4.1+ and its Vite plugin as a development dependency. Required for Filament styling if not already present in your Laravel project.

```bash
npm install tailwindcss @tailwindcss/vite --save-dev
```

--------------------------------

### Create a Basic Multi-Step Wizard in Filament

Source: https://filamentphp.com/docs/4.x/schemas/wizards

This example demonstrates how to define a multi-step wizard using `Filament\Schemas\Components\Wizard` and `Filament\Schemas\Components\Wizard\Step`. Each step can contain its own schema for form fields, guiding the user through a defined process.

```php
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

Wizard::make([
    Step::make('Order')
        ->schema([
            // ...
        ]),
    Step::make('Delivery')
        ->schema([
            // ...
        ]),
    Step::make('Billing')
        ->schema([
            // ...
        ]),
])
```

--------------------------------

### Implement Multi-step Wizard for Filament Create Action (PHP)

Source: https://filamentphp.com/docs/4.x/actions/create

This example demonstrates how to transform a standard Filament `CreateAction` into a multi-step wizard. Instead of a single `schema()`, you define an array of `Step` objects, each containing its own title, description, and form components, allowing for a guided creation process.

```php
use Filament\Actions\CreateAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard\Step;

CreateAction::make()
    ->steps([
        Step::make('Name')
            ->description('Give the category a unique name')
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->disabled()
                    ->required()
                    ->unique(Category::class, 'slug'),
            ])
            ->columns(2),
        Step::make('Description')
            ->description('Add some extra details')
            ->schema([
                MarkdownEditor::make('description'),
            ]),
        Step::make('Visibility')
            ->description('Control who can view it')
            ->schema([
                Toggle::make('is_visible')
                    ->label('Visible to customers.')
                    ->default(true),
            ]),
    ])
```

--------------------------------

### Add Multiple Example Rows to ImportColumn

Source: https://filamentphp.com/docs/4.x/actions/import

Provide multiple example values for an ImportColumn by passing an array to the examples() method. These examples populate the generated example CSV file to demonstrate various valid data formats.

```PHP
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->examples(['ABC123', 'DEF456'])
```

--------------------------------

### Rendering Dynamic Text Content with JavaScript in Filament Schemas (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This example uses `JsContent::make()` to render dynamic text content for a `TextInput` label based on other field values. The provided JavaScript string is interpreted by the frontend, allowing for interactive labels. `$state` and `$get` utilities are available within the JavaScript context for accessing field data.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\JsContent;

TextInput::make('greetingResponse')
    ->label(JsContent::make(<<<'JS'
        ($get('name') === 'John Doe') ? 'Hello, John!' : 'Hello, stranger!'
        JS
    ))

```

--------------------------------

### Set default active step in FilamentPHP Wizard

Source: https://filamentphp.com/docs/4.x/schemas/wizards

This example shows how to configure a FilamentPHP Wizard to start on a specific step using the `startOnStep()` method. The step index can be a static integer or dynamically determined by a closure, which supports utility injection for flexible control over the initial step.

```php
use Filament\Schemas\Components\Wizard;

Wizard::make([
    // ...
])->startOnStep(2)
```

--------------------------------

### Injecting Multiple Utilities into Filament Schema Functions (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates combining multiple dynamically injected parameters, such as Livewire component, `Get`, and `Set` utilities, into a single Filament schema function. Parameters can be combined in any order due to reflection-based injection, offering flexible dependency management.

```php
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Livewire\Component as Livewire;

function (Livewire $livewire, Get $get, Set $set) {
    // ...
}

```

--------------------------------

### Align Content to Start with Schema::start() - Filament PHP

Source: https://filamentphp.com/docs/4.x/infolists

Shows how to align the afterLabel() content to the start of the container instead of the default end alignment. Uses Schema::start() wrapper to control content positioning. Useful for left-aligned labels or custom layout requirements.

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->afterLabel(Schema::start([
        Icon::make(Heroicon::Star),
        'This is the content after the entry\'s label'
    ]))
```

--------------------------------

### Making Filament Form Fields Reactive on Blur (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

To optimize performance for fields like `TextInput`, this example shows how to configure a field to re-render the schema only when it loses focus. Using `live(onBlur: true)` prevents frequent network requests while the user is actively typing, improving user experience.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('username')
    ->live(onBlur: true)

```

--------------------------------

### Verify Filament Tables installation using Composer

Source: https://filamentphp.com/docs/4.x/components/table

This command checks if the `filament/tables` package is already installed in your project. It's a crucial prerequisite for using Filament tables, and if not present, the package needs to be installed according to the official guide.

```bash
composer show filament/tables
```

--------------------------------

### Create Filament Admin User

Source: https://filamentphp.com/docs/4.x/introduction/installation

Generate a new user account for accessing the Filament admin panel. After creating the user, access the panel at /admin in your web browser and sign in to begin building your application.

```bash
php artisan make:filament-user
```

--------------------------------

### Define Filament Grid Column Start Positions in PHP

Source: https://filamentphp.com/docs/4.x/schemas/layouts

This code demonstrates how to use the `columnStart()` method on a `TextInput` component within a Filament `Grid` to define its starting column based on different screen breakpoints. The `Grid` itself is configured with a responsive number of columns, and `columnStart()` ensures the input's position adapts accordingly.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

Grid::make()
    ->columns([
        'sm' => 3,
        'xl' => 6,
        '2xl' => 8,
    ])
    ->schema([
        TextInput::make('name')
            ->columnStart([
                'sm' => 2,
                'xl' => 3,
                '2xl' => 4,
            ]),
        // ...
    ])
```

--------------------------------

### Check Filament Forms Installation with Composer

Source: https://filamentphp.com/docs/4.x/components/form

Verifies that the filament/forms package is installed in your project. Run this command in the terminal to check the installation status before proceeding with form setup.

```bash
composer show filament/forms
```

--------------------------------

### Install Phiki Package for Code Highlighting

Source: https://filamentphp.com/docs/4.x/infolists/code-entry

Installs the Phiki package required for server-side code highlighting in Filament infolists. Filament does not include Phiki by default to allow explicit version selection, as different major versions may have different grammars and themes available.

```bash
composer require phiki/phiki
```

--------------------------------

### Filament Partial Rendering for Current Component (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This example demonstrates how to use `partiallyRenderAfterStateUpdated()` to re-render only the current reactive component. It's useful when a component's reactivity is self-contained and other parts of the form do not depend on its updated state, displaying dynamic content below the field.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->live()
    ->partiallyRenderAfterStateUpdated()
    ->belowContent(fn (Get $get): ?string => filled($get('name')) ? "Hi, {$get('name')}!" : null)
```

--------------------------------

### Injecting Multiple Filament Utilities via Reflection (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/overview

Demonstrates how FilamentPHP uses reflection to dynamically inject multiple utility classes like `Get` and `Set` along with Livewire components into a function. Parameters can be combined and ordered flexibly.

```php
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Livewire\Component as Livewire;

function (Livewire $livewire, Get $get, Set $set) {
    // ...
}
```

--------------------------------

### Filament Infolist Entry Dynamic Configuration with Callbacks

Source: https://filamentphp.com/docs/4.x/infolists

These examples illustrate how to use callback functions instead of static values to dynamically configure various aspects of Filament Infolist entries. This enables customization based on the entry's state, the current user, or the associated Eloquent record, allowing for flexible UI behavior.

```php
use AppModelsUser;
use FilamentInfolistsComponentsTextEntry;

TextEntry::make('name')
    ->label(fn (string $state): string => str_contains($state, ' ') ? 'Full name' : 'Name')

TextEntry::make('currentUserEmail')
    ->state(fn (): string => auth()->user()->email)

TextEntry::make('role')
    ->hidden(fn (User $record): bool => $record->role === 'admin')
```

--------------------------------

### Import Filament Stylesheets in app.css

Source: https://filamentphp.com/docs/4.x/introduction/installation

This CSS snippet demonstrates how to import the necessary Filament package stylesheets into your application's `resources/css/app.css` file. It allows for selective importing based on installed Filament components to optimize the CSS bundle size and includes a Tailwind CSS import and dark mode variant.

```css
@import 'tailwindcss';

/* Required by all components */
@import '../../vendor/filament/support/resources/css/index.css';

/* Required by actions and tables */
@import '../../vendor/filament/actions/resources/css/index.css';

/* Required by actions, forms and tables */
@import '../../vendor/filament/forms/resources/css/index.css';

/* Required by actions and infolists */
@import '../../vendor/filament/infolists/resources/css/index.css';

/* Required by notifications */
@import '../../vendor/filament/notifications/resources/css/index.css';

/* Required by actions, infolists, forms, schemas and tables */
@import '../../vendor/filament/schemas/resources/css/index.css';

/* Required by tables */
@import '../../vendor/filament/tables/resources/css/index.css';

/* Required by widgets */
@import '../../vendor/filament/widgets/resources/css/index.css';

@variant dark (&:where(.dark, .dark *));
```

--------------------------------

### Configure New Filament Plugin with Skeleton (Bash)

Source: https://filamentphp.com/docs/4.x/plugins/getting-started

This command initiates the configuration process for a new Filament plugin after cloning the official Plugin Skeleton repository. It executes a PHP script that interactively prompts the user for setup details, automating the generation of boilerplate code for the new plugin.

```bash
php ./configure.php
```

--------------------------------

### Conditionally Show FilamentPHP Form Field with `visible()`

Source: https://filamentphp.com/docs/4.x/forms/overview

This example illustrates how to conditionally display a FilamentPHP form field using the `visible()` method. Similar to `hidden()`, it uses a closure with the `Get` utility to check another field's value, in this case, making `company_name` visible when `is_company` is true.

```PHP
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;

Checkbox::make('is_company')
    ->live()
    
TextInput::make('company_name')
    ->visible(fn (Get $get): bool => $get('is_company'))
```

--------------------------------

### Initialize Filament Date and Time Pickers

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

This example demonstrates how to create basic date, time, and date-time picker form components in Filament. It uses `DatePicker`, `DateTimePicker`, and `TimePicker` classes to provide interactive interfaces for selecting date and time values.

```php
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;

DateTimePicker::make('published_at');
DatePicker::make('date_of_birth');
TimePicker::make('alarm_at');
```

--------------------------------

### Add Single Example Value to ImportColumn

Source: https://filamentphp.com/docs/4.x/actions/import

Provide an example value for an ImportColumn that appears in the downloadable example CSV file. This helps users understand what format of data should be provided for that column.

```PHP
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->example('ABC123')
```

--------------------------------

### Check Filament Schemas Package Installation (Composer)

Source: https://filamentphp.com/docs/4.x/components/schema

This Composer command verifies if the `filament/schemas` package is installed in your project. It's a prerequisite for rendering schemas in Blade views. If not found, consult the installation guide for individual components.

```bash
composer show filament/schemas
```

--------------------------------

### Inject Another Entry's State in Filament Infolist Callback using $get

Source: https://filamentphp.com/docs/4.x/infolists

This example demonstrates how to retrieve the state (value) of another entry or form field from within a callback function using the `$get` parameter. The `$get` utility, provided by `Filament\Schemas\Components\Utilities\Get`, allows fetching values by name. For real-time reactions, ensure the target field is `live()`.

```php
use FilamentSchemasComponentsUtilitiesGet;

function (Get $get) {
    $email = $get('email'); // Store the value of the `email` entry in the `$email` variable.
    //...
}
```

--------------------------------

### Build Schema with Prime Components - PHP

Source: https://filamentphp.com/docs/4.x/schemas/primes

Demonstrates how to construct a Filament schema using prime components to display user instructions, a QR code image, and a list of recovery codes. The example uses Text for instructions, Image for QR code display, and UnorderedList to render recovery codes with copyable functionality and monospace font styling.

```php
use Filament\Actions\Action;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\UnorderedList;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

$schema
    ->components([
        Text::make('Scan this QR code with your authenticator app:')
            ->color('neutral'),
        Image::make(
            url: asset('images/qr.jpg'),
            alt: 'QR code to scan with an authenticator app',
        )
            ->imageHeight('12rem')
            ->alignCenter(),
        Section::make()
            ->schema([
                Text::make('Please save the following recovery codes in a safe place. They will only be shown once, but you\'ll need them if you lose access to your authenticator app:')
                    ->weight(FontWeight::Bold)
                    ->color('neutral'),
                UnorderedList::make(fn (): array => array_map(
                    fn (string $recoveryCode): Text => Text::make($recoveryCode)
                        ->copyable()
                        ->fontFamily(FontFamily::Mono)
                        ->size(TextSize::ExtraSmall)
                        ->color('neutral'),
                    ['tYRnCqNLUx-3QOLNKyDiV', 'cKok2eImKc-oWHHH4VhNe', 'C0ZstEcSSB-nrbyk2pv8z', '49EXLRQ8MI-FpWywpSDHE', 'TXjHnvkUrr-KuiVJENPmJ', 'BB574ookll-uI20yxP6oa', 'BbgScF2egu-VOfHrMtsCl', 'cO0dJYqmee-S9ubJHpRFR'],
                ))
                    ->size(TextSize::ExtraSmall),
            ])
            ->compact()
            ->secondary(),
    ])
```

--------------------------------

### Transform field state on dehydration using dehydrateStateUsing in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Customizes how field data is extracted and transformed when the schema's getState() method is called (typically on form submission). This example capitalizes the name field during dehydration before returning the state.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->required()
    ->dehydrateStateUsing(fn (string $state): string => ucwords($state))
```

--------------------------------

### Filament Client-Side State Updates with JavaScript (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This example contrasts traditional server-side `afterStateUpdated` with client-side `afterStateUpdatedJs` for updating other field states. `afterStateUpdatedJs()` executes a JavaScript expression directly in the browser, using `$state`, `$get()`, and `$set()` to update fields like 'email' without network requests. Caution is advised against XSS vulnerabilities when incorporating user input into the JS string.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

// Old name input that is `live()`, so it makes a network request and render each time it is updated.
TextInput::make('name')
    ->live()
    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('email', ((string) str($state)->replace(' ', '.')->lower()) . '@example.com'))

// New name input that uses `afterStateUpdatedJs()` to set the state of the email field and doesn't make a network request.
TextInput::make('name')
    ->afterStateUpdatedJs(<<<'JS'
        $set('email', ($state ?? '').replaceAll(' ', '.').toLowerCase() + '@example.com')
        JS)
    
TextInput::make('email')
    ->label('Email address')
```

--------------------------------

### Configure Global TextEntry Settings in Filament Service Provider

Source: https://filamentphp.com/docs/4.x/infolists

Shows how to use the static configureUsing() method to apply default settings to all instances of a Filament component globally. This must be called in a service provider's boot() method and accepts a Closure to modify component behavior. Example sets all TextEntry components to display a maximum of 10 words.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::configureUsing(function (TextEntry $entry): void {
    $entry->words(10);
});
```

--------------------------------

### Install TipTap Highlight Extension with npm

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Command to install the TipTap highlight extension as a development dependency. This must be executed before creating the JavaScript extension file.

```bash
npm install @tiptap/extension-highlight --save-dev
```

--------------------------------

### Format field state on hydration using afterStateHydrated in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Customizes field behavior when hydrated with data from the schema's fill() method. This example capitalizes the name field using the afterStateHydrated() callback, which receives the field component and current state as parameters.

```php
use Closure;
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->required()
    ->afterStateHydrated(function (TextInput $component, string $state) {
        $component->state(ucwords($state));
    })
```

--------------------------------

### Add Static Content After Field Label in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Insert static text, icons, or schema components after a field's label using the afterLabel() method. This example demonstrates adding a star icon and descriptive text after a TextInput field's label.

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->afterLabel([
        Icon::make(Heroicon::Star),
        'This is the content after the field\'s label'
    ])
```

--------------------------------

### Add Datalist Options to Filament TextInput

Source: https://filamentphp.com/docs/4.x/forms/text-input

This example illustrates how to provide a list of suggested options for a Filament TextInput using the `datalist()` method. The provided array of strings populates the datalist, offering users autocomplete recommendations while still allowing free text entry. The method also supports dynamic calculation via a function.

```php
TextInput::make('manufacturer')
    ->datalist([
        'BMW',
        'Ford',
        'Mercedes-Benz',
        'Porsche',
        'Toyota',
        'Volkswagen',
    ])
```

--------------------------------

### Injecting Current Operation into Filament Schema Functions (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This example shows how to inject the current schema operation (e.g., 'create', 'edit', 'view') into a Filament schema function. Type-hint the `$operation` parameter as `string` to access this context, useful for conditional logic based on the schema's purpose.

```php
function (string $operation) {
    // ...
}

```

--------------------------------

### Set Dynamic Placeholder with Utility Injection

Source: https://filamentphp.com/docs/4.x/forms/overview

Sets a placeholder using a callback function that can inject utilities like Get, Livewire, model, operation, record, and state. This allows dynamic placeholder calculation based on form context and current field values.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->placeholder(function ($get, $state, $record) {
        // Dynamically calculate placeholder
    })
```

--------------------------------

### Retrieve Another Field's State Using Get Utility

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates using the Get utility to retrieve the state (value) of another field within a callback function. The Get parameter must be named exactly $get for Filament to inject it. Provides access to form data without running validation.

```PHP
use Filament\Schemas\Components\Utilities\Get;

function (Get $get) {
    $email = $get('email'); // Store the value of the `email` field in the `$email` variable.
    //...
}
```

--------------------------------

### Dependent Select Options with Static Data in Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Create a dependent select field where options dynamically update based on another field's value using the `$get()` utility and PHP's `match()` statement. The parent select field uses `live()` to trigger schema rerendering when its value changes. This example demonstrates using static option arrays mapped to category values.

```PHP
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Select;

Select::make('category')
    ->options([
        'web' => 'Web development',
        'mobile' => 'Mobile development',
        'design' => 'Design',
    ])
    ->live()

Select::make('sub_category')
    ->options(fn (Get $get): array => match ($get('category')) {
        'web' => [
            'frontend_web' => 'Frontend development',
            'backend_web' => 'Backend development',
        ],
        'mobile' => [
            'ios_mobile' => 'iOS development',
            'android_mobile' => 'Android development',
        ],
        'design' => [
            'app_design' => 'Panel design',
            'marketing_website_design' => 'Marketing website design',
        ],
        default => [],
    })
```

--------------------------------

### Install Node.js Dependencies for Filament Plugin Assets

Source: https://filamentphp.com/docs/4.x/plugins/building-a-panel-plugin

This command installs the required Node.js development dependencies, such as `esbuild`, that are specified in the `package.json` file. These dependencies are essential for building and compiling the plugin's frontend assets.

```bash
npm install
```

--------------------------------

### Configure Tailwind CSS Plugin in Vite

Source: https://filamentphp.com/docs/4.x/introduction/installation

This JavaScript configuration for `vite.config.js` integrates the `@tailwindcss/vite` plugin into a Laravel project's Vite setup. It ensures that Tailwind CSS is correctly processed during asset compilation, alongside other Laravel-specific assets like `app.css` and `app.js`, facilitating proper styling for the application.

```javascript
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
})
```

--------------------------------

### Check Filament Actions installation in PHP project

Source: https://filamentphp.com/docs/4.x/components/action

This command verifies if the `filament/actions` package is properly installed in your PHP project. It is a prerequisite for utilizing Filament's action system within Livewire components.

```bash
composer show filament/actions
```

--------------------------------

### Align After-Label Content to Start with Schema in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Align extra content after a field's label to the start of the container using Schema::start() wrapper. By default, afterLabel() content aligns to the end; use Schema::start() to override this behavior.

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->afterLabel(Schema::start([
        Icon::make(Heroicon::Star),
        'This is the content after the field\'s label'
    ]))
```

--------------------------------

### Configure FilamentPHP Custom Component with Method Chaining (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/custom-components

This PHP example shows how to instantiate a `Chart` custom component and configure its properties using method chaining. The `make()` static method creates an instance, and the `heading()` method sets the desired value, demonstrating a fluent API for component configuration.

```php
use App\Filament\Schemas\Components\Chart;

Chart::make()
    ->heading('Sales')
```

--------------------------------

### Register Filament Plugin Livewire and Alpine Components in Service Provider

Source: https://filamentphp.com/docs/4.x/plugins/building-a-panel-plugin

This PHP code demonstrates configuring a `PackageServiceProvider` for a Filament plugin. It uses `packageBooted()` to register a Livewire component (`ClockWidget`) and an Alpine component (`clock-widget`) with Filament's asset manager. This setup ensures that the plugin's interactive components and their associated JavaScript are loaded efficiently only when the widget is actively used within a panel.

```php
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ClockWidgetServiceProvider extends PackageServiceProvider
{
    public static string $name = 'clock-widget';

    public function configurePackage(Package $package):
    void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        Livewire::component('clock-widget', ClockWidget::class);

        // Asset Registration
        FilamentAsset::register(
            assets:[
                 AlpineComponent::make('clock-widget', __DIR__ . '/../resources/dist/clock-widget.js'),
            ],
            package: 'awcodes/clock-widget'
        );
    }
}
```

--------------------------------

### Globally configure Filament Checkbox component default behavior

Source: https://filamentphp.com/docs/4.x/forms/overview

This PHP snippet illustrates how to use the static `configureUsing()` method on a Filament `Checkbox` component to apply global default settings. In this example, it sets all `Checkbox` components to `inline(false)` by default within a service provider's `boot()` method or a middleware, reducing repetitive configuration.

```php
use Filament\Forms\Components\Checkbox;

Checkbox::configureUsing(function (Checkbox $checkbox): void {
    $checkbox->inline(false);
});
```

--------------------------------

### Inject Livewire Component Instance in Filament Infolist Callback

Source: https://filamentphp.com/docs/4.x/infolists

This example demonstrates how to access the current Livewire component instance from within a Filament Infolist callback function. By defining a `Component $livewire` parameter, the Livewire component instance is injected, allowing interaction with its properties or methods.

```php
use LivewireComponent;

function (Component $livewire) {
    // ...
}
```

--------------------------------

### Example KeyValueEntry state data structure

Source: https://filamentphp.com/docs/4.x/infolists/key-value-entry

Demonstrates the expected state format for KeyValueEntry component as a one-dimensional associative array containing key-value pairs that will be displayed in the infolist.

```php
[
    'description' => 'Filament is a collection of Laravel packages',
    'og:type' => 'website',
    'og:site_name' => 'Filament',
]
```

--------------------------------

### Implement various lifecycle hooks for Filament PHP Create pages

Source: https://filamentphp.com/docs/4.x/resources/creating-records

This example showcases all available lifecycle hooks for a Filament PHP Create page, extending `CreateRecord`. These methods allow developers to inject custom logic at different stages of the record creation process, from filling the form to saving data, ensuring fine-grained control over user interactions and data persistence.

```php
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    // ...

    protected function beforeFill(): void
    {
        // Runs before the form fields are populated with their default values.
    }

    protected function afterFill(): void
    {
        // Runs after the form fields are populated with their default values.
    }

    protected function beforeValidate(): void
    {
        // Runs before the form fields are validated when the form is submitted.
    }

    protected function afterValidate(): void
    {
        // Runs after the form fields are validated when the form is submitted.
    }

    protected function beforeCreate(): void
    {
        // Runs before the form fields are saved to the database.
    }

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.
    }
}
```

--------------------------------

### Customize Example CSV Header in ImportColumn

Source: https://filamentphp.com/docs/4.x/actions/import

Set a custom header label for an ImportColumn in the example CSV file using the exampleHeader() method. This allows you to display more user-friendly or descriptive column names in the example output.

```PHP
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->exampleHeader('SKU')
```

--------------------------------

### Implement Basic Filament Pagination in Livewire

Source: https://filamentphp.com/docs/4.x/components/pagination

This example demonstrates how to integrate Filament's pagination component into a Livewire view. It involves setting up a Livewire component to fetch paginated data and then rendering it using `<x-filament::pagination>`. It also includes usage of Laravel's `simplePaginate` and `cursorPaginate` methods for alternative pagination styles.

```php
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListUsers extends Component
{
    // ...
    
    public function render(): View
    {
        return view('livewire.list-users', [
            'users' => User::query()->paginate(10),
        ]);
    }
}
```

```blade
<x-filament::pagination :paginator="$users" />
```

```php
use App\Models\User;

User::query()->simplePaginate(10)
User::query()->cursorPaginate(10)
```

--------------------------------

### Generate Slug from Title in FilamentPHP Form

Source: https://filamentphp.com/docs/4.x/forms/overview

This example demonstrates generating a slug from a title field in a FilamentPHP form. It uses `afterStateUpdated()` on the `title` field to call the `Str::slug()` helper and `$set()` the `slug` field's value. The `live(onBlur: true)` ensures the slug updates as the title is typed and the field loses focus.

```PHP
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

TextInput::make('title')
    ->live(onBlur: true)
    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
    
TextInput::make('slug')
```

--------------------------------

### Add Multiple Content Items to Filament Entry Slot (PHP)

Source: https://filamentphp.com/docs/4.x/infolists

This example demonstrates how to insert a combination of content types (an icon, plain text, and an action) into an entry slot by providing them as an array. It leverages `Icon` from `Filament\Schemas\Components` and `Heroicon` for specifying the icon type.

```php
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->belowContent([
        Icon::make(Heroicon::InformationCircle),
        'This is the user's full name.',
        Action::make('generate'),
    ])
```

--------------------------------

### Configure global record action defaults with service provider

Source: https://filamentphp.com/docs/4.x/tables/actions

Customize default settings for ungrouped record actions across your application using `Table::configureUsing()` in a service provider's `boot()` method. This example sets all ungrouped record actions to render as icon buttons by default.

```PHP
use Filament\Actions\Action;
use Filament\Tables\Table;

Table::configureUsing(function (Table $table): void {
    $table
        ->modifyUngroupedRecordActionsUsing(fn (Action $action) => $action->iconButton());
});
```

--------------------------------

### Build Form Schema with Grid and Section Layout Components in Filament

Source: https://filamentphp.com/docs/4.x/schemas

Demonstrates creating a responsive grid layout with nested sections containing form inputs (TextInput, Select, Checkbox) and read-only info entries (TextEntry). The Grid component organizes components in columns, Section wraps related components in cards, and TextEntry displays formatted data like timestamps using the dateTime() method.

```PHP
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

$schema
    ->components([
        Grid::make(2)
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextInput::make('name'),
                        Select::make('position')
                            ->options([
                                'developer' => 'Developer',
                                'designer' => 'Designer',
                            ]),
                        Checkbox::make('is_admin'),
                    ]),
                Section::make('Auditing')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),
            ]),
    ])
```

--------------------------------

### Configure package.json with CSS build tools

Source: https://filamentphp.com/docs/4.x/plugins/building-a-standalone-plugin

Set up package.json with build scripts and development dependencies for PostCSS processing. Includes cssnano for minification, postcss-cli for command-line processing, and postcss-nesting for CSS nesting support to compile stylesheets for the plugin.

```json
{
    "private": true,
    "scripts": {
        "build": "postcss resources/css/index.css -o resources/dist/headings.css"
    },
    "devDependencies": {
        "cssnano": "^6.0.1",
        "postcss": "^8.4.27",
        "postcss-cli": "^10.1.0",
        "postcss-nesting": "^13.0.0"
    }
}
```

--------------------------------

### Conditionally save Filament relationship data

Source: https://filamentphp.com/docs/4.x/forms/overview

This PHP example shows how to use a `condition` function within the `relationship()` method of a Filament `Group` component. This function dynamically determines whether a related record, like a `customer`, should be created, updated, or deleted based on the state of form fields (e.g., if the `name` field is filled). It uses `Filament\Forms\Components\TextInput` and `Filament\Schemas\Components\Group`.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;

Group::make()
    ->relationship(
        'customer',
        condition: fn (?array $state): bool => filled($state['name']),
    )
    ->schema([
        TextInput::make('name')
            ->label('Customer'),
        TextInput::make('email')
            ->label('Email address')
            ->email()
            ->requiredWith('name'),
    ])
```

--------------------------------

### Enable global trimming for TextInput via configureUsing (PHP - Filament)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Demonstrates enabling trimming globally for all TextInput components using configureUsing in a service provider. Requires Filament Forms component and registration in a service provider. Input: any TextInput value; Output: globally trimmed state for all text inputs. Limitation: this configuration runs at boot/configure time and affects all TextInput instances.

```PHP
use Filament\Forms\Components\TextInput;

TextInput::configureUsing(function (TextInput $component): void {
    $component->trim();
});

```

--------------------------------

### Globally Configuring Filament Component Defaults (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/overview

Shows how to change the default behavior of a Filament component globally by calling the static `configureUsing()` method within a service provider's `boot()` method. A Closure is passed to modify the component's properties, for example, setting a section to have 2 columns by default.

```php
use Filament\Schemas\Components\Section;

Section::configureUsing(function (Section $section): void {
    $section
        ->columns(2);
});
```

--------------------------------

### Define wizard steps with form components for Filament PHP Create page

Source: https://filamentphp.com/docs/4.x/resources/creating-records

This method illustrates how to define the individual steps for a Filament PHP creation wizard. Each `Step` is configured with a title, description, and an array of Filament form components (`TextInput`, `MarkdownEditor`, `Toggle`) to collect data across multiple stages of the record creation process.

```php
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard\Step;

protected function getSteps(): array
{
    return [
        Step::make('Name')
            ->description('Give the category a clear and unique name')
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->disabled()
                    ->required()
                    ->unique(Category::class, 'slug', fn ($record) => $record),
            ]),
        Step::make('Description')
            ->description('Add some extra details')
            ->schema([
                MarkdownEditor::make('description')
                    ->columnSpan('full'),
            ]),
        Step::make('Visibility')
            ->description('Control who can view it')
            ->schema([
                Toggle::make('is_visible')
                    ->label('Visible to customers.')
                    ->default(true),
            ]),
    ];
}
```

--------------------------------

### Align Field Slot Content Using Schema Methods

Source: https://filamentphp.com/docs/4.x/forms/overview

Control the alignment of slot content using Schema::start(), Schema::end(), or Schema::between(). These methods position array content at the start, end, or space-between respectively, providing flexible layout control.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->belowContent(Schema::end([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]))

TextInput::make('name')
    ->belowContent(Schema::between([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]))

TextInput::make('name')
    ->belowContent(Schema::between([
        Flex::make([
            Icon::make(Heroicon::InformationCircle)
                ->grow(false),
            'This is the user\'s full name.',
        ]),
        Action::make('generate'),
    ]))
```

--------------------------------

### Type-Safe Field State Retrieval with Get Utility

Source: https://filamentphp.com/docs/4.x/forms/overview

Shows type-safe methods available on the Get utility for retrieving field states with specific data types. Includes methods for string, integer, float, boolean, array, date, and enum types. Each method assumes non-nullable returns unless isNullable: true is passed.

```PHP
use Filament\Schemas\Components\Utilities\Get;

$get->string('email');
$get->integer('age');
$get->float('price');
$get->boolean('is_admin');
$get->array('tags');
$get->date('published_at');
$get->enum('status', StatusEnum::class);
$get->filled('email'); // Returns the result of the `filled()` helper for the field.
$get->blank('email'); // Returns the result of the `blank()` helper for the field.
```

--------------------------------

### Dynamic Field Schema Based on Select Option in Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Render different field sets conditionally based on a select field's value using dynamic schema functions and the `afterStateUpdated()` callback. The example switches between 'employee' and 'freelancer' field schemas, with `afterStateUpdated()` initializing the new fields when the type selection changes. Uses a keyed Grid component for targeted schema management.

```PHP
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;

Select::make('type')
    ->options([
        'employee' => 'Employee',
        'freelancer' => 'Freelancer',
    ])
    ->live()
    ->afterStateUpdated(fn (Select $component) => $component
        ->getContainer()
        ->getComponent('dynamicTypeFields')
        ->getChildSchema()
        ->fill())
    
Grid::make(2)
    ->schema(fn (Get $get): array => match ($get('type')) {
        'employee' => [
            TextInput::make('employee_number')
                ->required(),
            FileUpload::make('badge')
                ->image()
                ->required(),
        ],
        'freelancer' => [
            TextInput::make('hourly_rate')
                ->numeric()
                ->required()
                ->prefix('€'),
            FileUpload::make('contract')
                ->required(),
        ],
        default => [],
    })
    ->key('dynamicTypeFields')
```

--------------------------------

### Use Dynamic Utilities in Field Slot Functions

Source: https://filamentphp.com/docs/4.x/forms/overview

Pass functions to slot methods to dynamically calculate content. Available utilities include $get for retrieving form values, $state for field state, $record for Eloquent records, $operation for create/edit/view context, and others. Validation is not run on these values.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->belowContent(function ($get, $state, $record, $operation) {
        // Dynamic content based on form state
        return 'User: ' . $get('name');
    })
```

--------------------------------

### Install and Run Filament v4 Upgrade Script - Composer

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Installs the Filament upgrade package and executes the automated upgrade script to handle breaking changes. The script automatically updates application code for most v4 compatibility issues. After running the script commands, remove the upgrade package as it's no longer needed.

```bash
composer require filament/upgrade:"^4.0" -W --dev

vendor/bin/filament-v4

# Run the commands output by the upgrade script, they are unique to your app
composer require filament/filament:"^4.0" -W --no-update
composer update
```

--------------------------------

### Type-Safe Nullable Field State Retrieval

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates how to force nullable return types when retrieving another field's state using the Get utility. Pass isNullable: true to any typed retrieval method to allow null values in the return type.

```PHP
use Filament\Schemas\Components\Utilities\Get;

$get->string('email', isNullable: true);
```

--------------------------------

### Install esbuild via NPM

Source: https://filamentphp.com/docs/4.x/advanced/assets

Install esbuild as a development dependency using NPM. This package is required to compile JavaScript and Alpine components into a single bundled file.

```bash
npm install esbuild --save-dev
```

--------------------------------

### Navigate to Next Wizard Step with Pest Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-schemas

Tests wizard step progression using goToNextWizardStep() method. This helper advances to the next step in a wizard sequence and can be chained with assertion methods to verify form errors or other conditions after navigation.

```php
use function Pest\Livewire\livewire;

it('moves to next wizard step', function () {
    livewire(CreatePost::class)
        ->goToNextWizardStep()
        ->assertHasFormErrors(['title']);
});
```

--------------------------------

### Add Label to FusedGroup

Source: https://filamentphp.com/docs/4.x/forms/overview

Adds a label above a FusedGroup component using the label() method. This provides context for the grouped fields.

```php
use Filament\Schemas\Components\FusedGroup;

FusedGroup::make([
    // ...
])
    ->label('Location')
```

--------------------------------

### Install and Run Filament v4 Upgrade Script - Windows PowerShell

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Alternative upgrade procedure for Windows PowerShell environments that ignores caret (^) characters in version constraints. Uses tilde (~) version constraints instead to ensure proper dependency resolution on Windows systems.

```bash
composer require filament/upgrade:"~4.0" -W --dev

vendor/bin/filament-v4

# Run the commands output by the upgrade script, they are unique to your app
composer require filament/filament:"~4.0" -W --no-update
composer update
```

--------------------------------

### Create basic Text component with plain text

Source: https://filamentphp.com/docs/4.x/schemas/primes

Demonstrates how to create a basic Text component with static text content using the make() method in Filament schemas. This is the simplest way to display text messages or information.

```php
use Filament\Schemas\Components\Text;

Text::make('Modifying these permissions may give users access to sensitive information.')
```

--------------------------------

### Example Custom Blade View for Filament Page (Blade)

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

This Blade template provides a starting point for a custom Filament page view. It utilizes `x-filament-panels::page` for the basic panel structure and demonstrates how to access the current Eloquent record and render the content defined in the page's `content()` method. This allows for a complete overhaul of the page's presentation using standard Blade templating.

```blade
<x-filament-panels::page>
    {{-- `$this->getRecord()` will return the current Eloquent record for this page --}}
    
    {{ $this->content }} {{-- This will render the content of the page defined in the `content()` method, which can be removed if you want to start from scratch --}}
</x-filament-panels::page>
```

--------------------------------

### Create and configure ImportAction

Source: https://filamentphp.com/docs/4.x/actions/import

Instantiate an ImportAction with a specified importer class to handle CSV imports. This basic example shows how to create an import action that uses a ProductImporter class to process uploaded CSV data.

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
```

--------------------------------

### Handle field updates with afterStateUpdated in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Executes custom logic when a user updates a field value. The callback receives parameters like current state, old state, and utility injections ($get, $set, $component) for accessing and modifying form data without full validation.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->afterStateUpdated(function (?string $state, ?string $old) {
        // ...
    })
```

--------------------------------

### Instantiate Plugin Using Fluent make() Method

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Use the fluent make() method to instantiate a plugin in panel configuration, providing a cleaner syntax compared to direct instantiation. This approach supports dependency injection through the Laravel container.

```php
use DanHarrin\FilamentBlog\BlogPlugin;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(BlogPlugin::make());
}
```

--------------------------------

### Create EmptyState with Heading, Description, Icon, and Footer Actions

Source: https://filamentphp.com/docs/4.x/schemas/empty-states

Creates an empty state component with a heading, description text, icon from Heroicon library, and a footer action button. This is used to display when no content exists and guide users to the next action.

```PHP
use Filament\Actions\Action;
use Filament\Schemas\Components\EmptyState;
use Filament\Support\Icons\Heroicon;

EmptyState::make('No users yet')
    ->description('Get started by creating a new user.')
    ->icon(Heroicon::OutlinedUser)
    ->footer([
        Action::make('createUser')
            ->icon(Heroicon::Plus),
    ])
```

--------------------------------

### Create Basic Code Entry with PHP Grammar in Filament

Source: https://filamentphp.com/docs/4.x/infolists/code-entry

Creates a basic CodeEntry component that displays PHP code with syntax highlighting. Requires the phiki/phiki Composer package to be installed first. The component uses the PHP grammar by default and applies server-side code highlighting through Phiki.

```php
use Filament\Infolists\Components\CodeEntry;
use Phiki\Grammar\Grammar;

CodeEntry::make('code')
    ->grammar(Grammar::Php)
```

--------------------------------

### Add Plain Text to Filament Entry Slot (PHP)

Source: https://filamentphp.com/docs/4.x/infolists

This example demonstrates how to insert a simple string of plain text into a Filament entry slot using the `belowContent()` method of a `TextEntry` component. It uses the `TextEntry` component from `Filament\Infolists\Components`.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->belowContent('This is the user's full name.')
```

--------------------------------

### Align Multiple Content Items in Filament Entry Slots (PHP)

Source: https://filamentphp.com/docs/4.x/infolists

This example demonstrates how to align multiple content items within an entry slot using `Schema::end()` for right alignment or `Schema::between()` for justified spacing. It also shows using a `Flex` component with `grow(false)` to precisely control the layout of an icon and text pair within `Schema::between()`.

```php
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->belowContent(Schema::end([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]))

TextEntry::make('name')
    ->belowContent(Schema::between([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]))

TextEntry::make('name')
    ->belowContent(Schema::between([
        Flex::make([
            Icon::make(Heroicon::InformationCircle)
                ->grow(false),
            'This is the user\'s full name.',
        ]),
        Action::make('generate'),
    ]))
```

--------------------------------

### Access parent field values in Filament Builder using $get('../')

Source: https://filamentphp.com/docs/4.x/forms/builder

This section explains how to retrieve values from fields outside the current builder item's scope within a Filament form using the `$get()` utility. By default, `$get()` is scoped to the current builder item, but prefixing the field name with `../` allows access to parent-level data. The provided example illustrates a typical data structure where this technique is useful.

```php
[
    'client_id' => 1,

    'builder' => [
        'item1' => [
            'service_id' => 2,
        ],
    ],
]
```

--------------------------------

### Test Filament Action Modal Form Filling (PHP)

Source: https://filamentphp.com/docs/4.x/testing/testing-actions

This example shows how to pre-fill an action modal's form data using `fillForm()` after mounting the action. It's useful for setting up data without immediately invoking the action, allowing for separate testing of form interaction.

```php
use function Pest\Livewire\livewire;

it('can send invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->mountAction('send')
        ->fillForm([
            'email' => $email = fake()->email(),
        ]);

});
```

--------------------------------

### Create Password Field with Filament TextInput

Source: https://filamentphp.com/docs/4.x/forms/overview

Creates a basic password input field using Filament's TextInput component. This is the foundation for password fields in Filament forms.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
```

--------------------------------

### Disable Filament Text Input Statically

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates how to prevent a Filament `TextInput` field from being edited by the user by calling the `disabled()` method without any arguments, making it read-only.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->disabled()
```

--------------------------------

### Generate Livewire Layout File

Source: https://filamentphp.com/docs/4.x/introduction/installation

This Artisan command creates a new Blade layout file for Livewire components at `resources/views/components/layouts/app.blade.php`. This file is crucial for Filament's UI rendering, providing the base template where Filament's styles and scripts will be integrated, setting up the foundational structure for the application's interface.

```php
php artisan livewire:layout
```

--------------------------------

### Making Filament Form Fields Reactive (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This code demonstrates how to enable reactivity for a Filament form field, such as a `Select` component, using the `live()` method. When the user interacts with this field, the schema will re-render, allowing for dynamic updates based on the new value without a full page reload.

```php
use Filament\Forms\Components\Select;

Select::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
    ->live()

```

--------------------------------

### Access Import Options in resolveRecord Method

Source: https://filamentphp.com/docs/4.x/actions/import

Retrieve and use import options within the importer class's resolveRecord() method to conditionally update or create database records. This example demonstrates updating existing products based on the updateExisting option.

```PHP
use App\Models\Product;

public function resolveRecord(): ?Product
{
    if ($this->options['updateExisting'] ?? false) {
        return Product::firstOrNew([
            'sku' => $this->data['sku'],
        ]);
    }

    return new Product();
}
```

--------------------------------

### Define a Basic Image Entry in Filament Infolists (PHP)

Source: https://filamentphp.com/docs/4.x/infolists/image-entry

Demonstrates how to create a simple image entry in Filament Infolists using `ImageEntry::make()`. The image path, derived from the `header_image` state, can be relative to the configured storage disk's root directory or an absolute URL.

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')
```

--------------------------------

### Navigate to Previous Wizard Step with Pest Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-schemas

Tests backward navigation in a wizard using goToPreviousWizardStep() method. Allows reverting to the previous step and validating form state or errors after moving backward in the wizard sequence.

```php
use function Pest\Livewire\livewire;

it('moves to next wizard step', function () {
    livewire(CreatePost::class)
        ->goToPreviousWizardStep()
        ->assertHasFormErrors(['title']);
});
```

--------------------------------

### Set Static Placeholder on TextInput Field

Source: https://filamentphp.com/docs/4.x/forms/overview

Creates a TextInput field with a static placeholder value. The placeholder is displayed in the UI when the field has no value but is never saved when the form is submitted.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->placeholder('John Doe')
```

--------------------------------

### Add Filament Action to Entry Slot (PHP)

Source: https://filamentphp.com/docs/4.x/infolists

This example shows how to insert a Filament `Action` component into an entry slot. The `Action::make('generate')` creates a simple interactive action that can be displayed alongside the entry's content.

```php
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->belowContent(Action::make('generate'))
```

--------------------------------

### Create Action with Static Keybindings in Filament

Source: https://filamentphp.com/docs/4.x/actions/overview

Demonstrates how to attach static keyboard shortcuts to a Filament action. The keyBindings() method accepts an array of key codes compatible with Mousetrap library. This example shows binding both Command+S and Ctrl+S to a save action that executes a save method.

```php
use Filament\Actions\Action;

Action::make('save')
    ->action(fn () => $this->save())
    ->keyBindings(['command+s', 'ctrl+s'])
```

--------------------------------

### Configure Navigation Icon Property Visibility in Filament (PHP)

Source: https://filamentphp.com/docs/4.x/advanced/file-generation

Demonstrates overriding a configuration method to modify an existing property's attributes, such as its visibility. This example changes the `$navigationIcon` property from `protected` to `public` using the `nette/php-generator` `Property` object passed into the method.

```php
use Nette\PhpGenerator\Property;

protected function configureNavigationIconProperty(Property $property): void
{
    $property->setPublic();
}
```

--------------------------------

### Configure FusedGroup Column Layout

Source: https://filamentphp.com/docs/4.x/forms/overview

Sets the number of columns for FusedGroup fields using the columns() method. By default each field has its own row, but this allows horizontal display on desktop by specifying column count.

```php
use Filament\Schemas\Components\FusedGroup;

FusedGroup::make([
    // ...
])
    ->label('Location')
    ->columns(2)
```

--------------------------------

### Insert Actions into Field Slots

Source: https://filamentphp.com/docs/4.x/forms/overview

Add Action or ActionGroup instances to field slots to provide interactive elements. Actions can be triggered within the field context and are rendered in the specified slot position.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->belowContent(Action::make('generate'))
```

--------------------------------

### Create Tenant Registration Page Class in Filament

Source: https://filamentphp.com/docs/4.x/users/tenancy

Create a registration page by extending RegisterTenant base class. Implement getLabel() to set page title, form() to define schema components, and handleRegistration() to create the tenant model and associate the current user. This Livewire component allows new users to register as tenants.

```PHP
namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                // ...
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);

        $team->members()->attach(auth()->user());

        return $team;
    }
}
```

--------------------------------

### Display Authorization Tooltip for Filament Action

Source: https://filamentphp.com/docs/4.x/actions/overview

This example shows how to configure a Filament action to display a tooltip with a policy's response message when authorization fails. Using `authorizationTooltip()` causes the action to be disabled but visible, rather than entirely hidden. This provides user feedback on why an action cannot be performed.

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->authorize('update')
    ->authorizationTooltip()
```

--------------------------------

### Conditionally Hide FilamentPHP Form Field with `hidden()`

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet demonstrates how to hide a FilamentPHP form field based on the value of another field. It uses the `hidden()` method with a closure that injects the `Get` utility to check the `is_company` checkbox value. The `live()` method on the checkbox ensures the form re-renders dynamically.

```PHP
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;

Checkbox::make('is_company')
    ->live()

TextInput::make('company_name')
    ->hidden(fn (Get $get): bool => ! $get('is_company'))
```

--------------------------------

### Install Filament Spark Billing Provider via Composer

Source: https://filamentphp.com/docs/4.x/users/tenancy

Install the Filament billing integration package for Laravel Spark using Composer. This package enables subscription management and billing features within Filament, allowing users to manage their subscriptions through the application interface.

```Bash
composer require filament/spark-billing-provider
```

--------------------------------

### Show Field with Conditional Boolean in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Control field visibility using the visible() method as an alternative to hidden(). This accepts a boolean expression and can improve code readability when checking positive conditions.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->visible(FeatureFlag::active())
```

--------------------------------

### Receive Parameters in Livewire Component Mount Method

Source: https://filamentphp.com/docs/4.x/schemas/custom-components

This code illustrates how to define the `mount()` method in a Livewire component to receive parameters passed from the Filament schema. The parameters are type-hinted and can be used within the component's initialization logic.

```php
class Chart extends Component
{
    public function mount(string $bar): void
    {
        // ...
    }
}
```

--------------------------------

### Enable SPA Mode in Filament Panel

Source: https://filamentphp.com/docs/4.x/panel-configuration

Enable single-page-application mode using the spa() method to leverage Livewire's wire:navigate feature. Provides faster page transitions with reduced delay and displays a loading bar for longer requests, creating a smooth SPA-like experience.

```PHP
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->spa();
}
```

--------------------------------

### Create Eloquent Record with CreateAction in Filament

Source: https://filamentphp.com/docs/4.x/actions/create

Initialize a CreateAction with a form schema containing a required TextInput field. The action opens a modal when triggered, validates the form data, and saves it to the database. This example demonstrates basic setup with a title field that has validation rules.

```php
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;

CreateAction::make()
    ->schema([
        TextInput::make('title')
            ->required()
            ->maxLength(255),
        // ...
    ])
```

--------------------------------

### Inject Raw Field State Parameter

Source: https://filamentphp.com/docs/4.x/forms/overview

Shows how to access the raw, uncast state of a field using a $rawState parameter. This is useful when a field automatically casts its state to a different format and you need the original value before casting.

```PHP
function ($rawState) {
    // ...
}
```

--------------------------------

### Align Slot Content with Schema Methods

Source: https://filamentphp.com/docs/4.x/forms

Control content alignment in slots using Schema::start(), Schema::end(), or Schema::between(). This example demonstrates end-alignment, between-alignment, and flex-based layout for icon-text grouping.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->belowContent(Schema::end([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]));

TextInput::make('name')
    ->belowContent(Schema::between([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]));

TextInput::make('name')
    ->belowContent(Schema::between([
        Flex::make([
            Icon::make(Heroicon::InformationCircle)
                ->grow(false),
            'This is the user\'s full name.',
        ]),
        Action::make('generate'),
    ]));
```

--------------------------------

### Format Currency with Division in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example shows how to use the `divideBy` argument with the `money()` method, which is useful when the database stores values in smaller units, like cents. A `Sum` summarizer on a `price` `TextColumn` formats the total as 'EUR' after dividing by 100.

```php
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->summarize(Sum::make()->money('EUR', divideBy: 100))
```

--------------------------------

### Divide and Format Money in Filament TextEntry (PHP)

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This example shows how to use the `divideBy` argument with the `money()` method to adjust the value before formatting. It's useful when storing prices in smaller units (e.g., cents) in the database and needing to display them in a standard currency unit. The `divideBy` argument can also accept a function for dynamic calculation with utility injection.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('price')
    ->money('EUR', divideBy: 100)
```

--------------------------------

### Enable SPA Prefetching in Filament Panel

Source: https://filamentphp.com/docs/4.x/panel-configuration

This snippet demonstrates how to enable Single Page Application (SPA) prefetching for a Filament panel. By setting `hasPrefetching: true` in the `spa()` method, links will automatically prefetch content on hover, using Livewire's `wire:navigate.hover`. This enhances navigation responsiveness but should be used judiciously for heavy pages to manage bandwidth and server load.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->spa(hasPrefetching: true);
}
```

--------------------------------

### Hide Field Using JavaScript Expression

Source: https://filamentphp.com/docs/4.x/forms/overview

Hide a form field based on another field's value using client-side JavaScript with the `hiddenJs()` method. This approach avoids network requests by executing logic in the browser. The `$get()` utility function is available in JavaScript and works similarly to its PHP counterpart without requiring the dependent field to be live().

```PHP
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])

Toggle::make('is_admin')
    ->hiddenJs(<<<'JS'
        $get('role') !== 'staff'
        JS)
```

--------------------------------

### Hide Field with Conditional Boolean in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Control field visibility by passing a boolean expression to the hidden() method. This allows conditional hiding based on feature flags, configuration, or other runtime conditions.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->hidden(! FeatureFlag::active())
```

--------------------------------

### Apply Outlined Style to Filament Action Button (PHP)

Source: https://filamentphp.com/docs/4.x/actions

This PHP code demonstrates how to make a Filament action button appear outlined. The first example shows a static application of the `outlined()` method. The second example illustrates how to conditionally apply the outlined style by passing a boolean value, such as from a `FeatureFlag`, to the method.

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->button()
    ->outlined()
```

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->button()
    ->outlined(FeatureFlag::active())
```

--------------------------------

### Dehydrate Filament Toggle Statically

Source: https://filamentphp.com/docs/4.x/forms/overview

Explains how to make a Filament `Toggle` field non-editable using `disabled()` while still ensuring its value is saved to the database by also calling the `dehydrated()` method without arguments.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->disabled()
    ->dehydrated()
```

--------------------------------

### Set Individual Field Column Span in FusedGroup

Source: https://filamentphp.com/docs/4.x/forms/overview

Adjusts the width of individual fields within a FusedGroup by passing columnSpan() to each field. This allows fine-grained control over field widths within the grid layout.

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;

FusedGroup::make([
    TextInput::make('city')
        ->placeholder('City')
        ->columnSpan(2),
    Select::make('country')
        ->placeholder('Country')
        ->options([
            // ...
        ]),
])
    ->label('Location')
    ->columns(3)
```

--------------------------------

### Enable Skippable Steps in Filament Create Action Wizard (PHP)

Source: https://filamentphp.com/docs/4.x/actions/create

This snippet shows how to enable free navigation within a multi-step wizard for a Filament `CreateAction`. By calling the `skippableSteps()` method after defining the steps, users can skip or revisit any step in the wizard.

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->steps(
        // ...
    )
    ->skippableSteps()
```

--------------------------------

### Add Extra Content Above Field Label - Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Insert content above a field's label using the aboveLabel() method. Accepts static content like text, schema components, actions, or action groups. Also supports dynamic calculation through a callback function with injected utilities like $get, $state, $record, and $operation for conditional rendering based on form data.

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->aboveLabel([
        Icon::make(Heroicon::Star),
        'This is the content above the field\'s label'
    ])
```

--------------------------------

### Define Different Relation Managers for Multiple Filament View Pages (PHP)

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

These PHP examples demonstrate how to provide distinct sets of relation managers for different view pages within the same resource. Each view page (e.g., `ViewCustomer.php`, `ViewCustomerContact.php`) can implement its own `getAllRelationManagers()` method, allowing for highly customized sub-navigation content. This is crucial for presenting relevant related data on specialized view pages.

```php
// ViewCustomer.php
protected function getAllRelationManagers(): array
{
    return [
        RelationManagers\OrdersRelationManager::class,
        RelationManagers\SubscriptionsRelationManager::class,
    ];
}
```

```php
// ViewCustomerContact.php 
protected function getAllRelationManagers(): array
{
    return [
        RelationManagers\ContactsRelationManager::class,
        RelationManagers\AddressesRelationManager::class,
    ];
}
```

--------------------------------

### Generate Basic Filament Livewire Form using Artisan CLI (Bash)

Source: https://filamentphp.com/docs/4.x/components/form

This command line example demonstrates how to generate a new Filament Livewire form component. The `make:filament-livewire-form` Artisan command creates a customizable form component in your application's `app/Livewire` directory, ready for further configuration.

```bash
php artisan make:filament-livewire-form RegistrationForm
```

--------------------------------

### Inject Multiple Utilities in Filament Infolist Callbacks

Source: https://filamentphp.com/docs/4.x/infolists

This snippet demonstrates the flexibility of Filament's utility injection, allowing multiple parameters to be defined in a callback function. Utilities like `Livewire` component, `$get` function, and `User` record can be combined in any order, as they are dynamically injected using reflection.

```php
use AppModelsUser;
use FilamentSchemasComponentsUtilitiesGet;
use LivewireComponent as Livewire;

function (Livewire $livewire, Get $get, User $record) {
    // ...
}
```

--------------------------------

### Create FusedGroup with Multiple Fields

Source: https://filamentphp.com/docs/4.x/forms/overview

Groups multiple form fields (TextInput, Select, DateTimePicker, ColorPicker) together into a FusedGroup component. Fields are passed as an array to the make() method and fused together visually in the UI.

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;

FusedGroup::make([
    TextInput::make('city')
        ->placeholder('City'),
    Select::make('country')
        ->placeholder('Country')
        ->options([
            // ...
        ]),
])
```

--------------------------------

### Enable User Selection of Filament Table Groupings (PHP)

Source: https://filamentphp.com/docs/4.x/tables/grouping

This example shows how to allow users to choose from multiple grouping options in a Filament table. An array of attribute names is passed to the `groups()` method, enabling a dropdown for user selection.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->groups([
            'status',
            'category',
        ]);
}

```

--------------------------------

### Add prefix and suffix text to TextInput

Source: https://filamentphp.com/docs/4.x/forms/text-input

Attach static or dynamic text before and after a TextInput using prefix() and suffix() methods. These methods accept both string values and callable functions with utility injection for dynamic calculation based on form state.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('domain')
    ->prefix('https://')
    ->suffix('.com')
```

--------------------------------

### Execute Logic When Filament PHP Action Modal Opens

Source: https://filamentphp.com/docs/4.x/actions/modals

This example shows how to run custom code within a closure when a Filament action modal is mounted and opens. The `mountUsing()` method allows injecting a schema object to fill or manipulate form data, requiring an explicit `$form->fill()` call if overriding default initialization.

```php
use Filament\Actions\Action;
use Filament\Schemas\Schema;

Action::make('create')
    ->mountUsing(function (Schema $form) {
        $form->fill();

        // ...
    })
```

--------------------------------

### Conditionally Require FilamentPHP Form Field

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet shows how to make a FilamentPHP form field conditionally required based on the input of another field. It uses the `required()` method with a closure that utilizes the `Get` utility and `filled()` helper to check if the `company_name` field has a value, making `vat_number` required only then. The `live(onBlur: true)` ensures validation triggers after field interaction.

```PHP
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\TextInput;

TextInput::make('company_name')
    ->live(onBlur: true)
    
TextInput::make('vat_number')
    ->required(fn (Get $get): bool => filled($get('company_name')))
```

--------------------------------

### Format field state using formatStateUsing in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Provides a shortcut for transforming field state during hydration. This method accepts a closure that receives the current state and returns the formatted value, simplifying the afterStateHydrated() syntax for simple transformations.

```php
use Closure;
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->formatStateUsing(fn (string $state): string => ucwords($state))
```

--------------------------------

### Save Relationship Data with Group Component

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates using a Group layout component (which has no styling) with the relationship() method to save nested form fields to a BelongsTo relationship. This approach provides more flexibility than Fieldset for relationship data handling.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;

Group::make()
    ->relationship('customer')
    ->schema([
        TextInput::make('name')
            ->label('Customer')
            ->required(),
        TextInput::make('email')
            ->label('Email address')
            ->email()
            ->required(),
    ])
```

--------------------------------

### Generate Slug with User Override Protection in FilamentPHP Form

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet refines slug generation by preventing overwriting of manually edited slugs. It uses `afterStateUpdated()` with `Get` and `Set` utilities, comparing the current slug with a slug generated from the old title state. If they don't match, it assumes manual intervention and skips updating the slug, otherwise, it generates a new slug from the current title state.

```PHP
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

TextInput::make('title')
    ->live(onBlur: true)
    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
        if (($get('slug') ?? '') !== Str::slug($old)) {
            return;
        }
    
        $set('slug', Str::slug($state));
    })
    
TextInput::make('slug')
```

--------------------------------

### Require Password on User Creation Operation

Source: https://filamentphp.com/docs/4.x/forms/overview

Makes the password field required only during record creation by injecting the $operation parameter and checking if it equals 'create'. This allows optional password updates while requiring passwords for new user creation.

```php
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

TextInput::make('password')
    ->password()
    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
    ->dehydrated(fn (?string $state): bool => filled($state))
    ->required(fn (string $operation): bool => $operation === 'create')
```

--------------------------------

### Test Filament Action Existence (PHP)

Source: https://filamentphp.com/docs/4.x/testing/testing-actions

This example demonstrates how to assert the existence or non-existence of specific actions on a component using `assertActionExists()` and `assertActionDoesNotExist()`. It verifies that a 'send' action is present while an 'unsend' action is not.

```php
use function Pest\Livewire\livewire;

it('can send but not unsend invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionExists('send')
        ->assertActionDoesNotExist('unsend');
});
```

--------------------------------

### Insert Multiple Content Types into Field Slots

Source: https://filamentphp.com/docs/4.x/forms/overview

Combine multiple content types (icons, text, actions) into a single field slot by passing an array. This enables complex layouts with mixed content within field slots.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->belowContent([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ])
```

--------------------------------

### Create Multistep Wizard in Modal - Filament PHP

Source: https://filamentphp.com/docs/4.x/actions/modals

Creates a wizard-based modal form with multiple steps using Filament Actions. Each step contains a schema with form components (TextInput, MarkdownEditor, Toggle) and supports features like live validation, auto-slug generation, and uniqueness constraints. The wizard progresses through Name, Description, and Visibility steps.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard\Step;

Action::make('create')
    ->steps([
        Step::make('Name')
            ->description('Give the category a unique name')
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->disabled()
                    ->required()
                    ->unique(Category::class, 'slug'),
            ])
            ->columns(2),
        Step::make('Description')
            ->description('Add some extra details')
            ->schema([
                MarkdownEditor::make('description'),
            ]),
        Step::make('Visibility')
            ->description('Control who can view it')
            ->schema([
                Toggle::make('is_visible')
                    ->label('Visible to customers.')
                    ->default(true),
            ]),
    ])
```

--------------------------------

### Hash Password on Form Submission with Dehydration

Source: https://filamentphp.com/docs/4.x/forms/overview

Uses a dehydration function to automatically hash the password using Laravel's Hash facade when the form is submitted. The dehydrateStateUsing() method transforms the field value before it is saved to the database.

```php
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

TextInput::make('password')
    ->password()
    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
```

--------------------------------

### Install Chart.js Data Labels Plugin with NPM

Source: https://filamentphp.com/docs/4.x/widgets/charts

Installs the 'chartjs-plugin-datalabels' package using npm as a development dependency. This command adds the plugin to your project, making it available for import in your JavaScript files.

```bash
npm install chartjs-plugin-datalabels --save-dev
```

--------------------------------

### Verify Filament Widgets Installation via Composer

Source: https://filamentphp.com/docs/4.x/components/widget

Command to check if the filament/widgets package is installed in your project. Run this in your terminal to verify the installation status before proceeding with widget implementation.

```bash
composer show filament/widgets
```

--------------------------------

### Align TextEntry Content Using Methods - Filament PHP

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates using alignStart(), alignCenter(), and alignEnd() methods to position TextEntry component content. alignStart() is the default alignment. These methods provide a fluent interface for setting static alignment values on individual TextEntry components.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->alignStart() // This is the default alignment.

TextEntry::make('title')
    ->alignCenter()

TextEntry::make('title')
    ->alignEnd()
```

--------------------------------

### Register assets using FilamentAsset facade in service provider

Source: https://filamentphp.com/docs/4.x/advanced/assets

Demonstrates how to register assets in the boot() method of a service provider. The FilamentAsset::register() method accepts an array of assets and copies them to the /public directory. This is the primary entry point for the asset registration system.

```php
use Filament\Support\Facades\FilamentAsset;

public function boot(): void
{
    // ...

    FilamentAsset::register([
        // ...
    ]);

    // ...
}
```

--------------------------------

### Conditionally Dehydrate Password Field When Not Empty

Source: https://filamentphp.com/docs/4.x/forms/overview

Prevents password field dehydration when the value is null or empty using the filled() helper. This preserves existing passwords when updating records if the password field is left blank.

```php
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

TextInput::make('password')
    ->password()
    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
    ->dehydrated(fn (?string $state): bool => filled($state))
```

--------------------------------

### Injecting Livewire Component into Filament Schema Functions (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

To access the current Livewire component instance within a Filament schema function, type-hint the `$livewire` parameter with `Livewire\Component`. This enables interaction with the Livewire component's properties and methods, facilitating advanced dynamic behavior.

```php
use Livewire\Component;

function (Component $livewire) {
    // ...
}

```

--------------------------------

### Insert Schema Components into Field Slots

Source: https://filamentphp.com/docs/4.x/forms/overview

Add schema components like Text with styling to field slots. This allows for formatted and styled content insertion, including components with properties like font weight. Prime components are typically used for this purpose.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

TextInput::make('name')
    ->belowContent(Text::make('This is the user\'s full name.')->weight(FontWeight::Bold))
```

--------------------------------

### Add Content Before Filament Field using `beforeContent()` (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

Use the `beforeContent()` method in Filament PHP to insert custom content before a form field's main input. This method supports static content like an icon or a dynamic function that can leverage injected utilities such as the current field component, form data retrieval (`$get`), Livewire instance, or the Eloquent record. Consider using `prefix()` for adjoined content.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->beforeContent(Icon::make(Heroicon::Star))
```

--------------------------------

### Grid Component with Responsive Columns - PHP

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Demonstrates the Grid component as an alternative to using the columns() method on other layout components. This provides explicit grid syntax without additional styling and accepts column configuration directly in Grid::make(). Supports all Tailwind breakpoints (default, sm, md, lg, xl, 2xl) for fully responsive layouts.

```php
use Filament\Schemas\Components\Grid;

Grid::make([
    'default' => 1,
    'sm' => 2,
    'md' => 3,
    'lg' => 4,
    'xl' => 6,
    '2xl' => 8,
])
    ->schema([
        // ...
    ])
```

--------------------------------

### Create a custom Blade view for Filament page

Source: https://filamentphp.com/docs/4.x/resources/listing-records

This example provides the basic structure for a custom Blade view in Filament. It includes a placeholder to render content defined in the `content()` method, which can be removed for a completely custom layout.

```blade
<x-filament-panels::page>
    {{ $this->content }} {{-- This will render the content of the page defined in the `content()` method, which can be removed if you want to start from scratch --}}
</x-filament-panels::page>
```

--------------------------------

### Add Schema Component to Filament Entry Slot (PHP)

Source: https://filamentphp.com/docs/4.x/infolists

This example illustrates how to embed a Filament schema component, specifically a `Text` component, into an entry slot. The `Text` component is configured with bold font weight using `FontWeight::Bold` for visual emphasis.

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

TextEntry::make('name')
    ->belowContent(Text::make('This is the user's full name.')->weight(FontWeight::Bold))
```

--------------------------------

### Conditionally Disable Filament Toggle with Boolean

Source: https://filamentphp.com/docs/4.x/forms/overview

Illustrates how to conditionally disable a Filament `Toggle` field by passing a boolean value to the `disabled()` method, allowing dynamic control over its editability, often based on application logic or feature flags.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->disabled(! FeatureFlag::active())
```

--------------------------------

### Generate New Filament Cluster Using Artisan Command (Bash)

Source: https://filamentphp.com/docs/4.x/navigation/clusters

This Bash command creates a new Filament cluster class. It generates a boilerplate cluster file in the configured cluster directory, named `SettingsCluster` in this example. This command streamlines the setup process for new clusters within your Filament project.

```Bash
php artisan make:filament-cluster Settings
```

--------------------------------

### Hide Field with Static Value in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Hide a form field using the hidden() method without parameters. This completely hides the field from the form. The method returns the field component instance for method chaining.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->hidden()
```

--------------------------------

### Customize Record Creation with using() Method - Filament PHP

Source: https://filamentphp.com/docs/4.x/actions/create

Demonstrates how to customize the record creation process using the `using()` method in Filament's CreateAction. The method receives form data and model class name, allowing you to implement custom creation logic. The function supports dependency injection of multiple utility parameters including actions, Livewire components, and schema utilities.

```php
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;

CreateAction::make()
    ->using(function (array $data, string $model): Model {
        return $model::create($data);
    })
```

--------------------------------

### Create Plugin Class Implementing Plugin Interface

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Create a plugin class that implements the Filament Plugin interface with three required methods: getId() for unique identification, register() to configure panel resources and pages, and boot() for runtime initialization. This example registers PostResource, CategoryResource, and Settings pages.

```php
<?php

namespace DanHarrin\FilamentBlog;

use DanHarrin\FilamentBlog\Pages\Settings;
use DanHarrin\FilamentBlog\Resources\CategoryResource;
use DanHarrin\FilamentBlog\Resources\PostResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class BlogPlugin implements Plugin
{
    public function getId(): string
    {
        return 'blog';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                PostResource::class,
                CategoryResource::class,
            ])
            ->pages([
                Settings::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
```

--------------------------------

### Create Stats Overview Widget - Filament Artisan Command

Source: https://filamentphp.com/docs/4.x/widgets/stats-overview

Generate a new stats overview widget using Filament's artisan command. This creates a StatsOverview.php file with the necessary structure for displaying statistics in your Filament dashboard.

```bash
php artisan make:filament-widget StatsOverview --stats-overview
```

--------------------------------

### Check OPcache Status - PHP CLI

Source: https://filamentphp.com/docs/4.x/deployment

Verify if OPcache is enabled on the server. Returns opcache.enable value (1 = enabled, 0 = disabled). Essential for production performance optimization.

```bash
php -r "echo 'opcache.enable => ' . ini_get('opcache.enable') . PHP_EOL;"
```

--------------------------------

### Save Form Fields to Relationship with Fieldset

Source: https://filamentphp.com/docs/4.x/forms/overview

Uses a Fieldset layout component with the relationship() method to automatically load and save nested fields to a HasOne, BelongsTo, or MorphOne Eloquent relationship. Related records are automatically created if they don't exist.

```php
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

Fieldset::make('Metadata')
    ->relationship('metadata')
    ->schema([
        TextInput::make('title'),
        Textarea::make('description'),
        FileUpload::make('image'),
    ])
```

--------------------------------

### Utilize a custom Filament entry with a utility-injected closure

Source: https://filamentphp.com/docs/4.x/infolists/custom-entries

This PHP example shows how to use the `AudioPlayerEntry` component, passing a closure to its `speed()` method. The closure demonstrates utility injection by accepting a `Conference` record parameter, allowing the speed to be dynamically determined based on the record's properties.

```php
use App\Filament\Infolists\Components\AudioPlayerEntry;

AudioPlayerEntry::make('recording')
    ->speed(fn (Conference $record): float => $record->isGlobal() ? 1 : 0.5)
```

--------------------------------

### Injecting Current Field Instance into Filament Schema Functions (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet illustrates how to inject the current field instance into a Filament schema function. Type-hint the `$component` parameter with `Filament\Forms\Components\Field` to interact directly with the field itself, allowing for field-specific customizations.

```php
use Filament\Forms\Components\Field;

function (Field $component) {
    // ...
}

```

--------------------------------

### Get enabled ToggleButtons options for validation in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/toggle-buttons

This PHP example illustrates how to retrieve only the enabled options from a Filament ToggleButtons component for validation purposes. It combines the `disableOptionWhen()` method with `getEnabledOptions()` and the `in()` validation rule to ensure that only non-disabled options are considered valid choices.

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'published')
    ->in(fn (ToggleButtons $component): array => array_keys($component->getEnabledOptions()))
```

--------------------------------

### Configure full domain-based tenant routing

Source: https://filamentphp.com/docs/4.x/users/tenancy

Set up complete domain-based tenant identification where each tenant uses its own domain by mapping the tenantDomain() to a domain attribute on the tenant model. The domain attribute should contain a valid domain host like 'example.com' or 'subdomain.example.com'. This registers a global route parameter pattern [a-z0-9.\-]+ for all tenant parameters.

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class, slugAttribute: 'domain')
        ->tenantDomain('{tenant:domain}');
}
```

--------------------------------

### Set another field state using $set utility in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Updates the value of a different field from within afterStateUpdated() using the $set parameter. By default, the afterStateUpdated() hook of the target field is not triggered unless shouldCallUpdatedHooks: true is passed.

```php
use Filament\Schemas\Components\Utilities\Set;

function (Set $set) {
    $set('title', 'Blog Post'); // Set the `title` field to `Blog Post`.
    //...
}
```

```php
use Filament\Schemas\Components\Utilities\Set;

function (Set $set) {
    $set('title', 'Blog Post', shouldCallUpdatedHooks: true);
    //...
}
```

--------------------------------

### Prevent field dehydration using dehydrated(false) in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

Excludes a field from being included in the state array returned by getState() and prevents it from being saved to the database in auto-saving schemas. The field is still validated even when dehydrated(false) is set.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password_confirmation')
    ->password()
    ->dehydrated(false)
```

--------------------------------

### Use Heading Component in Filament Project

Source: https://filamentphp.com/docs/4.x/plugins/building-a-standalone-plugin

Demonstrates instantiating and configuring the Heading component with a specific level, content text, and custom color. Shows the fluent interface pattern for chaining component configuration methods.

```php
use Awcodes\Headings\Heading;

Heading::make(2)
    ->content('Product Information')
    ->color(Color::Lime),
```

--------------------------------

### Add Heading and Description to Filament PHP Table

Source: https://filamentphp.com/docs/4.x/tables/overview

This example illustrates how to include both a heading and a descriptive text for a Filament table. The `$table->description()` method allows you to add supplementary information beneath the main table heading.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->heading('Clients')
        ->description('Manage your clients here.')
        ->columns([
            // ...
        ]);
}
```

--------------------------------

### Publish Filament Configuration File

Source: https://filamentphp.com/docs/4.x/introduction/installation

This Artisan command publishes Filament's default configuration file to `config/filament.php`, allowing developers to override shared settings across all Filament packages. This file enables customization of options like the default filesystem disk, file generation flags, and UI defaults. The command can be re-run to update the configuration with newly added keys.

```php
php artisan vendor:publish --tag=filament-config
```

--------------------------------

### Injecting Eloquent Record into Filament Schema Functions (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet demonstrates how to inject the current Eloquent model instance into a Filament schema function by type-hinting the `$record` parameter with `Illuminate\Database\Eloquent\Model`. This allows access to the model's data within the schema logic.

```php
use Illuminate\Database\Eloquent\Model;

function (?Model $record) {
    // ...
}

```

--------------------------------

### Dynamically Configure FilamentPHP Components with Utility Injection

Source: https://filamentphp.com/docs/4.x/schemas/overview

Illustrates how FilamentPHP allows dynamic configuration of components by accepting functions as parameters for properties like `Grid::make()` and `Section::heading()`. This enables conditional logic, such as adjusting layout columns or headings based on user roles or other application states.

```php
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

Grid::make(fn (): array => [
    'lg' => auth()->user()->isAdmin() ? 4 : 6
])->schema([
    // ...
])

Section::make()
    ->heading(fn (): string => auth()->user()->isAdmin() ? 'Admin Dashboard' : 'User Dashboard')
    ->schema([
    // ...
])
```

--------------------------------

### Add Helper Text to FilamentPHP Import Column

Source: https://filamentphp.com/docs/4.x/actions/import

This example shows how to add informative `helperText()` to a FilamentPHP import column. The text is displayed below the column mapping select, providing users with additional context or instructions before validation. This is particularly useful for explaining expected data formats, like comma-separated lists.

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('skus')
    ->multiple(',')
    ->helperText('A comma-separated list of SKUs.')
```

--------------------------------

### Navigate to Specific Wizard Step with Pest Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-schemas

Tests direct navigation to a specific wizard step using goToWizardStep() with a step number parameter. Uses assertWizardCurrentStep() to verify the correct step is reached without triggering validation from previous steps.

```php
use function Pest\Livewire\livewire;

it('moves to the wizards second step', function () {
    livewire(CreatePost::class)
        ->goToWizardStep(2)
        ->assertWizardCurrentStep(2);
});
```

--------------------------------

### Create Basic Revealable Password Input in FilamentPHP (PHP)

Source: https://filamentphp.com/docs/4.x/forms/text-input

This example shows how to configure a FilamentPHP `TextInput` as a revealable password field. By chaining `password()` and `revealable()` methods, the input allows users to toggle visibility of the entered password text. This enhances user experience by providing an option to confirm typed characters.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
    ->revealable()
```

--------------------------------

### Insert Plain Text into Field Slots

Source: https://filamentphp.com/docs/4.x/forms/overview

Add static text content to field slots using string values. This is the simplest way to add descriptive text below or above field labels and content areas. Text is inserted directly into the specified slot position.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->belowContent('This is the user\'s full name.')
```

--------------------------------

### Set Custom Label for Form Field - Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Shows how to override the default auto-generated label for a form field using the label() method. Supports both static string values and dynamic callbacks with injected utilities for conditional label rendering.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Full name')
```

--------------------------------

### Injecting Laravel Container Dependencies into Filament Schema Functions (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

Shows how to inject services from Laravel's service container, such as `Illuminate\Http\Request`, alongside Filament utilities like `Set`, into a schema function. This allows for seamless integration with Laravel's dependency injection system for powerful backend interactions.

```php
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Http\Request;

function (Request $request, Set $set) {
    // ...
}

```

--------------------------------

### Basic Stats Overview Widget Implementation - PHP

Source: https://filamentphp.com/docs/4.x/widgets/stats-overview

Implement a basic stats overview widget by extending BaseWidget and returning Stat instances from the getStats() method. Each stat displays a label and value that appear on the dashboard.

```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Unique views', '192.1k'),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
```

--------------------------------

### Add description to FilamentPHP Wizard step

Source: https://filamentphp.com/docs/4.x/schemas/wizards

This snippet demonstrates how to add a short description to a FilamentPHP Wizard step using the `description()` method. The description can be a static string or dynamically calculated using a closure, which allows injecting utilities like `Component`, `Get`, `Livewire`, `model`, `operation`, or `record` for context-aware descriptions.

```php
use Filament\Schemas\Components\Wizard\Step;

Step::make('Order')
    ->description('Review your basket')
    ->schema([
        // ...
    ]),
```

--------------------------------

### Implement FilamentPHP Action Lifecycle Hooks

Source: https://filamentphp.com/docs/4.x/actions/create

This example showcases various lifecycle hooks available for FilamentPHP actions, enabling developers to execute custom code at specific points within an action's workflow. Hooks such as `beforeFormFilled`, `afterFormFilled`, `beforeFormValidated`, `afterFormValidated`, `before`, and `after` allow for interventions before and after form population, validation, and data persistence.

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->beforeFormFilled(function () {
        // Runs before the form fields are populated with their default values.
    })
    ->afterFormFilled(function () {
        // Runs after the form fields are populated with their default values.
    })
    ->beforeFormValidated(function () {
        // Runs before the form fields are validated when the form is submitted.
    })
    ->afterFormValidated(function () {
        // Runs after the form fields are validated when the form is submitted.
    })
    ->before(function () {
        // Runs before the form fields are saved to the database.
    })
    ->after(function () {
        // Runs after the form fields are saved to the database.
    })
```

--------------------------------

### FilamentPHP: Resolve Relationship with Multiple Columns

Source: https://filamentphp.com/docs/4.x/actions/import

This example shows how to configure a FilamentPHP `ImportColumn` to attempt resolution of a related record using multiple columns. The system will search for a match using either 'email' or 'username' to find the author.

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('author')
    ->relationship(resolveUsing: ['email', 'username'])
```

--------------------------------

### Apply inline labels to layout components and sections

Source: https://filamentphp.com/docs/4.x/forms/overview

Use inlineLabel() on layout components like Section or the entire Schema to display all nested field labels inline. Individual fields can override this setting using inlineLabel(false) to opt-out of inline label display.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make('Details')
    ->inlineLabel()
    ->schema([
        TextInput::make('name'),
        TextInput::make('email')
            ->label('Email address'),
        TextInput::make('phone')
            ->label('Phone number'),
    ])
```

```php
use Filament\Schemas\Schema;

public function form(Schema $schema): Schema
{
    return $schema
        ->inlineLabel()
        ->components([
            // ...
        ]);
}
```

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make('Details')
    ->inlineLabel()
    ->schema([
        TextInput::make('name'),
        TextInput::make('email')
            ->label('Email address'),
        TextInput::make('phone')
            ->label('Phone number')
            ->inlineLabel(false),
    ])
```

--------------------------------

### Send POST request from Filament user menu item

Source: https://filamentphp.com/docs/4.x/navigation/user-menu

This example demonstrates how to configure a user menu item to send an HTTP POST request to a specified URL. It uses the `url()` method to define the target and `postToUrl()` to ensure the request is a POST.

```PHP
use Filament\Actions\Action;

Action::make('lockSession')
    ->url(fn (): string => route('lock-session'))
    ->postToUrl()
```

--------------------------------

### Configure Custom Entry with Methods

Source: https://filamentphp.com/docs/4.x/infolists/custom-entries

Instantiate and configure a custom entry using fluent method chaining to set configuration values passed from the entry class definition.

```php
use App\Filament\Infolists\Components\AudioPlayerEntry;

AudioPlayerEntry::make('recording')
    ->speed(0.5)
```

--------------------------------

### Overriding Global Filament Component Settings (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/overview

Explains how to override a globally configured default setting for a Filament component on an individual basis. This example demonstrates setting the number of columns for a `Section` component to 1, even if a global default of 2 columns was previously established.

```php
use Filament\Schemas\Components\Section;

Section::make()
    ->columns(1)
```

--------------------------------

### Set Translatable Label for Form Field - Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates using Laravel's translation helper function to set a translatable label for form fields. The label() method accepts the __() helper function to support multi-language form labels.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label(__('fields.name'))
```

--------------------------------

### Disable Filament Toggle on Specific Operation

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates how to disable a Filament `Toggle` field specifically when the form is performing a certain operation (e.g., 'edit') using the `disabledOn()` method. It also shows the equivalent dynamic `disabled()` function using an operation parameter.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->disabledOn('edit')

// is the same as

Toggle::make('is_admin')
    ->disabled(fn (string $operation): bool => $operation === 'edit')
```

--------------------------------

### Verify Filament Notifications Package Installation (Composer)

Source: https://filamentphp.com/docs/4.x/components/notifications

Before rendering notifications, verify that the `filament/notifications` package is installed in your project. This command uses Composer to list installed packages, confirming its presence.

```shell
composer show filament/notifications
```

--------------------------------

### Conditionally Dehydrate Filament Toggle with Boolean

Source: https://filamentphp.com/docs/4.x/forms/overview

Shows how to conditionally control whether a disabled Filament `Toggle` field's value should be saved by passing a boolean value to the `dehydrated()` method, enabling dynamic data persistence based on conditions like feature flags.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->disabled()
    ->dehydrated(FeatureFlag::active())
```

--------------------------------

### Format Currency in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

The `money()` method facilitates formatting monetary values with a specified currency. This example applies a `Sum` summarizer to a `price` `TextColumn` and formats the total as 'EUR'.

```php
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->summarize(Sum::make()->money('EUR'))
```

--------------------------------

### Preview Directory Structure Migration - Artisan Command

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Previews the proposed directory structure changes for Filament v4 without applying them. The --dry-run flag shows what files and directories would be reorganized for the new default structure supporting resources and clusters.

```bash
php artisan filament:upgrade-directory-structure-to-v4 --dry-run
```

--------------------------------

### Show Field Using JavaScript Expression

Source: https://filamentphp.com/docs/4.x/forms/overview

Show a form field based on another field's value using the `visibleJs()` method. This method works similarly to `hiddenJs()` but controls visibility instead of hiding. When both `hiddenJs()` and `visibleJs()` are used, the field is only shown if both indicate visibility.

```PHP
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])

Toggle::make('is_admin')
    ->visibleJs(<<<'JS'
        $get('role') === 'staff'
        JS)
```

--------------------------------

### Apply Global Middleware to Filament Panel Routes

Source: https://filamentphp.com/docs/4.x/panel-configuration

This code shows how to apply additional middleware to all routes within a Filament panel. By passing an array of middleware classes to the `middleware()` method, developers can enforce global policies or behaviors. By default, middleware runs only on initial page load, not Livewire AJAX requests.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->middleware([
            // ...
        ]);
}
```

--------------------------------

### Add Content After Filament Field using `afterContent()` (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

Employ the `afterContent()` method in Filament PHP to insert custom content immediately following a form field's main input. Similar to `beforeContent()`, it accepts static content (e.g., an icon) or a dynamic function that can utilize injected utilities like the current field, `$get` function for data retrieval, Livewire component, or the Eloquent model. For content directly adjoined to the field, `suffix()` is often a better UI choice.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->afterContent(Icon::make(Heroicon::Star))
```

--------------------------------

### Autofocus form fields on schema load

Source: https://filamentphp.com/docs/4.x/forms/overview

Enable autofocus on a specific form field using the autofocus() method. This method accepts an optional boolean value or callback function to dynamically determine if the field should receive focus when the schema loads. Best practice is to autofocus the first significant field.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->autofocus()
```

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->autofocus(FeatureFlag::active())
```

--------------------------------

### Add icon affixes to TextInput field

Source: https://filamentphp.com/docs/4.x/forms/text-input

Display Heroicon icons before or after a TextInput using prefixIcon() and suffixIcon() methods. Supports static icon references or callable functions with utility injection for dynamic icon selection based on field state.

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

TextInput::make('domain')
    ->url()
    ->suffixIcon(Heroicon::GlobeAlt)
```

--------------------------------

### Optimize Laravel Application - Laravel Artisan

Source: https://filamentphp.com/docs/4.x/deployment

Cache Laravel configuration files and routes for production optimization. Should be run in deployment scripts to improve application startup performance.

```bash
php artisan optimize
```

--------------------------------

### Use Dynamic Content with Utility Injection

Source: https://filamentphp.com/docs/4.x/forms

Insert dynamic content into slots using function parameters for utility injection. Available utilities include $component, $get, $livewire, $model, $operation, $rawState, $record, and $state for conditional or data-driven content.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->belowContent(function ($get, $state, $record, $operation) {
        // Use injected utilities to dynamically determine content
        if ($operation === 'create') {
            return 'Creating new record';
        }
        return 'Editing existing record: ' . $record?->name;
    });
```

--------------------------------

### Format Currency with Custom Locale in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example demonstrates customizing the locale for currency formatting using the `locale` argument with the `money()` method. A `Sum` summarizer on a `price` `TextColumn` formats the total as 'EUR' according to the 'nl' (Dutch) locale.

```php
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->summarize(Sum::make()->money('EUR', locale: 'nl'))
```

--------------------------------

### Inject Utilities into Filament Component Configuration Functions

Source: https://filamentphp.com/docs/4.x/schemas

Shows how to use callback functions with injected utilities to dynamically configure Grid column counts and Section headings based on user authentication status. This pattern applies to most customization methods accepting functions as parameters.

```PHP
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

Grid::make(fn (): array => [
    'lg' => auth()->user()->isAdmin() ? 4 : 6,
])->schema([
    // ...
])

Section::make()
    ->heading(fn (): string => auth()->user()->isAdmin() ? 'Admin Dashboard' : 'User Dashboard')
    ->schema([
        // ...
    ])
```

--------------------------------

### Halt Form Saving Process with Conditional Check in Filament

Source: https://filamentphp.com/docs/4.x/resources/editing-records

Use the halt() method inside a lifecycle hook to stop the entire saving process conditionally. In this example, the save is halted if the user's team lacks an active subscription, and a notification with a subscribe action is displayed to guide the user.

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

protected function beforeSave(): void
{
    if (! $this->getRecord()->team->subscribed()) {
        Notification::make()
            ->warning()
            ->title('You don\'t have an active subscription!')
            ->body('Choose a plan to continue.')
            ->persistent()
            ->actions([
                Action::make('subscribe')
                    ->button()
                    ->url(route('subscribe'), shouldOpenInNewTab: true),
            ])
            ->send();

        $this->halt();
    }
}
```

--------------------------------

### Disable Filament Toggle on Multiple Operations

Source: https://filamentphp.com/docs/4.x/forms/overview

Illustrates how to disable a Filament `Toggle` field when the form is performing any of several specified operations (e.g., 'edit' or 'view') by passing an array of operation names to `disabledOn()`. It also provides the equivalent dynamic `disabled()` function with an `in_array` check.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->disabledOn(['edit', 'view'])
    
// is the same as

Toggle::make('is_admin')
    ->disabled(fn (string $operation): bool => in_array($operation, ['edit', 'view']))
```

--------------------------------

### Show Field Based on Operation Using visibleOn

Source: https://filamentphp.com/docs/4.x/forms/overview

Show a form field based on the current operation using the `visibleOn()` method. Accepts a single operation string or an array of operations. This method overwrites previous calls to `visible()` and may improve code readability compared to negating operation conditions.

```PHP
use Filament\Forms\Components\Toggle;

// Show on a single operation
Toggle::make('is_admin')
    ->visibleOn('create')

// Show on multiple operations
Toggle::make('is_admin')
    ->visibleOn(['create', 'edit'])
```

--------------------------------

### Execute Code on Panel Lifecycle with bootUsing Hook

Source: https://filamentphp.com/docs/4.x/panel-configuration

Register a lifecycle hook using bootUsing() to execute code on every request within a panel. The hook runs from middleware after all service providers have been booted. In multi-panel setups, only the current panel's bootUsing() hook executes per request.

```PHP
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->bootUsing(function (Panel $panel) {
            // ...
        });
}
```

--------------------------------

### Debouncing Reactive Filament Form Fields (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet demonstrates debouncing for reactive fields using `live(debounce: 500)`. This prevents the schema from re-rendering immediately after every input, instead waiting for a specified period (e.g., 500ms) after the user stops typing to send a network request, balancing reactivity and performance.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('username')
    ->live(debounce: 500) // Wait 500ms before re-rendering the schema.

```

--------------------------------

### Inject and Retrieve FilamentPHP Component State ($get)

Source: https://filamentphp.com/docs/4.x/schemas/overview

Explains the `$get` utility injection, which allows callback functions within Filament schemas to retrieve the current state (value) of other form fields or infolist entries. This is essential for building interdependent and reactive form logic.

```php
use Filament\Schemas\Components\Utilities\Get;

function (Get $get) {
    $email = $get('email'); // Store the value of the `email` entry in the `$email` variable.
    //...
}
```

--------------------------------

### Bind Form Field to Array Keys Using Dot Notation - Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates how to use dot notation to bind form fields to nested array keys or object properties. This allows fields to interact with complex data structures beyond simple model attributes.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('socials.github_url')
```

--------------------------------

### Basic Filament Forms Builder Block Definition (PHP)

Source: https://filamentphp.com/docs/4.x/forms/builder

This PHP example illustrates the fundamental structure for defining blocks within a Filament `Builder` component. It shows that `Block::make()` requires a unique name and an array of form components within its `schema()` method. This snippet simplifies the previous example to highlight the core block definition process.

```php
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;

Builder::make('content')
    ->blocks([
        Block::make('heading')
            ->schema([
                TextInput::make('content')->required(),
                // ...
            ]),
        // ...
    ])
```

--------------------------------

### Add Extra HTML Attributes to Field Wrapper

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates how to pass extra HTML attributes to the outer field wrapper element using the extraFieldWrapperAttributes() method. This allows CSS styling of the wrapper and its child elements. The method accepts either static values or functions for dynamic calculation.

```PHP
use Filament\Forms\Components\TextInput;

TextInput::make('categories')
    ->extraFieldWrapperAttributes(['class' => 'components-locked'])
```

--------------------------------

### Filament Prevent Livewire Component Re-render (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet illustrates using `skipRenderAfterStateUpdated()` to prevent the entire Livewire component from re-rendering after a `live()` field's state is updated. It allows custom server-side logic to be executed via `afterStateUpdated()` without triggering a costly frontend re-render.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->live()
    ->skipRenderAfterStateUpdated()
    ->afterStateUpdated(function (string $state) {
        // Do something with the state, but don't re-render the Livewire component.
    })
```

--------------------------------

### Set Filament TextInput Autocomplete Behavior

Source: https://filamentphp.com/docs/4.x/forms/text-input

These snippets illustrate how to control the browser's autocomplete feature for Filament TextInput components. The `autocomplete()` method can specify a standard value like 'new-password' to guide browser suggestions or use `false` as a shortcut to disable autocomplete entirely ('off'), which is useful for sensitive fields.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
    ->autocomplete('new-password')
```

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
    ->autocomplete(false)
```

--------------------------------

### Display inline labels on individual TextInput fields

Source: https://filamentphp.com/docs/4.x/forms/overview

Enable inline label display for a single form field using the inlineLabel() method. This method accepts an optional boolean value or callback function to dynamically determine if the label should be displayed inline. Useful for forms with space constraints.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->inlineLabel()
```

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->inlineLabel(FeatureFlag::active())
```

--------------------------------

### Extend Filament Resource Class Generator (PHP)

Source: https://filamentphp.com/docs/4.x/advanced/file-generation

Demonstrates how to create a custom class that extends an existing Filament class generator, such as `ResourceClassGenerator`, to override its default behavior. This is the first step in customizing the file generation process for resources.

```php
namespace App\Filament\Commands\FileGenerators\Resources;

use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;

class ResourceClassGenerator extends BaseResourceClassGenerator
{
   // ...
}
```

--------------------------------

### Integrate Filament Styles and Scripts into Blade Layout

Source: https://filamentphp.com/docs/4.x/introduction/installation

This Blade template code defines the essential HTML structure for a Filament application, including meta tags and viewport settings. It crucially integrates `@filamentStyles` in the `<head>` and `@filamentScripts` at the end of the `<body>`, alongside Vite-compiled CSS and JavaScript, to enable Filament's UI and functionality within the application.

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        {{ $slot }}

        @livewire('notifications') {{-- Only required if you wish to send flash notifications --}}

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
```

--------------------------------

### Filament Partial Rendering for Specific Fields (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

To optimize performance, this snippet shows how to use `partiallyRenderComponentsAfterStateUpdated()` on a `live()` field. It accepts an array of field names, ensuring that only the specified fields (e.g., 'email') are re-rendered after the reactive field's state ('name') is updated, avoiding a full component re-render.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->live()
    ->partiallyRenderComponentsAfterStateUpdated(['email'])
```

--------------------------------

### Custom Implement New Option Creation Logic for Filament Select (PHP)

Source: https://filamentphp.com/docs/4.x/forms/select

This PHP example shows how to customize the record creation process initiated by a Filament `Select` field's 'create new option' modal. The `createOptionUsing()` method allows developers to define custom logic, such as associating the new record with a specific user or team, using data from the modal's form. It expects the primary key of the newly created record to be returned after the custom logic executes.

```php
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->createOptionForm([
       // ...
    ])
    ->createOptionUsing(function (array $data): int {
        return auth()->user()->team->members()->create($data)->getKey();
    }),
```

--------------------------------

### Implement Lifecycle Hooks for Filament Toggle Column Updates

Source: https://filamentphp.com/docs/4.x/tables/columns/toggle

This example illustrates how to attach `beforeStateUpdated` and `afterStateUpdated` lifecycle hooks to a Filament `ToggleColumn`. These hooks enable the execution of custom code or logic before the toggle's state is saved to the database and immediately after, respectively, providing control over the update process.

```php
ToggleColumn::make()
    ->beforeStateUpdated(function ($record, $state) {
        // Runs before the state is saved to the database.
    })
    ->afterStateUpdated(function ($record, $state) {
        // Runs after the state is saved to the database.
    })
```

--------------------------------

### Add Validation Rules to Form Fields - Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Demonstrates adding frontend and backend validation rules to form fields using method chaining. Methods like required() and maxLength() provide IDE autocomplete support and are more maintainable than Laravel's traditional validation array syntax.

```php
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

TextInput::make('name')
    ->required()
    ->maxLength(255)
```

--------------------------------

### Create TextInput (PHP - Filament Forms)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Creates a basic TextInput field instance using Filament Forms. Depends on Filament\Forms\Components\TextInput being available via Composer. Input: field name string; Output: a TextInput component instance that can be further chained. No runtime validation or context shown here.

```php
use Filament\Forms\Components\TextInput;\n\nTextInput::make('name')\n
```

--------------------------------

### Implement ModalTableSelect for single relationship in Filament (PHP)

Source: https://filamentphp.com/docs/4.x/forms/select

This example demonstrates implementing `ModalTableSelect` for a single relationship in Filament forms. It links the component to a `category_id` field, specifying the related model's name field for display and referencing the `CategoriesTable::class` for its table configuration. This setup allows users to select a single record from the modal.

```php
use Filament\Forms\Components\ModalTableSelect;

ModalTableSelect::make('category_id')
    ->relationship('category', 'name')
    ->tableConfiguration(CategoriesTable::class)
```

--------------------------------

### Compile Alpine Component with esbuild

Source: https://filamentphp.com/docs/4.x/advanced/assets

Run the build script to compile the Alpine component. Use the basic command for a one-time build, or add the --dev flag to enable watch mode for continuous compilation during development.

```bash
node bin/build.js
```

```bash
node bin/build.js --dev
```

--------------------------------

### Configure Filament Filesystem Disk in PHP

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This PHP configuration snippet shows how to set the 'default_filesystem_disk' for Filament v4. It demonstrates using the 'FILAMENT_FILESYSTEM_DISK' environment variable to maintain v3 behavior, ensuring consistency with previous versions by explicitly defining the disk.

```php
return [

    // ...

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    // ...

]
```

--------------------------------

### Configure Global Phone Number Regex for All Filament TextInputs

Source: https://filamentphp.com/docs/4.x/forms/text-input

This example illustrates how to set a custom phone number validation regex globally for all `TextInput` components across your Filament application. This is achieved by using `TextInput::configureUsing()` within a service provider, ensuring consistent validation rules. The `telRegex()` method also supports dynamic functions.

```php
use Filament\Forms\Components\TextInput;

TextInput::configureUsing(function (TextInput $component): void {
    $component->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/');
});
```

--------------------------------

### Conditionally enable input types with FeatureFlag (PHP - Filament Forms)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Demonstrates passing a boolean (or callable that returns a boolean) to helper methods to conditionally apply a type. Depends on a FeatureFlag utility or any boolean-returning callable. Input: boolean or callable; Output: TextInput that may or may not apply the helper type depending on the condition. Limitations: the example references FeatureFlag::active() and assumes that utility exists in the project.

```php
use Filament\Forms\Components\TextInput;\n\nTextInput::make('text')\n    ->email(FeatureFlag::active()) // or\n    ->numeric(FeatureFlag::active()) // or\n    ->integer(FeatureFlag::active()) // or\n    ->password(FeatureFlag::active()) // or\n    ->tel(FeatureFlag::active()) // or\n    ->url(FeatureFlag::active())\n
```

--------------------------------

### Conditionally Show FilamentPHP Form Field

Source: https://filamentphp.com/docs/4.x/forms

This example shows an alternative to conditionally hiding a field by using the `visible()` method. Similar to `hidden()`, it takes a closure with the `Get` utility to determine visibility based on another field's value, in this case, the `is_company` checkbox. The `live()` method on the checkbox triggers the re-rendering needed for the visibility update.

```php
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;

Checkbox::make('is_company')
    ->live();
    
TextInput::make('company_name')
    ->visible(fn (Get $get): bool => $get('is_company'));
```

--------------------------------

### Apply middleware to tenant-aware routes

Source: https://filamentphp.com/docs/4.x/users/tenancy

Configure middleware to run on all tenant-aware routes using the tenantMiddleware() method in the panel configuration. By default, middleware runs on page load but not on subsequent Livewire AJAX requests. Use isPersistent: true parameter to run middleware on every request.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMiddleware([
            // ...
        ]);
}
```

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMiddleware([
            // ...
        ], isPersistent: true);
}
```

--------------------------------

### Dynamically Load Select Filter Options

Source: https://filamentphp.com/docs/4.x/tables/filters/overview

Use filter utility injection to dynamically load options for select filters using a closure. This example injects utilities to fetch Author data at runtime, enabling dependent filters and dynamic option loading.

```php
use App\Models\Author;
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('author')
    ->options(fn (): array => Author::query()->pluck('name', 'id')->all())
```

--------------------------------

### Pass arguments to a Filament action in a Blade loop

Source: https://filamentphp.com/docs/4.x/components/action

This Blade example demonstrates how to render a Filament action dynamically within a loop, passing different arguments for each iteration. It invokes the action as an object, providing an array of arguments, such as a specific post ID.

```blade
<div>
    @foreach ($posts as $post)
        <h2>{{ $post->title }}</h2>

        {{ ($this->deleteAction)(['post' => $post->id]) }}
    @endforeach

    <x-filament-actions::modals />
</div>
```

--------------------------------

### Enable multi-step wizard for Filament PHP record creation

Source: https://filamentphp.com/docs/4.x/resources/creating-records

This code snippet demonstrates how to transform a Filament PHP Create page into a multi-step wizard by applying the `CreateRecord\Concerns\HasWizard` trait. It sets up the page class to utilize wizard functionality, requiring the definition of `getSteps()` to specify the wizard's sequence of steps and form components.

```php
use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    
    protected static string $resource = CategoryResource::class;

    protected function getSteps(): array
    {
        // ...
    }
}
```

--------------------------------

### Format Currency with Custom Decimal Places in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example illustrates how to customize the number of decimal places for currency formatting using the `decimalPlaces` argument. A `Sum` summarizer on a `price` `TextColumn` formats the total as 'EUR' with three decimal places.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->summarize(Sum::make()->money('EUR', decimalPlaces: 3))
```

--------------------------------

### Align FilamentPHP TextEntry content using Alignment enum

Source: https://filamentphp.com/docs/4.x/infolists/overview

This example illustrates how to align a FilamentPHP `TextEntry` component's content by passing an `Alignment` enum value to the `alignment()` method. This approach offers a more explicit way to set the alignment, including dynamic calculation capabilities not shown in this specific code example.

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\Alignment;

TextEntry::make('title')
    ->alignment(Alignment::Center);
```

--------------------------------

### Apply Inline Labels to All Entries in a Filament Infolist Schema

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates how to set all entry labels to display inline across an entire Filament `Schema` by calling the `inlineLabel()` method directly on the schema instance.

```php
use FilamentSchemasSchema;

public function infolist(Schema $schema): Schema
{
    return $schema
        ->inlineLabel()
        ->components([
            // ...
        ]);
}
```

--------------------------------

### Create TextInput Form Field - Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Creates a basic text input form field using the TextInput component from Filament's Forms package. The field is uniquely identified by the 'name' parameter, which typically corresponds to an Eloquent model attribute. This is the foundation for building form fields in Filament.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
```

--------------------------------

### Calculate Sum with Filament Table Column Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

The `Sum` summarizer calculates the total of all values in a dataset. This example shows how to apply a `Sum` summarizer to a `price` `TextColumn` to aggregate all prices in the table.

```php
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->summarize(Sum::make())
```

--------------------------------

### Add Filament Force-Delete Bulk Action to a Table (PHP)

Source: https://filamentphp.com/docs/4.x/actions/force-delete

This example illustrates how to integrate the `ForceDeleteBulkAction` into a Filament table. This allows users to select multiple records and perform a bulk force-delete operation via the table's toolbar.

```php
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->toolbarActions([
            ForceDeleteBulkAction::make(),
        ]);
}
```

--------------------------------

### Create Filament Widget using Artisan Command

Source: https://filamentphp.com/docs/4.x/resources/widgets

This command generates a new Filament widget class and its corresponding view file, specifically tailored for a given Filament resource. It places the files in the appropriate `app/Filament/Resources/{Resource}/Widgets` and `resources/views/filament/resources/{resource}/widgets` directories.

```bash
php artisan make:filament-widget CustomerOverview --resource=CustomerResource
```

--------------------------------

### Scope Dataset for Filament Table Average Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example demonstrates how to apply a database query scope to an `Average` summarizer's dataset. The `query()` method is used to filter records, ensuring only rows where `is_published` is true are included in the average calculation for the `rating` `TextColumn`.

```php
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Query\Builder;

TextColumn::make('rating')
    ->summarize(
        Average::make()->query(fn (Builder $query) => $query->where('is_published', true)),
    ),
```

--------------------------------

### Filament Table Layout: Stack Columns on Mobile with Split

Source: https://filamentphp.com/docs/4.x/tables/layout

This example introduces the `Split` component in Filament, which allows wrapping multiple table columns. By default, columns within `Split` appear side-by-side, but they will automatically stack on mobile devices for better responsiveness.

```php
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

Split::make([
    ImageColumn::make('avatar')
        ->circular(),
    TextColumn::make('name')
        ->weight(FontWeight::Bold)
        ->searchable()
        ->sortable(),
    TextColumn::make('email'),
])
```

--------------------------------

### Enable OPcache in php.ini Configuration

Source: https://filamentphp.com/docs/4.x/deployment

Configuration directive to enable OPcache in php.ini file. OPcache stores compiled PHP code in memory to greatly improve production performance.

```ini
opcache.enable=1 # Enable OPcache
```

--------------------------------

### Register Error Notifications in Page Setup Method

Source: https://filamentphp.com/docs/4.x/panel-configuration

Registers custom error notification text on a specific page by calling registerErrorNotification() inside the setUpErrorNotifications() method of the page class.

```PHP
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function setUpErrorNotifications(): void
    {
        $this->registerErrorNotification(
            title: 'An error occurred',
            body: 'Please try again later.',
        );
    }

    // ...
}
```

--------------------------------

### Import WidgetsRenderHook for Widget Customization

Source: https://filamentphp.com/docs/4.x/advanced/render-hooks

Imports the WidgetsRenderHook class to access widget render hook constants. Allows customization of table widget start and end positions.

```PHP
use Filament\Widgets\View\WidgetsRenderHook;
```

--------------------------------

### Specify related model for Filament MorphTo relationship

Source: https://filamentphp.com/docs/4.x/forms/overview

This PHP snippet demonstrates how to use the `relatedModel` parameter with Filament's `relationship()` method on a `Group` component. This allows Filament to create new records for `MorphTo` relationships, such as an `Organization` for a `customer` relationship, instead of just updating existing ones. It depends on `App\Models\Organization`, `Filament\Forms\Components\TextInput`, and `Filament\Schemas\Components\Group`.

```php
use App\Models\Organization;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;

Group::make()
    ->relationship('customer', relatedModel: Organization::class)
    ->schema([
        // ...
    ])
```

--------------------------------

### Hide Field Based on Operation Using hiddenOn

Source: https://filamentphp.com/docs/4.x/forms/overview

Hide a form field based on the current operation (create, edit, or view) using the `hiddenOn()` method. Accepts a single operation string or an array of operations. This method overwrites previous calls to `hidden()` and provides a more readable alternative to operation-based conditional logic.

```PHP
use Filament\Forms\Components\Toggle;

// Hide on a single operation
Toggle::make('is_admin')
    ->hiddenOn('edit')

// Equivalent to
Toggle::make('is_admin')
    ->hidden(fn (string $operation): bool => $operation === 'edit')

// Hide on multiple operations
Toggle::make('is_admin')
    ->hiddenOn(['edit', 'view'])

// Equivalent to
Toggle::make('is_admin')
    ->hidden(fn (string $operation): bool => in_array($operation, ['edit', 'view']))
```

--------------------------------

### Access Plugin Configuration Using Static Method

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Call plugin configuration methods using the static get() method to access plugin preferences with improved type safety and IDE autocompletion support.

```php
BlogPlugin::get()->hasAuthorResource()
```

--------------------------------

### Inject Multiple Utilities with Combined Parameters

Source: https://filamentphp.com/docs/4.x/actions/overview

Combine multiple injected parameters in any order using dynamic reflection-based dependency injection. Parameters can include utilities like array arguments and Livewire components, which are resolved automatically.

```PHP
use Livewire\Component;

function (array $arguments, Component $livewire) {
    // ...
}
```

--------------------------------

### Dynamically Calculate HTML Rendering with Utility Injection - Filament

Source: https://filamentphp.com/docs/4.x/forms/select

Use allowHtml() method with a closure function to dynamically determine if HTML should be rendered based on injected utilities like Get, Livewire component, model, operation, record, and state. Enables context-aware HTML rendering decisions.

```php
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

Select::make('technology')
    ->options([
        'tailwind' => '<span class="text-blue-500">Tailwind</span>',
        'alpine' => '<span class="text-green-500">Alpine</span>',
        'laravel' => '<span class="text-red-500">Laravel</span>',
        'livewire' => '<span class="text-pink-500">Livewire</span>',
    ])
    ->allowHtml(
        function (
            Field $component,
            Get $get,
            Component $livewire,
            ?string $model,
            string $operation,
            mixed $rawState,
            ?Model $record,
            mixed $state,
        ): bool {
            return true;
        }
    )
```

--------------------------------

### Set up Laravel migrations for Filament exports

Source: https://filamentphp.com/docs/4.x/actions/export

Publishes and runs required Laravel migrations for queue batches, notifications, and Filament export tables. Required before using export functionality. Supports PostgreSQL JSON columns and UUID morphs.

```bash
php artisan make:queue-batches-table
php artisan make:notifications-table
php artisan vendor:publish --tag=filament-actions-migrations
php artisan migrate
```

--------------------------------

### Add Extra Content Before Field Label - Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Insert content before a field's label using the beforeLabel() method. Accepts static content like icons, text, actions, or components. Supports dynamic calculation via callback functions with utility injection for accessing form state, current field, Livewire component, and operation context.

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->beforeLabel(Icon::make(Heroicon::Star))
```

--------------------------------

### Create Compact Layout with dense() Method

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Applies the dense() method to a Grid component to reduce spacing between components by 50%, creating a more compact visual layout. Useful for optimizing space in constrained layout scenarios.

```php
use Filament\Schemas\Components\Grid;

Grid::make()
    ->dense()
    ->schema([
        // ...
    ])
```

--------------------------------

### Customize Filament Authentication Profile Page (PHP)

Source: https://filamentphp.com/docs/4.x/users

This PHP example demonstrates how to replace the default Filament profile page with a custom page. By passing a custom page class, such as `EditProfile::class`, to the `profile()` method in the panel configuration, developers can override the default behavior and implement bespoke profile management logic.

```php
use AppFilamentPagesAuthEditProfile;
use FilamentPanel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->profile(EditProfile::class);
}

```

--------------------------------

### Group select options under labels with Filament

Source: https://filamentphp.com/docs/4.x/forms/select

The `options()` method accepts grouped arrays where keys serve as group labels and values are arrays of options within each group. This example organizes status options into 'In Process' and 'Reviewed' groups for better organization and user experience.

```PHP
use Filament\Forms\Components\Select;

Select::make('status')
    ->searchable()
    ->options([
        'In Process' => [
            'draft' => 'Draft',
            'reviewing' => 'Reviewing',
        ],
        'Reviewed' => [
            'published' => 'Published',
            'rejected' => 'Rejected',
        ],
    ])
```

--------------------------------

### Create a Basic Filament Code Editor Component

Source: https://filamentphp.com/docs/4.x/forms/code-editor

This example demonstrates the fundamental usage of the Filament CodeEditor component. It creates a textarea with line numbers for code input, without any syntax highlighting by default.

```PHP
use Filament\Forms\Components\CodeEditor;

CodeEditor::make('code')
```

--------------------------------

### Test Filament Create Page Load with Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-resources

Tests that a Filament create page loads successfully as a Livewire component. Uses assertOk() to verify an HTTP 200 response. This is the basic test to ensure the page renders without errors.

```PHP
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\User;

it('can load the page', function () {
    livewire(CreateUser::class)
        ->assertOk();
});
```

--------------------------------

### Upgrade Filament Assets - Laravel Artisan

Source: https://filamentphp.com/docs/4.x/deployment

Ensure Filament assets are up to date. Automatically added to composer.json post-autoload-dump script during installation. Should remain in deployment process to prevent missing or outdated assets.

```bash
php artisan filament:upgrade
```

--------------------------------

### Define Custom Summary Logic in Filament Tables

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example demonstrates how to create a custom summary for a `TextColumn` in Filament. It utilizes the `Summarizer::make()->using()` method with a callback function that receives the database query builder to perform custom calculations, such as finding the minimum 'last_name' from the queried data.

```php
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Query\Builder;

TextColumn::make('name')
    ->summarize(Summarizer::make()
        ->label('First last name')
        ->using(fn (Builder $query): string => $query->min('last_name')))
```

--------------------------------

### Inject Get Function for Cross-Field Access - Filament

Source: https://filamentphp.com/docs/4.x/infolists/overview

Use the Get utility with a $get parameter to retrieve values from other entries or form fields within a callback. This enables cross-field dependencies and conditional logic based on multiple field values.

```php
use Filament\Schemas\Components\Utilities\Get;

function (Get $get) {
    $email = $get('email'); // Retrieve the value of the 'email' entry
    // ...
}
```

--------------------------------

### Configure Query Builder with Multiple Constraints in Filament

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

This PHP example demonstrates how to instantiate a `QueryBuilder` filter and register a diverse set of constraints, including `TextConstraint`, `BooleanConstraint`, `NumberConstraint`, `SelectConstraint`, `DateConstraint`, and `RelationshipConstraint`. It shows configuration for multiple selection in `SelectConstraint` and a searchable, selectable related operator for `RelationshipConstraint`.

```php
use Filament\Tables\Filters\QueryBuilder;
use Filament\QueryBuilder\Constraints\BooleanConstraint;
use Filament\QueryBuilder\Constraints\DateConstraint;
use Filament\QueryBuilder\Constraints\NumberConstraint;
use Filament\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\QueryBuilder\Constraints\SelectConstraint;
use Filament\QueryBuilder\Constraints\TextConstraint;

QueryBuilder::make()
    ->constraints([
        TextConstraint::make('name'),
        BooleanConstraint::make('is_visible'),
        NumberConstraint::make('stock'),
        SelectConstraint::make('status')
            ->options([
                'draft' => 'Draft',
                'reviewing' => 'Reviewing',
                'published' => 'Published',
            ])
            ->multiple(),
        DateConstraint::make('created_at'),
        RelationshipConstraint::make('categories')
            ->multiple()
            ->selectable(
                IsRelatedToOperator::make()
                    ->titleAttribute('name')
                    ->searchable()
                    ->multiple(),
            ),
        NumberConstraint::make('reviews.rating')
            ->integer(),
    ])
```

--------------------------------

### Insert Content Above Field Content with aboveContent() in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

The aboveContent() method adds extra content positioned above a field's content in Filament Forms. It accepts static content such as text, icons, components, actions, or action groups. This method also supports dynamic callbacks with dependency injection for accessing form state and field metadata.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->aboveContent([
        Icon::make(Heroicon::Star),
        'This is the content above the field\'s content'
    ])
```

--------------------------------

### Display Icon Component in Filament PHP Schema

Source: https://filamentphp.com/docs/4.x/schemas/primes

This example demonstrates how to add an `Icon` component to a Filament PHP schema. It uses `Icon::make()` along with `Filament\Support\Icons\Heroicon` to display a star icon.

```php
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

Icon::make(Heroicon::Star)
```

--------------------------------

### Doesn't start with validation in Filament

Source: https://filamentphp.com/docs/4.x/forms/validation

Validates that a field does not start with any of the specified values. Accepts an array of values to exclude from the start of the field.

```php
Field::make('name')->doesntStartWith(['admin'])
```

--------------------------------

### Configure Storage Disk for Filament Image Entry (PHP)

Source: https://filamentphp.com/docs/4.x/infolists/image-entry

Illustrates how to specify a custom storage disk for an Image Entry using the `disk()` method. This allows images to be loaded from different filesystems, such as 's3', overriding the default 'local' disk. The `disk()` method also supports a function for dynamic calculation based on various injected utilities.

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')
    ->disk('s3')
```

--------------------------------

### Set Custom Range for Filament Forms Slider Component

Source: https://filamentphp.com/docs/4.x/forms/slider

This example shows how to configure the minimum and maximum selectable values for a Filament Forms Slider component using the `range()` method. By default, the range is 0 to 100, but this can be adjusted with static values or dynamically calculated using functions, which can inject various utilities like form data (`$get`) or the Livewire component.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 40, maxValue: 80)
```

--------------------------------

### Insert Content Below Field Label with belowLabel() in Filament

Source: https://filamentphp.com/docs/4.x/forms/overview

The belowLabel() method adds extra content positioned below a field's label in Filament Forms. It accepts static content like text, icons, components, actions, or action groups. This method also supports dynamic callbacks that inject utilities for retrieving form data and field context.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->belowLabel([
        Icon::make(Heroicon::Star),
        'This is the content below the field\'s label'
    ])
```

--------------------------------

### Filament PHP: Client-side Field State Updates with JavaScript

Source: https://filamentphp.com/docs/4.x/forms

This example showcases how to perform client-side state updates without a network request using `afterStateUpdatedJs()`. It takes a JavaScript expression that runs in the browser, allowing direct manipulation of other field states (e.g., setting 'email' based on 'name') using `$state`, `$get()`, and `$set()` utilities. It contrasts this with the server-side `afterStateUpdated()` method that would trigger a network request.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

// Old name input that is `live()`, so it makes a network request and render each time it is updated.
TextInput::make('name')
    ->live()
    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('email', ((string) str($state)->replace(' ', '.')->lower()) . '@example.com'));

// New name input that uses `afterStateUpdatedJs()` to set the state of the email field and doesn't make a network request.
TextInput::make('name')
    ->afterStateUpdatedJs(<<<'JS'
        $set('email', ($state ?? '').replaceAll(' ', '.').toLowerCase() + '@example.com')
        JS);
    
TextInput::make('email')
    ->label('Email address');
```

--------------------------------

### Add operators to start of list with unshiftOperators() - PHP

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

Prepend custom operators to the beginning of the existing operators list using unshiftOperators() method. This adds new operators at the start while preserving existing ones.

```PHP
use Filament\QueryBuilder\Constraints\Operators\IsFilledOperator;
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('author.name')
    ->unshiftOperators([
        IsFilledOperator::class,
    ])
```

--------------------------------

### FilamentPHP: Hide Form Field Label for Accessibility (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet demonstrates how to visually hide a form field's label while ensuring it remains accessible to screen readers using FilamentPHP's `hiddenLabel()` method. It also illustrates how to conditionally hide the label based on a dynamic boolean value, supporting scenarios like feature flags.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->hiddenLabel()
```

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->hiddenLabel(FeatureFlag::active())
```

--------------------------------

### Set Custom Label for Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example shows how to set a custom label for a column summarizer using the `label()` method. It applies a `Sum` summarizer to a `price` `TextColumn` and sets its label to 'Total'.

```php
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->summarize(Sum::make()->label('Total'))
```

--------------------------------

### Dependent Select Options with Eloquent Models in Filament PHP

Source: https://filamentphp.com/docs/4.x/forms/overview

Implement dependent select fields that query Eloquent models to populate options dynamically. The parent category select loads options from the Category model, while the sub_category select filters SubCategory records based on the selected category using a WHERE clause. This pattern enables scalable dependent selects with database-driven data.

```PHP
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;

Select::make('category')
    ->options(Category::query()->pluck('name', 'id'))
    ->live()
    
Select::make('sub_category')
    ->options(fn (Get $get): Collection => SubCategory::query()
        ->where('category', $get('category'))
        ->pluck('name', 'id'))
```

--------------------------------

### Customize Filament TextEntry date formats with specific Carbon macro-formats

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Demonstrates applying custom Carbon macro-format strings to `isoDate()`, `isoDateTime()`, and `isoTime()` methods. This allows for tailored date and time output using Carbon's extensive formatting options.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->isoDate('L')

TextEntry::make('created_at')
    ->isoDateTime('LLL')

TextEntry::make('created_at')
    ->isoTime('LT')
```

--------------------------------

### Filament Table: Format Date Range with Minimal Difference

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example utilizes the `Range` summarizer with `minimalDateTimeDifference()` to present date ranges concisely. It formats the 'created_at' column's minimum and maximum dates, showing only the differing parts (e.g., only dates if days vary, or date and time if only times vary on the same day).

```php
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->dateTime()
    ->summarize(Range::make()->minimalDateTimeDifference())
```

--------------------------------

### Configure custom Filament field using fluent methods (PHP)

Source: https://filamentphp.com/docs/4.x/forms/custom-fields

This PHP example demonstrates how to instantiate and configure a custom Filament form field, `LocationPicker`, using its fluent method `zoom()`. This allows passing configuration values directly during the field's creation chain.

```php
use App\Filament\Forms\Components\LocationPicker;

LocationPicker::make('location')
    ->zoom(0.5)
```

--------------------------------

### Dynamic Entry Slot Content with Utility Injection

Source: https://filamentphp.com/docs/4.x/infolists/overview

Uses callback functions in slot methods to dynamically generate content based on injected utilities like $get, $record, $state, and $operation. Allows conditional content rendering based on form data and context.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->belowContent(function ($get, $record, $state, $operation) {
        // Dynamic content generation based on utilities
        // $get: retrieve values from current schema data
        // $record: the Eloquent model instance
        // $state: current entry value
        // $operation: 'create', 'edit', or 'view'
    })
```

--------------------------------

### Create Filament Theme with Artisan Command

Source: https://filamentphp.com/docs/4.x/styling

Generates a custom Filament theme using the make:filament-theme Artisan command. Creates necessary files, installs Tailwind CSS dependencies, generates a theme CSS file, and automatically configures Vite and panel provider. Supports multiple package managers via the --pm option.

```bash
php artisan make:filament-theme
```

```bash
php artisan make:filament-theme admin
```

```bash
php artisan make:filament-theme --pm=bun
```

--------------------------------

### Create Filament View Page using Artisan Command

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

This command-line interface (CLI) instruction generates a new Filament View page. It requires specifying the page name, the resource it belongs to, and the page type. This is the first step in adding new custom view pages to a Filament resource.

```bash
php artisan make:filament-page ViewCustomerContact --resource=CustomerResource --type=ViewRecord
```

--------------------------------

### Apply Directory Structure Migration - Artisan Command

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Executes the directory structure migration to reorganize Filament resources and clusters according to v4 conventions. Run after reviewing the dry-run output to confirm the changes are acceptable for your application.

```bash
php artisan filament:upgrade-directory-structure-to-v4
```

--------------------------------

### Define Import Job Backoff Strategy (FilamentPHP)

Source: https://filamentphp.com/docs/4.x/actions/import

This example illustrates how to customize the backoff strategy for import job retries. By implementing the `getJobBackoff()` method, you can return an array of integers representing the wait times in seconds (e.g., 60, 120, 300, 600) before subsequent retries.

```php
/**
* @return int | array<int> | null
 */
public function getJobBackoff(): int | array | null
{
    return [60, 120, 300, 600];
}
```

--------------------------------

### Create New Filament Panel using Artisan

Source: https://filamentphp.com/docs/4.x/panel-configuration

This Artisan command generates a new Filament panel with a unique name, creating a dedicated configuration file (e.g., `AppPanelProvider.php`). It's used for setting up distinct administrative or user interfaces, accessible at a default path.

```bash
php artisan make:filament-panel app
```

--------------------------------

### Filament PHP: Add Static Content Above Form Field Error Message

Source: https://filamentphp.com/docs/4.x/forms/overview

This PHP code demonstrates how to use Filament's `aboveErrorMessage()` method to display static custom content directly above a form field's validation error message. It shows passing an array containing an `Icon` component and a string, which only becomes visible when an error message is present for the `TextInput`.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->required()
    ->aboveErrorMessage([
        Icon::make(Heroicon::Star),
        'This is the content above the field's error message'
    ])
```

--------------------------------

### Configure Default Pagination Options in Filament Service Provider

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Preserves the v3 pagination behavior by setting the default paginationPageOptions() across the entire Filament application to include the 'all' option. Add this configuration in the boot() method of AppServiceProvider to restore the old default behavior for all tables.

```php
use Filament\Tables\Table;

Table::configureUsing(fn (Table $table) => $table
    ->paginationPageOptions([5, 10, 25, 50, 'all']));
```

--------------------------------

### Generate Filament resource with associated model, migration, and factory

Source: https://filamentphp.com/docs/4.x/resources/overview

This command allows you to scaffold the associated Eloquent model, database migration, and factory alongside your Filament resource in a single step. You can combine the `--model`, `--migration`, and `--factory` flags as needed to accelerate development.

```bash
php artisan make:filament-resource Customer --model --migration --factory
```

--------------------------------

### Test Filament table column formatted state with Pest Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-tables

This example shows how to test the formatted state of a Filament table column using Pest and Livewire. It utilizes `assertTableColumnFormattedStateSet()` to check for a specific formatted value and `assertTableColumnFormattedStateNotSet()` to ensure a value is not the formatted output for a given record.

```php
use function Pest\Livewire\livewire;

it('can get post author names', function () {
    $post = Post::factory(['name' => 'John Smith'])->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableColumnFormattedStateSet('author.name', 'Smith, John', record: $post)
        ->assertTableColumnFormattedStateNotSet('author.name', $post->author->name, record: $post);
});
```

--------------------------------

### Add External API Filtering to FilamentPHP Tables (PHP)

Source: https://filamentphp.com/docs/4.x/tables/custom-data

This example demonstrates how to add filtering capabilities to FilamentPHP tables by fetching data from an external API. It dynamically constructs the API endpoint based on selected category filters, using Laravel's `Http` facade to fetch data from DummyJSON. The `SelectFilter` options are also populated by an API call to get available categories, providing a fully dynamic filtering experience.

```PHP
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

public function table(Table $table): Table
{
    return $table
        ->records(function (array $filters): array {
            $category = $filters['category']['value'] ?? null;

            $endpoint = filled($category)
                ? "products/category/{$category}"
                : 'products';

            $response = Http::baseUrl('https://dummyjson.com/')
                ->get($endpoint);

            return $response
                ->collect()
                ->get('products', []);
        })
        ->columns([
            TextColumn::make('title'),
            TextColumn::make('category'),
            TextColumn::make('price')
                ->money(),
        ])
        ->filters([
            SelectFilter::make('category')
                ->label('Category')
                ->options(fn (): Collection => Http::baseUrl('https://dummyjson.com/')
                    ->get('products/categories')
                    ->collect()
                    ->pluck('name', 'slug')
                ),
        ]);
}
```

--------------------------------

### FilamentPHP: Set Default Value for Form Field (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet illustrates how to define a default value for a FilamentPHP form field using the `default()` method. The specified default value is applied only when a schema is loaded without existing data, typically for new record creation (e.g., on a 'Create' page), and not for editing existing records.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->default('John')
```

--------------------------------

### Format Filament TextEntry dates using standard PHP tokens

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Demonstrates how to format `TextEntry` states using the `date()`, `dateTime()`, and `time()` methods. These methods leverage standard PHP date formatting tokens for basic date and time display.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->date()

TextEntry::make('created_at')
    ->dateTime()

TextEntry::make('created_at')
    ->time()
```

--------------------------------

### Handle enum field state as enum instance - Filament v4

Source: https://filamentphp.com/docs/4.x/upgrade-guide

In Filament v4, field state for enum-based fields (Select, CheckboxList, Radio) now consistently returns enum instances instead of mixing enum values and instances. This requires updating code to handle enum instances through callbacks (afterStateUpdated), Get helper, or form state retrieval. Enum methods can now be safely called on field state values.

```PHP
use App\Enums\Status;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

Select::make('status')
    ->options(Status::class)
    ->afterStateUpdated(function (?Status $state) {
        // `$state` is now always an instance of `Status`, or `null` if the field is empty.
    });

TextInput::make('...')
    ->afterStateUpdated(function (Get $get) {
        // `$get('status')` is now always an instance of `Status`, or `null` if the field is empty.
    });

$data = $this->form->getState();
// `$data['status']` is now always an instance of `Status`, or `null` if the field is empty.
```

--------------------------------

### Generate a Livewire component using Artisan

Source: https://filamentphp.com/docs/4.x/components/table

This Artisan command creates a new Livewire component, `ListProducts`, which will serve as the container for your Filament table. This component will handle the logic and rendering of the table's data.

```bash
php artisan make:livewire ListProducts
```

--------------------------------

### Configure Filament TextInput Numeric Step

Source: https://filamentphp.com/docs/4.x/forms/text-input

This code demonstrates how to set a fixed numeric step for a Filament TextInput component. The `step()` method accepts an integer to define the increment/decrement value for the input, ensuring that the 'number' field only changes by multiples of 100. It can also accept a function for dynamic calculation.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('number')
    ->numeric()
    ->step(100)
```

--------------------------------

### Insert Action into Field Slot

Source: https://filamentphp.com/docs/4.x/forms

Add an Action component to a field slot for user interactions. This example inserts a generate action below a TextInput field's content.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->belowContent(Action::make('generate'));
```

--------------------------------

### Scope Dataset for Filament Table Count Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example illustrates using the `query()` method with a `Count` summarizer to count records based on a specific condition. It counts how many `IconColumn` entries for `is_published` are true, effectively showing the number of published posts.

```php
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Illuminate\Database\Query\Builder;

IconColumn::make('is_published')
    ->boolean()
    ->summarize(
        Count::make()->query(fn (Builder $query) => $query->where('is_published', true)),
    ),
```

--------------------------------

### Align FilamentPHP Image Component with Direct Methods

Source: https://filamentphp.com/docs/4.x/schemas/primes

This snippet shows how to align a FilamentPHP Image component using convenience methods like `alignStart()`, `alignCenter()`, and `alignEnd()`. `alignStart()` is the default alignment.

```php
use Filament\Schemas\Components\Image;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->alignStart() // This is the default alignment.

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->alignCenter()

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->alignEnd()
```

--------------------------------

### Example Inline SVG for Filament Brand Logo Blade View

Source: https://filamentphp.com/docs/4.x/styling

This HTML snippet provides an example of an inline SVG structure suitable for use within a Blade view (e.g., `filament.admin.logo`) to define a custom brand logo for Filament. It includes common attributes like `viewBox`, `xmlns`, and Tailwind CSS classes for styling.

```html
<svg
    viewBox="0 0 128 26"
    xmlns="http://www.w3.org/2000/svg"
    class="h-full fill-gray-500 dark:fill-gray-400"
>
    <!-- ... -->
</svg>
```

--------------------------------

### Starts With - Prefix Validation in Filament Forms

Source: https://filamentphp.com/docs/4.x/forms/validation

Validates that a field value begins with one of the specified prefixes. Accepts an array of acceptable starting values.

```php
Field::make('name')->startsWith(['a'])
```

--------------------------------

### Create edit and delete record actions with callbacks

Source: https://filamentphp.com/docs/4.x/tables/actions

Create specific record actions using `Action::make()` with either a URL destination or an action callback. The edit action opens a URL in a new tab, while the delete action requires confirmation before executing the deletion callback. Both actions receive the current table record as a parameter.

```PHP
use App\Models\Post;
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (Post $record): string => route('posts.edit', $record))
    ->openUrlInNewTab()

Action::make('delete')
    ->requiresConfirmation()
    ->action(fn (Post $record) => $record->delete())
```

--------------------------------

### Conditionally Render Filament Infolist TextEntry as Badge

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This PHP example illustrates how to conditionally display a `TextEntry` as a badge in Filament Infolists. By passing a boolean or a dynamic function to the `badge()` method, developers can control the badge's visibility based on feature flags or other conditions.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('status')
    ->badge(FeatureFlag::active())
```

--------------------------------

### Clean Up Upgrade Package - Composer

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Removes the temporary filament/upgrade development dependency after the upgrade process is complete. This package is only needed during the upgrade and should be removed to keep your composer.json clean.

```bash
composer remove filament/upgrade --dev
```

--------------------------------

### Implement Filament Action Lifecycle Hooks (PHP)

Source: https://filamentphp.com/docs/4.x/actions/replicate

Shows how to use lifecycle hooks (`before`, `beforeReplicaSaved`, `after`) within a Filament `ReplicateAction` to execute custom code at different stages of the action's execution. This allows for pre-replication setup, post-replication but pre-save modifications, and post-save actions.

```php
use Filament\Actions\ReplicateAction;
use Illuminate\Database\Eloquent\Model;

ReplicateAction::make()
    ->before(function () {
        // Runs before the record has been replicated.
    })
    ->beforeReplicaSaved(function (Model $replica): void {
        // Runs after the record has been replicated but before it is saved to the database.
    })
    ->after(function (Model $replica): void {
        // Runs after the replica has been saved to the database.
    })

```

--------------------------------

### Add Page Subheading in Filament using Method

Source: https://filamentphp.com/docs/4.x/navigation/custom-pages

This example shows how to dynamically add a subheading to a Filament page by returning a string from the `getSubheading()` method. This method allows for conditional or translated subheading content.

```PHP
public function getSubheading(): ?string
{
    return __('Custom Page Subheading');
}
```

--------------------------------

### Register Panel Provider in Composer.json Extra Configuration

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Register the PanelProvider as a Laravel service provider in the composer.json extra section of your package. This enables automatic discovery and registration of the panel when the package is installed.

```json
"extra": {
    "laravel": {
        "providers": [
            "DanHarrin\\FilamentBlog\\BlogPanelProvider"
        ]
    }
}
```

--------------------------------

### Filament PHP: Add Static Content Below Form Field Error Message

Source: https://filamentphp.com/docs/4.x/forms/overview

This PHP code illustrates the use of Filament's `belowErrorMessage()` method to insert static custom content directly beneath a form field's validation error message. Similar to `aboveErrorMessage()`, it accepts an array with an `Icon` component and a string, appearing only when the `TextInput` has a validation error.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextInput::make('name')
    ->required()
    ->belowErrorMessage([
        Icon::make(Heroicon::Star),
        'This is the content below the field's error message'
    ])
```

--------------------------------

### Dynamically preload relationship options for FilamentPHP SelectColumn

Source: https://filamentphp.com/docs/4.x/tables/columns/select

This example shows how to conditionally preload relationship options for a `SelectColumn` using a dynamic boolean value. The `preload()` method accepts a boolean, which can be determined by a function or a feature flag, to control preloading behavior based on specific conditions.

```php
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('author_id')
    ->optionsRelationship(name: 'author', titleAttribute: 'name')
    ->searchableOptions()
    ->preload(FeatureFlag::active())
```

--------------------------------

### Load Asynchronous Alpine Component in Blade View

Source: https://filamentphp.com/docs/4.x/advanced/assets

Load the compiled Alpine component in a Blade view using x-load and x-load-src attributes from the Async Alpine package. The example demonstrates passing state parameter entangled with a Livewire property for a custom form field.

```html
<div
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('test-component') }}"
    x-data="testComponent({
        state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
    })"
>
    <input x-model="state" />
</div>
```

--------------------------------

### Configure PostCSS plugins in postcss.config.js

Source: https://filamentphp.com/docs/4.x/plugins/building-a-standalone-plugin

Set up PostCSS configuration file to enable nesting support and CSS minification. This configuration processes CSS files with postcss-nesting plugin first, then applies cssnano for optimization and compression.

```javascript
module.exports = {
    plugins: [
        require('postcss-nesting')(),
        require('cssnano')({
            preset: 'default',
        }),
    ],
};
```

--------------------------------

### Hide Field Using PHP Function with Live Reloading

Source: https://filamentphp.com/docs/4.x/forms/overview

Hide a form field based on another field's value using the `hidden()` method with a PHP function. The `live()` method on the dependent field causes the schema to reload when that field changes, triggering re-evaluation of the hidden condition. This approach requires network requests but provides server-side logic.

```PHP
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])
    ->live()

Toggle::make('is_admin')
    ->hidden(fn (Get $get): bool => $get('role') !== 'staff')
```

--------------------------------

### Implement Export Policy View Authorization in PHP

Source: https://filamentphp.com/docs/4.x/actions/export

Implement the view() method in an ExportPolicy class to control which users can download export files. By default, only the user who initiated the export can download files. This example demonstrates how to maintain that behavior while allowing custom authorization logic to be added.

```php
use App\Models\User;
use Filament\Actions\Exports\Models\Export;

public function view(User $user, Export $export): bool
{
    return $export->user()->is($user);
}
```

--------------------------------

### Create esbuild Compilation Script

Source: https://filamentphp.com/docs/4.x/advanced/assets

Create a build script that configures esbuild for compiling Alpine.js components with support for watch mode during development and minification for production. The script uses esbuild context API to handle both one-time builds and continuous file watching.

```javascript
import * as esbuild from 'esbuild'

const isDev = process.argv.includes('--dev')

async function compile(options) {
    const context = await esbuild.context(options)

    if (isDev) {
        await context.watch()
    } else {
        await context.rebuild()
        await context.dispose()
    }
}

const defaultOptions = {
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
    },
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    sourcemap: isDev ? 'inline' : false,
    sourcesContent: isDev,
    treeShaking: true,
    target: ['es2020'],
    minify: !isDev,
    plugins: [{
        name: 'watchPlugin',
        setup(build) {
            build.onStart(() => {
                console.log(`Build started at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
            })

            build.onEnd((result) => {
                if (result.errors.length > 0) {
                    console.log(`Build failed at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`, result.errors)
                } else {
                    console.log(`Build finished at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
                }
            })
        }
    }],
}

compile({
    ...defaultOptions,
    entryPoints: ['./resources/js/components/test-component.js'],
    outfile: './resources/js/dist/components/test-component.js',
})
```

--------------------------------

### Add Prefix or Suffix to Filament Table Summarizer Output

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example demonstrates how to add a prefix and/or suffix to a summarizer's output using the `prefix()` and `suffix()` methods. A `Sum` summarizer on a `volume` `TextColumn` adds 'Total volume: ' as a prefix and ' m³' as a suffix, using `HtmlString` for special characters.

```php
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

TextColumn::make('volume')
    ->summarize(Sum::make()
        ->prefix('Total volume: ')
        ->suffix(new HtmlString(' m&sup3;'))
    )
```

--------------------------------

### Format Number in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

The `numeric()` method allows formatting a summarizer's output as a number. This example applies `numeric()` to an `Average` summarizer on a `rating` `TextColumn` for standard number formatting.

```php
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('rating')
    ->summarize(Average::make()->numeric())
```

--------------------------------

### Set Size for Filament Infolist IconEntry (PHP)

Source: https://filamentphp.com/docs/4.x/infolists/icon-entry

This example demonstrates how to set a specific size for an `IconEntry` in Filament Infolists. It uses the `size()` method with `IconSize::Medium` to adjust the icon's dimensions, allowing for visual hierarchy and improved readability within the UI. This functionality relies on `Filament\Infolists\Components\IconEntry` and `Filament\Support\Enums\IconSize`.

```php
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Enums\IconSize;

IconEntry::make('status')
    ->size(IconSize::Medium)
```

--------------------------------

### Conditionally Enable Multi-select with Feature Flag

Source: https://filamentphp.com/docs/4.x/forms/select

Shows how to pass a boolean value to the `multiple()` method to conditionally control whether the input accepts multiple selections. This example uses a feature flag to dynamically determine if multi-select should be enabled.

```php
use Filament\Forms\Components\Select;

Select::make('technologies')
    ->multiple(FeatureFlag::active())
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
    ])
```

--------------------------------

### Create Filament Resource with View Page

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

Generate a new Filament resource with a View page using the artisan command with the --view flag. This creates the resource structure and automatically includes a View page for displaying record details.

```shell
php artisan make:filament-resource User --view
```

--------------------------------

### Define a FilamentPHP Form Schema with Layout and Input Components

Source: https://filamentphp.com/docs/4.x/schemas/overview

Describes constructing a complex Filament schema using `Grid`, `Section`, `TextInput`, `Select`, `Checkbox`, and `TextEntry`. It highlights how these components are nested to create a structured form or infolist layout, demonstrating basic schema definition.

```php
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

$schema
    ->components([
        Grid::make(2)
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextInput::make('name'),
                        Select::make('position')
                            ->options([
                                'developer' => 'Developer',
                                'designer' => 'Designer'
                            ]),
                        Checkbox::make('is_admin')
                    ]),
                Section::make('Auditing')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime()
                    ])
            ])
    ])
```

--------------------------------

### Set the placement of a Filament Blade Dropdown

Source: https://filamentphp.com/docs/4.x/components/dropdown

This example demonstrates how to control the positioning of the dropdown menu relative to its trigger button. Use the 'placement' attribute with values like 'top-start'.

```blade
<x-filament::dropdown placement="top-start">
    {{-- Dropdown items --}}
</x-filament::dropdown>
```

--------------------------------

### Filament Table: Add Multiple Summarizers to Column

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example shows how to attach multiple summarizers, specifically 'Average' and 'Range', to a single numeric `TextColumn`. This allows for displaying various aggregate calculations like the average and the minimum/maximum values for the 'rating' column simultaneously.

```php
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('rating')
    ->numeric()
    ->summarize([
        Average::make(),
        Range::make(),
    ])
```

--------------------------------

### Autocapitalize TextInput field

Source: https://filamentphp.com/docs/4.x/forms/text-input

Enable browser-level text autocapitalization on a TextInput component using the autocapitalize() method. Accepts static string values or callable functions that support utility injection for dynamic capitalization strategies.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->autocapitalize('words')
```

--------------------------------

### Control Modal Alignment - Blade

Source: https://filamentphp.com/docs/4.x/components/modal

Sets the alignment of modal content using the alignment attribute with values 'start' or 'center'. By default, modals align to start, but xs and sm width modals center automatically. Use this attribute to customize content positioning.

```blade
<x-filament::modal alignment="center">
    {{-- Modal content --}}
</x-filament::modal>
```

--------------------------------

### Limit Text Length in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

The `limit()` method allows restricting the length of the summarizer's output value. This example applies a `Range` summarizer to an `sku` `TextColumn` and limits the displayed text to 5 characters.

```php
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('sku')
    ->summarize(Range::make()->limit(5))
```

--------------------------------

### Dynamic Chunk Size Calculation with Utility Injection

Source: https://filamentphp.com/docs/4.x/actions/export

Uses a callable function in chunkSize method to dynamically calculate chunk size based on injected utilities like Action, Arguments, Data, Livewire component, Eloquent model, and other contextual parameters. Enables performance optimization based on runtime conditions.

```PHP
ExportAction::make()
    ->exporter(ProductExporter::class)
    ->chunkSize(function ($action, $arguments, $data, $livewire, $model, $record) {
        // Dynamic calculation based on injected utilities
        return 250;
    })
```

--------------------------------

### Implement FilamentUser for Conditional Panel Access (PHP)

Source: https://filamentphp.com/docs/4.x/users/overview

This example extends basic panel access by showing how to conditionally grant access to different Filament panels. By checking the `$panel->getId()` within the `canAccessPanel` method, you can restrict access to specific panels (e.g., 'admin') while allowing broader access to others.

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    // ...

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        }

        return true;
    }
}
```

--------------------------------

### Conditionally Show a Filament Table Column Summary

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example demonstrates how to make a column summary visible based on a condition using the `visible()` method. The callback function is passed the Eloquent query builder to evaluate the condition dynamically, ensuring the summary is displayed only when the condition, such as the existence of records, is met.

```php
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

TextColumn::make('sku')
    ->summarize(Summarizer::make()
        ->visible(fn (Builder $query): bool => $query->exists()))
```

--------------------------------

### Custom constraint with built-in operators - PHP

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

Create a custom constraint with both custom operators and built-in operators like IsFilledOperator. This example shows how to combine custom operator logic with Filament's pre-built operators.

```PHP
use Filament\QueryBuilder\Constraints\Constraint;
use Filament\QueryBuilder\Constraints\Operators\IsFilledOperator;

Constraint::make('subscribed')
    ->label('Subscribed to updates')
    ->icon('heroicon-m-bell')
    ->operators([
        // ...
        IsFilledOperator::class,
    ]),
```

--------------------------------

### Configure Global Field Settings using configureUsing in Filament

Source: https://filamentphp.com/docs/4.x/forms

Demonstrates how to apply global configuration to field components using the static configureUsing() method. This method accepts a closure that modifies the component and should be called in a service provider's boot() method or middleware. Individual field instances can still override these global settings.

```php
use Filament\Forms\Components\Checkbox;

Checkbox::configureUsing(function (Checkbox $checkbox): void {
    $checkbox->inline(false);
});
```

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_admin')
    ->inline()
```

--------------------------------

### Use Custom Icon Sets in Filament PHP

Source: https://filamentphp.com/docs/4.x/styling/icons

Demonstrates using icons from alternative Blade Icons packages (other than Heroicons) by passing the icon name as a string to component methods. Requires the icon set to be installed before use.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;

Action::make('star')
    ->icon('iconic-star')
    
Toggle::make('is_starred')
    ->onIcon('iconic-check-circle')
```

--------------------------------

### Disable PHP Xdebug

Source: https://filamentphp.com/docs/4.x/introduction/optimizing-local-development

If Xdebug is installed but not currently in use, explicitly disable it in your `php.ini` file by setting its mode to 'off'. Xdebug, while powerful for debugging, can introduce significant performance overhead when active.

```ini
xdebug.mode=off # Disable Xdebug
```

--------------------------------

### Access Eloquent Record in Livewire Component Mount Method or Property

Source: https://filamentphp.com/docs/4.x/schemas/custom-components

This example shows how to access the current Eloquent record within a Livewire component integrated into Filament. The record can be obtained via the `$record` parameter in the `mount()` method or as a public property, which will be `null` if the record has not been created yet.

```php
use Illuminate\Database\Eloquent\Model;

class Chart extends Component
{
    public function mount(?Model $record = null): void
    {
        // ...
    }
    
    // or
    
    public ?Model $record = null;
}
```

--------------------------------

### Define Basic Filament View Action Schema with Form Fields

Source: https://filamentphp.com/docs/4.x/actions/view

This snippet demonstrates how to set up a basic Filament View action. It shows how to use `ViewAction::make()` and define a schema with `TextInput` components to display Eloquent record data in a modal. All form fields within this action are automatically disabled, making them read-only.

```php
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;

ViewAction::make()
    ->schema([
        TextInput::make('title')
            ->required()
            ->maxLength(255),
        // ...
    ])
```

--------------------------------

### Format Number with Custom Decimal Places in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example shows how to customize the number of decimal places for numeric formatting using the `decimalPlaces` argument. An `Average` summarizer on a `rating` `TextColumn` is configured to display numbers with zero decimal places.

```php
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('rating')
    ->summarize(Average::make()->numeric(
        decimalPlaces: 0,
    ))
```

--------------------------------

### Require Multi-Factor Authentication in Panel Configuration

Source: https://filamentphp.com/docs/4.x/users/multi-factor-authentication

Enforces multi-factor authentication setup for all users by passing isRequired: true to the multiFactorAuthentication() method. Users will be prompted to configure MFA after signing in if not already done.

```PHP
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make(),
        ], isRequired: true);
}
```

--------------------------------

### Custom XLSX Cell Styling with OpenSpout PHP

Source: https://filamentphp.com/docs/4.x/actions/export

This example demonstrates how to customize individual cell styles within an XLSX row using OpenSpout `Cell` objects and a `StyleMerger`. It allows for conditional styling based on column names, merging default styles with custom ones to achieve specific visual effects like underlining or custom font sizes for particular columns.

```php
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Manager\Style\StyleMerger;

/**
 * @param array<mixed> $values
 */
public function makeXlsxRow(array $values, ?Style $style = null): Row
{
    $styleMerger = new StyleMerger();

    $cells = [];
    
    foreach (array_keys($this->columnMap) as $columnIndex => $column) {
        $cells[] = match ($column) {
            'name' => Cell::fromValue(
                $values[$columnIndex],
                $styleMerger->merge(
                    (new Style())->setFontUnderline(),
                    $style,
                ),
            ),
            'price' => Cell::fromValue(
                $values[$columnIndex],
                (new Style())->setFontSize(12),
            ),
            default => Cell::fromValue($values[$columnIndex]),
        };
    }
    
    return new Row($cells, $style);
}
```

--------------------------------

### Create Fieldset component with responsive grid columns

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Creates a fieldset with a label, border, and responsive multi-column grid layout. The columns() method accepts an array of breakpoint configurations (default, md, xl) to define column counts at different responsive sizes. Supports dynamic label calculation through callback functions.

```php
use Filament\Schemas\Components\Fieldset;

Fieldset::make('Label')
    ->columns([
        'default' => 1,
        'md' => 2,
        'xl' => 3,
    ])
    ->schema([
        // ...
    ])
```

--------------------------------

### Trim Whitespace for a Specific Filament TagsInput

Source: https://filamentphp.com/docs/4.x/forms/tags-input

This example shows how to enable automatic whitespace trimming for tags in a single Filament `TagsInput` component. The `trim()` method removes leading and trailing whitespace from each tag entered by the user.

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->trim()
```

--------------------------------

### Create esbuild Compilation Script

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Node.js script that configures and executes esbuild to compile TipTap extensions. Accepts configuration options including entry points, output file paths, and optimization settings. Place at `bin/build.js`.

```javascript
import * as esbuild from 'esbuild'

async function compile(options) {
    const context = await esbuild.context(options)

    await context.rebuild()
    await context.dispose()
}

compile({
    define: {
        'process.env.NODE_ENV': `'production'`,
    },
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    sourcemap: false,
    sourcesContent: false,
    treeShaking: true,
    target: ['es2020'],
    minify: true,
    entryPoints: ['./resources/js/filament/rich-content-plugins/highlight.js'],
    outfile: './resources/js/dist/filament/rich-content-plugins/highlight.js',
})
```

--------------------------------

### Set Dynamic Timezone using Utility Injection

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Use the timezone() method with a closure function to dynamically calculate the timezone based on injected utilities. Supports injection of Entry component, Get function, Livewire component, Eloquent model FQN, operation string, Eloquent record, and current state value.

```php
TextEntry::make('created_at')
    ->timezone(function (?string $model, string $operation, ?Illuminate\Database\Eloquent\Model $record, mixed $state): string {
        // Dynamically calculate and return timezone
        return 'America/New_York';
    })
    ->dateTime()
```

--------------------------------

### Enable Live Reactivity on Field Blur Event

Source: https://filamentphp.com/docs/4.x/forms

Re-render the schema only after the user finishes interacting with a field using `live(onBlur: true)`. Improves performance for text inputs by avoiding network requests while the user is still typing.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('username')
    ->live(onBlur: true)
```

--------------------------------

### Use Input Component with Input Wrapper in Blade

Source: https://filamentphp.com/docs/4.x/components/input

Demonstrates how to use the Filament Input Blade component wrapped in an input wrapper component. The input wrapper provides styling, borders, and support for prefix/suffix elements. The example shows a text input bound to a Livewire property using wire:model directive.

```blade
<x-filament::input.wrapper>
    <x-filament::input
        type="text"
        wire:model="name"
    />
</x-filament::input.wrapper>
```

--------------------------------

### Create a Filament Widget using Artisan Command

Source: https://filamentphp.com/docs/4.x/widgets/overview

Use the `make:filament-widget` Artisan command to generate a new widget file. This command will prompt you to select a widget type, such as Custom, Chart, Stats overview, or Table, providing a starting point for your widget development.

```bash
php artisan make:filament-widget MyWidget
```

--------------------------------

### Show Entry Based on Current Operation in Filament

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates showing an infolist entry based on the current schema operation using the `visibleOn()` method. Accepts a single operation string or an array of operations. This method overwrites previous `visible()` calls and provides better readability than `hiddenOn()` in some cases.

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('is_admin')
    ->boolean()
    ->visibleOn('create')

IconEntry::make('is_admin')
    ->boolean()
    ->visibleOn(['create', 'edit'])
```

--------------------------------

### Add HTML Attributes to Filament Field's Inner Input Element (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet shows how to add static HTML attributes directly to the underlying `<input>` or `<select>` element within a Filament form field using the `extraInputAttributes()` method. This is useful when `extraAttributes()` doesn't target the desired inner element. Attributes are passed as an associative array, and like `extraAttributes()`, multiple calls overwrite previous attributes unless `merge: true` is passed.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('categories')
    ->extraInputAttributes(['width' => 200])
```

--------------------------------

### Basic FileUpload Component Setup

Source: https://filamentphp.com/docs/4.x/forms/file-upload

Creates a simple file upload field named 'attachment' using the Filament FileUpload component. This is the minimal configuration needed to add file upload functionality to a form.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
```

--------------------------------

### Injecting Laravel Dependencies and Filament Utilities (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/overview

Illustrates how to inject standard Laravel container dependencies, such as an `Illuminate\Http\Request`, alongside Filament utilities like `Set` into a function. This combines framework and Filament-specific functionalities.

```php
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Http\Request;

function (Request $request, Set $set) {
    // ...
}
```

--------------------------------

### Add HTML Attributes to Filament Field's Outer Element (PHP)

Source: https://filamentphp.com/docs/4.x/forms/overview

This snippet demonstrates how to add static HTML attributes to the outer HTML element of a Filament form field using the `extraAttributes()` method. The attributes are provided as an associative array where keys are attribute names and values are their corresponding values. This method applies to the field's wrapper element, and by default, multiple calls overwrite previous attributes unless `merge: true` is passed.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->extraAttributes(['title' => 'Text input'])
```

--------------------------------

### Test Filament table column extra attributes with Pest Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-tables

This example demonstrates how to assert the presence and absence of extra attributes on a Filament table column. It uses `assertTableColumnHasExtraAttributes()` to check for specific attributes and `assertTableColumnDoesNotHaveExtraAttributes()` to confirm their absence for a record.

```php
use function Pest\Livewire\livewire;

it('displays author in red', function () {
    $post = Post::factory()->create();

    livewire(PostsTable::class)
        ->assertTableColumnHasExtraAttributes('author', ['class' => 'text-danger-500'], $post)
        ->assertTableColumnDoesNotHaveExtraAttributes('author', ['class' => 'text-primary-500'], $post);
});
```

--------------------------------

### Add an image to a Filament Blade Dropdown item

Source: https://filamentphp.com/docs/4.x/components/dropdown

This example demonstrates how to display a circular image next to a dropdown list item. Provide the image URL to the 'image' attribute.

```blade
<x-filament::dropdown.list.item image="https://filamentphp.com/dan.jpg">
    Dan Harrin
</x-filament::dropdown.list.item>
```

--------------------------------

### Create and send basic notification - PHP & JavaScript

Source: https://filamentphp.com/docs/4.x/notifications/overview

Demonstrates how to instantiate a Notification object and send it using the fluent API. The notification displays a title and is dispatched immediately via the send() method. Works from Livewire components, middleware, or any application code.

```PHP
<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;

class EditPost extends Component
{
    public function save(): void
    {
        // ...

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
```

```JavaScript
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .send()
```

--------------------------------

### Send Filament Database Notification using Fluent API

Source: https://filamentphp.com/docs/4.x/notifications/database-notifications

This example demonstrates sending a database notification using Filament's fluent API. Import the `Notification` facade, create a notification instance with a title, and then use `sendToDatabase()` to dispatch it to a specified recipient.

```php
use Filament\Notifications\Notification;

$recipient = auth()->user();

Notification::make()
    ->title('Saved successfully')
    ->sendToDatabase($recipient);
```

--------------------------------

### Enable 'all' Pagination Option in Filament Table

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Adds the 'all' pagination option to a Filament table's paginationPageOptions() method. This enables users to display all records on a single page, though it should be used cautiously with large datasets as it may cause performance issues.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->paginationPageOptions([5, 10, 25, 50, 'all']);
}
```

--------------------------------

### Hide Entry Using PHP Callback with Live Updates in Filament

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates hiding an infolist entry using the `hidden()` method with a PHP callback function. The example shows a role selector that triggers schema reload on change, causing the `is_admin` entry visibility to be re-evaluated server-side. Note that this approach requires network requests and may impact performance.

```php
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])
    ->live()

IconEntry::make('is_admin')
    ->boolean()
    ->hidden(fn (Get $get): bool => $get('role') !== 'staff')
```

--------------------------------

### Implement Lifecycle Hooks in View Page

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

Execute custom code at specific points in the View page lifecycle using protected hook methods. The beforeFill() hook runs before form fields populate from the database (not for infolists), while afterFill() runs after population, enabling data preprocessing and postprocessing.

```php
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    // ...

    protected function beforeFill(): void
    {
        // Runs before the disabled form fields are populated from the database. Not run on pages using an infolist.
    }

    protected function afterFill(): void
    {
        // Runs after the disabled form fields are populated from the database. Not run on pages using an infolist.
    }
}
```

--------------------------------

### Setup Polymorphic User Relationship in Exports Table

Source: https://filamentphp.com/docs/4.x/actions/export

Replaces the standard user_id column with polymorphic columns to support multiple user model types in the exports table. This enables associating exports with different user models simultaneously.

```PHP
$table->morphs('user');
```

--------------------------------

### Create Filament Page using Artisan Command

Source: https://filamentphp.com/docs/4.x/navigation/custom-pages

This command generates a new Filament page, creating both the page class in the `/Pages` directory and its corresponding view file in the Filament views directory. Page classes are Livewire components with additional panel utilities.

```CLI
php artisan make:filament-page Settings
```

--------------------------------

### Check Outdated Translations in Filament

Source: https://filamentphp.com/docs/4.x/introduction/contributing

Shell script sequence to clone Filament repository, install dependencies, and run the translation checking tool. Identifies missing translations for specified locales and helps maintain translation completeness across the framework.

```bash
# Clone
git clone git@github.com:filamentphp/filament.git

# Install dependencies
composer install

# Run the tool
./bin/translation-tool.php
```

--------------------------------

### Disable FilamentPHP Topbar

Source: https://filamentphp.com/docs/4.x/navigation/overview

This PHP example shows how to disable the topbar component in the FilamentPHP admin panel. Setting the `topbar()` method to `false` will prevent the topbar from being rendered.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->topbar(false);
}
```

--------------------------------

### Add Static Method for Type-Safe Plugin Configuration Access

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Implement a static get() method on the plugin class to provide better type safety and IDE autocompletion when accessing configuration options. This method retrieves the plugin instance from the service container and returns it with proper typing.

```php
use Filament\Contracts\Plugin;

class BlogPlugin implements Plugin
{
    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }
    
    // ...
}
```

--------------------------------

### Configure Email Code Expiration Time

Source: https://filamentphp.com/docs/4.x/users/multi-factor-authentication

Sets the expiration time for email authentication codes using the codeExpiryMinutes() method. Default is 4 minutes; this example configures codes to expire after 2 minutes.

```PHP
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            EmailAuthentication::make()
                ->codeExpiryMinutes(2),
        ]);
}
```

--------------------------------

### Set Public Visibility for Filament ImageColumn

Source: https://filamentphp.com/docs/4.x/tables/columns/image

This example sets the visibility of an `ImageColumn` to `public`. When an image is stored on a public disk, Filament can avoid generating temporary URLs, directly linking to the public asset for efficiency.

```php
use Filament\Tables\Columns\ImageColumn;

ImageColumn::make('header_image')
    ->visibility('public')
```

--------------------------------

### Implement HasAppAuthentication interface for Filament MFA in User model

Source: https://filamentphp.com/docs/4.x/users/multi-factor-authentication

This snippet demonstrates implementing the `HasAppAuthentication` interface on the `User` model. This interface requires methods like `getAppAuthenticationSecret`, `saveAppAuthenticationSecret`, and `getAppAuthenticationHolderName` to be defined. These methods provide Filament with the necessary logic to retrieve, store, and display the app authentication secret for MFA setup and verification.

```php
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, MustVerifyEmail
{
    // ...

    public function getAppAuthenticationSecret(): ?string
    {
        // This method should return the user's saved app authentication secret.
    
        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        // This method should save the user's app authentication secret.
    
        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        // In a user's authentication app, each account can be represented by a "holder name".
        // If the user has multiple accounts in your app, it might be a good idea to use
        // their email address as then they are still uniquely identifiable.
    
        return $this->email;
    }
}
```

--------------------------------

### Create Custom Active Indicator with Formatted Data in Filament

Source: https://filamentphp.com/docs/4.x/tables/filters/custom

Shows how to use `indicateUsing()` to create dynamic, custom active indicators for complex filters. This example creates a date filter that displays a formatted date string in the indicator when active, returning null if no date is selected. Useful for providing user-friendly feedback about active filter states.

```PHP
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

Filter::make('created_at')
    ->schema([DatePicker::make('date')])
    // ...
    ->indicateUsing(function (array $data): ?string {
        if (! $data['date']) {
            return null;
        }

        return 'Created at ' . Carbon::parse($data['date'])->toFormattedDateString();
    })
```

--------------------------------

### Use JavaScript to Determine Dynamic Text Content

Source: https://filamentphp.com/docs/4.x/forms

Render dynamic content in HTML-supporting methods like `label()` and `Text::make()` using JavaScript via `JsContent` object. The `$state` and `$get` utilities are available in the JavaScript context for accessing field state and values.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\JsContent;

TextInput::make('greetingResponse')
    ->label(JsContent::make(<<<'JS'
        ($get('name') === 'John Doe') ? 'Hello, John!' : 'Hello, stranger!'
        JS
    ))
```

--------------------------------

### Create Panel Provider Service Provider for Plugin Distribution

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Create a BlogPanelProvider class extending PanelProvider to distribute an entire pre-built panel in a Laravel package. The panel() method configures resources, pages, widgets, middleware, and authentication middleware for the distributed panel.

```php
<?php

namespace DanHarrin\FilamentBlog;

use Filament\Panel;
use Filament\PanelProvider;

class BlogPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('blog')
            ->path('blog')
            ->resources([
                // ...
            ])
            ->pages([
                // ...
            ])
            ->widgets([
                // ...
            ])
            ->middleware([
                // ...
            ])
            ->authMiddleware([
                // ...
            ]);
    }
}
```

--------------------------------

### Define FilamentPHP Table Structure in External Class

Source: https://filamentphp.com/docs/4.x/resources/overview

This PHP example details how to configure a FilamentPHP table's columns, filters, record actions, and toolbar actions within an external table class. It demonstrates adding `TextColumn` for data display, a `Filter` for queries, and various `Action` components.

```php
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name'),
            TextColumn::make('email'),
            // ...
        ])
        ->filters([
            Filter::make('verified')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            // ...
        ])
        ->recordActions([
            EditAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}
```

--------------------------------

### Generate Livewire Component for Filament Infolist

Source: https://filamentphp.com/docs/4.x/components/infolist

Generate a new Livewire component using the Artisan command. This component will serve as the container for your Filament infolist.

```bash
php artisan make:livewire ViewProduct
```

--------------------------------

### Add Description to Filament Table Group (PHP)

Source: https://filamentphp.com/docs/4.x/tables/grouping

This example shows how to add a custom description to a Filament table group, which appears below the group title. The `getDescriptionFromRecordUsing()` method accepts a callback to generate the description dynamically based on the record.

```php
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->groups([
            Group::make('status')
                ->getDescriptionFromRecordUsing(fn (Post $record): string => $record->status->getDescription()),
        ]);
}

```

--------------------------------

### Test Filament Action Modal Pre-filled Schema State (PHP)

Source: https://filamentphp.com/docs/4.x/testing/testing-actions

This example demonstrates asserting that an action modal's form fields are pre-filled with expected data using `assertSchemaStateSet()`. It mounts the action, verifies the initial state, then calls the mounted action and confirms the final invoice state.

```php
use function Pest\Livewire\livewire;

it('can send invoices to the primary contact by default', function () {
    $invoice = Invoice::factory()->create();
    $recipientEmail = $invoice->company->primaryContact->email;

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->mountAction('send')
        ->assertSchemaStateSet([
            'email' => $recipientEmail,
        ])
        ->callMountedAction()
        ->assertHasNoFormErrors();

    expect($invoice->refresh())
        ->isSent()->toBeTrue()
        ->recipient_email->toBe($recipientEmail);
});
```

--------------------------------

### Convert Markdown to HTML in Text component

Source: https://filamentphp.com/docs/4.x/schemas/primes

Demonstrates rendering Markdown content by using Laravel's str() helper to convert Markdown syntax to HTML and transform it into an HtmlString object. This provides a safe way to display formatted text with Markdown syntax.

```php
use Filament\Schemas\Components\Text;

Text::make(
    str('**Warning:** Modifying these permissions may give users access to sensitive information.')
        ->inlineMarkdown()
        ->toHtmlString(),
)
```

--------------------------------

### Apply Filament colors in Blade components

Source: https://filamentphp.com/docs/4.x/styling/colors

This example shows how to pass a registered Filament color, like 'success', as an attribute to a Blade component. This allows the Blade component to render with the specified color palette.

```blade
<x-filament::badge color="success">
    Active
</x-filament::badge>
```

--------------------------------

### Filament Action Utility Injection Parameters Reference

Source: https://filamentphp.com/docs/4.x/actions/overview

Comprehensive reference table of injectable utilities available in Filament action callbacks. These utilities enable access to the action instance, form data, Livewire component, Eloquent models, schema operations, and more. Different utilities are available depending on the action context (schemas, tables, bulk actions).

```php
// Example utility injection in an action callback
$action->outlined(function (Action $action, array $data, Livewire\Component $livewire, ?Illuminate\Database\Eloquent\Model $record): bool {
    // Access injected utilities
    // $action - Current action instance
    // $data - Form submission data
    // $livewire - Livewire component instance
    // $record - Eloquent model record if attached
    return true;
})
```

--------------------------------

### Customize Entire Filament Create Action Success Notification (PHP)

Source: https://filamentphp.com/docs/4.x/actions/create

This example shows how to replace the default success notification with a custom `Notification` object for a Filament `CreateAction`. The `successNotification()` method allows defining a custom title and body, providing full control over the notification's appearance and content.

```php
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;

CreateAction::make()
    ->successNotification(
       Notification::make()
            ->success()
            ->title('User registered')
            ->body('The user has been created successfully.'),
    )
```

--------------------------------

### Configure FilamentPHP Custom Component with Utility Injection Closure (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/custom-components

This PHP example demonstrates configuring a `Chart` component using a closure for its heading property. The closure accepts injected utilities, such as a `Product $record`, to dynamically generate the heading based on contextual data, showcasing Filament's utility injection feature.

```php
use App\Filament\Schemas\Components\Chart;

Chart::make()
    ->heading(fn (Product $record): string => "{$record->name} Sales")
```

--------------------------------

### Build CSS Assets with npm

Source: https://filamentphp.com/docs/4.x/plugins/building-a-standalone-plugin

Compiles CSS stylesheets for the plugin. This command processes the custom styles defined in resources/css/index.css and generates optimized production-ready stylesheets.

```bash
npm run build
```

--------------------------------

### Dispatch Livewire Event from Action (PHP)

Source: https://filamentphp.com/docs/4.x/resources/global-search

Allows a global search action to dispatch a Livewire event with optional data when clicked. This enables executing custom server-side logic in response to user interaction with search results. The event name and parameters are specified.

```php
use Filament\Actions\Action;

Action::make('quickView')
    ->dispatch('quickView', [$record->id])
```

--------------------------------

### Create Select component with static options in Filament

Source: https://filamentphp.com/docs/4.x/forms/select

Creates a basic Select form component with predefined static options. The component accepts an array where keys are the option values and values are the display labels. This example shows a status field with three options: draft, reviewing, and published.

```php
use Filament\Forms\Components\Select;

Select::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
```

--------------------------------

### Format Filament TextEntry dates using Carbon macro-formats

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Illustrates how to format `TextEntry` states using `isoDate()`, `isoDateTime()`, and `isoTime()` methods. These methods utilize Carbon's predefined macro-formats for ISO-style date and time representation.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->isoDate()

TextEntry::make('created_at')
    ->isoDateTime()

TextEntry::make('created_at')
    ->isoTime()
```

--------------------------------

### Conditionally Enable CodeEntry Copyable with Boolean Flag

Source: https://filamentphp.com/docs/4.x/infolists/code-entry

Demonstrates passing a boolean value to the copyable() method to dynamically control whether the code should be copyable. This example uses a FeatureFlag to conditionally enable the copyable functionality based on application state or configuration.

```php
use Filament\Infolists\Components\CodeEntry;

CodeEntry::make('code')
    ->copyable(FeatureFlag::active())
```

--------------------------------

### Create custom operator with dynamic filtering - PHP

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

Build a custom operator using Operator::make() with label, summary, and baseQuery methods. This example demonstrates relationship-based filtering with inversion support, checking user subscriptions against authenticated user.

```PHP
use Filament\QueryBuilder\Constraints\Operators\Operator;

Operator::make('subscribed')
    ->label(fn (bool $isInverse): string => $isInverse ? 'Not subscribed' : 'Subscribed')
    ->summary(fn (bool $isInverse): string => $isInverse ? 'You are not subscribed' : 'You are subscribed')
    ->baseQuery(fn (Builder $query, bool $isInverse) => $query->{$isInverse ? 'whereDoesntHave' : 'whereHas'}(
        'subscriptions.user',
        fn (Builder $query) => $query->whereKey(auth()->user()),
    )),
```

--------------------------------

### Responsive Grid Layout with Section Component - PHP

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Creates a responsive grid layout using a Section component with responsive columns configuration and TextInput fields that span different numbers of columns based on screen size. The columnOrder() method controls the visual order of fields on different breakpoints. This example shows how to configure different column counts for sm, xl, and 2xl Tailwind breakpoints.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make()
    ->columns([
        'sm' => 3,
        'xl' => 6,
        '2xl' => 8,
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                'default' => 1,
                'sm' => 2,
                'xl' => 3,
                '2xl' => 4,
            ])
            ->columnOrder([
                'default' => 2,
                'xl' => 1,
            ]),
        TextInput::make('email')
            ->columnSpan([
                'default' => 1,
                'xl' => 2,
            ])
            ->columnOrder([
                'default' => 1,
                'xl' => 2,
            ]),
        // ...
    ])
```

--------------------------------

### Set FilamentPHP Panel Authentication Guard

Source: https://filamentphp.com/docs/4.x/users/overview

This configuration snippet shows how to specify the authentication guard that FilamentPHP should use for a panel. The `authGuard()` method takes the guard name as a string, for example, 'web'.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->authGuard('web');
}
```

--------------------------------

### Specify Filament Repeater Component Schema

Source: https://filamentphp.com/docs/4.x/forms/repeater

This example illustrates the basic structure for defining the internal schema of a Filament Repeater component using the `schema()` method. It shows how to include a TextInput field and serves as a foundation for adding more components to the repeater.

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

Repeater::make('members')
    ->schema([
        TextInput::make('name')->required(),
        // ...
    ])
```

--------------------------------

### Basic Color Entry Component in PHP

Source: https://filamentphp.com/docs/4.x/infolists/color-entry

Creates a basic ColorEntry component that displays a color preview from a CSS color definition. The component requires Filament's infolist component infrastructure.

```php
use Filament\Infolists\Components\ColorEntry;

ColorEntry::make('color')
```

--------------------------------

### Override default operators with operators() method - PHP

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

Replace all default operators on a constraint with custom operators using the operators() method. This example sets a TextConstraint to use only the IsFilledOperator, removing all other default operators.

```PHP
use Filament\QueryBuilder\Constraints\Operators\IsFilledOperator;
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('author.name')
    ->operators([
        IsFilledOperator::make(),
    ])
```

--------------------------------

### Implement Record and Bulk Actions in Filament PHP Tables

Source: https://filamentphp.com/docs/4.x/tables

This example illustrates how to define both individual record actions and bulk actions for Filament PHP tables. It includes custom 'feature' and 'unfeature' actions that modify a record's `is_featured` attribute based on its current state, utilizing `hidden()` and `visible()` methods. Additionally, it shows how to add a built-in `DeleteBulkAction` to the table's toolbar, enabling operations on multiple selected records.

```php
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->recordActions([
            Action::make('feature')
                ->action(function (Post $record) {
                    $record->is_featured = true;
                    $record->save();
                })
                ->hidden(fn (Post $record): bool => $record->is_featured),
            Action::make('unfeature')
                ->action(function (Post $record) {
                    $record->is_featured = false;
                    $record->save();
                })
                ->visible(fn (Post $record): bool => $record->is_featured),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}
```

--------------------------------

### Override Global TextEntry Configuration per Instance

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates how to override global TextEntry settings on individual component instances. Global configurations applied via configureUsing() can be selectively disabled or modified for specific entries by calling the same method with custom parameters.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->words(null)
```

--------------------------------

### Access Another Component State in Blade - Filament

Source: https://filamentphp.com/docs/4.x/schemas/custom-components

Access the state of another component in the schema using the $get() function within a Blade view. This retrieves the current value of a named component.

```html
<div>
    {{ $get('email') }}
</div>
```

--------------------------------

### PHP: Reusable Form Fields for Filament Schemas and Wizards

Source: https://filamentphp.com/docs/4.x/resources/creating-records

Demonstrates how to create reusable form field definitions by extracting them into public static methods within a dedicated form class. This approach reduces repetition when using the same fields in both resource forms and Filament wizard steps, ensuring consistency and maintainability.

```php
use Filament\Forms;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                static::getNameFormField(),
                static::getSlugFormField(),
                // ...
            ]);
    }
    
    public static function getNameFormField(): Forms\Components\TextInput
    {
        return TextInput::make('name')
            ->required()
            ->live()
            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)));
    }
    
    public static function getSlugFormField(): Forms\Components\TextInput
    {
        return TextInput::make('slug')
            ->disabled()
            ->required()
            ->unique(Category::class, 'slug', fn ($record) => $record);
    }
}
```

```php
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    
    protected static string $resource = CategoryResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Name')
                ->description('Give the category a clear and unique name')
                ->schema([
                    CategoryForm::getNameFormField(),
                    CategoryForm::getSlugFormField(),
                ]),
            // ...
        ];
    }
}
```

--------------------------------

### Set Static Label on Filament Action

Source: https://filamentphp.com/docs/4.x/actions/overview

Demonstrates how to customize the default trigger button label using the label() method with a static string value. The example shows setting a custom label for an edit action with a corresponding URL route.

```php
use Filament\Actions\Action;

Action::make('edit')
    ->label('Edit post')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
```

--------------------------------

### Set HTML input type with helper methods (PHP - Filament Forms)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Configures common HTML input types using built-in helper methods (email, numeric, integer, password, tel, url). Requires Filament Forms. Input: TextInput instance; Output: configured TextInput with appropriate HTML type and optional built-in validation for some helpers (e.g., email). Limitations: helper methods map to commonly used types only.

```php
use Filament\Forms\Components\TextInput;\n\nTextInput::make('text')\n    ->email() // or\n    ->numeric() // or\n    ->integer() // or\n    ->password() // or\n    ->tel() // or\n    ->url()\n
```

--------------------------------

### Set a notification title in Filament (PHP & JS)

Source: https://filamentphp.com/docs/4.x/notifications

This example shows how to set the main title of a Filament notification. The title can contain basic, safe HTML elements and can be generated with Markdown using `Str::markdown()`.

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->send();

```

```javascript
new FilamentNotification()
    .title('Saved successfully')
    .send()

```

--------------------------------

### Search multiple columns in FilamentPHP SelectColumn relationship options

Source: https://filamentphp.com/docs/4.x/tables/columns/select

This example illustrates how to enable searching across multiple columns for a `SelectColumn`'s relationship options. By passing an array of column names (e.g., `['name', 'email']`) to the `searchableOptions()` method, the search will consider all specified fields for matching.

```php
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('author_id')
    ->optionsRelationship(name: 'author', titleAttribute: 'name')
    ->searchableOptions(['name', 'email'])
```

--------------------------------

### Configure Filament Table within a Livewire component class

Source: https://filamentphp.com/docs/4.x/components/table

This PHP class demonstrates how to set up a Filament table within a Livewire component. It requires implementing specific interfaces and using traits to enable table functionality, then configuring the table's query, columns, filters, and actions in the `table()` method.

```php
<?php

namespace App\Livewire;

use App\Models\Shop\Product;
use Filament\Actions\Concerns\InteractsWithActions;  
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListProducts extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                // ...
            ]);
    }
    
    public function render(): View
    {
        return view('livewire.list-products');
    }
}
```

--------------------------------

### Set Static Label for FilamentPHP Infolist TextEntry

Source: https://filamentphp.com/docs/4.x/infolists

This example shows how to customize the display label of a `TextEntry` component in FilamentPHP Infolists using the `label()` method with a static string. By default, the label is generated from the entry's name, but this method allows for explicit naming.

```php
use FilamentInfolistsComponentsTextEntry;

TextEntry::make('name')
    ->label('Full name')
```

--------------------------------

### Insert Plain Text into Field Slot

Source: https://filamentphp.com/docs/4.x/forms

Add static plain text content to a field slot using the slot method. This example demonstrates adding descriptive text below a TextInput field's content area.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->belowContent('This is the user\'s full name.');
```

--------------------------------

### Apply Persistent Middleware to Filament Panel Routes

Source: https://filamentphp.com/docs/4.x/panel-configuration

This snippet demonstrates how to apply middleware that runs on every request, including Livewire AJAX requests, within a Filament panel. By passing `true` as the `isPersistent` argument to the `middleware()` method, the specified middleware will consistently execute, not just on the initial page load.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->middleware([
            // ...
        ], isPersistent: true);
}
```

--------------------------------

### Scope Relationship Query with modifyRelationshipQueryUsing

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

Filters related records by applying a custom query scope using modifyRelationshipQueryUsing(). This example filters the creator.name constraint to only show admin creators by adding a where clause to the relationship query.

```PHP
use Filament\QueryBuilder\Constraints\TextConstraint;
use Illuminate\Database\Eloquent\Builder;

TextConstraint::make('creator.name')
    ->label('Admin creator name')
    ->modifyRelationshipQueryUsing(fn (Builder $query) => $query->where('is_admin', true))
```

--------------------------------

### Customize Entire Restore Notification Object in FilamentPHP

Source: https://filamentphp.com/docs/4.x/actions/restore

This example shows how to fully customize the success notification for a FilamentPHP `RestoreAction`. It uses the `successNotification()` method to define a custom `Notification` object, allowing control over its type, title, and body.

```php
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;

RestoreAction::make()
    ->successNotification(
       Notification::make()
            ->success()
            ->title('User restored')
            ->body('The user has been restored successfully.'),
    )
```

--------------------------------

### Set Filament Text Component Font Weight

Source: https://filamentphp.com/docs/4.x/schemas/primes

This example illustrates how to modify the font weight of a `Text` component. The `weight()` method, in conjunction with `FontWeight` enums (e.g., `FontWeight::Bold`), allows for emphasizing text by adjusting its thickness.

```php
use FilamentSchemasComponentsText;
use FilamentSupportEnumsFontWeight;

Text::make('Modifying these permissions may give users access to sensitive information.')
    ->weight(FontWeight::Bold)
```

--------------------------------

### Add Dynamic Prefix and Suffix to Text Input Column

Source: https://filamentphp.com/docs/4.x/tables/columns/text-input

Use prefix() and suffix() methods with callback functions to dynamically calculate affix values. Supports utility injection including $column, $livewire, $record, $rowLoop, $state, and $table parameters.

```php
TextInputColumn::make('field')
    ->prefix(function ($record, $state) {
        // Dynamically calculate prefix based on record or state
        return 'dynamic-prefix';
    })
    ->suffix(function ($table) {
        // Dynamically calculate suffix based on table context
        return 'dynamic-suffix';
    })
```

--------------------------------

### Set Minimum and Maximum Length on Textarea

Source: https://filamentphp.com/docs/4.x/forms/textarea

Demonstrates how to use minLength() and maxLength() methods to limit textarea input length. Both methods apply frontend and backend validation automatically. The example limits description field between 2 and 1024 characters.

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->minLength(2)
    ->maxLength(1024)
```

--------------------------------

### Control Decimal Places for Money Formatting in Filament TextEntry (PHP)

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This example demonstrates using the `decimalPlaces` argument with the `money()` method to customize the number of decimal places shown in the formatted output. This provides fine-grained control over the precision of the displayed monetary value. Like other arguments, `decimalPlaces` can also take a function for dynamic determination with utility injection.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('price')
    ->money('EUR', decimalPlaces: 3)
```

--------------------------------

### Add ImportAction to table header

Source: https://filamentphp.com/docs/4.x/actions/import

Register an ImportAction in a table's header actions to allow users to import CSV data. This example demonstrates adding the import action to a Filament table resource with a specified importer class.

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->headerActions([
            ImportAction::make()
                ->importer(ProductImporter::class)
        ]);
}
```

--------------------------------

### Implement Before and After Lifecycle Hooks in Filament DeleteAction (PHP)

Source: https://filamentphp.com/docs/4.x/actions/delete

This example illustrates how to attach `before()` and `after()` lifecycle hooks to a Filament `DeleteAction`. The `before()` callback executes custom logic prior to the record's deletion, while the `after()` callback runs after the deletion is completed, enabling pre- and post-processing tasks like logging or related data updates. These hooks can accept various injected utilities as parameters.

```php
use Filament\Actions\DeleteAction;

DeleteAction::make()
    ->before(function () {
        // ... execute code before deletion
    })
    ->after(function () {
        // ... execute code after deletion
    })
```

--------------------------------

### Generate Livewire Table Component via Artisan CLI

Source: https://filamentphp.com/docs/4.x/components/table

Uses the `make:livewire-table` Artisan command to generate a new Livewire table component. The command prompts for a prebuilt model name and creates a customizable component file at the specified path.

```Bash
php artisan make:livewire-table Products/ListProducts
```

--------------------------------

### Call Action in Livewire Test - Filament PHP

Source: https://filamentphp.com/docs/4.x/testing/testing-actions

Tests a basic action by passing its name to callAction() method within a Livewire component test. This example demonstrates testing an invoice send action and verifying the result state after execution.

```PHP
use function Pest\Livewire\livewire;

it('can send invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->callAction('send');

    expect($invoice->refresh())
        ->isSent()->toBeTrue();
});
```

--------------------------------

### Customize Filament Table Grouping Label (PHP)

Source: https://filamentphp.com/docs/4.x/tables/grouping

This example shows how to set a custom display label for a Filament table group. By using a `Group` object and its `label()` method, the default attribute-based label can be overridden with a more user-friendly name.

```php
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->groups([
            Group::make('author.name')
                ->label('Author name'),
        ]);
}

```

--------------------------------

### Configure Filament Radio to Preserve v3 Inline Label Behavior Globally in v4

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This PHP code snippet, intended for placement in a service provider's `boot()` method (e.g., `AppServiceProvider`), globally configures Filament v4 radio components. It ensures that when `inline()` is used, the radio buttons are also inline with their labels, replicating the default behavior from Filament v3.

```php
use Filament\Forms\Components\Radio;\n\nRadio::configureUsing(fn (Radio $radio) => $radio\n    ->inlineLabel(fn (): bool => $radio->isInline()));
```

--------------------------------

### Add date tooltips to Filament table columns

Source: https://filamentphp.com/docs/4.x/tables/columns/text

Use dateTooltip(), dateTimeTooltip(), timeTooltip(), isoDateTooltip(), isoDateTimeTooltip(), isoTimeTooltip(), or sinceTooltip() methods to display formatted dates in tooltips. Provides additional date information without cluttering the main column display.

```PHP
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->since()
    ->dateTooltip() // Accepts a custom PHP date formatting string

TextColumn::make('created_at')
    ->since()
    ->dateTimeTooltip() // Accepts a custom PHP date formatting string

TextColumn::make('created_at')
    ->since()
    ->timeTooltip() // Accepts a custom PHP date formatting string

TextColumn::make('created_at')
    ->since()
    ->isoDateTooltip() // Accepts a custom Carbon macro format string

TextColumn::make('created_at')
    ->since()
    ->isoDateTimeTooltip() // Accepts a custom Carbon macro format string

TextColumn::make('created_at')
    ->since()
    ->isoTimeTooltip() // Accepts a custom Carbon macro format string

TextColumn::make('created_at')
    ->dateTime()
    ->sinceTooltip()
```

--------------------------------

### Configure a Filament Markdown Editor Component

Source: https://filamentphp.com/docs/4.x/forms/markdown-editor

This code snippet demonstrates how to instantiate and configure a basic Markdown editor component in Filament Forms. It uses `MarkdownEditor::make('content')` to create an editor instance linked to a 'content' field.

```PHP
use Filament\Forms\Components\MarkdownEditor;

MarkdownEditor::make('content')
```

--------------------------------

### Insert Schema Component into Field Slot

Source: https://filamentphp.com/docs/4.x/forms

Embed a schema component like Text or Icon into a field slot for styled content rendering. This example uses a Text component with bold font weight below a TextInput field.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

TextInput::make('name')
    ->belowContent(Text::make('This is the user\'s full name.')->weight(FontWeight::Bold));
```

--------------------------------

### Configure MorphToSelect with types in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/select

This code snippet demonstrates the basic setup of a `MorphToSelect` component in FilamentPHP. It specifies the polymorphic types (e.g., `Product`, `Post`) that the field can relate to and defines the `titleAttribute` for displaying options from each type.

```php
use Filament\Forms\Components\MorphToSelect;

MorphToSelect::make('commentable')
    ->types([
        MorphToSelect\Type::make(Product::class)
            ->titleAttribute('name'),
        MorphToSelect\Type::make(Post::class)
            ->titleAttribute('title'),
    ])
```

--------------------------------

### Enable File Reordering in Filament FileUpload

Source: https://filamentphp.com/docs/4.x/forms/file-upload

Allows users to re-order uploaded files by enabling the `reorderable()` method on a FileUpload component. This basic example shows how to make multiple file uploads reorderable.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->reorderable()
```

--------------------------------

### Get Notification ID - PHP

Source: https://filamentphp.com/docs/4.x/notifications

Retrieve the unique ID of a sent notification using the getId() method. This ID can be used to close the notification on demand by dispatching a close-notification browser event.

```php
use Filament\Notifications\Notification;

$notification = Notification::make()
    ->title('Hello')
    ->persistent()
    ->send()

$notificationId = $notification->getId()
```

--------------------------------

### Execute esbuild Compilation via Node

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Command to run the esbuild compilation script, which compiles the TipTap extension from the source file to the distribution directory.

```bash
node bin/build.js
```

--------------------------------

### Define Custom Export Connection in Filament PHP Exporter

Source: https://filamentphp.com/docs/4.x/actions/export

This example shows how to specify a custom queue connection for export jobs by overriding the `getJobConnection()` method in your exporter class. This is useful for utilizing different queue drivers or configurations for export processes, such as 'sqs' for AWS SQS.

```php
public function getJobConnection(): ?string
{
    return 'sqs';
}
```

--------------------------------

### Basic Modal Component Setup - Blade

Source: https://filamentphp.com/docs/4.x/components/modal

Creates a simple modal dialog with a trigger button. The trigger slot renders a button that opens the modal, and the main content area contains the modal body. This is the fundamental structure for implementing modals in Filament.

```blade
<x-filament::modal>
    <x-slot name="trigger">
        <x-filament::button>
            Open modal
        </x-filament::button>
    </x-slot>

    {{-- Modal content --}}
</x-filament::modal>
```

--------------------------------

### Conditionally make Filament TextEntry copyable (PHP)

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Control the copyable behavior of a Filament `TextEntry` dynamically by passing a boolean value or a function to the `copyable()` method. This example demonstrates using a feature flag to enable or disable copying based on a condition.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('apiKey')
    ->label('API key')
    ->copyable(FeatureFlag::active())
```

--------------------------------

### Group Filament ToggleButtons for Compact Display (PHP)

Source: https://filamentphp.com/docs/4.x/forms/toggle-buttons

Demonstrates how to use the `grouped()` method on Filament's `ToggleButtons` component to arrange options compactly and horizontally. Examples include statically grouping buttons and dynamically controlling grouping based on a boolean value, such as a feature flag.

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->grouped()
```

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->grouped(FeatureFlag::active())
```

--------------------------------

### Set Default State for Empty Filament TextEntry Components in PHP

Source: https://filamentphp.com/docs/4.x/infolists

This example demonstrates how to define a fallback default value for a `TextEntry` component using the `default()` method. If the entry's primary state is `null` or empty, the specified default value ('Untitled' in this case) will be displayed instead.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->default('Untitled')
```

--------------------------------

### Set Filament Blade badge size to extra small

Source: https://filamentphp.com/docs/4.x/components/badge

Shows how to change the size of a Filament badge to 'extra small' using the `size` attribute. This example sets the text 'New' within the badge.

```blade
<x-filament::badge size="xs">
    New
</x-filament::badge>
```

--------------------------------

### Configure Grid Columns with Dynamic Function in Filament

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Calculate column count dynamically using a closure function that receives injected utilities like $get, $component, $livewire, $model, $operation, and $record. This allows responsive behavior based on runtime conditions and schema data.

```php
columns(function ($get, $component, $livewire, $model, $operation, $record) {
  // Dynamic column calculation logic
})
```

--------------------------------

### Dispatch Livewire Events from Filament Notification Actions (PHP & JavaScript)

Source: https://filamentphp.com/docs/4.x/notifications/overview

Explains how to dispatch Livewire events when a notification action is clicked. Examples include dispatching a general event, dispatching to the component itself (`dispatchSelf`), and dispatching to another specific Livewire component (`dispatchTo`), optionally with data.

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the post have been saved.')
    ->actions([
        Action::make('view')
            ->button()
            ->url(route('posts.show', $post), shouldOpenInNewTab: true),
        Action::make('undo')
            ->color('gray')
            ->dispatch('undoEditingPost', [$post->id]),

        // Additional dispatch options:
        // Action::make('undo_self')
        //     ->color('gray')
        //     ->dispatchSelf('undoEditingPost', [$post->id]),
        // Action::make('undo_to_component')
        //     ->color('gray')
        //     ->dispatchTo('another_component', 'undoEditingPost', [$post->id])
    ])
    ->send();
```

```javascript
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .actions([
        new FilamentNotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new FilamentNotificationAction('undo')
            .color('gray')
            .dispatch('undoEditingPost'),

        // Additional dispatch options:
        // new FilamentNotificationAction('undo_self')
        //     .color('gray')
        //     .dispatchSelf('undoEditingPost'),
        // new FilamentNotificationAction('undo_to_component')
        //     .color('gray')
        //     .dispatchTo('another_component', 'undoEditingPost')
    ])
    .send()
```

--------------------------------

### Register Filament Render Hook with Data (PHP)

Source: https://filamentphp.com/docs/4.x/advanced/render-hooks

This example shows how to register a Filament render hook that can receive an `array $data` parameter. The data is passed to the rendering function, allowing the hook to display dynamic content based on information provided at the time of the hook's execution.

```php
use Filament\Support\Facades\FilamentView;
use Filament\Tables\View\TablesRenderHook;

FilamentView::registerRenderHook(
    TablesRenderHook::FILTER_INDICATORS,
    fn (array $data): View => view('filter-indicators', ['indicators' => $data['filterIndicators']]),
);
```

--------------------------------

### Register CSS assets in Filament service provider

Source: https://filamentphp.com/docs/4.x/plugins/building-a-standalone-plugin

Set up the HeadingsServiceProvider to register compiled stylesheets with Filament's Asset Manager using the loadedOnRequest() method. This ensures stylesheets load only when needed, optimizing performance for the Filament panel.

```php
namespace Awcodes\Headings;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HeadingsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'headings';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('headings', __DIR__ . '/../resources/dist/headings.css')->loadedOnRequest(),
        ], 'awcodes/headings');
    }
}
```

--------------------------------

### Format Number with Custom Locale in Filament Table Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

This example demonstrates how to customize the locale used for number formatting by passing the `locale` argument to the `numeric()` method. An `Average` summarizer on a `rating` `TextColumn` is set to format numbers according to the 'nl' (Dutch) locale.

```php
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('rating')
    ->summarize(Average::make()->numeric(
        locale: 'nl',
    ))
```

--------------------------------

### Test Filament Action Visibility (PHP)

Source: https://filamentphp.com/docs/4.x/testing/testing-actions

This snippet demonstrates how to assert the visibility state of actions using `assertActionHidden()` and `assertActionVisible()`. It verifies that certain actions are hidden or visible based on the component's logic, for example, 'send' being hidden and 'print' being visible.

```php
use function Pest\Livewire\livewire;

it('can only print invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionHidden('send')
        ->assertActionVisible('print');
});
```

--------------------------------

### Override Filament Export File Disk in Exporter Class

Source: https://filamentphp.com/docs/4.x/actions/export

This example illustrates how to define the storage disk directly within an exporter class. By overriding the `getFileDisk()` method, the exporter can programmatically return the desired disk name, such as 's3', for its exports.

```php
public function getFileDisk(): string
{
    return 's3';
}
```

--------------------------------

### Get Notification ID for Closing - PHP

Source: https://filamentphp.com/docs/4.x/notifications/overview

Retrieve the unique ID of a persistent notification using the getId() method. This ID can be used later to close the notification programmatically via browser events.

```php
use Filament\Notifications\Notification;

$notification = Notification::make()
    ->title('Hello')
    ->persistent()
    ->send();

$notificationId = $notification->getId();
```

--------------------------------

### Customize Badge Color on Filament Link Component (Blade)

Source: https://filamentphp.com/docs/4.x/components/link

Shows how to change the color of a badge attached to a Filament Link component by using the `badge-color` attribute. This example sets the badge color to 'danger'.

```blade
<x-filament::link badge-color="danger">
    Mark notifications as read

    <x-slot name="badge">
        3
    </x-slot>
</x-filament::link>
```

--------------------------------

### Pass Data to Filament Widgets from Page

Source: https://filamentphp.com/docs/4.x/navigation/custom-pages

This example illustrates how to pass an associative array of data from a Filament page to its widgets. The data is made available to widgets by returning it from the `getWidgetData()` method, allowing widgets to access contextual information from the page.

```PHP
public function getWidgetData(): array
{
    return [
        'stats' => [
            'total' => 100,
        ],
    ];
}
```

--------------------------------

### Set TableColumn Header Alignment in PHP

Source: https://filamentphp.com/docs/4.x/forms/repeater

Adjusts the alignment of column headers using the Alignment enum with options for Start, Center, or End positioning. Improves visual organization and data hierarchy in table layouts.

```php
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Support\Enums\Alignment;

TableColumn::make('Name')
    ->alignment(Alignment::Start)
```

--------------------------------

### Customize FilamentPHP Sidebar and Collapsed Widths

Source: https://filamentphp.com/docs/4.x/navigation/overview

This snippet shows how to customize the width of the FilamentPHP sidebar using the `sidebarWidth()` method. It also includes an example of customizing the width of the collapsed sidebar icons with `collapsedSidebarWidth()` when `sidebarCollapsibleOnDesktop()` is enabled.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarWidth('40rem');
}
```

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarCollapsibleOnDesktop()
        ->collapsedSidebarWidth('9rem');
}
```

--------------------------------

### Make Filament Collapsible Panel Expanded by Default

Source: https://filamentphp.com/docs/4.x/tables/layout

This example illustrates how to configure a `Panel` component to be expanded by default within a Filament table. By chaining the `collapsed(false)` method, the content inside the panel will be visible to the user immediately upon loading the table.

```php
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;

Panel::make([
    Split::make([
        TextColumn::make('phone')
            ->icon('heroicon-m-phone'),
        TextColumn::make('email')
            ->icon('heroicon-m-envelope'),
    ])->from('md'),
])->collapsed(false)
```

--------------------------------

### Create Flex layout with responsive sections in Filament

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Creates a flexible layout using the Flex component with two sections that respond to breakpoints. The first section grows to fill available space while the second maintains fixed width. The from() method controls the Tailwind breakpoint (md, lg, xl, etc.) at which the layout becomes horizontal.

```php
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;

Flex::make([
    Section::make([
        TextInput::make('title'),
        Textarea::make('content'),
    ]),
    Section::make([
        Toggle::make('is_published'),
        Toggle::make('is_featured'),
    ])->grow(false),
])->from('md')
```

--------------------------------

### Authenticate User in Laravel TestCase for Filament Tests

Source: https://filamentphp.com/docs/4.x/testing/testing-resources

This snippet demonstrates how to authenticate a user in a Laravel `TestCase` by calling `actingAs()` within the `setUp()` method. This ensures a user is logged in for all tests extending this `TestCase`, granting access to protected Filament routes and components.

```php
use App\Models\User;

protected function setUp(): void
{
    parent::setUp();

    $this->actingAs(User::factory()->create());
}
```

--------------------------------

### Access Eloquent Model Attributes for Filament TextEntry State in PHP

Source: https://filamentphp.com/docs/4.x/infolists

These examples illustrate how Filament Infolist `TextEntry` components automatically display data from an Eloquent record. The first shows accessing a direct attribute, while the second demonstrates fetching an attribute from a related model using dot notation.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
```

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('author.name')
```

--------------------------------

### Create a Headerless Filament Schema Section (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/sections

This example shows how to create a Filament Schema Section that acts as a simple card wrapper for components without a visible header. By calling `Section::make()` without arguments, it omits the title and description, providing a clean visual grouping for schema elements.

```php
use Filament\Schemas\Components\Section;

Section::make()
    ->schema([
        // ...
    ])
```

--------------------------------

### Search Eloquent relationship across multiple columns

Source: https://filamentphp.com/docs/4.x/forms/select

The `searchable()` method accepts an array of column names to search across multiple columns instead of just the title column. This example enables searching an author relationship by both name and email columns, improving search flexibility and user experience.

```PHP
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->searchable(['name', 'email'])
```

--------------------------------

### Enable Opening Files in New Tab with Filament FileUpload

Source: https://filamentphp.com/docs/4.x/forms/file-upload

Adds a button to open each file in a new tab using the `openable()` method on a FileUpload component. This basic example demonstrates simple file opening functionality.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->openable()
```

--------------------------------

### Trigger Filament action from JavaScript with $wire utility

Source: https://filamentphp.com/docs/4.x/components/action

Demonstrates triggering a Filament action directly from JavaScript using Livewire's `$wire` utility object. This approach allows conditional action mounting based on client-side logic, passing arguments in the same format as the `wire:click` method.

```javascript
$wire.mountAction('test', { id: 12345 })
```

--------------------------------

### Create a Filament Action with Form Input for Email in PHP

Source: https://filamentphp.com/docs/4.x/actions/overview

This PHP example illustrates a Filament Action that gathers user input through a modal form before proceeding. It uses `TextInput` and `RichEditor` components to collect email subject and body, respectively. The collected data is then used within the `action` callback to send an email.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Mail;

Action::make('sendEmail')
    ->schema([
        TextInput::make('subject')->required(),
        RichEditor::make('body')->required(),
    ])
    ->action(function (array $data) {
        Mail::to($this->client)
            ->send(new GenericEmail(
                subject: $data['subject'],
                body: $data['body'],
            ));
    })
```

--------------------------------

### Conditionally Open URL in New Tab using Feature Flag in Filament

Source: https://filamentphp.com/docs/4.x/infolists

Use a boolean or closure argument with `openUrlInNewTab()` to conditionally determine whether URLs should open in a new tab. This example demonstrates using a feature flag to control the behavior dynamically.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
    ->openUrlInNewTab(FeatureFlag::active())
```

--------------------------------

### Conditionally Display Filament Text Component as a Badge

Source: https://filamentphp.com/docs/4.x/schemas/primes

This example shows how to conditionally apply badge styling to a `Text` component. By passing a boolean value or a dynamic condition to the `badge()` method, you can control whether the text appears as a badge based on specific logic, such as a feature flag.

```php
use FilamentSchemasComponentsText;

Text::make('Warning')
    ->color('warning')
    ->badge(FeatureFlag::active())
```

--------------------------------

### Change the color of a Filament Blade Dropdown item

Source: https://filamentphp.com/docs/4.x/components/dropdown

This example illustrates how to apply different color themes to dropdown list items using the 'color' attribute. Available colors include danger, info, primary, success, and warning.

```blade
<x-filament::dropdown.list.item color="danger">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="info">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="primary">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="success">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="warning">
    Edit
</x-filament::dropdown.list.item>
```

--------------------------------

### Example Output of HasLabel Enum Options

Source: https://filamentphp.com/docs/4.x/advanced/enums

This PHP array shows the structure of options generated when an enum implementing `HasLabel` is used with Filament components. The keys are the enum's raw values, and the values are the human-readable labels provided by the `getLabel()` method.

```php
[
    'draft' => 'Draft',
    'reviewing' => 'Reviewing',
    'published' => 'Published',
    'rejected' => 'Rejected',
]
```

--------------------------------

### Access Related Component State in Blade View

Source: https://filamentphp.com/docs/4.x/infolists/custom-entries

Retrieve the state of another component in the schema using the $get() function with the component name. Useful for displaying related field values within the entry template.

```blade
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    {{ $get('email') }}
</x-dynamic-component>
```

--------------------------------

### Disable All Behaviors for FilamentPHP Slider

Source: https://filamentphp.com/docs/4.x/forms/slider

This example illustrates how to completely disable all default and custom behaviors for a FilamentPHP slider component. By passing `null` to the `behavior()` method, all interactive functionalities, including the default `Behavior::Tap`, are turned off.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 0, maxValue: 100)
    ->behavior(null)
```

--------------------------------

### Fill Form with Data in Pest Livewire Test

Source: https://filamentphp.com/docs/4.x/testing/testing-schemas

Demonstrates how to populate a form with test data using the fillForm() method in Pest Livewire tests. Supports multiple schemas on a single component by specifying the form name as a second parameter.

```php
use function Pest\Livewire\livewire;

livewire(CreatePost::class)
    ->fillForm([
        'title' => fake()->sentence(),
        // ...
    ]);
```

--------------------------------

### Customize Select Field Action with Modal Width in Filament

Source: https://filamentphp.com/docs/4.x/forms/select

Example demonstrating how to customize a Select field's create option action by modifying the modal width. Uses the createOptionAction() method with a callback function that receives the $action object for customization.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->createOptionAction(
        fn (Action $action) => $action->modalWidth('3xl'),
    )
```

--------------------------------

### Initialize FilamentPHP Custom Component Properties via Constructor (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/custom-components

This PHP code demonstrates how to accept and initialize a custom component's property, like `heading`, directly in its constructor and `make()` static method. This approach allows for mandatory or default configuration values to be passed at component creation, while still leveraging the setter method internally.

```php
use Closure;
use Filament\Schemas\Components\Component;

class Chart extends Component
{
    protected string $view = 'filament.schemas.components.chart';
    
    protected string | Closure | null $heading = null;

    public function __construct(string | Closure | null $heading = null)
    {
        $this->heading($heading);
    }

    public static function make(string | Closure | null $heading = null): static
    {
        return app(static::class, ['heading' => $heading]);
    }
    
    public function heading(string | Closure | null $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->evaluate($this->heading);
    }
}
```

--------------------------------

### Implement RichContentPlugin Interface - PHP

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Creates a custom rich editor plugin by implementing the RichContentPlugin interface. The class provides methods to register TipTap PHP extensions, JavaScript assets, editor toolbar tools, and associated actions. This example demonstrates a highlight extension with both simple and custom color variants.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Icons\Heroicon;
use Tiptap\Core\Extension;
use Tiptap\Marks\Highlight;

class HighlightRichContentPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [
            app(Highlight::class, [
                'options' => ['multicolor' => true],
            ]),
        ];
    }

    public function getTipTapJsExtensions(): array
    {
        return [
            FilamentAsset::getScriptSrc('rich-content-plugins/highlight'),
        ];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('highlight')
                ->jsHandler('$getEditor()?.chain().focus().toggleHighlight().run()')
                ->icon(Heroicon::CursorArrowRays),
            RichEditorTool::make('highlightWithCustomColor')
                ->action(arguments: '{ color: $getEditor().getAttributes(\'highlight\')?.[\"data-color\"] }')
                ->icon(Heroicon::CursorArrowRipple),
        ];
    }

    public function getEditorActions(): array
    {
        return [
            Action::make('highlightWithCustomColor')
                ->modalWidth(Width::Large)
                ->fillForm(fn (array $arguments): array => [
                    'color' => $arguments['color'] ?? null,
                ])
                ->schema([
                    ColorPicker::make('color'),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $component->runCommands(
                        [
                            EditorCommand::make(
                                'toggleHighlight',
                                arguments: [[
                                    'color' => $data['color'],
                                ]],
                            ),
                        ],
                        editorSelection: $arguments['editorSelection'],
                    );
                }),
        ];
    }
}
```

--------------------------------

### Allow Free Cropping alongside Fixed Aspect Ratios in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/file-upload

This example shows how to offer 'free cropping' functionality in addition to specific aspect ratios within the FilamentPHP image editor. By including `null` in the array passed to `imageEditorAspectRatios()`, users can choose to crop without a constrained ratio. Like other image editor configurations, this method also supports dynamic calculation via a function with utility injection.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatios([
        null,
        '16:9',
        '4:3',
        '1:1',
    ])
```

--------------------------------

### Define Static Column Order for FilamentPHP Grid Components

Source: https://filamentphp.com/docs/4.x/schemas/layouts

This example demonstrates how to use integer values with the `columnOrder()` method on `TextInput` components within a FilamentPHP `Grid`. It allows developers to visually reorder grid items independently of their markup position, with lower integer values appearing earlier in the visual flow.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

Grid::make()
    ->columns(3)
    ->schema([
        TextInput::make('first')
            ->columnOrder(3), // This will appear last
        TextInput::make('second')
            ->columnOrder(1), // This will appear first
        TextInput::make('third')
            ->columnOrder(2), // This will appear second
    ])
```

--------------------------------

### Create Basic Unordered List in Filament Schema (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/primes

Implement an `UnorderedList` component in a Filament schema by passing an array of plain text strings to its `make()` method. The `make()` method also supports a function for dynamically generating list items, allowing for flexible data presentation.

```php
use Filament\Schemas\Components\UnorderedList;

UnorderedList::make([
    'Tables',
    'Schemas',
    'Actions',
    'Notifications',
])
```

--------------------------------

### Set Tab Badge in FilamentPHP

Source: https://filamentphp.com/docs/4.x/schemas/tabs

This example illustrates how to add a numerical badge to a FilamentPHP tab using the `badge()` method. The badge can display a static value, such as `5`, and can also be dynamically calculated using a function with utility injection.

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Notifications')
            ->badge(5)
            ->schema([
                // ...
            ]),
        // ...
    ])
```

--------------------------------

### Set Static Label for Filament Table Filter

Source: https://filamentphp.com/docs/4.x/tables/filters/overview

Customize the displayed label of a Filament table filter using the `label()` method. This example sets the label for the 'is_featured' filter to 'Featured', overriding its default name-based label.

```php
use Filament\Tables\Filters\Filter;

Filter::make('is_featured')
    ->label('Featured')
```

--------------------------------

### Conditionally Hide FilamentPHP Navigation Items

Source: https://filamentphp.com/docs/4.x/navigation/overview

This example shows how to make a navigation item visible or hidden based on a condition using the `visible()` or `hidden()` methods. It typically relies on user permissions or other runtime checks, such as `auth()->user()->can('view-analytics')`.

```php
use Filament\Navigation\NavigationItem;

NavigationItem::make('Analytics')
    ->visible(fn(): bool => auth()->user()->can('view-analytics'))
    // or
    ->hidden(fn(): bool => ! auth()->user()->can('view-analytics')),
```

--------------------------------

### Include Null Values in Filament Table Range Summarizer

Source: https://filamentphp.com/docs/4.x/tables/summaries

By default, Filament table range summarizers exclude null values. This example demonstrates how to configure a `Range` summarizer to include null values by using the `excludeNull(false)` method on a `TextColumn`.

```php
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('sku')
    ->summarize(Range::make()->excludeNull(false))
```

--------------------------------

### Create Form with Data Handling in Filament Action Modal (PHP)

Source: https://filamentphp.com/docs/4.x/actions/modals

This example illustrates how to define a form within a Filament action's modal using a `Select` component. It shows how the submitted form data, available in the `$data` array, can be accessed within the `action()` closure to perform operations like associating an author with a post.

```php
use AppModelsPost;
use AppModelsUser;
use FilamentActionsAction;
use FilamentFormsComponentsSelect;

Action::make('updateAuthor')
    ->schema([
        Select::make('authorId')
            ->label('Author')
            ->options(User::query()->pluck('name', 'id'))
            ->required(),
    ])
    ->action(function (array $data, Post $record): void {
        $record->author()->associate($data['authorId']);
        $record->save();
    })
```

--------------------------------

### Group Navigation Items in Filament

Source: https://filamentphp.com/docs/4.x/navigation

Organize navigation items into groups using the $navigationGroup property on resources and custom pages. All items with the same group value are displayed together under a shared group label. Ungrouped items appear at the start of the navigation.

```php
use UnitEnum;

protected static string | UnitEnum | null $navigationGroup = 'Settings';
```

--------------------------------

### Conditionally Render Markdown in Filament TextEntry

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This snippet shows how to conditionally render Markdown in a Filament `TextEntry` by passing a boolean value to the `markdown()` method. This allows for dynamic control over Markdown rendering, for example, based on a feature flag or other logic.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->markdown(FeatureFlag::active())
```

--------------------------------

### Implement getDefaultName() method in PHP

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Override the getDefaultName() method instead of make() to provide a default name value when none is specified. This approach is recommended for cleaner code maintenance and avoids brittleness from future constructor parameter changes.

```php
public static function getDefaultName(): ?string
{
    return 'default';
}
```

--------------------------------

### Retrieve Current Page Records from Filament Widget

Source: https://filamentphp.com/docs/4.x/resources/widgets

Alternatively, to get a collection of the records currently displayed on the page's table, you can use the `$this->getPageTableRecords()` method within your widget. This provides access to the paginated records.

```php
use Filament\Widgets\StatsOverviewWidget\Stat;

Stat::make('Total Products', $this->getPageTableRecords()->count()),
```

--------------------------------

### Register Filament Panel Render Hook

Source: https://filamentphp.com/docs/4.x/panel-configuration

This PHP code demonstrates how to register a panel-specific render hook in Filament. Using the `renderHook()` method, you can inject Blade content at specific points in the panel's views, such as integrating Livewire components at the start of the body.

```php
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->renderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => Blade::render('@livewire(\'livewire-ui-modal\')'),
        );
}
```

--------------------------------

### Filament Infolists: Arrange RepeatableEntry Items in a Grid

Source: https://filamentphp.com/docs/4.x/infolists/repeatable-entry

This code shows how to arrange items within a `RepeatableEntry` component into a grid layout using the `grid()` method. The method accepts a static integer for the number of columns or a callable function for dynamic calculation based on various injected utilities, allowing for responsive design.

```php
use Filament\Infolists\Components\RepeatableEntry;

RepeatableEntry::make('comments')
    ->schema([
        // ...
    ])
    ->grid(2)
```

--------------------------------

### Set Filament Table Groups Collapsed by Default (PHP)

Source: https://filamentphp.com/docs/4.x/tables/grouping

To have all collapsible groups in a Filament table start in a collapsed state when the page loads, use the `collapsedGroupsByDefault()` method on the table instance. This provides a more compact initial view for the user.

```php
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->groups([
            Group::make('author.name')
                ->collapsible(),
        ])
        ->collapsedGroupsByDefault();
}

```

--------------------------------

### Inject Current Entry Instance in Filament Infolist Callback

Source: https://filamentphp.com/docs/4.x/infolists

This code illustrates how to access the current `Entry` component instance itself within a callback function. By defining an `Entry $component` parameter, you can interact directly with the entry being configured, potentially accessing its properties or calling its methods.

```php
use FilamentInfolistsComponentsEntry;

function (Entry $component) {
    // ...
}
```

--------------------------------

### Override Filament table pagination on relation manager

Source: https://filamentphp.com/docs/4.x/resources/managing-relationships

Example of overriding a shared table configuration on a FilamentPHP relation manager to disable pagination. This demonstrates how to modify settings inherited from the resource without affecting the resource itself.

```php
use App\Filament\Resources\Blog\Posts\PostResource;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return PostResource::table($table)
        ->paginated(false);
}
```

--------------------------------

### Validate Filament Builder item count with minItems() and maxItems()

Source: https://filamentphp.com/docs/4.x/forms/builder

This example shows how to enforce minimum and maximum item counts for an entire Filament Builder component. The `minItems()` and `maxItems()` methods, applied directly to the `Builder` component, can take either static integer values or dynamic functions for flexible validation. These methods help ensure the builder adheres to specific content quantity requirements.

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->minItems(1)
    ->maxItems(5)
```

--------------------------------

### Disable Seconds Input in Filament DateTimePicker

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

This example illustrates how to disable the seconds input field for a Filament `DateTimePicker` component. By calling the `seconds(false)` method, the time selection interface will only allow users to pick hours and minutes, simplifying input.

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->seconds(false);
```

--------------------------------

### Customize Filament TextEntry date formats with specific PHP tokens

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Shows how to apply custom format strings to the `date()`, `dateTime()`, and `time()` methods. By passing PHP date formatting tokens, developers can precisely control the output format of date and time values.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->date('M j, Y')
    
TextEntry::make('created_at')
    ->dateTime('M j, Y H:i:s')
    
TextEntry::make('created_at')
    ->time('H:i:s')
```

--------------------------------

### Allow free navigation between steps in a Filament PHP wizard

Source: https://filamentphp.com/docs/4.x/resources/creating-records

This method overrides the default behavior of a Filament PHP wizard, enabling users to freely navigate between steps without completing previous ones. By returning `true`, it marks all wizard steps as skippable, offering greater flexibility in the record creation workflow.

```php
public function hasSkippableSteps(): bool
{
    return true;
}
```

--------------------------------

### Simple Repeater Data Structure Example

Source: https://filamentphp.com/docs/4.x/forms/repeater

Demonstrates the flat array data structure used by simple repeaters. Unlike nested repeaters, simple repeaters store data as a single-level array of values without wrapping each item in an object.

```php
[
    'invitations' => [
        'dan@filamentphp.com',
        'ryan@filamentphp.com',
    ],
]
```

--------------------------------

### Apply FilamentPHP Text Input Min/Max Length Validation

Source: https://filamentphp.com/docs/4.x/forms/text-input

Illustrates how to enforce minimum and maximum character length constraints on a FilamentPHP `TextInput` using the `minLength()` and `maxLength()` methods. These methods apply both frontend and backend validation to ensure the input meets specified length requirements, and can accept dynamic values via functions for flexible validation rules.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->minLength(2)
    ->maxLength(255)
```

--------------------------------

### Override Filament Button Border Radius with Tailwind CSS

Source: https://filamentphp.com/docs/4.x/introduction/overview

Demonstrates how to customize the default Filament button styling by overriding the rounded-lg utility class with rounded-sm. This example shows the default Filament button CSS and how to override it in your own stylesheet to decrease border radius while preserving other styling properties.

```css
.fi-btn {
    @apply rounded-lg px-3 py-2 text-sm font-medium outline-none;
}
```

```css
.fi-btn {
    @apply rounded-sm;
}
```

--------------------------------

### Set Dynamic (Translation) Label for Filament Table Filter

Source: https://filamentphp.com/docs/4.x/tables/filters/overview

This example illustrates how to use a translation string for a filter's label, enabling localization. The `label()` method accepts a translation key, which Filament resolves dynamically, making it useful for internationalization.

```php
use Filament\Tables\Filters\Filter;

Filter::make('is_featured')
    ->label(__('filters.is_featured'))
```

--------------------------------

### Add Static Content After Entry Label - Filament PHP

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates how to insert static content after a TextEntry's label using the afterLabel() method. You can pass text, schema components, icons, or action groups as content. This example shows adding an icon and text content aligned to the end of the container by default.

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->afterLabel([
        Icon::make(Heroicon::Star),
        'This is the content after the entry\'s label'
    ])
```

--------------------------------

### Test Filament table filter configuration (Pest)

Source: https://filamentphp.com/docs/4.x/testing/testing-tables

This test extends filter existence checks by passing a callback to `assertTableFilterExists()`, allowing for assertions on the filter's configuration, such as its label or type. This is useful for verifying complex filter setups. Dependencies include `Pest\Livewire\livewire` and `Filament\Tables\Filters\SelectFilter`.

```php
use function Pest\Livewire\livewire;
use Filament\Tables\Filters\SelectFilter;

it('has an author filter', function () {
    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableFilterExists('author', function (SelectFilter $column): bool {
            return $column->getLabel() === 'Select author';
        });
});
```

--------------------------------

### Set Exact Length on Textarea Component

Source: https://filamentphp.com/docs/4.x/forms/textarea

Shows how to use the length() method to enforce an exact character count for textarea input. This method adds both frontend and backend validation. The example constrains the question field to exactly 100 characters.

```php
use Filament\Forms\Components\Textarea;

Textarea::make('question')
    ->length(100)
```

--------------------------------

### Test FilamentPHP Schema Component Existence with Pest

Source: https://filamentphp.com/docs/4.x/testing/testing-schemas

This snippet shows how to assert the presence or absence of schema components in FilamentPHP tests using Pest. assertSchemaComponentExists() checks for a component by its unique key(), while assertSchemaComponentDoesNotExist() verifies its absence. It includes an example of defining a component with a key.

```php
use Filament\Schemas\Components\Section;

Section::make('Comments')
    ->key('comments-section')
    ->schema([
        //
    ])
```

```php
use function Pest\Livewire\livewire;

test('comments section exists', function () {
    livewire(EditPost::class)
        ->assertSchemaComponentExists('comments-section');
});
```

```php
use function Pest\Livewire\livewire;

it('does not have a conditional component', function () {
    livewire(CreatePost::class)
        ->assertSchemaComponentDoesNotExist('no-such-section');
});
```

--------------------------------

### Inject Current Column Instance in Filament PHP Closure

Source: https://filamentphp.com/docs/4.x/tables/columns/overview

Details how to access the current column instance itself within a Filament PHP table column closure. Define a `$component` parameter type-hinted with `Filament\Tables\Columns\Column` to get its properties and methods.

```php
use Filament\Tables\Columns\Column;

function (Column $component) {
    // ...
}
```

--------------------------------

### Configure Filament TagsInput Split Keys with PHP

Source: https://filamentphp.com/docs/4.x/forms/tags-input

This snippet demonstrates how to configure the `TagsInput` component in Filament PHP to define custom keys for creating new tags. By default, 'Enter' creates a new tag, but this example adds 'Tab' and space (' ') as additional split keys. The `splitKeys()` method accepts an array of strings or a dynamic function.

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->splitKeys(['Tab', ' '])
```

--------------------------------

### Format export column state using options in Filament

Source: https://filamentphp.com/docs/4.x/actions/export

Access export options inside the formatStateUsing() closure of an ExportColumn to dynamically format column values based on user-selected or configured options. The $options parameter contains all export options passed to the exporter. This example limits description text length based on the descriptionLimit option.

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('description')
    ->formatStateUsing(function (string $state, array $options): string {
        return (string) str($state)->limit($options['descriptionLimit'] ?? 100);
    })
```

--------------------------------

### Validate SelectColumn Input with Laravel Rules

Source: https://filamentphp.com/docs/4.x/tables/columns/select

Applies Laravel validation rules to SelectColumn input by passing an array of validation rules. The example demonstrates using the 'required' rule to ensure a valid status selection is made before submission.

```php
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
    ->rules(['required'])
```

--------------------------------

### Use FilamentPHP Component Class in Schema Configuration

Source: https://filamentphp.com/docs/4.x/resources/code-quality-tips

This PHP example demonstrates how to integrate a dedicated `CustomerNameInput` component class into a FilamentPHP schema's `configure()` method. By calling its static `make()` method, the pre-configured component is effortlessly added, simplifying the main schema logic. It depends on the `CustomerNameInput` class and `Filament\Schemas\Schema`.

```php
use App\Filament\Resources\Customers\Schemas\Components\CustomerNameInput;
use Filament\Schemas\Schema;

public static function configure(Schema $schema): Schema
{
    return $schema
        ->components([
            CustomerNameInput::make(),
            // ...
        ]);
}

```

--------------------------------

### Combine Container and Fallback Breakpoints in Form Components

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Implements both container breakpoints and !@ fallback breakpoints across columnSpan() and columnOrder() methods for comprehensive browser compatibility. Ensures responsive layout behavior in all browsers, with fallback for those without container query support.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

Grid::make()
    ->gridContainer()
    ->columns([
        '@md' => 3,
        '@xl' => 4,
        '!@md' => 2,
        '!@xl' => 3,
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                '@md' => 2,
                '@xl' => 3,
                '!@md' => 2,
                '!@xl' => 2,
            ])
            ->columnOrder([
                'default' => 2,
                '@xl' => 1,
                '!@xl' => 1,
            ]),
        TextInput::make('email')
            ->columnOrder([
                'default' => 1,
                '@xl' => 2,
                '!@xl' => 2,
            ]),
        // ...
    ])
```

--------------------------------

### Customize Builder Action with Label in Filament

Source: https://filamentphp.com/docs/4.x/forms/builder

Demonstrates how to customize a builder action by passing a callback function to an action registration method. The example shows customizing the collapseAllAction() by modifying the action label. The $action parameter provides access to the action object for customization.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->collapseAllAction(
        fn (Action $action) => $action->label('Collapse all content'),
    )
```

--------------------------------

### Add Tooltip to Text Component in Filament PHP

Source: https://filamentphp.com/docs/4.x/schemas/primes

This example shows how to add a static tooltip to a `Text` component in Filament PHP using the `tooltip()` method. The tooltip text "Your secret recovery code" will appear when hovering over the component.

```php
use Filament\Schemas\Components\Text;

Text::make('28o.-AK%D~xh*.:[4"3)zPiC')
    ->tooltip('Your secret recovery code')
```

--------------------------------

### Create Plugin Class with Setter and Getter Methods

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Implement a Filament plugin class with configurable options using setter and getter methods. The setter stores preferences on a property and returns the plugin instance for fluent chaining, while the getter retrieves the stored preference. The register() method uses these preferences to conditionally register resources.

```php
use DanHarrin\FilamentBlog\Resources\AuthorResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class BlogPlugin implements Plugin
{
    protected bool $hasAuthorResource = false;
    
    public function authorResource(bool $condition = true): static
    {
        // This is the setter method, where the user's preference is
        // stored in a property on the plugin object.
        $this->hasAuthorResource = $condition;
    
        // The plugin object is returned from the setter method to
        // allow fluent chaining of configuration options.
        return $this;
    }
    
    public function hasAuthorResource(): bool
    {
        // This is the getter method, where the user's preference
        // is retrieved from the plugin property.
        return $this->hasAuthorResource;
    }
    
    public function register(Panel $panel): void
    {
        // Since the `register()` method is executed after the user
        // configures the plugin, you can access any of their
        // preferences inside it.
        if ($this->hasAuthorResource()) {
            // Here, we only register the author resource on the
            // panel if the user has requested it.
            $panel->resources([
                AuthorResource::class,
            ]);
        }
    }
    
    // ...
}
```

--------------------------------

### Retrieve Enabled Radio Options for Validation in Filament Forms

Source: https://filamentphp.com/docs/4.x/forms/radio

Get the list of enabled (non-disabled) radio options using `getEnabledOptions()` for validation purposes. Returns an array of options that have not been disabled by `disableOptionWhen()`, useful for constraining validation rules.

```PHP
use Filament\Forms\Components\Radio;

Radio::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'published')
    ->in(fn (Radio $component): array => array_keys($component->getEnabledOptions()))
```

--------------------------------

### Integrate Laravel Scout Search with FilamentPHP Tables

Source: https://filamentphp.com/docs/4.x/tables

These examples show how to integrate Laravel Scout for powerful search capabilities within FilamentPHP tables. The `searchUsing()` method filters the query based on Scout results, while `searchable()` can be applied to the entire table to enable global search using Scout's configured columns.

```php
use App\Models\Post;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->searchUsing(fn (Builder $query, string $search) => $query->whereKey(Post::search($search)->keys()));
}
```

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->searchable();
}
```

--------------------------------

### Generate Filament resource with auto-created forms and tables

Source: https://filamentphp.com/docs/4.x/resources/overview

This command streamlines resource creation by automatically generating the form and table definitions based on your model's database columns. It's a time-saving option for rapid development.

```bash
php artisan make:filament-resource Customer --generate
```

--------------------------------

### Add interactive actions to FilamentPHP notifications (PHP, JS)

Source: https://filamentphp.com/docs/4.x/notifications

This code illustrates how to embed interactive buttons, called actions, into FilamentPHP notifications. Actions appear below the body text and can be configured with different styles. Examples are provided for both PHP and JavaScript.

```php
use FilamentActionsAction;
use FilamentNotificationsNotification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the post have been saved.')
    ->actions([
        Action::make('view')
            ->button(),
        Action::make('undo')
            ->color('gray'),
    ])
    ->send();
```

```javascript
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .actions([
        new FilamentNotificationAction('view')
            .button(),
        new FilamentNotificationAction('undo')
            .color('gray'),
    ])
    .send()
```

--------------------------------

### Apply Inline Label to Filament Infolist Entry

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates how to display a single entry's label inline with the entry itself using the `inlineLabel()` method on a `TextEntry` component in Filament Infolists.

```php
use FilamentInfolistsComponentsTextEntry;

TextEntry::make('name')
    ->inlineLabel()
```

--------------------------------

### Injecting Laravel Container Dependencies into Filament Functions (PHP)

Source: https://filamentphp.com/docs/4.x/tables/filters/overview

This PHP example shows how to inject services from Laravel's service container, such as `Illuminate\Http\Request`, alongside Filament utilities like `Table`. This approach allows seamless integration of Laravel's core functionalities within Filament components. Dependencies are automatically resolved by the container.

```PHP
use Filament\Tables\Table;
use Illuminate\Http\Request;

function (Request $request, Table $table) {
    // ...
}
```

--------------------------------

### Optimize Filament for Production - Laravel Artisan

Source: https://filamentphp.com/docs/4.x/deployment

Run optimization command to cache Filament components and Blade Icons for improved production performance. This is a shorthand for both filament:cache-components and icons:cache commands.

```bash
php artisan filament:optimize
```

--------------------------------

### Trim whitespace on TextInput (PHP - Filament)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Shows how to trim leading and trailing whitespace for a specific TextInput using trim(). Requires Filament Forms component. Input: raw user input; Output: trimmed state before validation and persistence. Limitation: only affects the configured field unless applied globally.

```PHP
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->trim()


```

--------------------------------

### Initialize Filament form with existing data (PHP)

Source: https://filamentphp.com/docs/4.x/components/form

To pre-fill a Filament form with data from an existing record, use the `$this->form->fill()` method. This example shows populating a form with attributes from a `Post` model, ensuring the data is correctly transformed internally. Do not assign data directly to `$this->data`.

```php
use App\Models\Post;

public function mount(Post $post): void
{
    $this->form->fill($post->attributesToArray());
}
```

--------------------------------

### Chain multiple Filament actions sequentially in PHP

Source: https://filamentphp.com/docs/4.x/components/action

Demonstrates using `replaceMountedAction()` to chain multiple actions where one action transitions to another upon completion. Arguments from the first action persist and pass to the second action, enabling data continuity across requests. If the first action is canceled, the second won't open; if the second is canceled, the first has already executed.

```php
use App\Models\Post;
use Filament\Actions\Action;

public function editAction(): Action
{
    return Action::make('edit')
        ->schema([
            // ...
        ])
        // ...
        ->action(function (array $arguments) {
            $post = Post::find($arguments['post']);

            // ...

            $this->replaceMountedAction('publish', $arguments);
        });
}

public function publishAction(): Action
{
    return Action::make('publish')
        ->requiresConfirmation()
        // ...
        ->action(function (array $arguments) {
            $post = Post::find($arguments['post']);

            $post->publish();
        });
}
```

--------------------------------

### Distribute Content with Schema::between() in Entry Slot

Source: https://filamentphp.com/docs/4.x/infolists/overview

Spaces content elements apart using Schema::between(), which distributes items with space between them. Useful for separating icon/text groups from actions.

```php
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->belowContent(Schema::between([
        Flex::make([
            Icon::make(Heroicon::InformationCircle)
                ->grow(false),
            'This is the user\'s full name.',
        ]),
        Action::make('generate'),
    ]))
```

--------------------------------

### Create basic Textarea component in Filament

Source: https://filamentphp.com/docs/4.x/forms/textarea

Initializes a simple textarea field for capturing multi-line string input. This is the foundational usage of the Textarea component with the field name 'description'. No additional configuration is applied.

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
```

--------------------------------

### Dynamically Label Repeater Items in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/repeater

This example shows how to use the `itemLabel()` method to dynamically generate labels for individual items within a FilamentPHP Repeater. The label is created from the item's `name` field, which is made `live()` for real-time updates.

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

Repeater::make('members')
    ->schema([
        TextInput::make('name')
            ->required()
            ->live(onBlur: true),
        Select::make('role')
            ->options([
                'member' => 'Member',
                'administrator' => 'Administrator',
                'owner' => 'Owner',
            ])
            ->required(),
    ])
    ->columns(2)
    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
```

--------------------------------

### Customize Truncation End Indicator in FilamentPHP TextEntry

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This example shows how to customize the string appended to truncated text in a FilamentPHP `TextEntry` component. The `end` argument of the `limit()` method is used to replace the default ellipsis with a custom string like ' (more)'.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->limit(50, end: ' (more)')
```

--------------------------------

### Customize profile link in Filament user menu

Source: https://filamentphp.com/docs/4.x/navigation/user-menu

This example illustrates how to modify the default profile link within the Filament user menu. It registers an item with the `profile` array key in `userMenuItems()` and provides a closure to customize the `Action` object, such as changing its label.

```PHP
use Filament\Actions\Action;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->userMenuItems([
            'profile' => fn (Action $action) => $action->label('Edit profile'),
            // ...
        ]);
}
```

--------------------------------

### Utilize Utility Injection in Filament Table Column Methods (PHP)

Source: https://filamentphp.com/docs/4.x/tables/columns/overview

Many Filament column configuration methods, such as `placeholder()`, `badge()`, and `extraAttributes()`, accept functions as parameters. These functions allow for dynamic value generation and can receive various injected utilities (e.g., `$record`, `$column`, `$livewire`) by defining specific parameter names, offering extensive customization possibilities.

```php
use App\Models\User;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('email')
    ->placeholder(fn (User $record): string => "No email for {$record->name}")

TextColumn::make('role')
    ->badge(fn (User $record): bool => $record->role === 'admin')

TextColumn::make('name')
    ->extraAttributes(fn (User $record): array => ['class' => "{$record->getKey()}-name-column"])
```

--------------------------------

### Customize Repeater Action Label in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/repeater

This example demonstrates how to change the label of a Repeater component's action, specifically the `collapseAllAction`. It uses a closure that receives the `$action` object to set a custom label, enhancing user interface clarity.

```PHP
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->collapseAllAction(
        fn (Action $action) => $action->label('Collapse all members'),
    )
```

--------------------------------

### Apply Middleware to Authenticated Routes in Filament Panel

Source: https://filamentphp.com/docs/4.x/panel-configuration

Configure middleware classes to run on authenticated routes by passing an array to the authMiddleware() method. By default, middleware runs on page load but not on subsequent Livewire AJAX requests. Use isPersistent parameter to run middleware on every request.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->authMiddleware([
            // ...
        ]);
}
```

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->authMiddleware([
            // ...
        ], isPersistent: true);
}
```

--------------------------------

### Test FilamentPHP Table Column Range Summary with Pest

Source: https://filamentphp.com/docs/4.x/testing/testing-tables

This example illustrates how to test a column's range summarizer, which typically displays the minimum and maximum values. The `assertTableColumnSummarySet` method is used with a tuple-style `[$minimum, $maximum]` array as the expected value. This allows for validation of both the lowest and highest values within the column.

```php
use function Pest\Livewire\livewire;

it('can average values in a column', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->assertTableColumnSummarySet('rating', 'range', [$posts->min('rating'), $posts->max('rating')]);
});
```

--------------------------------

### Conditionally enable inline labels with feature flags in Filament

Source: https://filamentphp.com/docs/4.x/infolists/overview

Pass a boolean value or dynamic expression to the `inlineLabel()` method to control whether the label displays inline. This example uses a feature flag to conditionally enable inline labels based on runtime conditions.

```PHP
use Filament\Infolists\Components\TextInput;

TextInput::make('name')
    ->inlineLabel(FeatureFlag::active())
```

--------------------------------

### Create Table Repeatable Entry with Filament

Source: https://filamentphp.com/docs/4.x/infolists/repeatable-entry

Demonstrates how to create a repeatable entry in table format using RepeatableEntry::make() with table() method. Accepts an array of TableColumn objects for column definitions and schema() for the entry structure. Supports TextEntry, IconEntry, and other Filament components.

```php
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;

RepeatableEntry::make('comments')
    ->table([
        TableColumn::make('Author'),
        TableColumn::make('Title'),
        TableColumn::make('Published'),
    ])
    ->schema([
        TextEntry::make('author.name'),
        TextEntry::make('title'),
        IconEntry::make('is_published')
            ->boolean(),
    ])
```

--------------------------------

### Override Import Validation Messages (FilamentPHP)

Source: https://filamentphp.com/docs/4.x/actions/import

This example illustrates how to customize the default validation error messages for the import process. By overriding the `getValidationMessages()` method, you can return an associative array mapping validation rule keys to your custom error messages, providing more user-friendly feedback.

```php
public function getValidationMessages(): array
{
    return [
        'name.required' => 'The name column must not be empty.',
    ];
}
```

--------------------------------

### Set Minimal package.json for Filament Plugin Asset Building

Source: https://filamentphp.com/docs/4.x/plugins/building-a-panel-plugin

This JSON configuration defines a minimal `package.json` for a Filament plugin. It includes `dev` and `build` scripts that utilize `esbuild` for compiling frontend assets, ensuring efficient bundling without extraneous dependencies.

```json
{
    "private": true,
    "type": "module",
    "scripts": {
        "dev": "node bin/build.js --dev",
        "build": "node bin/build.js"
    },
    "devDependencies": {
        "esbuild": "^0.17.19"
    }
}
```

--------------------------------

### Customize MorphToSelect option labels in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/select

This example illustrates how to customize the display label for each option within a `MorphToSelect` field. By using `getOptionLabelFromRecordUsing()`, you can transform the Eloquent model record into a custom string label, providing more informative options to the user.

```php
use Filament\Forms\Components\MorphToSelect;

MorphToSelect::make('commentable')
    ->types([
        MorphToSelect\Type::make(Product::class)
            ->getOptionLabelFromRecordUsing(fn (Product $record): string => "{$record->name} - {$record->slug}"),
        MorphToSelect\Type::make(Post::class)
            ->titleAttribute('title'),
    ])
```

--------------------------------

### Add prefix and suffix text to Select field in Filament

Source: https://filamentphp.com/docs/4.x/forms/select

Use the prefix() and suffix() methods to add static text before and after a form input. These methods can accept either static strings or callback functions for dynamic calculation with injected utilities like $get, $state, and $record.

```php
use Filament\Forms\Components\Select;

Select::make('domain')
    ->prefix('https://')
    ->suffix('.com')
```

--------------------------------

### Upgrade Tailwind CSS Configuration to v4 (CLI)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This command line snippet executes the official `@tailwindcss/upgrade` tool. This utility automatically adjusts existing Tailwind CSS configuration files to the v4 format and helps in replacing v3 packages with their v4 counterparts, streamlining the upgrade process.

```bash
npx @tailwindcss/upgrade
```

--------------------------------

### Apply Custom CSS Classes to Avatar Size

Source: https://filamentphp.com/docs/4.x/components/avatar

Allows passing custom Tailwind CSS classes to the size attribute for precise control over avatar dimensions. This example sets width and height to 12 units (w-12 h-12).

```blade
<x-filament::avatar
    src="https://filamentphp.com/dan.jpg"
    alt="Dan Harrin"
    size="w-12 h-12"
/>
```

--------------------------------

### Render Filament Hook with Single Scope in Blade (PHP)

Source: https://filamentphp.com/docs/4.x/advanced/render-hooks

This example demonstrates how to render a Filament hook in a Blade template while providing a single scope. The hook will only render if its registered scope matches the provided `static::class` or other specified scope, allowing for context-specific rendering.

```php
{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::PAGE_START, scopes: $this->getRenderHookScopes()) }}
```

--------------------------------

### Display Formatted Date in Tooltip using TextEntry

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Use various tooltip methods on TextEntry components to display formatted dates with additional information. Methods include dateTooltip(), dateTimeTooltip(), timeTooltip(), isoDateTooltip(), isoDateTimeTooltip(), isoTimeTooltip(), and sinceTooltip(). Each method accepts optional custom PHP date formatting strings or Carbon macro format strings depending on the method used.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->since()
    ->dateTooltip() // Accepts a custom PHP date formatting string

TextEntry::make('created_at')
    ->since()
    ->dateTimeTooltip() // Accepts a custom PHP date formatting string

TextEntry::make('created_at')
    ->since()
    ->timeTooltip() // Accepts a custom PHP date formatting string

TextEntry::make('created_at')
    ->since()
    ->isoDateTooltip() // Accepts a custom Carbon macro format string

TextEntry::make('created_at')
    ->since()
    ->isoDateTimeTooltip() // Accepts a custom Carbon macro format string

TextEntry::make('created_at')
    ->since()
    ->isoTimeTooltip() // Accepts a custom Carbon macro format string

TextEntry::make('created_at')
    ->dateTime()
    ->sinceTooltip()
```

--------------------------------

### Add Content Before Filament Infolist Entry (PHP)

Source: https://filamentphp.com/docs/4.x/infolists

Demonstrates how to insert an icon before a Filament Infolist `TextEntry`'s content using the `beforeContent()` method. This method accepts various content types, including schema components, and can also be dynamically calculated with utility injection.

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->beforeContent(Icon::make(Heroicon::Star))
```

--------------------------------

### Define export options form components in Filament exporter

Source: https://filamentphp.com/docs/4.x/actions/export

Create a getOptionsFormComponents() method in an exporter class to render interactive form components that users can interact with during export. This example shows a TextInput component allowing users to customize the description column limit. The returned array of form components displays in the export modal.

```php
use Filament\Forms\Components\TextInput;

public static function getOptionsFormComponents(): array
{
    return [
        TextInput::make('descriptionLimit')
            ->label('Limit the length of the description column content')
            ->integer(),
    ];
}
```

--------------------------------

### Create Tenant Profile Page Class in Filament

Source: https://filamentphp.com/docs/4.x/users/tenancy

Create a profile page by extending EditTenantProfile base class. Implement getLabel() for page title and form() to define editable schema components. Form fields are automatically saved to the tenant model, allowing users to manage tenant information.

```PHP
namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Team profile';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                // ...
            ]);
    }
}
```

--------------------------------

### FilamentPHP: Apply Conditional Checkbox Declined Validation

Source: https://filamentphp.com/docs/4.x/forms/checkbox

This example illustrates how to conditionally apply the `declined()` validation rule to a FilamentPHP Checkbox component. By passing a boolean expression, such as `FeatureFlag::active()`, the validation rule is only enforced when the condition evaluates to true.

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_under_18')
    ->declined(FeatureFlag::active())
```

--------------------------------

### Conditionally Hide FilamentPHP Form Component with hiddenOn()

Source: https://filamentphp.com/docs/4.x/resources/overview

This PHP example demonstrates how to use the `hiddenOn()` method to dynamically hide a form component, such as a password field, based on the current operation (e.g., hiding it on the `Edit` page). This allows for operation-specific field visibility.

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Operation;

TextInput::make('password')
    ->password()
    ->required()
    ->hiddenOn(Operation::Edit),
```

--------------------------------

### Render Markdown in Filament TextEntry (Static)

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This snippet demonstrates how to configure a Filament `TextEntry` to automatically render its value as Markdown. It uses the `markdown()` method without arguments, applying Markdown rendering unconditionally to the entry's state.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->markdown()
```

--------------------------------

### Define `toHtml` method for Filament custom rich content block (PHP)

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

This example illustrates how to create a custom rich content block by extending `RichContentCustomBlock`. It focuses on the `toHtml()` method, which is responsible for rendering the block's HTML, receiving both configuration data and additional runtime data as parameters.

```php
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;

class HeroBlock extends RichContentCustomBlock
{
    // ...

    /**
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $data
     */
    public static function toHtml(array $config, array $data): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.hero.index', [
            'heading' => $config['heading'],
            'subheading' => $config['subheading'],
            'buttonLabel' => 'View category',
            'buttonUrl' => $data['categoryUrl'],
        ])->render();
    }
}
```

--------------------------------

### Implement Responsive Column Ordering in FilamentPHP Grid

Source: https://filamentphp.com/docs/4.x/schemas/layouts

This snippet illustrates how to apply responsive column ordering using an array of breakpoints with the `columnOrder()` method in FilamentPHP. It enables components to have different visual orders based on screen sizes (e.g., 'default', 'lg'), ensuring adaptable layouts across various devices.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

Grid::make()
    ->columns([
        'sm' => 2,
        'lg' => 3,
    ])
    ->schema([
        TextInput::make('title')
            ->columnOrder([
                'default' => 1,
                'lg' => 3,
            ]),
        TextInput::make('description')
            ->columnOrder([
                'default' => 2,
                'lg' => 1,
            ]),
        TextInput::make('category')
            ->columnOrder([
                'default' => 3,
                'lg' => 2,
            ]),
    ])
```

--------------------------------

### Add Confirmation Modal to Builder Delete Action in Filament

Source: https://filamentphp.com/docs/4.x/forms/builder

Shows how to add a confirmation modal to builder actions using the requiresConfirmation() method. This example applies confirmation to the deleteAction(). Note that confirmation modals are only supported on actions that trigger network requests.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->deleteAction(
        fn (Action $action) => $action->requiresConfirmation(),
    )
```

--------------------------------

### Set Avatar Size Using Predefined Size Attribute

Source: https://filamentphp.com/docs/4.x/components/avatar

Controls the avatar dimensions using predefined size options (sm, md, lg). The default size is 'md' (medium), and this example shows setting it to 'lg' (large).

```blade
<x-filament::avatar
    src="https://filamentphp.com/dan.jpg"
    alt="Dan Harrin"
    size="lg"
/>
```

--------------------------------

### Configure Filament Toggle field to be inline with label (PHP)

Source: https://filamentphp.com/docs/4.x/forms/toggle

This example demonstrates how to set a Filament Toggle component to display its label inline with the toggle itself. By default, Filament Toggles are inline. The `inline()` method is used for this purpose without any arguments or with `true`.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->inline()
```

--------------------------------

### Test Filament Action Configuration with Callback (PHP)

Source: https://filamentphp.com/docs/4.x/testing/testing-actions

This snippet illustrates how to assert an action's configuration by passing a closure as a second argument to `assertActionExists()`. The callback receives the `Action` object, allowing detailed checks on its properties, such as the modal description.

```php
use Filament\Actions\Action;
use function Pest\Livewire\livewire;

it('has the correct description', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionExists('send', function (Action $action): bool {
            return $action->getModalDescription() === 'This will send an email to the customer\'s primary address, with the invoice attached as a PDF';
        });
});
```

--------------------------------

### Configure Grid Columns with Breakpoint Array in Filament

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Define responsive column layouts by passing an associative array where keys are Tailwind breakpoints (sm, md, lg, xl, 2xl) and values are column counts. Use the 'default' key to specify columns for devices smaller than the first specified breakpoint.

```php
columns(['md' => 2, 'xl' => 4])
```

--------------------------------

### Configure FilamentPHP TextInput to be copyable with custom message and duration

Source: https://filamentphp.com/docs/4.x/forms/text-input

This snippet demonstrates how to make a `TextInput` component in FilamentPHP copyable. It shows how to specify a custom confirmation message (`copyMessage`) and the duration (`copyMessageDuration`) in milliseconds for which the message is displayed after the text is copied to the clipboard. This feature requires SSL to be enabled for the application.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('apiKey')
    ->label('API key')
    ->copyable(copyMessage: 'Copied!', copyMessageDuration: 1500)
```

--------------------------------

### Check Filament Table Relationship Existence (Scoped)

Source: https://filamentphp.com/docs/4.x/tables/columns

This example demonstrates checking for relationship existence for a Filament table column with a custom scope. It uses the `exists()` method with an array, providing a callback to filter the `users` relationship by `is_active` status before checking for any existing records.

```php
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

TextColumn::make('users_exists')->exists([
    'users' => fn (Builder $query) => $query->where('is_active', true),
])
```

--------------------------------

### Conditionally Make Filament DatePicker Read-Only (PHP)

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

This example illustrates how to dynamically set the read-only state of a FilamentPHP `DatePicker` component based on a boolean condition. The `readOnly()` method accepts a boolean argument, often derived from a feature flag or similar logic, to control its state.

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->readOnly(FeatureFlag::active())
```

--------------------------------

### Perform Direct Force-Deletion in Filament without Fetching Records (PHP)

Source: https://filamentphp.com/docs/4.x/actions/force-delete

This PHP example shows how to perform a force-delete bulk action in Filament without first loading the records into memory. Using `fetchSelectedRecords(false)` results in a single database query for deletion, significantly improving performance. However, this method bypasses individual record policy authorization and model events (like `forceDeleting` and `forceDeleted`), so it should only be used when these functionalities are not required.

```php
use Filament\Actions\ForceDeleteBulkAction;

ForceDeleteBulkAction::make()
    ->fetchSelectedRecords(false)
```

--------------------------------

### Integrate Reusable Filament Table Schema into Resource (PHP)

Source: https://filamentphp.com/docs/4.x/resources/code-quality-tips

This example illustrates how to incorporate an external, reusable table schema class (`CustomersTable`) into the `table()` method of a Filament resource. Similar to form schemas, this approach keeps the resource's `table()` method clean by delegating the table column definitions to a separate class, thereby improving code organization and reusability. It depends on an existing `CustomersTable` class.

```php
use App\Filament\Resources\Customers\Schemas\CustomersTable;
use Filament\Tables\Table;

public static function table(Table $table): Table
{
    return CustomersTable::configure($table);
}
```

--------------------------------

### Assert Filament table column existence with custom truth test using Pest Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-tables

This example shows how to assert the existence of a Filament table column while also applying a custom 'truth test' function. It allows for detailed validation of a column's configuration, such as its description below, and can target a specific record.

```php
use function Pest\Livewire\livewire;
use Filament\Tables\Columns\TextColumn;

it('has an author column', function () {
    $post = Post::factory()->create();
    
    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableColumnExists('author', function (TextColumn $column): bool {
            return $column->getDescriptionBelow() === $post->subtitle;
        }, $post);
});
```

--------------------------------

### Implement Schema Traits and Interface in Livewire Component

Source: https://filamentphp.com/docs/4.x/components/infolist

To enable Filament infolist functionality, your Livewire component class must implement the `HasSchemas` interface and use the `InteractsWithSchemas` trait.

```php
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class ViewProduct extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    // ...
}
```

--------------------------------

### Apply Closure Validation Rule with Dependency Injection in Filament

Source: https://filamentphp.com/docs/4.x/forms/validation

Demonstrates applying a closure-based validation rule in Filament while injecting utilities like `$get` (to retrieve other field values) into the closure. This is achieved by wrapping the rule closure in another function that accepts dependencies.

```php
use Filament\Schemas\Components\Utilities\Get;

TextInput::make('slug')->rules([
    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
        if ($get('other_field') === 'foo' && $value !== 'bar') {
            $fail("The {$attribute} is invalid.");
        }
    },
])
```

--------------------------------

### Configure Filament v4 File Generation Flags in PHP

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This PHP configuration snippet illustrates how to define 'file_generation' flags in Filament v4 to revert to v3 file generation styles. It includes flags for embedding panel resource schemas and tables, controlling resource/cluster class directory placement, and managing partial imports, allowing consistency with older code structures.

```php
use Filament\Support\Commands\FileGenerators\FileGenerationFlag;

return [

    // ...

    'file_generation' => [
        'flags' => [
            FileGenerationFlag::EMBEDDED_PANEL_RESOURCE_SCHEMAS, // Define new forms and infolists inside the resource class instead of a separate schema class.
            FileGenerationFlag::EMBEDDED_PANEL_RESOURCE_TABLES, // Define new tables inside the resource class instead of a separate table class.
            FileGenerationFlag::PANEL_CLUSTER_CLASSES_OUTSIDE_DIRECTORIES, // Create new cluster classes outside of their directories. Not required if you run `php artisan filament:upgrade-directory-structure-to-v4`.
            FileGenerationFlag::PANEL_RESOURCE_CLASSES_OUTSIDE_DIRECTORIES, // Create new resource classes outside of their directories. Not required if you run `php artisan filament:upgrade-directory-structure-to-v4`.
            FileGenerationFlag::PARTIAL_IMPORTS, // Partially import components such as form fields and table columns instead of importing each component explicitly.
        ],
    ],

    // ...

]
```

--------------------------------

### Generate Laravel Middleware for Tenant Scoping

Source: https://filamentphp.com/docs/4.x/users/tenancy

This command line instruction shows how to generate a new Laravel middleware class using `php artisan make:middleware`. This middleware will later be used to apply additional global scopes to Eloquent models, ensuring they are tenant-aware within a FilamentPHP panel.

```bash
php artisan make:middleware ApplyTenantScopes
```

--------------------------------

### Access Another Component's State in Blade View

Source: https://filamentphp.com/docs/4.x/forms/custom-fields

Demonstrates how to access the state of other form components in a schema using the $get() function within a Blade view, enabling cross-field references and conditional rendering based on sibling field values.

```Blade
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    {{ $get('email') }}
</x-dynamic-component>
```

--------------------------------

### Responsive Icon Button with Label in Filament

Source: https://filamentphp.com/docs/4.x/actions/overview

Demonstrates creating a responsive action trigger that displays as a labeled button on desktop and converts to an icon-only button on mobile devices. The labeledFrom() method accepts a breakpoint ('md', 'lg', etc.) to control when the label appears.

```PHP
use Filament\Actions\Action;

Action::make('edit')
    ->icon('heroicon-m-pencil-square')
    ->button()
    ->labeledFrom('md')
```

--------------------------------

### Filament Singular Resource Page Class

Source: https://filamentphp.com/docs/4.x/resources/singular

Complete page class implementation for managing a singular resource with form handling. Includes mount() to load records, form() to define schema with fields and actions, save() to persist data with validation, and getRecord() helper method. Uses WebsitePage model and includes success notifications.

```php
namespace App\Filament\Pages;

use App\Models\WebsitePage;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

/**
 * @property-read Schema $form
 */
class ManageHomepage extends Page
{
    protected string $view = 'filament.pages.manage-homepage';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    RichEditor::make('content'),
                    // ...
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        $record = $this->getRecord();
        
        if (! $record) {
            $record = new WebsitePage();
            $record->is_homepage = true;
        }
        
        $record->fill($data);
        $record->save();
        
        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title('Saved')
            ->send();
    }
    
    public function getRecord(): ?WebsitePage
    {
        return WebsitePage::query()
            ->where('is_homepage', true)
            ->first();
    }
}
```

--------------------------------

### Cast Each Item in Filament Import Column Array to Integer (PHP)

Source: https://filamentphp.com/docs/4.x/actions/import

After using `multiple()` to convert a column's string value into an array, this example shows how to chain built-in casting methods like `integer()` to apply a specific data type conversion to each individual item within that array. This ensures all elements conform to the desired type.

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('customer_ratings')
    ->multiple(',')
    ->integer(); // Casts each item in the array to an integer.
```

--------------------------------

### Apply a static color to a Filament action button badge (PHP)

Source: https://filamentphp.com/docs/4.x/actions/overview

This example illustrates how to assign a specific color to a badge on a Filament action button. The `badgeColor()` method accepts a static string value, such as 'success', to visually differentiate the badge. This allows for clear status communication or categorization within the UI.

```php
use Filament\Actions\Action;

Action::make('filter')
    ->iconButton()
    ->icon('heroicon-m-funnel')
    ->badge(5)
    ->badgeColor('success')
```

--------------------------------

### Create Custom Action in Header - Filament PHP

Source: https://filamentphp.com/docs/4.x/resources/editing-records

Demonstrates how to add a custom action button to the header of an Edit page by implementing the `getHeaderActions()` method. The example shows adding an 'impersonate' action alongside the default DeleteAction, which executes a Livewire method when clicked.

```php
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    // ...

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('impersonate')
                ->action(function (): void {
                    // ...
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
```

--------------------------------

### Set Custom Brand Name for App Authentication in Filament

Source: https://filamentphp.com/docs/4.x/users/multi-factor-authentication

Customizes the brand name displayed in the authentication app using the brandName() method on the AppAuthentication instance. This allows you to replace the default app name with a custom identifier visible to users during authentication setup.

```PHP
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make()
                ->brandName('Filament Demo'),
        ]);
}
```

--------------------------------

### Generate Custom Edit Page - Filament PHP Artisan

Source: https://filamentphp.com/docs/4.x/resources/editing-records

Command-line example showing how to create a new Edit page for a resource using Filament's make:filament-page command. The `--type=EditRecord` flag specifies that an Edit page should be generated, and `--resource` associates it with the specified resource.

```bash
php artisan make:filament-page EditCustomerContact --resource=CustomerResource --type=EditRecord
```

--------------------------------

### Control Individual Tooltips for Multiple Filament Slider Handles

Source: https://filamentphp.com/docs/4.x/forms/slider

This example demonstrates how to manage tooltips independently for multiple handles on a FilamentPHP Slider. By providing an array of boolean values to the `tooltips()` method, each value corresponds to a specific handle, enabling granular control over which handle displays a tooltip.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 0, maxValue: 100)
    ->tooltips([true, false])
```

--------------------------------

### Poll FilamentPHP Table Content for Automatic Refresh

Source: https://filamentphp.com/docs/4.x/tables

This example shows how to configure a FilamentPHP table to automatically refresh its content at specified intervals. The `poll()` method allows you to set a duration (e.g., '10s' for 10 seconds) after which the table data will be reloaded.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->poll('10s');
}
```

--------------------------------

### Remove Default Filament PHP Action Modal Footer Buttons

Source: https://filamentphp.com/docs/4.x/actions/modals

This example demonstrates how to remove a default submit or cancel action button from the footer of a Filament modal. By passing `false` to methods like `modalSubmitAction()`, the corresponding button will no longer be rendered.

```php
use Filament\Actions\Action;

Action::make('help')
    ->modalContent(view('actions.help'))
    ->modalSubmitAction(false)
```

--------------------------------

### Integrate Reusable Filament Form Schema into Resource (PHP)

Source: https://filamentphp.com/docs/4.x/resources/code-quality-tips

This code snippet demonstrates how to integrate a previously defined reusable form schema class (`CustomerForm`) into the `form()` method of a Filament resource. By calling the static `configure` method of the schema class, the resource's form definition becomes concise and delegates the actual component setup to the dedicated schema class, promoting modularity. It requires importing the `CustomerForm` class.

```php
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use Filament\Schemas\Schema;

public static function form(Schema $schema): Schema
{
    return CustomerForm::configure($schema);
}
```

--------------------------------

### Register Custom Plugin with Rich Editor - PHP

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Demonstrates how to register a custom rich editor plugin with the RichEditor component using the plugins() method and configure toolbar buttons. This example shows how to organize toolbar buttons into groups and include both built-in and custom tools.

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->toolbarButtons([
        ['bold', 'highlight', 'highlightWithCustomColor'],
        ['h2', 'h3'],
        ['bulletList', 'orderedList'],
    ])
```

--------------------------------

### Preload static relationship options for FilamentPHP SelectColumn

Source: https://filamentphp.com/docs/4.x/tables/columns/select

This code snippet demonstrates how to preload relationship options for a `SelectColumn` when the page loads, rather than when the user initiates a search. The `preloadOptions()` method fetches all available options from the database upfront, improving perceived performance.

```php
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('author_id')
    ->optionsRelationship(name: 'author', titleAttribute: 'name')
    ->searchableOptions()
    ->preloadOptions()
```

--------------------------------

### Set Filament Query Builder Constraint Picker Max Width

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

This example illustrates how to manually control the maximum width of the constraint picker dropdown in Filament's Query Builder. The `constraintPickerWidth()` method accepts a string corresponding to Tailwind CSS max-width scale options (e.g., '2xl', 'lg'), allowing for precise control over the dropdown's size, especially when increasing the number of columns. This provides more granular control than the default incremental width adjustment.

```php
use Filament\Tables\Filters\QueryBuilder;

QueryBuilder::make()
    ->constraintPickerColumns(3)
    ->constraintPickerWidth('2xl')
    ->constraints([
        // ...
    ])
```

--------------------------------

### Set Component Column Span with Dynamic Function in Filament

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Calculate column span dynamically using a closure with injected utilities like $get, $component, $livewire, $model, $operation, and $record. Enables responsive spans based on runtime conditions and form state.

```php
columnSpan(function ($get, $component, $livewire, $model, $operation, $record) {
  // Dynamic column span calculation logic
})
```

--------------------------------

### Customize Filament CheckboxList Action Objects

Source: https://filamentphp.com/docs/4.x/forms/checkbox-list

This PHP example illustrates how to customize action buttons like 'Select All' or 'Deselect All' within a Filament CheckboxList. The selectAllAction() method accepts a closure that receives the $action object, enabling modification of its properties such as the label.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->selectAllAction(
        fn (Action $action) => $action->label('Select all technologies'),
    )
```

--------------------------------

### Register Custom Navigation Groups in FilamentPHP

Source: https://filamentphp.com/docs/4.x/navigation/overview

This PHP example illustrates how to organize multiple navigation items into logical groups within the FilamentPHP admin panel. It uses the `NavigationGroup::make()` method within the navigation builder to create collapsible sections for related resources and settings.

```php
use App\Filament\Pages\HomePageSettings;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Pages\PageResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder->groups([
                NavigationGroup::make('Website')
                    ->items([
                        ...PageResource::getNavigationItems(),
                        ...CategoryResource::getNavigationItems(),
                        ...HomePageSettings::getNavigationItems(),
                    ]),
            ]);
        });
}
```

--------------------------------

### Inject Current FilamentPHP Component Instance into Callbacks ($component)

Source: https://filamentphp.com/docs/4.x/schemas/overview

Explains the `$component` utility injection, which provides the callback function with a reference to the current Filament component instance. This enables fine-grained control and dynamic configuration of the component itself based on its own properties or state.

```php
use Filament\Schemas\Components\Component;

function (Component $component) {
    // ...
}
```

--------------------------------

### Customize Filament KeyValue key field label

Source: https://filamentphp.com/docs/4.x/forms/key-value

This example shows how to customize the label for the key fields in a Filament KeyValue component using the `keyLabel()` method. This method accepts either a static string value or a function that dynamically calculates the label, with support for utility injection.

```PHP
use Filament\Forms\Components\KeyValue;

KeyValue::make('meta')
    ->keyLabel('Property name')
```

--------------------------------

### Create Custom Filament Avatar Provider (PHP)

Source: https://filamentphp.com/docs/4.x/users/overview

This snippet demonstrates how to create a custom avatar provider to use services other than `ui-avatars.com`. The `BoringAvatarsProvider` class implements `Contracts\AvatarProvider` and defines a `get` method to generate an avatar URL based on the user's name, allowing integration with services like `boringavatars.com`.

```php
<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class BoringAvatarsProvider implements Contracts\AvatarProvider
{
    public function get(Model | Authenticatable $record): string
    {
        $name = str(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        return 'https://source.boringavatars.com/beam/120/' . urlencode($name);
    }
}
```

--------------------------------

### Generate Resource URL using Panel Resource in Filament

Source: https://filamentphp.com/docs/4.x/infolists

Generate a URL to a panel resource page using the `getUrl()` method on a resource class. This approach leverages Filament's resource routing system to create links to resource pages like edit views.

```php
use App\Filament\Posts\PostResource;
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
```

--------------------------------

### Publish Livewire Configuration File

Source: https://filamentphp.com/docs/4.x/forms/file-upload

Execute the Livewire artisan command to publish the configuration file, which allows modification of temporary file upload settings and validation rules.

```bash
php artisan livewire:publish --config
```

--------------------------------

### Type-safely retrieve another FilamentPHP field's state

Source: https://filamentphp.com/docs/4.x/forms

Illustrates how to use type-specific methods on the `Get` utility (e.g., `string()`, `integer()`, `date()`) to retrieve the state of other fields in a type-safe manner. It also shows how to handle nullable fields using the `isNullable` argument.

```php
use Filament\Schemas\Components\Utilities\Get;

$get->string('email');
$get->integer('age');
$get->float('price');
$get->boolean('is_admin');
$get->array('tags');
$get->date('published_at');
$get->enum('status', StatusEnum::class);
$get->filled('email'); // Returns the result of the `filled()` helper for the field.
$get->blank('email'); // Returns the result of the `blank()` helper for the field.

// To force a nullable return type:
$get->string('email', isNullable: true);
```

--------------------------------

### Create Custom Action Below Form - Filament PHP

Source: https://filamentphp.com/docs/4.x/resources/editing-records

Shows how to add a custom action button below the form by overriding `getFormActions()` method. This example adds a 'close' button next to the default Save button that triggers a custom method to save and close the form.

```php
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    // ...

    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            Action::make('close')->action('saveAndClose'),
        ];
    }

    public function saveAndClose(): void
    {
        // ...
    }
}
```

--------------------------------

### Define action with confirmation for programmatic triggering

Source: https://filamentphp.com/docs/4.x/components/action

Creates a Filament action with confirmation requirement that can be triggered programmatically. The action receives arguments passed during mounting and executes custom logic. This pattern enables actions to be triggered from multiple sources while maintaining consistent behavior.

```php
use Filament\Actions\Action;

public function testAction(): Action
{
    return Action::make('test')
        ->requiresConfirmation()
        ->action(function (array $arguments) {
            dd('Test action called', $arguments);
        });
}
```

--------------------------------

### Customize Filament KeyValue Delete Action with PHP

Source: https://filamentphp.com/docs/4.x/forms/key-value

This PHP example demonstrates how to customize the `deleteAction` of a Filament `KeyValue` component. It uses a closure to modify the action's icon to `Heroicon::XMark`, showcasing how to access and modify action properties within Filament components.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\KeyValue;
use Filament\Support\Icons\Heroicon;

KeyValue::make('meta')
    ->deleteAction(
        fn (Action $action) => $action->icon(Heroicon::XMark),
    )
```

--------------------------------

### Customize Eloquent Query Scoping for Filament Table Groups (PHP)

Source: https://filamentphp.com/docs/4.x/tables/grouping

This example illustrates how to customize the Eloquent query scoping behavior for a Filament table group. The `scopeQueryByKeyUsing()` method on a `Group` object accepts a callback to define how the query is filtered or scoped based on a given group key.

```php
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->groups([
            Group::make('status')
                ->scopeQueryByKeyUsing(fn (Builder $query, string $key) => $query->where('status', $key)),
        ]);
}

```

--------------------------------

### Conditionally Hide or Show FilamentPHP Table Columns

Source: https://filamentphp.com/docs/4.x/tables/columns

This example shows how to conditionally hide or show a FilamentPHP table column by passing a boolean value to the `hidden()` or `visible()` methods. This allows for dynamic visibility based on application logic, such as feature flags or user permissions.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('role')
    ->hidden(FeatureFlag::active())

TextColumn::make('role')
    ->visible(FeatureFlag::active())
```

--------------------------------

### Allow Image File Uploads in Filament PHP (Shorthand)

Source: https://filamentphp.com/docs/4.x/forms/file-upload

This example shows a shorthand method `image()` on a `FileUpload` component to allow all common image MIME types in Filament PHP. This simplifies configuration for image-only uploads without needing to specify individual MIME types. It's a convenient utility for common image file restrictions.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
```

--------------------------------

### Customize 'No' Label for Boolean Select in Filament Forms

Source: https://filamentphp.com/docs/4.x/forms/select

This example demonstrates customizing the 'No' label for a boolean select component in Filament. The `falseLabel` argument of the `boolean()` method is used to set a custom string in place of the default 'No', improving the user experience.

```PHP
use Filament\Forms\Components\Select;

Select::make('feedback')
    ->label('Like this post?')
    ->boolean(falseLabel: 'Not at all!')
```

--------------------------------

### Configure Code Entry Light and Dark Themes in Filament

Source: https://filamentphp.com/docs/4.x/infolists/code-entry

Sets custom light and dark syntax highlighting themes for code entries using the lightTheme() and darkTheme() methods. Over 50 themes are available through the Phiki\Theme\Theme enum class. Both methods support static values and dynamic functions with utility injection for responsive theme selection.

```php
use Filament\Infolists\Components\CodeEntry;
use Phiki\Theme\Theme;

CodeEntry::make('code')
    ->lightTheme(Theme::Dracula)
    ->darkTheme(Theme::Dracula)
```

--------------------------------

### Add Content Below Entry Label in Filament Infolist

Source: https://filamentphp.com/docs/4.x/infolists/overview

Insert extra content below an entry's label using the belowLabel() method. Accepts static content (text, schema components, actions) or a callable that receives injected utilities like $component, $get, $livewire, $model, $operation, $record, and $state for dynamic content generation.

```PHP
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->belowLabel([
        Icon::make(Heroicon::Star),
        'This is the content below the entry\'s label'
    ])
```

--------------------------------

### Define Full-Page Livewire Component Route

Source: https://filamentphp.com/docs/4.x/components/infolist

Alternatively, configure a full-page Livewire component by mapping a specific route directly to your Livewire component class in your application's web routes.

```php
use App\Livewire\ViewProduct;
use Illuminate\Support\Facades\Route;

Route::get('products/{product}', ViewProduct::class);
```

--------------------------------

### Make Filament TextEntry Limited Lists Expandable

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This functionality allows a limited list within a Filament `TextEntry` to be expanded and collapsed. It requires the `listWithLineBreaks()` or `bulleted()` methods to be applied, as each item must be on its own line. The `expandableLimitedList()` method can accept a boolean or a dynamic function to control its state, with utilities like `$component` and `$get` available for injection.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)
    ->expandableLimitedList()
```

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)
    ->expandableLimitedList(FeatureFlag::active())
```

--------------------------------

### Configure Filament Toggle field to stack label above (PHP)

Source: https://filamentphp.com/docs/4.x/forms/toggle

This example shows how to configure a Filament Toggle component to display its label above the toggle, rather than inline. This is achieved by passing `false` to the `inline()` method. The `inline()` method also supports dynamic values via a closure by injecting various utilities.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->inline(false)
```

--------------------------------

### Apply Container Breakpoints to columnSpan and columnOrder

Source: https://filamentphp.com/docs/4.x/schemas/layouts

Uses container breakpoints in columnSpan() and columnOrder() methods to control individual component width and visual order based on container size. Demonstrates reordering and resizing form fields (TextInput) at different container breakpoints.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

Grid::make()
    ->gridContainer()
    ->columns([
        '@md' => 3,
        '@xl' => 4,
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                '@md' => 2,
                '@xl' => 3,
            ])
            ->columnOrder([
                'default' => 2,
                '@xl' => 1,
            ]),
        TextInput::make('email')
            ->columnSpan([
                'default' => 1,
                '@xl' => 1,
            ])
            ->columnOrder([
                'default' => 1,
                '@xl' => 2,
            ]),
        // ...
    ])
```

--------------------------------

### Generate Filament relation manager with associate and dissociate actions

Source: https://filamentphp.com/docs/4.x/resources/managing-relationships

To automatically include `AssociateAction`, `DissociateAction`, and `DissociateBulkAction` when generating a Filament relation manager, use the `--associate` flag with the `make:filament-relation-manager` Artisan command. This streamlines the setup for managing `HasMany` and `MorphMany` relationships.

```bash
php artisan make:filament-relation-manager CategoryResource posts title --associate
```

--------------------------------

### Set Minimum and Maximum Numeric Input Value in Filament Forms

Source: https://filamentphp.com/docs/4.x/forms/text-input

This snippet demonstrates how to apply minimum and maximum value validation to a numeric TextInput component in Filament. The `minValue()` and `maxValue()` methods accept static integers or dynamic functions for flexible validation rules.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('number')
    ->numeric()
    ->minValue(1)
    ->maxValue(100)
```

--------------------------------

### Set FilamentPHP TextInput Affix Icon Color (PHP)

Source: https://filamentphp.com/docs/4.x/forms/text-input

This code demonstrates how to set the color of an affix icon on a FilamentPHP `TextInput` component. It uses `suffixIcon()` to define the icon and `suffixIconColor('success')` to set its color. The `prefixIconColor()` and `suffixIconColor()` methods can accept static values or dynamic functions with injected utilities for flexible styling.

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

TextInput::make('domain')
    ->url()
    ->suffixIcon(Heroicon::CheckCircle)
    ->suffixIconColor('success')
```

--------------------------------

### Customize 'Yes' Label for Boolean Radio in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/radio

This example shows how to customize the 'Yes' label for a boolean radio button in FilamentPHP using the `trueLabel` argument within the `boolean()` method. This allows for more expressive positive responses while retaining the default 'No' label.

```php
use Filament\Forms\Components\Radio;

Radio::make('feedback')
    ->label('Like this post?')
    ->boolean(trueLabel: 'Absolutely!')
```

--------------------------------

### Statically Preload FilamentPHP Select Relationship Options

Source: https://filamentphp.com/docs/4.x/forms/select

Explains how to use the `preload()` method on a FilamentPHP `Select` component to populate searchable options from the database when the page loads, instead of on user search.

```php
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->searchable()
    ->preload()
```

--------------------------------

### Create Basic Text Column in Filament Tables (PHP)

Source: https://filamentphp.com/docs/4.x/tables/columns/text

Demonstrates how to create a simple text column in Filament tables, displaying content from a specified model attribute such as 'title'.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('title')
```

--------------------------------

### Configure Filament Page Max Content Width (PHP)

Source: https://filamentphp.com/docs/4.x/navigation/custom-pages

Override the `getMaxContentWidth()` method in a Filament page class to control the maximum content width. This example sets the width to `Full`, leveraging Filament's `Width` enum which maps to Tailwind's max-width scale.

```php
use Filament\Support\Enums\Width;

public function getMaxContentWidth(): Width
{
    return Width::Full;
}
```

--------------------------------

### Halt Filament PHP record creation with a lifecycle hook and notification

Source: https://filamentphp.com/docs/4.x/resources/creating-records

This `beforeCreate` hook demonstrates how to conditionally halt the record creation process in Filament PHP using `$this->halt()`. It also shows how to display a persistent notification to the user, guiding them to resolve an issue like an inactive subscription before proceeding with record creation.

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

protected function beforeCreate(): void
{
    if (! auth()->user()->team->subscribed()) {
        Notification::make()
            ->warning()
            ->title('You don\'t have an active subscription!')
            ->body('Choose a plan to continue.')
            ->persistent()
            ->actions([
                Action::make('subscribe')
                    ->button()
                    ->url(route('subscribe'), shouldOpenInNewTab: true),
            ])
            ->send();
    
        $this->halt();
    }
}
```

--------------------------------

### Disable CheckboxList with relationship in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/checkbox-list

This example shows how to disable a Filament CheckboxList that is linked to an Eloquent relationship. It's crucial to call the 'disabled()' method before 'relationship()' to prevent 'dehydrated()' from being overridden, ensuring the component remains disabled correctly.

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->disabled()
    ->relationship(titleAttribute: 'name')
```

--------------------------------

### Implement External API Search in FilamentPHP Tables (PHP)

Source: https://filamentphp.com/docs/4.x/tables/custom-data

This example shows how to integrate search functionality in FilamentPHP tables using an external API. It utilizes Laravel's `Http` facade to query the DummyJSON API's `/products/search` endpoint with a `q` parameter based on the user's search input. The response is then converted to a Laravel collection and the 'products' array is returned for display, allowing users to search across external data.

```PHP
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

public function table(Table $table): Table
{
    return $table
        ->records(function (?string $search): array {
            $response = Http::baseUrl('https://dummyjson.com/')
                ->get('products/search', [
                    'q' => $search,
                ]);

            return $response
                ->collect()
                ->get('products', []);
        })
        ->columns([
            TextColumn::make('title'),
            TextColumn::make('category'),
            TextColumn::make('price')
                ->money(),
        ])
        ->searchable();
}
```

--------------------------------

### Create Text Entry in Filament Infolists

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Basic implementation of a Text Entry component to display simple text values. This is the foundational component for rendering text content within Filament Infolists.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
```

--------------------------------

### Aggregate Filament Table Relationship Field (Scoped)

Source: https://filamentphp.com/docs/4.x/tables/columns

This example demonstrates aggregating a field from related records in a Filament table column with a custom scope. It uses the `avg()` method with an array for the relationship and a callback to filter `users` by `is_active` before averaging the `age` field for the column.

```php
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

TextColumn::make('users_avg_age')->avg([
    'users' => fn (Builder $query) => $query->where('is_active', true),
], 'age')
```

--------------------------------

### Add Extra Modal Footer Actions in Filament

Source: https://filamentphp.com/docs/4.x/actions/modals

Demonstrates how to use the extraModalFooterActions() method to render additional action buttons in a modal's footer. The method accepts a closure that receives the current Action instance and returns an array of Action instances to be rendered between default actions. This example shows creating a 'createAnother' submit action that passes custom arguments to modify action behavior.

```PHP
use Filament\Actions\Action;

Action::make('create')
    ->schema([
        // ...
    ])
    // ...
    ->extraModalFooterActions(fn (Action $action): array => [
        $action->makeModalSubmitAction('createAnother', arguments: ['another' => true]),
    ])
```

--------------------------------

### Configure Tenant Model in Filament Panel

Source: https://filamentphp.com/docs/4.x/users/tenancy

Configures a Filament panel to use a specific model (Team) as the tenant. This is the first step in setting up multi-tenancy in Filament and tells the framework which model represents a tenant in your application.

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class);
}
```

--------------------------------

### Render Filament Fieldset Blade Component

Source: https://filamentphp.com/docs/4.x/components/fieldset

This Blade component example demonstrates how to use the `x-filament::fieldset` component to group related form fields. It includes an optional `label` slot for providing a title to the fieldset, enhancing form structure and readability within Filament forms.

```blade
<x-filament::fieldset>
    <x-slot name="label">
        Address
    </x-slot>
    
    {{-- Form fields --}}
</x-filament::fieldset>
```

--------------------------------

### Conditionally Hide FilamentPHP Infolist TextEntry Label

Source: https://filamentphp.com/docs/4.x/infolists

This example demonstrates how to conditionally hide a `TextEntry`'s label in FilamentPHP Infolists by passing a boolean value to the `hiddenLabel()` method. This allows for dynamic control over label visibility based on application logic, such as feature flags or other runtime conditions.

```php
use FilamentInfolistsComponentsTextEntry;

TextEntry::make('name')
    ->hiddenLabel(FeatureFlag::active())
```

--------------------------------

### Count Filament Table Relationship Records (Scoped)

Source: https://filamentphp.com/docs/4.x/tables/columns

This example shows how to count related records for a Filament table column while applying a scope to the relationship. It utilizes the `counts()` method with an array, allowing a callback function to filter the `users` relationship, in this case, by `is_active` status, before counting.

```php
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

TextColumn::make('users_count')->counts([
    'users' => fn (Builder $query) => $query->where('is_active', true),
])
```

--------------------------------

### Define Filament Table Filter with Query Scope

Source: https://filamentphp.com/docs/4.x/tables/filters/overview

This example demonstrates how to create a basic filter for a Filament table using the `Filter::make()` method and apply a query scope to filter records based on the 'is_featured' column. The filter is added within the `$table->filters()` method of a table definition, activating the query when the filter is enabled.

```php
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->filters([
            Filter::make('is_featured')
                ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
            // ...
        ]);
}
```

--------------------------------

### FilamentPHP: Custom BelongsToMany Resolution with Closure

Source: https://filamentphp.com/docs/4.x/actions/import

For `BelongsToMany` relationships in FilamentPHP imports, this example shows how to provide a closure that receives an array of states. The closure should return an Eloquent `Collection` of resolved records, allowing for custom logic like finding multiple authors by their emails or usernames.

```php
use App\Models\Author;
use Filament\Actions\Imports\ImportColumn;
use Illuminate\Database\Eloquent\Collection;

ImportColumn::make('authors')
    ->relationship(resolveUsing: function (array $state): Collection {
        return Author::query()
            ->whereIn('email', $state)
            ->orWhereIn('username', $state)
            ->get();
    })
```

--------------------------------

### Apply FilamentPHP Text Input Exact Length Validation

Source: https://filamentphp.com/docs/4.x/forms/text-input

Demonstrates how to validate a FilamentPHP `TextInput` for an exact character length using the `length()` method. This method provides both frontend and backend validation, ensuring the input precisely matches the specified length, and supports dynamic length calculation via a function for more advanced scenarios.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('code')
    ->length(8)
```

--------------------------------

### Set Sub-navigation Position for FilamentPHP 4.x Cluster

Source: https://filamentphp.com/docs/4.x/navigation/clusters

This snippet demonstrates how to set the sub-navigation position for all pages within a FilamentPHP cluster. By defining the `$subNavigationPosition` property, you can specify whether the sub-navigation appears at the 'Start', 'End', or 'Top' of the page using the `SubNavigationPosition` enum.

```php
use Filament\Pages\Enums\SubNavigationPosition;

protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;
```

--------------------------------

### Enable Filament user profile page

Source: https://filamentphp.com/docs/4.x/users/multi-factor-authentication

This code snippet demonstrates how to enable the default user profile page in a Filament panel. Enabling the profile page automatically adds the necessary UI elements for users to set up multi-factor authentication (MFA). It extends the panel configuration with the `profile()` method, typically found in a `PanelProvider`.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->profile();
}
```

--------------------------------

### Set default image URL in Filament ImageEntry

Source: https://filamentphp.com/docs/4.x/infolists/image-entry

Display a placeholder image when no image exists by passing a URL to the defaultImageUrl() method. This method accepts both static URLs and dynamic functions with injectable utilities like $component, $get, $livewire, $model, $operation, $record, and $state for conditional logic.

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')
    ->defaultImageUrl(url('storage/posts/header-images/default.jpg'))
```

--------------------------------

### Set Maximum File Size for Image Uploads

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Control the maximum file size for uploaded images using fileAttachmentsMaxSize(), specified in kilobytes. The default maximum is 12288 KB (12 MB). This example sets the limit to 5120 KB (5 MB).

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->fileAttachmentsMaxSize(5120) // 5 MB
```

--------------------------------

### Apply Colors to ToggleButtons Options (Filament PHP)

Source: https://filamentphp.com/docs/4.x/forms/toggle-buttons

This example shows how to customize the colors of individual option buttons within a Filament ToggleButtons component using the `colors()` method. It assigns 'info', 'warning', and 'success' colors to 'draft', 'scheduled', and 'published' options respectively, enhancing visual feedback. The `colors()` method also supports dynamic calculation via a function.

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published'
    ])
    ->colors([
        'draft' => 'info',
        'scheduled' => 'warning',
        'published' => 'success',
    ])
```

--------------------------------

### Insert Image component in Filament schema

Source: https://filamentphp.com/docs/4.x/schemas/primes

Create an Image component by passing URL and alt text to the make() method. Both parameters accept static values or functions for dynamic calculation using utility injection based on component instance, form data retrieval, Livewire component, Eloquent model FQN, operation type, and record data.

```PHP
use Filament\Schemas\Components\Image;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
```

--------------------------------

### Add Custom Extra Item Actions to FilamentPHP Repeater

Source: https://filamentphp.com/docs/4.x/forms/repeater

This example illustrates adding custom action buttons to each repeater item's header using `extraItemActions()`. It demonstrates how to create a custom action that sends an email, accessing item-specific data using `getItemState()` or `getRawItemState()`.

```PHP
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;

Repeater::make('members')
    ->schema([
        TextInput::make('email')
            ->label('Email address')
            ->email(),
        // ...
    ])
    ->extraItemActions([
        Action::make('sendEmail')
            ->icon(Heroicon::Envelope)
            ->action(function (array $arguments, Repeater $component): void {
                $itemData = $component->getItemState($arguments['item']);

                Mail::to($itemData['email'])
                    ->send(
                        // ...
                    );
            }),
    ])
```

--------------------------------

### Dynamically allow HTML in Filament SelectColumn option labels (PHP)

Source: https://filamentphp.com/docs/4.x/tables/columns/select

This example shows how to dynamically control whether HTML is allowed in `SelectColumn` option labels using `allowOptionsHtml()` with a boolean parameter. It illustrates using a feature flag to enable or disable HTML rendering based on application logic. As with static HTML, thorough sanitization is crucial to mitigate XSS risks.

```php
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('technology')
    ->options([
        'tailwind' => '<span class="text-blue-500">Tailwind</span>',
        'alpine' => '<span class="text-green-500">Alpine</span>',
        'laravel' => '<span class="text-red-500">Laravel</span>',
        'livewire' => '<span class="text-pink-500">Livewire</span>',
    ])
    ->searchableOptions()
    ->allowOptionsHtml(FeatureFlag::active())
```

--------------------------------

### Configure Filament Database Notifications Polling Interval

Source: https://filamentphp.com/docs/4.x/notifications/database-notifications

Filament allows polling for new database notifications periodically. You can set the polling interval using `databaseNotificationsPolling()`, for example, '30s' for every 30 seconds. This ensures users receive updates without a full page reload.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->databaseNotifications()
        ->databaseNotificationsPolling('30s');
}
```

--------------------------------

### Insert Components into Filament PHP Section Footer

Source: https://filamentphp.com/docs/4.x/schemas/sections

This snippet demonstrates how to insert components, such as actions, into the footer of a Filament PHP section using the `footer()` method. Similar to `afterHeader()`, this method takes an array of schema components. It also supports dynamic calculation via a closure, allowing access to various utilities like `$component`, `$get`, and `$record` for flexible component rendering.

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->schema([
        // ...
    ])
    ->footer([
        Action::make('test'),
    ])
```

--------------------------------

### Pass Livewire Properties to Filament Widget via make() Method

Source: https://filamentphp.com/docs/4.x/navigation/custom-pages

When registering a Filament widget, you can use the `make()` method to pass an array of Livewire properties directly to the widget instance. This allows for dynamic configuration of widgets during their registration within methods like `getHeaderWidgets()`.

```PHP
use App\Filament\Widgets\StatsOverviewWidget;

protected function getHeaderWidgets(): array
{
    return [
        StatsOverviewWidget::make([
            'status' => 'active',
        ]),
    ];
}
```

--------------------------------

### Register Filament View Pages in Resource getPages Method (PHP)

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

This PHP method defines the routes and associated page classes for a Filament resource. To make a newly created View page accessible, it must be registered within this array, mapping a route segment to its corresponding page class. This example shows how to add a custom 'view-contact' page.

```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListCustomers::route('/'),
        'create' => Pages\CreateCustomer::route('/create'),
        'view' => Pages\ViewCustomer::route('/{record}'),
        'view-contact' => Pages\ViewCustomerContact::route('/{record}/contact'),
        'edit' => Pages\EditCustomer::route('/{record}/edit'),
    ];
}
```

--------------------------------

### Use virtual column as title attribute in Filament Select

Source: https://filamentphp.com/docs/4.x/forms/select

Configures a Filament `Select` component to display relationship options using a specified `titleAttribute`. This example uses a previously defined virtual column 'full_name' to show concatenated names for author records.

```php
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'full_name')
```

--------------------------------

### Customize Placeholder for Boolean Select in Filament Forms

Source: https://filamentphp.com/docs/4.x/forms/select

This snippet illustrates how to customize the placeholder text for a boolean select component in Filament. The `placeholder` argument within the `boolean()` method allows you to define a custom message shown when no option has been selected, guiding the user.

```PHP
use Filament\Forms\Components\Select;

Select::make('feedback')
    ->label('Like this post?')
    ->boolean(placeholder: 'Make your mind up...')
```

--------------------------------

### Create Blade View for Filament Clock Widget

Source: https://filamentphp.com/docs/4.x/plugins/building-a-panel-plugin

This Blade template defines the front-end structure for the `ClockWidget`. It utilizes Filament's `x-filament-widgets::widget` and `x-filament::section` components for styling and layout. The `x-load` attributes dynamically load an Alpine.js component, `clockWidget()`, which displays the current time and a description, with content retrieved from translation keys.

```blade
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __("clock-widget::clock-widget.title") }}
        </x-slot>

        <div
            x-load
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('clock-widget', 'awcodes/clock-widget') }}"
            x-data="clockWidget()"
            class="text-center"
        >
            <p>{{ __("clock-widget::clock-widget.description") }}</p>
            <p class="text-xl" x-text="time"></p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
```

--------------------------------

### Conditionally Enable Tooltips on Filament Slider

Source: https://filamentphp.com/docs/4.x/forms/slider

This example shows how to conditionally activate or deactivate tooltips on a FilamentPHP Slider component by passing a boolean value to the `tooltips()` method. This allows dynamic control over tooltip visibility, often based on feature flags or other application logic.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 0, maxValue: 100)
    ->tooltips(FeatureFlag::active())
```

--------------------------------

### Filament Table Layout: Prevent Column Growth with grow(false)

Source: https://filamentphp.com/docs/4.x/tables/layout

This example demonstrates how to use `grow(false)` on a column within a `Split` component. This prevents the column from taking up proportionate whitespace, allowing it to sit tightly against adjacent columns, while other columns expand to fill the available space.

```php
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

Split::make([
    ImageColumn::make('avatar')
        ->circular()
        ->grow(false),
    TextColumn::make('name')
        ->weight(FontWeight::Bold)
        ->searchable()
        ->sortable(),
    TextColumn::make('email'),
])
```

--------------------------------

### Dynamically Control Multiple File Uploads in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/file-upload

This example shows how to dynamically enable or disable multiple file uploads based on a boolean value or a callable function. The `multiple()` method accepts a static boolean or a function that can inject various utilities for conditional logic.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple(FeatureFlag::active())
```

--------------------------------

### Test Loading View Page with Infolist State Verification

Source: https://filamentphp.com/docs/4.x/testing/testing-resources

Tests if a Filament view resource page loads successfully with HTTP 200 response and verifies infolist entries display correct values. Uses Livewire component testing with assertOk() and assertSchemaStateSet() methods.

```php
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;

it('can load the page', function () {
    $user = User::factory()->create();

    livewire(ViewUser::class, [
        'record' => $user->id,
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});
```

--------------------------------

### Conditionally Apply Distinct Validation to FilamentPHP Field

Source: https://filamentphp.com/docs/4.x/forms/repeater

This example shows how to dynamically control the application of the `distinct()` validation method to a FilamentPHP field using a boolean value, potentially derived from a feature flag. This allows for conditional enforcement of uniqueness based on application logic.

```PHP
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_correct')
    ->distinct(FeatureFlag::active())
```

--------------------------------

### Configure DateTimePicker Time Input Step Intervals in Filament

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

Customize hour, minute, and second increments for DateTimePicker input controls. The hoursStep(), minutesStep(), and secondsStep() methods accept static integer values or dynamic functions with utility injection to determine step intervals based on form context or state.

```PHP
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->native(false)
    ->hoursStep(2)
    ->minutesStep(15)
    ->secondsStep(10)
```

--------------------------------

### Use Virtual Column as Filament SelectColumn Relationship `titleAttribute`

Source: https://filamentphp.com/docs/4.x/tables/columns/select

This example illustrates how to configure a Filament `SelectColumn` to use a previously defined virtual database column (e.g., `full_name`) as its `titleAttribute`. By leveraging virtual columns, developers can provide custom, concatenated labels for relationship options without complex logic in the Filament component itself.

```php
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('author_id')
    ->optionsRelationship(name: 'author', titleAttribute: 'full_name')
```

--------------------------------

### Globally Configure Filament Layout Components to Span Full Column (PHP)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This PHP code provides a way to globally configure Filament's `Fieldset`, `Grid`, and `Section` layout components to span the full width by default. By placing this in a service provider's `boot()` method, it ensures all instances of these components will use `columnSpanFull()` unless explicitly overridden.

```php
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

Fieldset::configureUsing(fn (Fieldset $fieldset) => $fieldset
    ->columnSpanFull());

Grid::configureUsing(fn (Grid $grid) => $grid
    ->columnSpanFull());

Section::configureUsing(fn (Section $section) => $section
    ->columnSpanFull());
```

--------------------------------

### Customize Filament Builder Block Label (PHP)

Source: https://filamentphp.com/docs/4.x/forms/builder

This PHP code snippet demonstrates how to override the default label of a Filament `Builder\Block` using the `label()` method. It shows an example of setting a localized label using a translation string (`__('blocks.heading')`), which is useful for internationalization.

```php
use Filament\Forms\Components\Builder\Block;

Block::make('heading')
    ->label(__('blocks.heading'))
```

--------------------------------

### Filament PHP: Partially Re-rendering Specific Form Fields

Source: https://filamentphp.com/docs/4.x/forms

This example shows how to optimize re-rendering by instructing Filament to update only specific components after a state change. By calling `partiallyRenderComponentsAfterStateUpdated(['email'])`, only the 'email' field is re-rendered when the 'name' field (which is `live()`) is updated, avoiding a full component re-render and improving performance.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->live()
    ->partiallyRenderComponentsAfterStateUpdated(['email']);
```

--------------------------------

### Pass Data to Custom Modal Content in FilamentPHP Action

Source: https://filamentphp.com/docs/4.x/actions/modals

This example shows how to dynamically pass data, such as an Eloquent record, to a Blade view used for a FilamentPHP action's modal content. The `modalContent()` method accepts a function that returns a `View` instance with an array of data.

```PHP
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;

Action::make('advance')
    ->action(fn (Contract $record) => $record->advance())
    ->modalContent(fn (Contract $record): View => view(
        'filament.pages.actions.advance',
        ['record' => $record]
    ))
```

--------------------------------

### Group multiple bulk actions in dropdown

Source: https://filamentphp.com/docs/4.x/tables/actions

Use BulkActionGroup to organize multiple bulk actions into a dropdown menu, with ungrouped actions rendered as separate buttons. This improves UI organization when you have several bulk actions available. The example demonstrates grouping delete and forceDelete actions while keeping export as a standalone button.

```php
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->toolbarActions([
            BulkActionGroup::make([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
                BulkAction::make('forceDelete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->forceDelete()),
            ]),
            BulkAction::make('export')->button()->action(fn (Collection $records) => ...),
        ]);
}
```

--------------------------------

### Color Entry with Copyable Functionality in PHP

Source: https://filamentphp.com/docs/4.x/infolists/color-entry

Extends the basic ColorEntry with clipboard copy functionality. Clicking the color preview copies the CSS value to clipboard with a custom confirmation message and duration (in milliseconds). This feature requires SSL to be enabled.

```php
use Filament\Infolists\Components\ColorEntry;

ColorEntry::make('color')
    ->copyable()
    ->copyMessage('Copied!')
    ->copyMessageDuration(1500)
```

--------------------------------

### Configure Custom Query String Key for Tab Persistence - Filament PHP

Source: https://filamentphp.com/docs/4.x/schemas/tabs

Customizes the query string parameter name for tab persistence by passing a static string key to persistTabInQueryString(). This example uses 'settings-tab' instead of the default 'tab' key to identify the active tab in the URL.

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])
    ->persistTabInQueryString('settings-tab')
```

--------------------------------

### Define FilamentPHP Form Schema in External Class

Source: https://filamentphp.com/docs/4.x/resources/overview

This PHP example illustrates how to define the actual form fields and layout within an external schema class (e.g., `CustomerForm`). It uses Filament's `components()` method to add `TextInput` fields, specifying their names, validation rules, and other attributes.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

public static function configure(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            // ...
        ]);
}
```

--------------------------------

### Adjust Search Debounce Time for Filament Select Component (PHP)

Source: https://filamentphp.com/docs/4.x/forms/select

This example illustrates how to modify the search debounce delay for Filament's searchable `Select` or `Multi-Select` components using the `searchDebounce()` method. By default, it's 1000ms, and adjusting it can prevent excessive network requests. The method accepts a static integer for milliseconds or a dynamic closure.

```php
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->searchable()
    ->searchDebounce(500)
```

--------------------------------

### Customize Phone Number Validation Regex for a Filament TextInput

Source: https://filamentphp.com/docs/4.x/forms/text-input

This code shows how to override the default phone number validation regex for a specific `TextInput` component using the `telRegex()` method. This allows for precise control over accepted phone number formats.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('phone')
    ->tel()
    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
```

--------------------------------

### Generate importer class with artisan command

Source: https://filamentphp.com/docs/4.x/actions/import

Create a new importer class for a model using the make:filament-importer command. Use the --generate flag to automatically generate columns based on the model's database schema.

```bash
php artisan make:filament-importer Product
```

```bash
php artisan make:filament-importer Product --generate
```

--------------------------------

### Implement Responsive Grid Columns for Filament Widgets

Source: https://filamentphp.com/docs/4.x/widgets

Return an associative array from `getColumns()` to define different numbers of grid columns based on responsive breakpoints (e.g., 'md' for medium, 'xl' for extra-large screens). This allows the dashboard layout to adapt to various screen sizes.

```php
public function getColumns(): int | array
{
    return [
        'md' => 4,
        'xl' => 5,
    ];
}
```

--------------------------------

### Enable Filament Panel Authentication Features (PHP)

Source: https://filamentphp.com/docs/4.x/users

This PHP code snippet shows how to enable various authentication features within a Filament panel configuration. By chaining methods like `login()`, `registration()`, `passwordReset()`, `emailVerification()`, and `profile()` to the panel instance, you can quickly activate standard authentication flows for your application.

```php
use FilamentPanel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->login()
        ->registration()
        ->passwordReset()
        ->emailVerification()
        ->emailChangeVerification()
        ->profile();
}

```

--------------------------------

### Render HTML content in Text component

Source: https://filamentphp.com/docs/4.x/schemas/primes

Shows how to render raw HTML content by passing an HtmlString object to the make() method. Requires careful validation to prevent XSS attacks, as unsafe HTML will make the application vulnerable to security issues.

```php
use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;

Text::make(new HtmlString('<strong>Warning:</strong> Modifying these permissions may give users access to sensitive information.'))
```

--------------------------------

### Create Outlined Button Action in Filament

Source: https://filamentphp.com/docs/4.x/actions/overview

Creates a basic outlined button action using the `outlined()` method on a Filament Action. The method accepts an optional boolean value to control visibility of the button label. This example shows how to create an edit action with an outlined button style that links to a post edit route.

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->button()
    ->outlined()
```

--------------------------------

### Integrate Select with Eloquent BelongsTo relationship

Source: https://filamentphp.com/docs/4.x/forms/select

The `relationship()` method configures a BelongsTo relationship to automatically retrieve options from an Eloquent model. The `titleAttribute` parameter specifies which column generates option labels. This example links an author_id field to an author relationship using the name column as the display label.

```PHP
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
```

--------------------------------

### Configure first day of week in Filament DateTimePicker (PHP)

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

This PHP snippet demonstrates how to customize the first day of the week for a Filament Forms DateTimePicker component. Using the `firstDayOfWeek()` method, an integer value (0-7, where 7 is Sunday) sets the start of the week. The `native(false)` call ensures the component uses Filament's custom UI instead of the browser's native date picker.

```PHP
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->native(false)
    ->firstDayOfWeek(7)
```

--------------------------------

### Configure RichEditor Image Upload Storage and Visibility

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Set up image uploads for RichEditor by specifying the storage disk, directory, and visibility settings. Supports both static values and dynamic functions with injected utilities like $get, $record, and $operation for conditional configuration based on form state.

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->fileAttachmentsDisk('s3')
    ->fileAttachmentsDirectory('attachments')
    ->fileAttachmentsVisibility('private')
```

--------------------------------

### Enable Reordering with Custom Sort Column in Filament Table (PHP)

Source: https://filamentphp.com/docs/4.x/tables/overview

This example shows enabling drag-and-drop reordering using a custom sort column, such as `order_column`, often used with packages like `spatie/eloquent-sortable`. The specified column will store the order of records. Ensure the column is properly configured in your model.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->reorderable('order_column');
}
```

--------------------------------

### Configure Dropdown Width for FilamentPHP ActionGroup

Source: https://filamentphp.com/docs/4.x/actions/grouping-actions

This example illustrates setting the visual width of an `ActionGroup` dropdown in FilamentPHP using the `dropdownWidth()` method. It leverages the `Filament\Support\Enums\Width` enum to specify predefined Tailwind CSS `max-width` options like `Width::ExtraSmall`. This allows developers to control the presentation of action menus for better UI/UX.

```php
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Width;

ActionGroup::make([
    // Array of actions
])
    ->dropdownWidth(Width::ExtraSmall)
```

--------------------------------

### Customize Key Select Field for All Morph Types - Filament

Source: https://filamentphp.com/docs/4.x/forms/select

Apply customizations to the key select field across all morphed types using modifyKeySelectUsing() method directly on the MorphToSelect component. This example demonstrates making the select field native across all morph types.

```php
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;

MorphToSelect::make('commentable')
    ->types([
        MorphToSelect\Type::make(Product::class)
            ->titleAttribute('name'),
        MorphToSelect\Type::make(Post::class)
            ->titleAttribute('title'),
    ])
    ->modifyKeySelectUsing(fn (Select $select): Select => $select->native())
```

--------------------------------

### Customize Full Success Notification Object for FilamentPHP Force-Delete Action (PHP)

Source: https://filamentphp.com/docs/4.x/actions/force-delete

This PHP example illustrates how to completely customize the success notification, including its title and body, for a FilamentPHP `ForceDeleteAction`. It uses the `successNotification()` method to define a custom `Notification` instance, allowing for more detailed feedback to the user.

```php
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;

ForceDeleteAction::make()
    ->successNotification(
       Notification::make()
            ->success()
            ->title('User force-deleted')
            ->body('The user has been force-deleted successfully.'),
    )
```

--------------------------------

### Create new Filament Chart Widget using Artisan

Source: https://filamentphp.com/docs/4.x/widgets/charts

Use this Artisan command to generate a new Filament chart widget. The `--chart` flag ensures that the widget is pre-configured as a chart type, extending the ChartWidget class.

```bash
php artisan make:filament-widget BlogPostsChart --chart
```

--------------------------------

### Prevent Adding Items in Filament Builder (PHP)

Source: https://filamentphp.com/docs/4.x/forms/builder

This example shows how to disable the ability for users to add new items to a Filament Builder component. The `addable(false)` method is used for this purpose, and it can also accept a dynamic function for conditional prevention based on injected utilities.

```php
use FilamentFormsComponentsBuilder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->addable(false)
```

--------------------------------

### Configure Filament Form Fields with Enum Descriptions

Source: https://filamentphp.com/docs/4.x/advanced/enums

This PHP code demonstrates how to integrate an enum (that implements `HasDescription`) with Filament's `Radio` and `CheckboxList` form components. By passing the enum class directly to the `options()` method, Filament automatically populates the choices and displays the descriptions defined in the enum's `getDescription()` method. This simplifies form configuration and provides users with helpful context for each selection.

```php
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;

Radio::make('status')
    ->options(Status::class)

CheckboxList::make('status')
    ->options(Status::class)
```

--------------------------------

### Set Filament Panel Path to '/app'

Source: https://filamentphp.com/docs/4.x/panel-configuration

This PHP code snippet demonstrates how to define the base URL path for a Filament panel within its service provider. Using the `path('app')` method configures the panel to be accessible under the `/app` URL segment.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->path('app');
}
```

--------------------------------

### Manually Set State for Filament TextEntry Components in PHP

Source: https://filamentphp.com/docs/4.x/infolists

This code shows how to explicitly set the display state of a `TextEntry` component using the `state()` method. This allows for overriding the default attribute lookup and providing a custom value, which can also be dynamically calculated with a function.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->state('Hello, world!')
```

--------------------------------

### Set Custom Loading Message for Searchable Select Columns in Filament

Source: https://filamentphp.com/docs/4.x/tables/columns/select

Shows how to display a custom loading message while searchable select or multi-select options are being fetched. The optionsLoadingMessage() method accepts either a static string or a callback function for dynamic message generation. Supports utility injection for accessing column, Livewire component, record, and table context.

```PHP
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('author_id')
    ->optionsRelationship(name: 'author', titleAttribute: 'name')
    ->searchableOptions()
    ->optionsLoadingMessage('Loading authors...')
```

--------------------------------

### Configure Copyable TextColumn with Custom Message

Source: https://filamentphp.com/docs/4.x/tables/columns/text

Makes a TextColumn copyable with a custom confirmation message and display duration. Clicking the column copies the text to clipboard and shows the specified message for the given millisecond duration. Requires SSL to be enabled.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('email')
    ->copyable()
    ->copyMessage('Email address copied')
    ->copyMessageDuration(1500)
```

--------------------------------

### Set Default Active Tab in Filament Schemas

Source: https://filamentphp.com/docs/4.x/schemas/tabs

This PHP example illustrates how to configure the default active tab for a Filament Tabs component using the `activeTab()` method. This allows you to specify which tab is open when the schema initially loads, either with a static value or a dynamically calculated one.

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])
    ->activeTab(2)
```

--------------------------------

### Registering Custom Color Palette in FilamentPHP (PHP)

Source: https://filamentphp.com/docs/4.x/styling/colors

This PHP code demonstrates how to register custom color palettes within FilamentPHP using the `FilamentColor` facade. It shows examples of defining a color, such as 'danger', using both a hexadecimal value and an RGB functional notation for flexibility in color specification.

```php
use Filament\Support\Facades\FilamentColor;

FilamentColor::register([
    'danger' => '#ff0000'
]);

FilamentColor::register([
    'danger' => 'rgb(255, 0, 0)'
]);
```

--------------------------------

### Implement Single Record Deletion in Filament Table

Source: https://filamentphp.com/docs/4.x/resources/deleting-records

This PHP example illustrates how to integrate a `DeleteAction` into the `recordActions` section of a Filament table definition. By adding this action, developers enable users to delete individual records directly from the list page, providing a convenient option for managing records alongside existing bulk-delete functionalities.

```php
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->recordActions([
            // ...
            DeleteAction::make(),
        ]);
}
```

--------------------------------

### Implement Lifecycle Hooks in Filament RestoreAction

Source: https://filamentphp.com/docs/4.x/actions/restore

This PHP code illustrates how to use `before()` and `after()` lifecycle hooks with a Filament `RestoreAction`. These methods allow you to execute custom logic immediately before or after the record restoration process, respectively. The hook functions can accept various injected utilities as parameters, enabling powerful customization.

```php
use Filament\Actions\RestoreAction;

RestoreAction::make()
    ->before(function () {
        // ...
    })
    ->after(function () {
        // ...
    })

```

--------------------------------

### Generate a basic Filament resource for an Eloquent model

Source: https://filamentphp.com/docs/4.x/resources/overview

This command creates a new Filament resource for the specified Eloquent model, generating a resource class, pages, schemas, and tables to manage its data. The generated files provide a foundation for building CRUD interfaces within Filament.

```bash
php artisan make:filament-resource Customer
```

--------------------------------

### Apply static input mask on TextInput (PHP - Filament)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Shows how to apply a static Alpine.js mask to a Filament TextInput. Requires Filament Forms component. Input: the form field value (e.g., 'birthday'); Output: client-side masked input. Limitation: mask is applied client-side; server receives masked value unless stripped.

```PHP
use Filament\Forms\Components\TextInput;

TextInput::make('birthday')
    ->mask('99/99/9999')
    ->placeholder('MM/DD/YYYY')


```

--------------------------------

### Add Static HTML Attributes to Filament Action

Source: https://filamentphp.com/docs/4.x/actions/overview

Demonstrates how to pass static HTML attributes to a Filament action using the extraAttributes() method. The method accepts an array where keys are attribute names and values are attribute values, which get merged onto the action's outer HTML element.

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->extraAttributes([
        'title' => 'Edit this post',
    ])
```

--------------------------------

### Enable Lazy Loading for Filament Livewire Component

Source: https://filamentphp.com/docs/4.x/schemas/custom-components

This code shows how to enable lazy loading for a Livewire component embedded in a Filament schema using the `lazy()` method. Lazy loading defers the component's rendering until it's needed, improving initial page load performance by not loading unnecessary resources upfront.

```php
use Filament\Schemas\Components\Livewire;
use App\Livewire\Chart;

Livewire::make(Chart::class)
    ->lazy()
```

--------------------------------

### Use Custom Column with Closure Utility Injection

Source: https://filamentphp.com/docs/4.x/tables/columns/custom-columns

Demonstrates how to instantiate the AudioPlayerColumn and pass a closure to the speed() method. The closure receives the Conference record as a parameter and returns a float value (1 for global conferences, 0.5 otherwise). The evaluate() method automatically injects the record utility into the closure when executed.

```php
use App\Filament\Tables\Columns\AudioPlayerColumn;

AudioPlayerColumn::make('recording')
    ->speed(fn (Conference $record): float => $record->isGlobal() ? 1 : 0.5)
```

--------------------------------

### Generate a new Livewire component in PHP

Source: https://filamentphp.com/docs/4.x/components/action

This Artisan command creates a new Livewire component named `ManagePost`. This component will serve as the container for implementing and managing Filament actions.

```bash
php artisan make:livewire ManagePost
```

--------------------------------

### Implement Custom Individual Column Search in Filament using PHP

Source: https://filamentphp.com/docs/4.x/tables/custom-data

This PHP example illustrates how to handle individual column searches within a Filament table. Instead of a single global `$search` variable, it injects an `$columnSearches` array into the `records()` function. The code then checks for a search query specific to the 'title' column and filters the collection accordingly, enabling precise filtering for that column by using `searchable(isIndividual: true)`.

```php
use FilamentTablesColumnsTextColumn;
use FilamentTablesTable;
use IlluminateSupportCollection;
use IlluminateSupportStr;

public function table(Table $table): Table
{
    return $table
        ->records(
            fn (array $columnSearches): Collection => collect([
                1 => ['title' => 'First item'],
                2 => ['title' => 'Second item'],
                3 => ['title' => 'Third item'],
            ])->when(
                filled($columnSearches['title'] ?? null),
                fn (Collection $data) => $data->filter(
                    fn (array $record): bool => str_contains(
                        Str::lower($record['title']),
                        Str::lower($columnSearches['title'])
                    ),
                ),
            )
        )
        ->columns([
            TextColumn::make('title')
                ->searchable(isIndividual: true),
        ]);
}
```

--------------------------------

### Register Multiple Error Notifications with HTTP Status Codes on Page

Source: https://filamentphp.com/docs/4.x/panel-configuration

Registers multiple custom error notification messages for different HTTP status codes on a specific page using the setUpErrorNotifications() method. Allows page-level customization of error responses.

```PHP
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function setUpErrorNotifications(): void
    {
        $this->registerErrorNotification(
            title: 'An error occurred',
            body: 'Please try again later.',
        );
    
        $this->registerErrorNotification(
            title: 'Record not found',
            body: 'A record you are looking for does not exist.',
            statusCode: 404,
        );
    }

    // ...
}
```

--------------------------------

### Dynamically Configure FilamentPHP Text Input as Read-Only

Source: https://filamentphp.com/docs/4.x/forms/text-input

Shows how to conditionally set a FilamentPHP `TextInput` as read-only by passing a boolean value or a callback function to the `readOnly()` method. This allows the read-only state to be determined at runtime based on conditions like feature flags or other injected utilities available to the function.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->readOnly(FeatureFlag::active())
```

--------------------------------

### Custom State Casting with castStateUsing in Filament

Source: https://filamentphp.com/docs/4.x/actions/import

Demonstrates custom casting of CSV column data using a closure function. This example converts a price column by removing non-numeric characters, converting to float, and rounding to 2 decimal places. The castStateUsing() method accepts various injected parameters including $state, $column, $data, $importer, $options, $originalData, $originalState, and $record.

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('price')
    ->castStateUsing(function (string $state): ?float {
        if (blank($state)) {
            return null;
        }
        
        $state = preg_replace('/[^0-9.]/', '', $state);
        $state = floatval($state);
    
        return round($state, precision: 2);
    })
```

--------------------------------

### Use status methods for Filament notifications (PHP & JS)

Source: https://filamentphp.com/docs/4.x/notifications

Illustrates the use of convenience methods like `success()`, `warning()`, `danger()`, and `info()` to automatically set notification icons and colors based on a predefined status, simplifying the notification configuration.

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->send();

```

```javascript
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .send()

```

--------------------------------

### Configure grid columns within Filament tabs

Source: https://filamentphp.com/docs/4.x/schemas/tabs

Use the `columns()` method to customize the grid layout within individual tabs. This method accepts either a static integer value or a callback function that can inject utilities like Get, Livewire, model, operation, and record for dynamic column calculation based on component state.

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ])
            ->columns(3),
        // ...
    ])
```

--------------------------------

### Format Money with Currency in Filament TextEntry (PHP)

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This snippet demonstrates how to format a monetary value in a FilamentPHP Infolist `TextEntry` using the `money()` method. It takes the field name (e.g., 'price') and a currency code (e.g., 'EUR') as arguments to display the value appropriately. This requires the `Filament\Infolists\Components\TextEntry` class and can also accept a function for dynamic currency calculation.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('price')
    ->money('EUR')
```

--------------------------------

### Reorder selected options in multi-select with Filament

Source: https://filamentphp.com/docs/4.x/forms/select

The `reorderable()` method enables users to reorder selected options in a multi-select field, useful when the order of selections matters. This example demonstrates a technologies multi-select with reorderable functionality applied to Tailwind CSS, Alpine.js, Laravel, and Livewire options.

```PHP
use Filament\Forms\Components\Select;

Select::make('technologies')
    ->multiple()
    ->reorderable()
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
    ])
```

--------------------------------

### Create a Text Constraint for Filament Query Builder

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

This PHP example illustrates the usage of `TextConstraint` to filter text fields in Filament's Query Builder. It shows how to target a direct column like `name` and also how to filter a related column, such as `creator.name`, using dot syntax for nested relationships.

```php
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('name'); // Filter the `name` column

TextConstraint::make('creator.name'); // Filter the `name` column on the `creator` relationship using dot syntax
```

--------------------------------

### Improve CSV Column Mapping with Guess Method

Source: https://filamentphp.com/docs/4.x/actions/import

Enhance Filament's default column name guessing algorithm by providing additional column name variations using the guess() method. This helps match CSV columns to database columns more accurately across different naming conventions.

```PHP
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->guess(['id', 'number', 'stock-keeping unit'])
```

--------------------------------

### Position ToggleButtons inline (PHP)

Source: https://filamentphp.com/docs/4.x/forms/toggle-buttons

Displays ToggleButtons options inline using the inline() method. Requires Filament\Forms\Components\ToggleButtons; accepts a boolean or a closure to compute the inline state dynamically (the example shows a feature-flag call). Note: closures may receive utility-injected parameters for context-aware behavior.

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->inline();

```

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->inline(FeatureFlag::active());

```

--------------------------------

### Dynamic RichEditor Toolbar Configuration with Utility Injection

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Shows how to use the toolbarButtons() method as a callback function to dynamically calculate toolbar buttons based on form context. Supports injection of utilities like Get, Field, Livewire component, Eloquent model, operation type, and field state for conditional button configuration.

```php
RichEditor::make('content')
    ->toolbarButtons(function (Get $get, Field $component, Livewire $livewire, ?string $model, string $operation, mixed $rawState, ?Illuminate\Database\Eloquent\Model $record, mixed $state) {
        return [
            ['bold', 'italic', 'underline'],
        ];
    })
```

--------------------------------

### Configure JavaScript Asset for On-Demand Loading in Filament (PHP)

Source: https://filamentphp.com/docs/4.x/advanced/assets

To enable a registered JavaScript asset for lazy loading, prevent its automatic inclusion on every page by chaining the `loadedOnRequest()` method during registration. This ensures the script is only loaded when explicitly requested, for example, through an Alpine.js directive, rather than being loaded globally.

```php
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

FilamentAsset::register([
    Js::make('custom-script', __DIR__ . '/../../resources/js/custom.js')->loadedOnRequest(),
]);
```

--------------------------------

### Customize Number of Generated Recovery Codes in Filament Panel

Source: https://filamentphp.com/docs/4.x/users/multi-factor-authentication

By default, Filament generates 8 recovery codes. To change this number, use the 'recoveryCodeCount()' method on the 'AppAuthentication' instance within your panel's 'multiFactorAuthentication()' configuration. Provide the desired number as an argument, for example, 10 recovery codes.

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make()
                ->recoverable()
                ->recoveryCodeCount(10),
        ]);
}
```

--------------------------------

### Configure SPA URL Exceptions with Wildcard Patterns

Source: https://filamentphp.com/docs/4.x/panel-configuration

Disable SPA navigation for multiple URLs matching a pattern using spaUrlExceptions() with wildcard characters. URLs must exactly match the domain and protocol, and asterisks (*) can be used as wildcards to match URL segments. Useful for excluding entire URL paths from SPA navigation.

```PHP
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->spa()
        ->spaUrlExceptions([
            '*/admin/posts/*',
        ]);
}
```

--------------------------------

### Add Static Prefix and Suffix Text to Text Input Column

Source: https://filamentphp.com/docs/4.x/tables/columns/text-input

Use prefix() and suffix() methods to display static text before and after the input field. Useful for displaying units, protocols, or domain extensions alongside user input.

```php
use Filament\Tables\Columns\TextInputColumn;

TextInputColumn::make('domain')
    ->prefix('https://')
    ->suffix('.com')
```

--------------------------------

### Access Owner Record in Filament Relation Manager

Source: https://filamentphp.com/docs/4.x/resources/managing-relationships

Get the parent Eloquent record (owner) from a relation manager using getOwnerRecord() method or access it via callback in static methods like form() and table(). This enables accessing related data and properties from the parent model.

```PHP
$this->getOwnerRecord()
```

```PHP
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            Forms\Components\Select::make('store_id')
                ->options(function (RelationManager $livewire): array {
                    return $livewire->getOwnerRecord()->stores()
                        ->pluck('name', 'id')
                        ->toArray();
                }),
            // ...
        ]);
}
```

--------------------------------

### Set inputmode attribute using inputMode() (PHP - Filament Forms)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Sets the HTML inputmode attribute to influence virtual keyboard behavior (e.g., 'decimal'). Requires Filament Forms and support in the target browsers. Input: inputmode string or callable; Output: TextInput with the inputmode attribute applied. Note: inputMode() also accepts callables for dynamic values.

```php
use Filament\Forms\Components\TextInput;\n\nTextInput::make('text')\n    ->numeric()\n    ->inputMode('decimal')\n
```

--------------------------------

### Define custom non-Tailwind color palettes in Filament (PHP)

Source: https://filamentphp.com/docs/4.x/styling/colors

This advanced example demonstrates how to use custom color palettes that are not part of Tailwind CSS. It requires providing an array of 11 specific OKLCH color shades (from 50 to 950) for a given color name, allowing for highly customized branding.

```php
use Filament\Support\Facades\FilamentColor;

FilamentColor::register([
    'danger' => [
        50 => 'oklch(0.969 0.015 12.422)',
        100 => 'oklch(0.941 0.03 12.58)',
        200 => 'oklch(0.892 0.058 10.001)',
        300 => 'oklch(0.81 0.117 11.638)',
        400 => 'oklch(0.712 0.194 13.428)',
        500 => 'oklch(0.645 0.246 16.439)',
        600 => 'oklch(0.586 0.253 17.585)',
        700 => 'oklch(0.514 0.222 16.935)',
        800 => 'oklch(0.455 0.188 13.697)',
        900 => 'oklch(0.41 0.159 10.272)',
        950 => 'oklch(0.271 0.105 12.094)',
    ],
]);
```

--------------------------------

### Enable Multi-select on Select Component

Source: https://filamentphp.com/docs/4.x/forms/select

Demonstrates basic usage of the `multiple()` method to enable multi-select on a Select component with predefined options. This creates a form field that allows users to select multiple values from the provided options list.

```php
use Filament\Forms\Components\Select;

Select::make('technologies')
    ->multiple()
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
    ])
```

--------------------------------

### Disable Native HTML5 DatePicker in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

This PHP example illustrates how to disable the default native HTML5 date picker for a `DatePicker` component in FilamentPHP, enabling a more customizable JavaScript-based picker. The `native(false)` method can also dynamically determine whether to use the native picker based on injected utilities.

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->native(false)
```

--------------------------------

### Create ColorPicker with default HEX format in Filament

Source: https://filamentphp.com/docs/4.x/forms/color-picker

Initialize a basic ColorPicker component that uses HEX color format by default. This is the simplest implementation requiring only the component import and make() method with a field name.

```php
use Filament\Forms\Components\ColorPicker;

ColorPicker::make('color')
```

--------------------------------

### Apply Custom Font Weight to Filament Link Component (Blade)

Source: https://filamentphp.com/docs/4.x/components/link

Shows how to provide a custom CSS class to the `weight` attribute of the Filament Link component, allowing for more granular control over font styling. This example uses a Tailwind CSS-like class for specific font weight on medium-sized screens.

```blade
<x-filament::link weight="md:font-[650]">
    New user
</x-filament::link>
```

--------------------------------

### Allow HTML in Select Option Labels - Filament

Source: https://filamentphp.com/docs/4.x/forms/select

Enable HTML rendering in select option labels using allowHtml() method. By default, Filament escapes HTML for security. This example demonstrates rendering styled span elements with Tailwind CSS classes in option labels.

```php
use Filament\Forms\Components\Select;

Select::make('technology')
    ->options([
        'tailwind' => '<span class="text-blue-500">Tailwind</span>',
        'alpine' => '<span class="text-green-500">Alpine</span>',
        'laravel' => '<span class="text-red-500">Laravel</span>',
        'livewire' => '<span class="text-pink-500">Livewire</span>',
    ])
    ->searchable()
    ->allowHtml()
```

--------------------------------

### Limit Text Length in Filament Text Column (PHP)

Source: https://filamentphp.com/docs/4.x/tables/columns/text

This example shows how to truncate the text in a Filament `TextColumn` to a specified maximum length. The `limit()` method is used to set the character limit, preventing long text from overflowing. This method also supports dynamic calculation via a callback function.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->limit(50)
```

--------------------------------

### Cache Filament Components - Laravel Artisan

Source: https://filamentphp.com/docs/4.x/deployment

Create cache files in bootstrap/cache/filament directory containing indexes for components. Improves performance by reducing file scanning and auto-discovery overhead, especially beneficial for apps with many resources, pages, widgets, or custom components.

```bash
php artisan filament:cache-components
```

--------------------------------

### Filament: Disable Default Primary Key Sorting for a Table (PHP)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Filament v4 introduces default primary key sorting for tables. To disable this behavior for a specific table, or if your table lacks a primary key, use the `defaultKeySort(false)` method within the table definition.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->defaultKeySort(false);
}
```

--------------------------------

### Implement Custom Sorting for Filament Table with Array Records

Source: https://filamentphp.com/docs/4.x/tables/custom-data

This example demonstrates how to handle sorting for custom data in a Filament table, as Filament's built-in sorting relies on SQL. It injects `$sortColumn` and `$sortDirection` into the `records()` function and uses Laravel's `Collection` to apply custom sorting logic dynamically to the data.

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

public function table(Table $table): Table
{
    return $table
        ->records(
            fn (?string $sortColumn, ?string $sortDirection): Collection => collect([
                1 => ['title' => 'First item'],
                2 => ['title' => 'Second item'],
                3 => ['title' => 'Third item'],
            ])->when(
                filled($sortColumn),
                fn (Collection $data): Collection => $data->sortBy(
                    $sortColumn,
                    SORT_REGULAR,
                    $sortDirection === 'desc',
                ),
            )
        )
        ->columns([
            TextColumn::make('title')
                ->sortable(),
        ]);
}
```

--------------------------------

### Programmatically Manipulate Repeater Component State in FilamentPHP

Source: https://filamentphp.com/docs/4.x/forms/repeater

This snippet demonstrates how to retrieve and modify the entire state of a FilamentPHP Repeater component. It shows getting the current state with `getState()`, adding a new item with a UUID, and then setting the updated state back to the component using `state()`.

```PHP
use Illuminate\Support\Str;

// Get the raw data for the entire repeater
$state = $component->getState();

// Add an item, with a random UUID as the key
$state[Str::uuid()] = [
    'email' => auth()->user()->email,
];

// Set the new data for the repeater
$component->state($state);
```

--------------------------------

### Set Default Values for Custom Filter Fields in Filament

Source: https://filamentphp.com/docs/4.x/tables/filters/custom

Shows how to set default values for fields within a custom filter schema using the `default()` method. This example sets a default date for the 'created_until' field using the `now()` helper function, which automatically populates the field when the filter is initialized.

```PHP
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

Filter::make('created_at')
    ->schema([
        DatePicker::make('created_from'),
        DatePicker::make('created_until')
            ->default(now()),
    ])
```

--------------------------------

### Create basic ExportAction with exporter

Source: https://filamentphp.com/docs/4.x/actions/export

Instantiates an ExportAction and registers a custom exporter class. The exporter defines which columns can be exported and handles the export logic for a specific model.

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
```

--------------------------------

### Configure Static Chunk Size for Filament ImportAction

Source: https://filamentphp.com/docs/4.x/actions/import

This PHP snippet demonstrates how to set a fixed chunk size for an `ImportAction` in Filament. By calling `chunkSize()` with an integer value (e.g., 250), you define how many rows are processed in each queued job. The `chunkSize()` method can also accept a callback function for dynamic calculation, although this example shows a static configuration.

```PHP
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->chunkSize(250)
```

--------------------------------

### Register Event Listener for Automatic Tenant Association

Source: https://filamentphp.com/docs/4.x/users/tenancy

Manually register a listener for the `creating` event to automatically associate models with the current tenant when they don't have a corresponding resource. This ensures the tenant foreign key is set correctly during model creation.

```php
Model::creating(function ($model) {
  $model->tenant_id = filament()->getTenant()->id;
});
```

--------------------------------

### Conditionally disable bulk actions for specific records

Source: https://filamentphp.com/docs/4.x/tables/actions

Use checkIfRecordIsSelectableUsing() to conditionally enable or disable bulk action selection for individual records based on custom logic. This example disables bulk actions for records with a disabled status, preventing users from performing bulk operations on restricted records.

```php
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

public function table(Table $table): Table
{
    return $table
        ->toolbarActions([
            // ...
        ])
        ->checkIfRecordIsSelectableUsing(
            fn (Model $record): bool => $record->status === Status::Enabled,
        );
}
```

--------------------------------

### Integrate multi-select with Eloquent BelongsToMany relationship

Source: https://filamentphp.com/docs/4.x/forms/select

Combining `multiple()` with `relationship()` enables BelongsToMany relationship handling, where Filament automatically loads options and saves selections to the pivot table. If no relationship name is provided, Filament uses the field name. This example demonstrates a technologies multi-select that persists to a pivot table.

```PHP
use Filament\Forms\Components\Select;

Select::make('technologies')
    ->multiple()
    ->relationship(titleAttribute: 'name')
```

--------------------------------

### Update Field::make() method signature in PHP

Source: https://filamentphp.com/docs/4.x/upgrade-guide

The make() method signature has changed for Field, MorphToSelect, Placeholder, and Builder\Block classes. Update overridden make() methods to accept an optional nullable string parameter and return static. This change introduces the getDefaultName() method for providing default name values.

```php
public static function make(?string $name = null): static
```

--------------------------------

### Add Description and Icon to Stat - PHP

Source: https://filamentphp.com/docs/4.x/widgets/stats-overview

Enhance stats with descriptions and icons using the description() and descriptionIcon() methods. This provides additional context for each statistic with visual indicators like trending arrows.

```php
use Filament\Widgets\StatsOverviewWidget\Stat;

protected function getStats(): array
{
    return [
        Stat::make('Unique views', '192.1k')
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
        Stat::make('Bounce rate', '21%')
            ->description('7% decrease')
            ->descriptionIcon('heroicon-m-arrow-trending-down'),
        Stat::make('Average time on page', '3:12')
            ->description('3% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
    ];
}
```

--------------------------------

### Add Tooltip to Filament Image Component (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/primes

Apply a tooltip to a Filament `Image` component using the `tooltip()` method. This allows displaying helpful text on hover and supports both static string values and dynamic functions that can leverage utility injection for complex scenarios.

```php
use Filament\Schemas\Components\Image;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->tooltip('Scan this QR code with your authenticator app')
    ->alignCenter()
```

--------------------------------

### Configure Unique Validation Behavior in Filament Service Provider

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Preserves the v3 unique validation behavior across the entire Filament application by disabling the default ignoreRecord functionality. Add this configuration in the boot() method of AppServiceProvider or any service provider to restore the old behavior where current form records are not automatically ignored during validation.

```php
use Filament\Forms\Components\Field;

Field::configureUsing(fn (Field $field) => $field
    ->uniqueValidationIgnoresRecordByDefault(false));
```

--------------------------------

### Configure Filament table for ModalTableSelect (PHP)

Source: https://filamentphp.com/docs/4.x/forms/select

This code defines a static `configure` method within a PHP class, `CategoriesTable`, to set up a Filament table. It specifies columns like 'name' and 'slug' as `TextColumn` and includes a `SelectFilter` for a 'parent' relationship, enabling advanced searching and filtering. This configuration class is a prerequisite for the `ModalTableSelect` component.

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('parent')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
```

--------------------------------

### Manually Reload FilamentPHP Sidebar and Topbar

Source: https://filamentphp.com/docs/4.x/navigation/overview

These code examples demonstrate various ways to manually refresh the sidebar and topbar components in FilamentPHP. You can dispatch `refresh-sidebar` or `refresh-topbar` events from PHP within a Livewire component or a Filament action, or from JavaScript using Alpine.js's `$dispatch()` or native `window.dispatchEvent()`.

```php
$this->dispatch('refresh-sidebar');
```

```php
use Filament\Actions\Action;
use Livewire\Component;

Action::make('create')
    ->action(function (Component $livewire) {
        // ...
    
        $livewire->dispatch('refresh-sidebar');
    })
```

```html
<button x-on:click="$dispatch('refresh-sidebar')" type="button">
    Refresh Sidebar
</button>
```

```javascript
window.dispatchEvent(new CustomEvent('refresh-sidebar'));
```

--------------------------------

### Configure FilamentPHP Text Input as Read-Only Statically

Source: https://filamentphp.com/docs/4.x/forms/text-input

Demonstrates how to make a FilamentPHP `TextInput` read-only using the `readOnly()` method without dynamic conditions. This prevents user modification in the UI but still submits the field's value to the server unless `dehydrated(false)` is explicitly used to prevent server-side processing.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->readOnly()
```

--------------------------------

### Create Custom Resource Page with Artisan Command

Source: https://filamentphp.com/docs/4.x/resources/custom-pages

Generate a new custom resource page using the make:filament-page artisan command. This creates both a page class in the Pages directory and a corresponding view file, providing a foundation for custom resource page functionality.

```bash
php artisan make:filament-page SortUsers --resource=UserResource --type=custom
```

--------------------------------

### Halt Saving Process with Subscription Check in Filament EditAction

Source: https://filamentphp.com/docs/4.x/actions/edit

Demonstrates how to halt the entire saving process in a Filament EditAction by calling $action->halt() within a before() lifecycle hook. This example checks if a user has an active subscription and displays a notification with action links before halting the operation if the subscription is inactive.

```PHP
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;

EditAction::make()
    ->before(function (EditAction $action, Post $record) {
        if (! $record->team->subscribed()) {
            Notification::make()
                ->warning()
                ->title('You don\'t have an active subscription!')
                ->body('Choose a plan to continue.')
                ->persistent()
                ->actions([
                    Action::make('subscribe')
                        ->button()
                        ->url(route('subscribe'), shouldOpenInNewTab: true),
                ])
                ->send();
        
            $action->halt();
        }
    })
```

--------------------------------

### Integrate Reusable Filament Infolist Schema into Resource (PHP)

Source: https://filamentphp.com/docs/4.x/resources/code-quality-tips

This snippet shows how to integrate a dedicated infolist schema class (`CustomerInfolist`) into the `infolist()` method of a Filament resource. This method promotes consistency and reusability for infolist definitions, preventing the infolist logic from cluttering the main resource file. It assumes the existence of a `CustomerInfolist` class with a `configure` method.

```php
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use Filament\Schemas\Schema;

public static function infolist(Schema $schema): Schema
{
    return CustomerInfolist::configure($schema);
}
```

--------------------------------

### Configure Min and Max Date Validation in Filament DatePicker

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

Sets minimum and maximum date constraints on a DatePicker component using minDate() and maxDate() methods. Accepts DateTime instances (like Carbon) or strings. The example restricts date selection to the last 150 years from today. These methods can also accept callback functions that return null to conditionally disable validation.

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->native(false)
    ->minDate(now()->subYears(150))
    ->maxDate(now())
```

--------------------------------

### Define Infolist or Form Schema for a Filament View Page (PHP)

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

This PHP method allows defining the structure and components of an infolist or form for a specific Filament View page. It uses Filament's Schema builder to construct the display logic for the page's content. This enables customizing what information is shown on the page, separate from the main resource view.

```php
use Filament\Schemas\Schema;

public function infolist(Schema $schema): Schema
{
    return $schema
        ->components([
            // ...
        ]);
}
```

--------------------------------

### Configure Live Column Manager in Filament Tables

Source: https://filamentphp.com/docs/4.x/tables/columns/overview

This snippet explains how to make column manager changes, such as toggling and reordering, apply instantly without an 'Apply' button. It uses the `deferColumnManager(false)` method to disable deferred updates for a more interactive user experience.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->reorderableColumns()
        ->deferColumnManager(false);
}
```

--------------------------------

### Filament Infolists: Example RepeatableEntry Data Structure

Source: https://filamentphp.com/docs/4.x/infolists/repeatable-entry

This JSON snippet illustrates the data structure that a Filament `RepeatableEntry` component might represent. It shows an array of objects, where each object contains properties like 'author', 'title', and 'content', mirroring the schema defined for the entry. This structure can be directly sourced from arrays or Eloquent relationships.

```json
[
    [
        "author" => ["name" => "Jane Doe"],
        "title" => "Wow!",
        "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nisl."
    ],
    [
        "author" => ["name" => "John Doe"],
        "title" => "This isn't working. Help!",
        "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nisl."
    ]
]
```

--------------------------------

### SelectColumn Lifecycle Hooks - beforeStateUpdated and afterStateUpdated

Source: https://filamentphp.com/docs/4.x/tables/columns/select

Demonstrates the use of lifecycle hooks on a SelectColumn to execute code before and after state updates are saved to the database. The beforeStateUpdated hook runs before the state is persisted, while afterStateUpdated runs after. Both hooks receive the record and new state value as parameters.

```php
SelectColumn::make()
    ->beforeStateUpdated(function ($record, $state) {
        // Runs before the state is saved to the database.
    })
    ->afterStateUpdated(function ($record, $state) {
        // Runs after the state is saved to the database.
    })
```

--------------------------------

### Filament: Disable Default Primary Key Sorting Globally (PHP)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

To revert the default primary key sorting behavior across your entire Filament v4 application, configure the `Table` class in a service provider's `boot()` method. This will apply `defaultKeySort(false)` to all tables by default.

```php
use Filament\Tables\Table;

Table::configureUsing(fn (Table $table) => $table
    ->defaultKeySort(false));
```

--------------------------------

### Add Validation Rules to Filament TagsInput

Source: https://filamentphp.com/docs/4.x/forms/tags-input

This example demonstrates how to apply validation rules to individual tags within a Filament `TagsInput` component. The `nestedRecursiveRules()` method accepts an array of standard Laravel validation rules, ensuring each tag meets specified criteria like minimum and maximum length.

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->nestedRecursiveRules([
        'min:3',
        'max:255',
    ])
```

--------------------------------

### Register View Page in Resource getPages Method

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

Register the newly created View page in the resource's getPages() method to make it accessible. The method returns an array mapping page names to their route definitions, where 'view' maps to the ViewRecord page with a route parameter for the record ID.

```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListUsers::route('/'),
        'create' => Pages\CreateUser::route('/create'),
        'view' => Pages\ViewUser::route('/{record}'),
        'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
}
```

--------------------------------

### Define Infolist for Resource View Page

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

Replace the default disabled form with an infolist by implementing an infolist() method in the resource class. The infolist displays record data using entry components organized through the components() method, providing a read-only structured layout.

```php
use Filament\Infolists;
use Filament\Schemas\Schema;

public static function infolist(Schema $schema): Schema
{
    return $schema
        ->components([
            Infolists\Components\TextEntry::make('name'),
            Infolists\Components\TextEntry::make('email'),
            Infolists\Components\TextEntry::make('notes')
                ->columnSpanFull(),
        ]);
}
```

--------------------------------

### Set Value Field Label in Filament KeyValue Component (PHP)

Source: https://filamentphp.com/docs/4.x/forms/key-value

This PHP example illustrates how to customize the label displayed for the value fields in a Filament `KeyValue` component using the `valueLabel()` method. This method accepts a static string or a callable function for dynamic label generation, with various form utilities available for injection.

```php
use Filament\Forms\Components\KeyValue;

KeyValue::make('meta')
    ->valueLabel('Property value')
```

--------------------------------

### Create View Page for Existing Filament Resource

Source: https://filamentphp.com/docs/4.x/resources/viewing-records

Add a View page to an existing resource by generating a new ViewRecord page in the resource's Pages directory. This creates the page class that handles displaying individual record details.

```shell
php artisan make:filament-page ViewUser --resource=UserResource --type=ViewRecord
```

--------------------------------

### Conditionally Hide a Filament Table Column Summary

Source: https://filamentphp.com/docs/4.x/tables/summaries

This snippet illustrates how to hide a column summary based on a specified condition using the `hidden()` method. A callback function is provided, which receives the Eloquent query builder instance, allowing dynamic evaluation, for example, hiding the summary if no records exist for the column.

```php
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

TextColumn::make('sku')
    ->summarize(Summarizer::make()
        ->hidden(fn (Builder $query): bool => ! $query->exists()))
```

--------------------------------

### Generate Livewire Component with Artisan

Source: https://filamentphp.com/docs/4.x/components/form

Creates a new Livewire component class named CreatePost using Laravel's artisan command. This component will serve as the foundation for building the form.

```bash
php artisan make:livewire CreatePost
```

--------------------------------

### Set first day of week using semantic helpers in Filament DateTimePicker (PHP)

Source: https://filamentphp.com/docs/4.x/forms/date-time-picker

This PHP snippet illustrates the use of semantic helper methods, `weekStartsOnMonday()` and `weekStartsOnSunday()`, to define the first day of the week for a Filament DateTimePicker. These methods offer a more readable alternative to numerical input for the `firstDayOfWeek()` method. The `native(false)` call configures the component to use Filament's custom UI.

```PHP
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->native(false)
    ->weekStartsOnMonday()

DateTimePicker::make('published_at')
    ->native(false)
    ->weekStartsOnSunday()
```

--------------------------------

### Mutate record data before form population

Source: https://filamentphp.com/docs/4.x/actions/edit

Use the mutateRecordDataUsing() method to modify the data array before it is populated into the form fields. This example sets the user_id to the authenticated user's ID. The closure receives the data array and must return the modified array before form display.

```php
use Filament\Actions\EditAction;

EditAction::make()
    ->mutateRecordDataUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })
```

--------------------------------

### Old Filament Custom Theme CSS Import Structure (CSS)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This CSS snippet shows the previous import structure for custom Filament themes in older versions (v3). It directly imported the core Filament theme CSS and used an `@config` directive to link to the project's `tailwind.config.js` file for Tailwind CSS configuration.

```css
@import '../../../../vendor/filament/filament/resources/css/theme.css';

@config 'tailwind.config.js';
```

--------------------------------

### Add Tooltip to Filament Infolist Entry

Source: https://filamentphp.com/docs/4.x/infolists

Shows how to attach a static tooltip to a Filament `TextEntry` component using the `tooltip()` method. The tooltip will display when a user hovers over the entry.

```php
use FilamentInfolistsComponentsTextEntry;

TextEntry::make('title')
    ->tooltip('Shown at the top of the page')
```

--------------------------------

### Control Slider Track Behavioral Padding

Source: https://filamentphp.com/docs/4.x/forms/slider

The rangePadding() method adds behavioral padding to constrain the selectable range. A single value applies equal padding to both ends, while an array [start, end] applies asymmetric padding. The method accepts static values or callables with utility injection for dynamic calculation.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 0, maxValue: 100)
    ->rangePadding(10)
```

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 0, maxValue: 100)
    ->rangePadding([10, 20])
```

--------------------------------

### Dynamically control FilamentPHP TextColumn limited list expansion

Source: https://filamentphp.com/docs/4.x/tables/columns/text

This example shows how to dynamically control whether a limited list in a FilamentPHP `TextColumn` is expandable. You can pass a boolean value or a function to the `expandableLimitedList()` method, allowing for conditional expansion based on various injected utilities like the column instance, Livewire component, Eloquent record, or state.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)
    ->expandableLimitedList(FeatureFlag::active())
```

--------------------------------

### Enable Automatic Dropdown Placement

Source: https://filamentphp.com/docs/4.x/actions/grouping-actions

Automatically determines the optimal dropdown position based on available screen space using dropdownAutoPlacement() method. Prevents dropdowns from being cut off by viewport edges or overlapping content.

```php
use Filament\Actions\ActionGroup;

ActionGroup::make([
    // Array of actions
])
    ->dropdownAutoPlacement()
```

--------------------------------

### Enable Collapsible Sidebar on Desktop

Source: https://filamentphp.com/docs/4.x/navigation

Make the sidebar collapsible on desktop using sidebarCollapsibleOnDesktop(). Optionally use sidebarFullyCollapsibleOnDesktop() to fully hide the sidebar including navigation icons when collapsed.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarCollapsibleOnDesktop();
}
```

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarFullyCollapsibleOnDesktop();
}
```

--------------------------------

### Implement Custom Blade View for FilamentPHP Page

Source: https://filamentphp.com/docs/4.x/resources/editing-records

This snippet demonstrates the complete process of using a custom Blade view for a FilamentPHP page. It includes setting the `$view` property in the page class to point to a custom template and provides an example of the Blade template structure itself, showcasing how to render default content or build a new layout from scratch.

```php
protected string $view = 'filament.resources.users.pages.edit-user';
```

```blade
<x-filament-panels::page>
    {{-- `$this->getRecord()` will return the current Eloquent record for this page --}}
    
    {{ $this->content }} {{-- This will render the content of the page defined in the `content()` method, which can be removed if you want to start from scratch --}}
</x-filament-panels::page>
```

--------------------------------

### Lazy load CSS from plugin with package parameter

Source: https://filamentphp.com/docs/4.x/advanced/assets

Loads CSS files from a plugin package on-demand by specifying the package name in the getStyleHref() method. Use this when registering CSS files from plugin vendor directories.

```html
<div
    x-data="{}"
    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('custom-stylesheet', package: 'danharrin/filament-blog'))]"
>
    <!-- ... -->
</div>
```

--------------------------------

### Apply dynamic input mask with RawJs (PHP - Filament)

Source: https://filamentphp.com/docs/4.x/forms/text-input

Demonstrates using Filament\Support\RawJs to provide a dynamic Alpine.js mask expression from PHP. Requires Filament Support RawJs. Input: runtime field value (accessible as $input in the JS expression); Output: mask string determined by the JS. Note: you can also inject server-side utilities into mask() when providing a PHP callable.

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

TextInput::make('cardNumber')
    ->mask(RawJs::make(<<<'JS'
        $input.startsWith('34') || $input.startsWith('37') ? '9999 999999 99999' : '9999 9999 9999 9999'
    JS))


```

--------------------------------

### Configure Spark Billing Provider in Filament Panel

Source: https://filamentphp.com/docs/4.x/users/tenancy

Set up the Spark billing provider in the Filament panel configuration using tenantBillingProvider(). This integrates Laravel Spark billing capabilities with Filament, enabling users to manage subscriptions via a link in the tenant menu.

```PHP
use Filament\Billing\Providers\SparkBillingProvider;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantBillingProvider(new SparkBillingProvider());
}
```

--------------------------------

### FilamentPHP: Disable All Form Fields in Action Modal

Source: https://filamentphp.com/docs/4.x/actions/modals

This example illustrates the use of the `disabledForm()` method to render all form fields within an action's modal as uneditable. This is useful when you want to display information without allowing the user to modify it, such as for confirmation or review purposes.

```php
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

Action::make('approvePost')
    ->schema([
        TextInput::make('title'),
        Textarea::make('content'),
    ])
    ->disabledForm()
    ->action(function (Post $record): void {
        $record->approve();
    })
```

--------------------------------

### Split CheckboxList Options into Columns

Source: https://filamentphp.com/docs/4.x/forms/checkbox-list

Configure a CheckboxList component to display options across multiple columns using the columns() method with a static integer value. This method also accepts a callable for dynamic column calculation with access to form utilities like $get, $component, $state, and $record.

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->columns(2)
```

--------------------------------

### Create Custom Filter with Schema Components in Filament

Source: https://filamentphp.com/docs/4.x/tables/filters/custom

Demonstrates how to create a custom date range filter using schema components (DatePicker) with a query callback that filters records. The filter data is available in the $data array, allowing flexible query modifications based on user input. Supports utility injection including Builder, array data, Get function, Livewire component, model, operation, query, raw state, record, and state parameters.

```PHP
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

Filter::make('created_at')
    ->schema([
        DatePicker::make('created_from'),
        DatePicker::make('created_until'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['created_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            )
            ->when(
                $data['created_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            );
    })
```

--------------------------------

### Filament PHP: Demonstrating Full Component Re-rendering

Source: https://filamentphp.com/docs/4.x/forms

This code snippet illustrates how a reactive field, configured with `live()`, can trigger a full re-render of the entire Livewire component. When the 'name' input changes, the 'email' input's label, which depends on the 'name' field's state, is updated after a full component re-render, showcasing a common performance bottleneck.

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

TextInput::make('name')
    ->live();
    
TextInput::make('email')
    ->label(fn (Get $get): string => filled($get('name')) ? "Email address for {$get('name')}" : 'Email address');
```

--------------------------------

### Add Fluent make() Method to Plugin Class

Source: https://filamentphp.com/docs/4.x/plugins/panel-plugins

Add a static make() method to the plugin class that returns a container-instantiated instance, enabling fluent initialization and runtime replacement of the plugin. This pattern provides flexibility for dependency injection and custom implementations.

```php
use Filament\Contracts\Plugin;

class BlogPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }
    
    // ...
}
```

--------------------------------

### Conditionally Fill Filament Slider Track with Color

Source: https://filamentphp.com/docs/4.x/forms/slider

This example illustrates how to conditionally enable or disable track filling for a FilamentPHP Slider. By passing a boolean value to the `fillTrack()` method, the track's filled state can be dynamically controlled, useful for varying visual cues based on application logic.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 0, maxValue: 100)
    ->fillTrack(FeatureFlag::active())
```

--------------------------------

### Open URLs from Filament Notification Actions (PHP & JavaScript)

Source: https://filamentphp.com/docs/4.x/notifications/overview

Shows how to configure notification actions to open a specified URL. This includes options for opening the URL in a new tab, providing dynamic routing in PHP, and static paths in JavaScript.

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the post have been saved.')
    ->actions([
        Action::make('view')
            ->button()
            ->url(route('posts.show', $post), shouldOpenInNewTab: true),
        Action::make('undo')
            ->color('gray'),
    ])
    ->send();
```

```javascript
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .actions([
        new FilamentNotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new FilamentNotificationAction('undo')
            .color('gray'),
    ])
    .send()
```

--------------------------------

### Test Table Rendering with Livewire

Source: https://filamentphp.com/docs/4.x/testing/testing-tables

Verify that a Filament table component renders successfully using the assertSuccessful() Livewire helper. This is the basic test to ensure the table page loads without errors.

```php
use function Pest\Livewire\livewire;

it('can render page', function () {
    livewire(ListPosts::class)
        ->assertSuccessful();
});
```

--------------------------------

### Hide Entry Using JavaScript Expression in Filament

Source: https://filamentphp.com/docs/4.x/infolists

Uses the `hiddenJs()` method to conditionally hide an infolist entry with a client-side JavaScript expression. This approach avoids network requests by evaluating the condition in the browser using Filament's `$get()` utility function, providing better performance than server-side callbacks.

```php
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])

IconEntry::make('is_admin')
    ->boolean()
    ->hiddenJs(<<<'JS'
        $get('role') !== 'staff'
        JS)
```

--------------------------------

### Implement Record-Based Custom Page with InteractsWithRecord Trait

Source: https://filamentphp.com/docs/4.x/resources/custom-pages

Create a custom page that works with a resource record using the InteractsWithRecord trait. The mount() method resolves the record from the URL and stores it in $this->record, which can be accessed throughout the class and view using $this->getRecord().

```php
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ManageUser extends Page
{
    use InteractsWithRecord;
    
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    // ...
}
```

--------------------------------

### Create a Boolean Constraint for Filament Query Builder

Source: https://filamentphp.com/docs/4.x/tables/filters/query-builder

This PHP snippet demonstrates how to use `BooleanConstraint` to filter boolean fields within Filament's Query Builder. It provides examples for filtering a direct boolean column (`is_visible`) and a boolean column on a related model (`creator.is_admin`) using dot notation.

```php
use Filament\QueryBuilder\Constraints\BooleanConstraint;

BooleanConstraint::make('is_visible'); // Filter the `is_visible` column

BooleanConstraint::make('creator.is_admin'); // Filter the `is_admin` column on the `creator` relationship using dot syntax
```

--------------------------------

### Create Basic Bulk Action in Filament Table

Source: https://filamentphp.com/docs/4.x/tables/actions

Defines a bulk action using the BulkAction::make() method with a callback function that receives selected Eloquent records. The action is added to the table's toolbarActions() method and includes confirmation requirement.

```PHP
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->toolbarActions([
            // ...
        ]);
}
```

```PHP
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

BulkAction::make('delete')
    ->requiresConfirmation()
    ->action(fn (Collection $records) => $records->each->delete())
```

--------------------------------

### Test Filament Resource List Page Loading and Records

Source: https://filamentphp.com/docs/4.x/testing/testing-resources

This test ensures a Filament resource list page loads correctly and displays its associated records. It uses Livewire's `livewire()` helper to interact with the component, `assertOk()` to verify a successful HTTP response, and `assertCanSeeTableRecords()` to confirm record visibility.

```php
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

it('can load the page', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users);
});
```

--------------------------------

### Render Heading Component with Blade Template

Source: https://filamentphp.com/docs/4.x/plugins/building-a-standalone-plugin

Creates a dynamic Blade view that renders heading elements (h1-h6) with asynchronous stylesheet loading via x-load-css. The template evaluates component properties for level, color, and content, applying Tailwind CSS classes and custom color variables for styling.

```blade
@php
    $level = $getLevel();
    $color = $getColor();
@endphp

<{{ $level }}
    x-data
    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('headings', package: 'awcodes/headings'))]"
    {{
        $attributes
            ->class([
                'headings-component',
                match ($color) {
                    'gray' => 'text-gray-600 dark:text-gray-400',
                    default => 'text-custom-500',
                },
            ])
            ->style([
                \Filament\Support\get_color_css_variables($color, [500]) => $color !== 'gray',
            ])
    }}
>
    {{ $getContent() }}
</{{ $level }}>
```

--------------------------------

### Define Infolist Schema Method in Livewire Component

Source: https://filamentphp.com/docs/4.x/components/infolist

Add a public method to your Livewire component that accepts a Filament `Schema` object. This method is responsible for defining the structure and components of your infolist.

```php
use Filament\Schemas\Schema;

public function productInfolist(Schema $schema): Schema
{
    return $schema
        ->record($this->product)
        ->components([
            // ...
        ]);
}
```

--------------------------------

### Disable placeholder selection in Filament SelectColumn (PHP)

Source: https://filamentphp.com/docs/4.x/tables/columns/select

This example illustrates how to prevent users from selecting the default null or placeholder option in a Filament `SelectColumn`. Calling `selectablePlaceholder(false)` ensures that users must choose one of the explicitly defined options, enforcing selection. This method supports both static boolean values and dynamic functions for control.

```php
use Filament\Tables\Columns\SelectColumn;

SelectColumn::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
    ->selectablePlaceholder(false)
```

--------------------------------

### Filament Table Layout: Define Split Breakpoint with from()

Source: https://filamentphp.com/docs/4.x/tables/layout

This code shows how to control the responsive behavior of a `Split` component using the `from()` method. Columns within the split will stack on top of each other *before* the specified breakpoint ('md' in this case) and appear horizontally *from* that breakpoint onwards.

```php
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

Split::make([
    ImageColumn::make('avatar')
        ->circular(),
    TextColumn::make('name')
        ->weight(FontWeight::Bold)
        ->searchable()
        ->sortable(),
    TextColumn::make('email'),
])->from('md')
```

--------------------------------

### Filament Toggle Declined Validation - Dynamic with Utility Injection

Source: https://filamentphp.com/docs/4.x/forms/toggle

Uses a closure with injected utilities to dynamically calculate the declined validation rule. Supports injection of Field component, Get function, Livewire instance, model FQN, operation type, raw state, Eloquent record, and current state for complex conditional validation logic.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_under_18')
    ->declined(function ($component, $get, $livewire, $model, $operation, $rawState, $record, $state) {
        // Dynamic validation logic using injected utilities
        return true; // or false based on conditions
    })
```

--------------------------------

### Customize Job Queue for Filament Importer

Source: https://filamentphp.com/docs/4.x/actions/import

Override the `getJobQueue()` method within your Filament importer class to direct import jobs to a specific queue. This enables managing import job priorities and resource allocation independently.

```php
public function getJobQueue(): ?string
{
    return 'imports';
}
```

--------------------------------

### Customize Filament View Action Data Before Form Filling

Source: https://filamentphp.com/docs/4.x/actions/view

This code example illustrates how to modify a record's data before it is filled into the View action's form. By using the `mutateRecordDataUsing()` method, you can intercept and alter the `$data` array, such as assigning the authenticated user's ID, ensuring the modified data is displayed in the modal.

```php
use Filament\Actions\ViewAction;

ViewAction::make()
    ->mutateRecordDataUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })
```

--------------------------------

### Register Global Search Key Bindings - PHP

Source: https://filamentphp.com/docs/4.x/resources/global-search

Configures keyboard shortcuts to open the global search field. This is done by passing an array of key bindings to the `globalSearchKeyBindings()` method in the panel configuration.

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->globalSearchKeyBindings(['command+k', 'ctrl+k']);
}
```

--------------------------------

### Customize Add Action Button Label for Filament Repeater

Source: https://filamentphp.com/docs/4.x/forms/repeater

This example shows how to change the text displayed on the 'Add Item' button for a Filament Repeater component. The `addActionLabel()` method allows setting a custom string, improving user experience and clarity within the form. It also supports dynamic values through a function.

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->addActionLabel('Add member')
```

--------------------------------

### Configure Global Tenant Ownership Relationship in Filament Panel

Source: https://filamentphp.com/docs/4.x/users/tenancy

Shows how to globally customize the ownership relationship name used across all resources for a tenant. This is achieved by passing the `ownershipRelationship` argument to the `tenant()` method in the Filament panel configuration, specifying the name of the relationship on the resource models.

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class, ownershipRelationship: 'owner');
}
```

--------------------------------

### Register JavaScript Asset in Filament Service Provider (PHP)

Source: https://filamentphp.com/docs/4.x/advanced/assets

To make a JavaScript file available across your Filament application, register it within a service provider's `boot()` method. This process involves using `FilamentAsset::register()` and `Js::make()` to define the asset's unique ID and its path, typically relative to `__DIR__`. Registered assets are copied to the public directory and loaded into all Filament Blade views upon running `php artisan filament:assets`.

```php
use Filament\Support\Assets\Js;

FilamentAsset::register([
    Js::make('custom-script', __DIR__ . '/../../resources/js/custom.js'),
]);
```

--------------------------------

### Add tenant route prefix to panel configuration

Source: https://filamentphp.com/docs/4.x/users/tenancy

Customize the URL structure for tenant routes by adding a prefix using the tenantRoutePrefix() method. This changes the URL from the default format (e.g., /admin/1) to a prefixed format (e.g., /admin/team/1), improving URL readability and SEO.

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->path('admin')
        ->tenant(Team::class)
        ->tenantRoutePrefix('team');
}
```

--------------------------------

### Set custom 'no search results' message for Filament Select component (PHP)

Source: https://filamentphp.com/docs/4.x/forms/select

This example shows how to configure a custom message to display when no search results are found in a searchable `Select` component within Filament PHP. The `noSearchResultsMessage()` method is used with a static string, and it also supports dynamic functions with utility injection. The `Select` component is set up with a relationship and enabled for searching.

```php
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->searchable()
    ->noSearchResultsMessage('No authors found.')
```

--------------------------------

### Conditionally Preserve Original Filenames in Filament FileUpload

Source: https://filamentphp.com/docs/4.x/forms/file-upload

This example illustrates how to dynamically control filename preservation in the Filament `FileUpload` component by passing a boolean value to `preserveFilenames()`. This allows for conditional activation, such as based on a feature flag, but still requires awareness of security risks, particularly with `local` or `public` disk usage.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->preserveFilenames(FeatureFlag::active())
```

--------------------------------

### Vertically Align Filament Table Column Content (PHP)

Source: https://filamentphp.com/docs/4.x/tables/columns/overview

Aligns the content within a Filament table column to the start, center, or end. This can be achieved using dedicated methods like `verticallyAlignStart()`, `verticallyAlignCenter()`, `verticallyAlignEnd()`, or by passing a `VerticalAlignment` enum to the `verticalAlignment()` method. Dynamic alignment is also supported via a callback function.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('name')
    ->verticallyAlignStart()

TextColumn::make('name')
    ->verticallyAlignCenter() // This is the default alignment.

TextColumn::make('name')
    ->verticallyAlignEnd()
```

```php
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('name')
    ->verticalAlignment(VerticalAlignment::Start)
```

--------------------------------

### Create a basic Filament Blade Dropdown component

Source: https://filamentphp.com/docs/4.x/components/dropdown

This snippet demonstrates the basic structure of a Filament dropdown component, including a trigger button and a list of dropdown items. Each item can trigger Livewire actions.

```blade
<x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::button>
            More actions
        </x-filament::button>
    </x-slot>
    
    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item wire:click="openViewModal">
            View
        </x-filament::dropdown.list.item>
        
        <x-filament::dropdown.list.item wire:click="openEditModal">
            Edit
        </x-filament::dropdown.list.item>
        
        <x-filament::dropdown.list.item wire:click="openDeleteModal">
            Delete
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>
```

--------------------------------

### Set custom search prompt for Filament Select component (PHP)

Source: https://filamentphp.com/docs/4.x/forms/select

This code snippet illustrates how to define a custom search prompt message for a searchable `Select` component in Filament PHP. The `searchPrompt()` method is utilized with a static string, and it also supports dynamic functions with utility injection. The `Select` component is configured with a relationship and searchable by specified attributes.

```php
use Filament\Forms\Components\Select;

Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->searchable(['name', 'email'])
    ->searchPrompt('Search authors by their name or email address')
```

--------------------------------

### Add a description to Filament Chart Widget (PHP)

Source: https://filamentphp.com/docs/4.x/widgets/charts

To display a descriptive text below the heading of a Filament chart widget, implement the `getDescription()` method. This method should return a string containing the desired text, enhancing user understanding of the chart's purpose or data.

```php
public function getDescription(): ?string
{
    return 'The number of blog posts published per month.';
}
```

--------------------------------

### Access Dashboard Filters in FilamentPHP Widgets

Source: https://filamentphp.com/docs/4.x/widgets/overview

This example illustrates how a FilamentPHP widget can access data from the dashboard's filter form to dynamically adjust its displayed content. By using the `InteractsWithPageFilters` trait, widget classes can retrieve raw filter values via `$this->pageFilters` and apply them to data queries, such as filtering blog posts by date.

```php
use App\Models\BlogPost;
use Carbon\CarbonImmutable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class BlogPostsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    public function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        return [
            StatsOverviewWidget\Stat::make(
                label: 'Total posts',
                value: BlogPost::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count(),
            ),
            // ...
        ];
    }
}
```

--------------------------------

### Insert Components into Filament PHP Section Header

Source: https://filamentphp.com/docs/4.x/schemas/sections

This snippet illustrates how to insert components, such as actions, into the header of a Filament PHP section using the `afterHeader()` method. The method accepts an array of schema components. It can also accept a closure for dynamic component rendering, allowing injection of utilities like `$component`, `$get`, and `$record`.

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->afterHeader([
        Action::make('test'),
    ])
    ->schema([
        // ...
    ])
```

--------------------------------

### Dynamically Customize Filament SelectColumn Option Labels with `getOptionLabelFromRecordUsing`

Source: https://filamentphp.com/docs/4.x/tables/columns/select

This snippet demonstrates using the `getOptionLabelFromRecordUsing` method to create highly customized labels for `SelectColumn` options. It allows transforming an Eloquent model record into a string label using a closure, and also shows how to order the relationship query and enable searchable options.

```php
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

SelectColumn::make('author_id')
    ->optionsRelationship(
        name: 'author',
        modifyQueryUsing: fn (Builder $query) => $query->orderBy('first_name')->orderBy('last_name'),
    )
    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
    ->searchableOptions(['first_name', 'last_name'])
```

--------------------------------

### Globally Disable Deferred Table Filters in Filament (PHP)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This PHP code provides a method to globally revert the default deferred filter behavior in Filament v4. By configuring `Table::configureUsing()` with `deferFilters(false)` in a service provider's `boot()` method, all tables across the application will apply filters immediately, matching the Filament v3 behavior.

```php
use Filament\Tables\Table;

Table::configureUsing(fn (Table $table) => $table
    ->deferFilters(false));
```

--------------------------------

### Create Link Trigger Style in Filament

Source: https://filamentphp.com/docs/4.x/actions/overview

Demonstrates creating a link-style action trigger in Filament. The link() method removes background color and displays the action as inline text, suitable for less prominent actions or secondary operations.

```PHP
use Filament\Actions\Action;

Action::make('edit')
    ->link()
```

--------------------------------

### Use FilamentPHP Action Class in Table Record Actions

Source: https://filamentphp.com/docs/4.x/resources/code-quality-tips

This PHP example demonstrates how to integrate a dedicated `EmailCustomerAction` class into a FilamentPHP table's record actions. This allows users to execute the pre-defined action directly on individual table records, centralizing action management. It depends on the `EmailCustomerAction` class and `Filament\Tables\Table`.

```php
use App\Filament\Resources\Customers\Actions\EmailCustomerAction;
use Filament\Tables\Table;

public static function configure(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->recordActions([
            EmailCustomerAction::make(),
        ]);
}

```

--------------------------------

### Vertically Aligning Filament Table Column Content (PHP)

Source: https://filamentphp.com/docs/4.x/tables/columns

Filament provides methods to vertically align the content of a table column to the start, center, or end. This can be achieved using dedicated helper methods (`verticallyAlignStart`, `verticallyAlignCenter`, `verticallyAlignEnd`) or by passing a `VerticalAlignment` enum to the `verticalAlignment()` method. The `verticalAlignment()` method also supports dynamic calculation via a callback function, allowing for flexible alignment based on various injected utilities.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('name')
    ->verticallyAlignStart();

TextColumn::make('name')
    ->verticallyAlignCenter(); // This is the default alignment.

TextColumn::make('name')
    ->verticallyAlignEnd();
```

```php
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('name')
    ->verticalAlignment(VerticalAlignment::Start);
```

--------------------------------

### Update columnSpan() for lg devices default - Filament v3 to v4

Source: https://filamentphp.com/docs/4.x/upgrade-guide

Demonstrates migration of columnSpan() API from v3 to v4. In v3, columnSpan() required explicit breakpoint arrays like ['lg' => 2]. In v4, columnSpan() defaults to >= lg devices, matching the columns() API behavior. This change improves API consistency and prevents layout issues on smaller devices.

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make()
    ->columns(3)
    ->schema([
        TextInput::make()
            ->columnSpan(['lg' => 2]),
    ])
```

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make()
    ->columns(3)
    ->schema([
        TextInput::make()
            ->columnSpan(2),
    ])
```

```PHP
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make()
    ->columns(3)
    ->schema([
        TextInput::make()
            ->columnSpan(['lg' => 3, 'xl' => 2, '2xl' => 1]),
    ])
```

--------------------------------

### Render Filament Widget in Blade View using Livewire

Source: https://filamentphp.com/docs/4.x/components/widget

Renders a Filament widget (Livewire component) within a Blade view using the @livewire directive. Specify the fully qualified class name of your widget component to embed it in the template. For table widgets, ensure filament/tables is also installed.

```blade
<div>
    @livewire(\App\Livewire\Dashboard\PostsChart::class)
</div>
```

--------------------------------

### Mutate Form Data Before Saving in Filament CreateAction

Source: https://filamentphp.com/docs/4.x/actions/create

Use the mutateDataUsing() method to modify form data before database persistence. This example adds the authenticated user's ID to the data array. The method accepts various injected utilities like $data, $action, $livewire, and $record for flexible data manipulation.

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->mutateDataUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })
```

--------------------------------

### Pass Properties to Relation Manager via make Method

Source: https://filamentphp.com/docs/4.x/resources/managing-relationships

Use the make() method to pass an array of Livewire properties when registering a relation manager. These properties are mapped to public properties on the relation manager class for dynamic configuration.

```php
use App\Filament\Resources\Blog\Posts\PostResource\RelationManagers\CommentsRelationManager;

public static function getRelations(): array
{
    return [
        CommentsRelationManager::make([
            'status' => 'approved',
        ]),
    ];
}
```

--------------------------------

### Remove Page from FilamentPHP Resource getPages Method

Source: https://filamentphp.com/docs/4.x/resources/overview

This PHP method, `getPages`, defines the available pages for a FilamentPHP resource. To delete a page, its entry must be removed from this array after deleting the corresponding page file. This example shows removing a 'Create' page, leaving only 'List' and 'Edit' pages for the 'Customer' resource.

```php
public static function getPages(): array
{
    return [
        'index' => ListCustomers::route('/'),
        'edit' => EditCustomer::route('/{record}/edit'),
    ];
}
```

--------------------------------

### Apply Custom CSS Classes to FilamentPHP Table Rows Conditionally

Source: https://filamentphp.com/docs/4.x/tables

This example illustrates how to conditionally apply custom CSS classes to table rows based on the record's data. The `recordClasses()` method takes a closure that returns a string or array of classes, allowing for dynamic styling based on record attributes like status.

```php
use App\Models\Post;
use Closure;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

public function table(Table $table): Table
{
    return $table
        ->recordClasses(fn (Post $record) => match ($record->status) {
            'draft' => 'draft-post-table-row',
            'reviewing' => 'reviewing-post-table-row',
            'published' => 'published-post-table-row',
            default => null,
        });
}
```

--------------------------------

### Implement view() Method in ImportPolicy

Source: https://filamentphp.com/docs/4.x/actions/import

Create an ImportPolicy class with a view() method to authorize access to failure CSV files. This method receives the current user and Import model, and should return true to allow access. Include logic to verify that only the user who initiated the import can access the failure file.

```php
use App\Models\User;
use Filament\Actions\Imports\Models\Import;

public function view(User $user, Import $import): bool
{
    return $import->user()->is($user);
}
```

--------------------------------

### Set Custom Text Colors in FilamentPHP RichEditor

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

This example shows how to override the default text color palette in the Filament PHP RichEditor. Using the `textColors()` method, developers can provide a specific array of hexadecimal color codes mapped to user-friendly names, which will then be available in the text color picker.

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->textColors([
        '#ef4444' => 'Red',
        '#10b981' => 'Green',
        '#0ea5e9' => 'Sky',
    ])
```

--------------------------------

### Use FilamentPHP Action Class in Header Actions

Source: https://filamentphp.com/docs/4.x/resources/code-quality-tips

This PHP example illustrates how to attach a dedicated `EmailCustomerAction` class to a FilamentPHP page's header actions. By invoking its `make()` method within `getHeaderActions()`, the predefined action becomes accessible at the top of the page. This promotes modularity and reusability of actions.

```php
use App\Filament\Resources\Customers\Actions\EmailCustomerAction;

protected function getHeaderActions(): array
{
    return [
        EmailCustomerAction::make(),
    ];
}

```

--------------------------------

### Aggregate Relationship Fields in Filament TextColumn

Source: https://filamentphp.com/docs/4.x/tables/columns/overview

Uses aggregation methods (`avg()`, `max()`, `min()`, `sum()`) to calculate values across related records. Example shows `avg()` for averaging a field on all related records. Column name must follow Laravel convention (e.g., 'users_avg_age'). Supports optional query scoping.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('users_avg_age')->avg('users', 'age')
```

```php
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

TextColumn::make('users_avg_age')->avg([
    'users' => fn (Builder $query) => $query->where('is_active', true),
], 'age')
```

--------------------------------

### Render HTML in Filament TextEntry (Static)

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

This snippet illustrates how to configure a Filament `TextEntry` to display its value as HTML. It uses the `html()` method without arguments, which will also sanitize the HTML content by default to prevent potential security vulnerabilities.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->html()
```

--------------------------------

### Make Filament Layout Components Span Full Column (PHP)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This PHP snippet illustrates how to explicitly configure Filament's `Fieldset`, `Grid`, and `Section` layout components to span the full width of their parent grid. This restores the Filament v3 default behavior, as v4 now defaults these components to occupy only one column.

```php
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

Fieldset::make()
    ->columnSpanFull();
    
Grid::make()
    ->columnSpanFull();

Section::make()
    ->columnSpanFull();
```

--------------------------------

### Conditionally apply 'accepted' validation to Filament Toggle field (PHP)

Source: https://filamentphp.com/docs/4.x/forms/toggle

This example illustrates how to conditionally apply the `accepted()` validation rule to a Filament Toggle component. A boolean value or a closure can be passed to the `accepted()` method to dynamically control when the validation rule is active. This allows for flexible validation based on application logic, such as feature flags or other injected utilities.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('terms_of_service')
    ->accepted(FeatureFlag::active())
```

--------------------------------

### Trigger Filament Checkbox error state using Blade

Source: https://filamentphp.com/docs/4.x/components/checkbox

This example demonstrates how to dynamically set the error state of a Filament Checkbox component using a Blade expression. The `:valid` attribute is bound to the negation of `$errors->has('isAdmin')`, causing the checkbox to display an error if validation fails for the `isAdmin` field.

```blade
<x-filament::input.checkbox
    wire:model="isAdmin"
    :valid="! $errors->has('isAdmin')"
/>
```

--------------------------------

### Limit Decimal Places on Slider Component

Source: https://filamentphp.com/docs/4.x/forms/slider

The decimalPlaces() method rounds user-selected slider values to a specified number of decimal places. It accepts either a static integer or a callable function that can inject utilities like $get, $state, $record, and other Filament context parameters to dynamically calculate the decimal precision.

```php
use Filament\Forms\Components\Slider;

Slider::make('slider')
    ->range(minValue: 0, maxValue: 100)
    ->decimalPlaces(2)
```

--------------------------------

### Add extreme pagination links to Filament table

Source: https://filamentphp.com/docs/4.x/tables

To add "extreme" links to the first and the last page, use the `extremePaginationLinks()` method on the Filament Table instance.

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->extremePaginationLinks();
}
```

--------------------------------

### Add an Icon to a Filament Schema Section Header (PHP)

Source: https://filamentphp.com/docs/4.x/schemas/sections

This snippet illustrates how to enhance a Filament Schema Section by adding an icon to its header using the `->icon()` method. It utilizes `Heroicon::ShoppingBag` as an example, providing a visual cue for the section's content. The icon can also be dynamically determined via a function.

```php
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->icon(Heroicon::ShoppingBag)
    ->schema([
        // ...
    ])
```

--------------------------------

### Format Filament TextEntry dates relatively using `since()`

Source: https://filamentphp.com/docs/4.x/infolists/text-entry

Shows how to display a relative date format for a `TextEntry` state using the `since()` method. This method leverages Carbon's `diffForHumans()` to present time differences in a human-readable format.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->since()
```

--------------------------------

### Enable Row Reordering in FilamentPHP KeyValue Field

Source: https://filamentphp.com/docs/4.x/forms/key-value

This example shows how to allow users to reorder rows in a FilamentPHP KeyValue component using the `reorderable()` method. Similar to `editableValues()`, `reorderable()` can also take a function as an argument, enabling dynamic control over reordering based on injected utilities like the current component, Livewire instance, or Eloquent record.

```php
use Filament\Forms\Components\KeyValue;

KeyValue::make('meta')
    ->reorderable()
```

--------------------------------

### Set Default Placeholder Image for Filament PHP ImageColumn

Source: https://filamentphp.com/docs/4.x/tables/columns/image

This example illustrates how to configure a fallback image URL for a Filament `ImageColumn` when the primary image is not available. The `defaultImageUrl()` method accepts a static URL or a callable function for dynamic URL generation. The function can leverage injected utilities such as the column instance, Livewire component, or Eloquent record to determine the default image.

```php
use Filament\Tables\Columns\ImageColumn;

ImageColumn::make('header_image')
    ->defaultImageUrl(url('storage/posts/header-images/default.jpg'))
```

--------------------------------

### Dynamically control FilamentPHP TextInput copyable state

Source: https://filamentphp.com/docs/4.x/forms/text-input

This snippet illustrates how to conditionally enable or disable the copyable feature for a FilamentPHP `TextInput` component. It passes a boolean value to the `copyable()` method, which can be derived from a feature flag or any other dynamic condition, to control whether the text should be copyable. This functionality also requires SSL to be enabled.

```php
use Filament\Forms\Components\TextInput;

TextInput::make('apiKey')
    ->label('API key')
    ->copyable(FeatureFlag::active())
```

--------------------------------

### Configure RichEditor Toolbar Buttons in Filament

Source: https://filamentphp.com/docs/4.x/forms/rich-editor

Demonstrates how to set custom toolbar buttons for a RichEditor component using the toolbarButtons() method. Buttons are organized into nested arrays representing toolbar groups. This example shows the default button configuration including text formatting, heading levels, alignment options, content structures, and editor actions.

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->toolbarButtons([
        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
        ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
        ['table', 'attachFiles'],
        ['undo', 'redo'],
    ])
```

--------------------------------

### Globally Configure Filament PHP TextColumn Behavior

Source: https://filamentphp.com/docs/4.x/tables/columns/overview

Illustrates how to change the default behavior of all `TextColumn` instances globally in Filament PHP. This is done by calling the static `configureUsing()` method within a service provider's `boot()` method, passing a closure to modify column properties.

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::configureUsing(function (TextColumn $column): void {
    $column->toggleable();
});
```

--------------------------------

### Define Table Filters in Filament PHP

Source: https://filamentphp.com/docs/4.x/tables

This code snippet demonstrates how to add filters to a Filament PHP table using the `$table->filters()` method. It includes examples for a boolean filter (`is_featured`) and a select filter (`status`), allowing users to efficiently narrow down table results. These filters require importing `Filament\Tables\Filters\Filter` and `SelectFilter` classes, and the `query` method is used to modify the Eloquent builder.

```php
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->filters([
            Filter::make('is_featured')
                ->query(fn (Builder $query) => $query->where('is_featured', true)),
            SelectFilter::make('status')
                ->options([
                    'draft' => 'Draft',
                    'reviewing' => 'Reviewing',
                    'published' => 'Published',
                ]),
        ]);
}
```

--------------------------------

### Set default filter value in Filament Chart Widget (PHP)

Source: https://filamentphp.com/docs/4.x/widgets/charts

To set a default filter value for a Filament chart, define the `$filter` property on the widget class. This property should hold the initial selected value for the filter, ensuring a consistent starting state for the chart data.

```php
public ?string $filter = 'today';
```

--------------------------------

### Define record actions in Filament table

Source: https://filamentphp.com/docs/4.x/tables/actions

Configure record actions in a Filament table by defining them within the `recordActions()` method. Actions are rendered at the end of each table row and can execute tasks or create links. Use the static `make()` method with a unique name, then chain `action()` for callbacks or `url()` for links.

```PHP
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->recordActions([
            // ...
        ]);
}
```

--------------------------------

### Disable FilamentPHP ImageEntry file existence checks

Source: https://filamentphp.com/docs/4.x/infolists/image-entry

This PHP example shows how to prevent FilamentPHP's ImageEntry component from performing automatic backend file existence checks. Disabling this feature with `checkFileExistence(false)` can improve performance, especially when dealing with remote storage or a large number of images. Like other methods, it also accepts a dynamic function with utility injection.

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('attachment')
    ->checkFileExistence(false)
```

--------------------------------

### Access Parent Field Values from Repeater using Relative Path Syntax

Source: https://filamentphp.com/docs/4.x/forms/repeater

Demonstrates using relative path syntax with $get() to access parent field values from within a repeater item. The '../' prefix navigates up one level in the data structure hierarchy, allowing access to fields outside the current repeater scope.

```php
[
    'client_id' => 1,
    'repeater' => [
        'item1' => [
            'service_id' => 2,
        ],
    ],
]

// Inside repeater item:
// $get('client_id') looks for 'repeater.item1.client_id' (scoped to current item)
// $get('../client_id') resolves to 'repeater.client_id'
// $get('../../client_id') resolves to 'client_id' (root level)
// $get() or $get('') or $get('./') returns full current repeater item data
```

--------------------------------

### Conditionally Apply Inline Label to Filament Infolist Entry

Source: https://filamentphp.com/docs/4.x/infolists

Shows how to dynamically control the inline label display for an entry by passing a boolean value to the `inlineLabel()` method. This is useful for conditional rendering based on feature flags or other logic.

```php
use FilamentInfolistsComponentsTextInput;

TextInput::make('name')
    ->inlineLabel(FeatureFlag::active())
```

--------------------------------

### Globally Set Filament File Upload and Image Components to Public Visibility (PHP)

Source: https://filamentphp.com/docs/4.x/upgrade-guide

This PHP snippet globally configures Filament's `FileUpload` form field, `ImageColumn` table column, and `ImageEntry` infolist entry to use `public` visibility by default. This reverts the Filament v4 change where non-local disk files are `private` by default, restoring the v3 behavior. It should be added to the `boot()` method of a service provider like `AppServiceProvider`.

```php
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Columns\ImageColumn;

FileUpload::configureUsing(fn (FileUpload $fileUpload) => $fileUpload
    ->visibility('public'));

ImageColumn::configureUsing(fn (ImageColumn $imageColumn) => $imageColumn
    ->visibility('public'));

ImageEntry::configureUsing(fn (ImageEntry $imageEntry) => $imageEntry
    ->visibility('public'));
```

--------------------------------

### Style Heading Component with CSS

Source: https://filamentphp.com/docs/4.x/plugins/building-a-standalone-plugin

Defines responsive font sizes and typography styles for heading elements (h1-h6) within the headings-component class. Applies consistent font weight, letter spacing, and line height to all heading levels with size-specific rules.

```css
.headings-component {
    &:is(h1, h2, h3, h4, h5, h6) {
         font-weight: 700;
         letter-spacing: -.025em;
         line-height: 1.1;
     }

    &h1 {
         font-size: 2rem;
     }

    &h2 {
         font-size: 1.75rem;
     }

    &h3 {
         font-size: 1.5rem;
     }

    &h4 {
         font-size: 1.25rem;
     }

    &h5,
    &h6 {
         font-size: 1rem;
     }
}
```

--------------------------------

### Upgrade Filament Plugin Service Provider (PHP)

Source: https://filamentphp.com/docs/4.x/plugins/getting-started

This PHP code demonstrates how to modify an existing Filament plugin's service provider to align with newer architectural changes. It shows extending `PackageServiceProvider` instead of `PluginServiceProvider` and defining a static `$name` property, which is crucial for proper plugin registration and asset management within Filament.

```php
class MyPluginServiceProvider extends PackageServiceProvider
{
    public static string $name = 'my-plugin';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
}
```

--------------------------------

### Set TextInputColumn Suffix Icon Color in Filament

Source: https://filamentphp.com/docs/4.x/tables/columns/text-input

Sets a suffix icon with a static color value using the suffixIcon() and suffixIconColor() methods on a Filament TextInputColumn. The example uses a Heroicon CheckCircle icon with 'success' color. Both prefixIconColor() and suffixIconColor() methods support static color strings and dynamic callback functions for calculating colors based on column context.

```PHP
use Filament\Tables\Columns\TextInputColumn;
use Filament\Support\Icons\Heroicon;

TextInputColumn::make('status')
    ->suffixIcon(Heroicon::CheckCircle)
    ->suffixIconColor('success')
```

--------------------------------

### Register Tenant Profile Page in Filament Panel Configuration

Source: https://filamentphp.com/docs/4.x/users/tenancy

Configure the Filament panel to use the custom tenant profile page by calling tenantProfile() with the page class. This enables users to access the profile management page from the tenant menu.

```PHP
use App\Filament\Pages\Tenancy\EditTeamProfile;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantProfile(EditTeamProfile::class);
}
```

--------------------------------

### Dynamically Enable Image Editor in FilamentPHP FileUpload

Source: https://filamentphp.com/docs/4.x/forms/file-upload

This example shows how to conditionally enable the image editor for a FilamentPHP `FileUpload` field by passing a boolean value to the `imageEditor()` method. This allows for dynamic control, potentially based on feature flags or other application logic. The method also supports a function for more complex dynamic calculations with utility injection.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor(FeatureFlag::active())
```