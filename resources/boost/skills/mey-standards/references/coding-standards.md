# Spine Coding Standards — Reference

## Table of contents
1. [PHP typing standards](#php-typing-standards)
2. [Eloquent models](#eloquent-models)
3. [PHPDoc rules](#phpdoc-rules)
4. [Action pattern](#action-pattern)
5. [Controllers](#controllers)
6. [Livewire components](#livewire-components)
7. [Blade view structure](#blade-view-structure)
8. [Spine package defaults](#spine-package-defaults)
9. [Testing](#testing)

---

## PHP typing standards

Every variable, parameter, and return value must be explicitly typed. Do not rely on `declare(strict_types=1)` — instead, enforce correctness through explicit declarations on every method and property.

### Rules
- Return type required on every method, including `void`.
- Every parameter must be type-hinted.
- Every class property must carry a type declaration.
- Use short nullable syntax: `?string` not `string|null`.
- Do **not** add `declare(strict_types=1)` at the top of files.
- Always import classes with `use` and use their short name in type hints — never inline fully qualified names (e.g. `View` not `\Illuminate\View\View`).

### Examples

```php
// Correct
protected int $throttle = 60;

public function markAsActive(?Carbon $timestamp = null): void
{
    // ...
}

private function shouldSkipUpdate(Carbon $timestamp, ?string $ip): bool
{
    // ...
}
```

```php
// Wrong — missing types
protected $throttle = 60;

public function markAsActive($timestamp = null)
{
    // ...
}
```

---

## Eloquent models

### PHPDoc property block
Every model must declare all its columns and virtual attributes in a PHPDoc block above the class.

```php
/**
 * @property-read string $id
 * @property string $invoice_id
 * @property float $amount
 * @property string $iban
 * @property Carbon $due_date
 * @property ?string $variable_symbol
 * @property ?string $constant_symbol
 * @property ?string $specific_symbol
 * @property ?string $qr_code
 * @property ?Carbon $paid_at
 * @property-read ?Carbon $created_at
 * @property-read ?Carbon $updated_at
 * @property-read Invoice $invoice
 */
class Payment extends Model
```

### `casts()` method
Must declare `array` return type and a `@return array<string, string>` PHPDoc since the native type alone is not specific enough.

```php
// Correct
/** @return array<string, string> */
protected function casts(): array
{
    return [
        'amount'  => 'decimal:2',
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];
}
```

```php
// Wrong — missing return type and PHPDoc generic
protected function casts()
{
    return [...];
}
```

### Accessors / mutators
Return type must use `Attribute` with generics describing the get and set types. Use `never` as the second generic when there is no setter.

```php
// Correct
/** @return Attribute<non-falsy-string, never> */
protected function name(): Attribute
{
    return Attribute::get(fn (): string => "{$this->first_name} {$this->last_name}");
}
```

```php
// Wrong — old-style accessor, missing return type
public function getNameAttribute()
{
    return "{$this->first_name} {$this->last_name}";
}
```

### Scopes
Must declare `void` return type and include a `@param Builder<$this>` PHPDoc.

```php
// Correct
/** @param Builder<$this> $query */
#[Scope]
protected function paid(Builder $query): void
{
    $query->whereNotNull('paid_at');
}
```

```php
// Wrong — old-style scope prefix, missing return type and PHPDoc
public function scopePaid($query)
{
    $query->whereNotNull('paid_at');
}
```

### Relationships
Import `Illuminate\Database\Eloquent\Relations` as a namespace (not individual classes). Declare return type with generic parameters.

```php
use Illuminate\Database\Eloquent\Relations;

// Correct
/** @return Relations\BelongsTo<Invoice, $this> */
public function invoice(): Relations\BelongsTo
{
    return $this->belongsTo(Invoice::class);
}

/** @return Relations\HasMany<OrderLine, $this> */
public function lines(): Relations\HasMany
{
    return $this->hasMany(OrderLine::class);
}
```

```php
// Wrong — missing return type and generic
public function invoice()
{
    return $this->belongsTo(Invoice::class);
}
```

### Factory trait
Always annotate with the concrete factory class.

```php
/** @use HasFactory<InvoiceFactory> */
use HasFactory;
```

---

## PHPDoc rules

### Required
| Situation | Required PHPDoc |
|---|---|
| `use HasFactory` | `/** @use HasFactory<ConcreteFactory> */` |
| `casts()` method | `/** @return array<string, string> */` |
| Relationship method | `/** @return Relations\BelongsTo<Related, $this> */` |
| Scope method | `/** @param Builder<$this> $query */` |
| Accessor (read-only) | `/** @return Attribute<GetType, never> */` |
| Accessor + mutator | `/** @return Attribute<GetType, SetType> */` |
| `@property` / `@property-read` | On every model |
| Typed collection variable | `/** @var Collection<int, Invoice> $invoices */` |

### Not required
- Docblocks that only restate what native type hints already express.
- `@param` or `@return` when the signature already carries full types with no generics needed.

```php
// No docblock needed — types are complete
public function handle(Request $request, \Closure $next): Response
{
    // ...
}
```

---

## Action pattern

Business logic lives in single-responsibility action classes under `app/Actions/`.

### Rules
- Class name is a verb + noun: `CreateOrder`, `MarkAsPaid`, `GenerateQrCode`, `ApplyDiscount`.
- One public method named `execute()`.
- Instantiate directly and call immediately: `new CreateOrder()->execute($params)`.
- Pass dependencies through the constructor or `execute()` parameters — no static methods.
- No `declare(strict_types=1)`, no service container binding needed.

### Example

```php
// app/Actions/CreateInvoice.php
namespace App\Actions;

use App\Models\Invoice;
use App\Models\Order;

class CreateInvoice
{
    public function execute(Order $order): Invoice
    {
        return Invoice::create([
            'order_id' => $order->id,
            'amount'   => $order->total,
            'due_date' => now()->addDays(30),
        ]);
    }
}
```

```php
// Calling the action
$invoice = new CreateInvoice()->execute($order);
```

```php
// With constructor dependency
class ApplyDiscount
{
    public function __construct(private readonly DiscountCalculator $calculator) {}

    public function execute(Order $order, string $couponCode): Order
    {
        $discount = $this->calculator->calculate($order, $couponCode);
        $order->update(['discount' => $discount]);

        return $order;
    }
}

// Called via the container when constructor dependencies need resolving
app(ApplyDiscount::class)->execute($order, $couponCode);
```

---

## Controllers

Only two controller styles are permitted. Every route must have a name.

### CRUD controllers
Use only the seven standard Laravel resource methods. Never add custom action methods.

```php
class InvoicesController extends Controller
{
    public function index(): View { }
    public function create(): View { }
    public function store(StoreInvoiceRequest $request): RedirectResponse { }
    public function show(Invoice $invoice): View { }
    public function edit(Invoice $invoice): View { }
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse { }
    public function destroy(Invoice $invoice): RedirectResponse { }
}
```

If an action doesn't fit one of these seven methods, extract it into an invokable controller instead.

### Invokable controllers
Single `__invoke()` method. Named as a verb phrase describing the action.

```php
// app/Http/Controllers/SendInvoiceController.php
class SendInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice): RedirectResponse
    {
        Mail::to($invoice->customer)->send(new InvoiceMail($invoice));

        return redirect()->back();
    }
}
```

```php
// Route definition
Route::post('invoices/{invoice}/send', SendInvoiceController::class)->name('invoices.send');
```

### What is not allowed
```php
// Wrong — custom method on a resource controller
class InvoicesController extends Controller
{
    public function send(Invoice $invoice): RedirectResponse { } // ❌ extract to invokable
    public function duplicate(Invoice $invoice): RedirectResponse { } // ❌ extract to invokable
}
```

---

## Livewire components

Uses Livewire v4. Always use **class-based components** — never functional/Volt-style.

### File locations
- Class: `app/Livewire/`
- View: `resources/views/livewire/`

### Rules
- Apply the same strict typing rules: typed properties, typed parameters, return types on all methods.
- Public properties that back form inputs must be typed.
- Lifecycle hooks (`mount`, `updated*`, etc.) must declare return types.
- Prefer binding models directly as typed public properties with `#[Locked]` over storing an ID and re-querying in `mount()`.

### Example

```php
// app/Livewire/InvoiceForm.php
namespace App\Livewire;

use App\Actions\CreateInvoice;
use App\Models\Order;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class InvoiceForm extends Component
{
    #[Locked]
    public Order $order;

    public string $notes = '';

    public function submit(): void
    {
        new CreateInvoice()->execute($this->order);

        $this->dispatch('invoice-created');
    }

    public function render(): View
    {
        return view('livewire.invoice-form');
    }
}
```

```php
// Wrong — Volt/functional style (never use)
<?php
use function Livewire\Volt\{state};
state(['notes' => '']);
// ...
?>
```

---

## Blade view structure

```
resources/views/
├── components/
│   ├── art/                      ← brand assets, illustrations
│   │   ├── icons/
│   │   │   └── lucide/           ← one file per Lucide icon
│   │   │       └── phone.blade.php
│   │   └── logo.blade.php
│   ├── domains/                  ← domain-specific components
│   │   └── product/
│   │       └── card.blade.php
│   ├── layouts/
│   │   └── app/
│   │       └── index.blade.php   ← main application layout
│   ├── sections/                 ← page sections
│   │   └── home/
│   │       └── hero.blade.php
│   └── ui/                       ← reusable primitives: buttons, inputs, modals, cards…
│       ├── form/
│       └── button.blade.php
├── livewire/                     ← Livewire component views
└── pages/                        ← route-facing page views (thin, delegate to components)
    ├── products/
    │   ├── index.blade.php
    │   └── show.blade.php
    └── about.blade.php
```

### Rules
- **Pages are thin** — a page view should largely consist of component calls, not raw HTML.
- **Componentise aggressively** — buttons, inputs, badges, modals, cards, and any repeated markup become components.
- **Icons** live at `components/art/icons/lucide/<name>.blade.php`. Create a new file per icon rather than inlining SVGs in templates.
- **The layout entry point** is `components/layouts/app/index.blade.php`.
- Component names follow the directory path: `<x-ui.button>`, `<x-art.icons.lucide.phone>`, `<x-layouts.app>`.

### Example page view

```blade
{{-- resources/views/pages/invoices/show.blade.php --}}
<x-layouts.app>
    <x-ui.card>
        <x-ui.heading>Invoice #{{ $invoice->number }}</x-ui.heading>

        <x-invoices.details :invoice="$invoice" />

        <x-ui.button href="{{ route('invoices.send', $invoice) }}" method="post">
            <x-art.icons.lucide.send />
            Send invoice
        </x-ui.button>
    </x-ui.card>
</x-layouts.app>
```

---

## Spine package defaults

The spine service provider configures the following global defaults. AI agents must be aware of these to avoid redundant or conflicting code.

| Default | Detail |
|---|---|
| `Model::automaticallyEagerLoadRelationships()` | Do not add `->with([...])` calls to fix N+1 — relationships are eager-loaded automatically. Only add explicit `with()` when overriding or fine-tuning. |
| `Model::shouldBeStrict(app()->isLocal())` | In local environments, accessing undefined attributes or unfilled fillable fields throws. Always declare `$fillable` on models. |
| `Relation::enforceMorphMap()` | Morph map is built automatically from `app/Models/` using snake_case keys. Use `'invoice'` not `'App\Models\Invoice'` in morph-related code. |
| `DB::prohibitDestructiveCommands()` | Enabled in production. Migrations that drop columns/tables will fail unless marked as destructive-safe. |
| `URL::forceHttps()` | Enabled in production. Do not manually prefix URLs with `https://` in config or code. |
| `Number::useCurrency('EUR')` | Default currency is EUR. Use `Number::currency($amount)` for formatting — do not hardcode currency symbols. |
| `Number::useLocale('en_US')` | Default locale for number formatting. |

---

## Testing

Uses Pest v4.

### Rules
- Always use the `test()` function — not `it()`.
- Closures must declare `void` return type.
- Follow Arrange / Act / Assert order with blank lines separating each section.
- Use `expect()` assertions — not `assert*()` functions.
- Use `beforeEach()` for shared setup.

### Example

```php
test('records activity on first request', function (): void {
    // Arrange
    $user = User::factory()->create();
    $now = now();

    // Act
    $user->markAsActive($now);

    // Assert
    expect($user->last_active_at)->toEqual($now);
});

test('skips update within throttle window', function (): void {
    // Arrange
    $user = User::factory()->create();
    $user->markAsActive($first = now());

    // Act
    $user->markAsActive($second = now()->addSeconds(30));

    // Assert
    expect($user->last_active_at)->toEqual($first);
});
```

```php
// Wrong — using it(), missing return type, PHPUnit assertions
it('records activity', function () {
    $user = User::factory()->create();
    $user->markAsActive();
    $this->assertNotNull($user->last_active_at); // ❌
});
```
