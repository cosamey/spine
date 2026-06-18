# Eloquent Models

## Model PHPDoc

Document columns, computed attributes, relationships, and mixins that static analysis or agents need.

Order model `@property` metadata by source:

1. Database columns in migration order.
2. Computed attributes from accessors, after timestamp columns.
3. Relationships.

Use `@property-read` for generated identifiers, timestamps, computed attributes, and relationship properties. Import classes used in PHPDoc and reference short names.

```php
/**
 * @property-read string $id
 * @property string $customer_id
 * @property string $reference
 * @property float $total
 * @property ?Carbon $confirmed_at
 * @property-read ?Carbon $created_at
 * @property-read ?Carbon $updated_at
 * @property-read bool $is_confirmed
 * @property-read Customer $customer
 * @property-read Collection<int, Item> $items
 */
class Order extends Model
{
    //
}
```

## Observers

Use observer classes for model lifecycle behavior. Register observers directly on the model with the `ObservedBy` attribute.

```php
use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(PaymentObserver::class)]
class Payment extends Model
{
    //
}
```

Prefer observers over model `booted()` callbacks for lifecycle side effects.

## Model Body Order

Keep model internals in this order:

1. Traits.
2. Class properties.
3. `casts()`.
4. Defaults and package configuration methods, such as Spatie Media Library collection/conversion registration.
5. Accessors and mutators.
6. Scopes.
7. Relationships.

This keeps metadata and configuration near the top, query helpers in the middle, and relationship graph methods at the bottom.

## Casts

```php
/** @return array<string, string> */
protected function casts(): array
{
    return [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];
}
```

## Attributes

```php
/** @return Attribute<non-falsy-string, never> */
protected function name(): Attribute
{
    return Attribute::get(fn (): string => "{$this->first_name} {$this->last_name}");
}
```

Use `never` as the setter generic when no setter exists.

## Scopes

```php
/** @param Builder<$this> $query */
#[Scope]
protected function paid(Builder $query): void
{
    $query->whereNotNull('paid_at');
}
```

## Relationships

```php
use Illuminate\Database\Eloquent\Relations;

/** @return Relations\BelongsTo<Invoice, covariant $this> */
public function invoice(): Relations\BelongsTo
{
    return $this->belongsTo(Invoice::class);
}

/** @return Relations\HasMany<Item, covariant $this> */
public function items(): Relations\HasMany
{
    return $this->hasMany(Item::class);
}
```

## Factories

```php
/** @use HasFactory<InvoiceFactory> */
use HasFactory;
```

## Queries

- Prefer expressive Eloquent relationship helpers such as `whereBelongsTo()` when they match the query.
- Use `withCount()` for counts.
- Use explicit `with()` only when the relation shape must be constrained, preloaded for serialization, or tuned beyond the project's automatic eager loading default.
- Avoid hardcoded table names in runtime queries; migrations may use table names because they are frozen snapshots.
