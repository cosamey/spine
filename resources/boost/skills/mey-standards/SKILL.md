---
name: mey-standards
description: Apply Mey coding standards for any task that creates, edits, reviews, or refactors PHP or Blade code; covers strict typing, action classes, Eloquent models, controllers, Livewire components, and Blade view structure.
---

# Spine Coding Standards

## When to activate
- Activate for any PHP or Blade coding work in a spine-based project.
- Activate when generating, editing, reviewing, refactoring, or formatting `.php` or `.blade.php` files.
- Activate when working on models, controllers, actions, Livewire components, migrations, or tests.

## Scope
- In scope: `.php`, `.blade.php`, Eloquent models, controllers, actions, Livewire, Blade views, tests.
- Out of scope: JavaScript, TypeScript, CSS, infrastructure, database schema design.

## Workflow
1. Identify what is being built (model, action, controller, component, view, test).
2. Read `references/coding-standards.md` and focus on the relevant section.
3. Apply spine typing rules first, then the pattern-specific conventions.
4. Check whether any spine global defaults affect the code being written.
5. After completing a feature, remind the user to run `composer format` then `composer check`.

## Core rules

- Every method must declare a return type, including `void`.
- Every parameter must be type-hinted.
- Every class property must be typed.
- Use short nullable syntax: `?string` not `string|null`.
- Do **not** add `declare(strict_types=1)` — rely on explicit declarations instead.
- Use PHPDoc generics wherever native types are insufficient (collections, relationships, factories, scopes).
- Use the action pattern for business logic: `new CreateOrder()->execute()`.
- Controllers are either CRUD-only or single invokable — never both, never custom methods.
- Blade views are heavily componentised; pages stay thin.
- Livewire uses class-based components only — never Volt/functional style.

## Do and don't

**Do:**
- Declare `array` return type and `/** @return array<string, string> */` on `casts()`.
- Add `/** @return Relations\BelongsTo<RelatedModel, $this> */` on relationship methods.
- Add `/** @param Builder<$this> $query */` on scope methods.
- Add `/** @use HasFactory<ConcreteFactory> */` when using the `HasFactory` trait.
- Add a `@property` / `@property-read` PHPDoc block on every model.
- Import `Illuminate\Database\Eloquent\Relations` as a namespace, not individual classes.
- Name action classes as verb + noun: `CreateOrder`, `MarkAsPaid`, `GenerateQrCode`.
- Use snake_case morph type strings (`invoice`, not `App\Models\Invoice`).
- Always name routes: `->name('invoices.send')`.

**Don't:**
- Add docblocks that only restate what the type hints already say.
- Add custom methods to CRUD controllers — extract an invokable instead.
- Use Volt/functional Livewire components.
- Call `with()` to fix N+1 — `Model::automaticallyEagerLoadRelationships()` is already active.
- Use `env()` outside config files.
- Use `string|null` — always prefer `?string`.
- Use fully qualified class names inline — always `use` import and reference by short name.

## Quick examples

```php
// Action
class CreateInvoice
{
    public function execute(Order $order): Invoice
    {
        // ...
    }
}

new CreateInvoice()->execute($order);
```

```php
// Invokable controller
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
// Model scope with correct typing
/** @param Builder<$this> $query */
#[Scope]
protected function paid(Builder $query): void
{
    $query->whereNotNull('paid_at');
}
```

## References
- `references/coding-standards.md`
