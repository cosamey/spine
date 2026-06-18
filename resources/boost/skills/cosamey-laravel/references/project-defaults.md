# Mey Project Defaults

The `spine` package exposes helpers for shared Cosa Mey conventions, but application defaults must live in the project so they stay easy to override. Put the defaults the project wants in `AppServiceProvider::boot()`.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Number;
use Mey\Spine\Support\ModelMorphMap;

public function boot(): void
{
    DB::prohibitDestructiveCommands($this->app->isProduction());

    Number::useCurrency(config()->string('app.currency', 'EUR'));
    Number::useLocale(config()->string('app.locale', 'en_US'));

    Model::automaticallyEagerLoadRelationships();
    Model::shouldBeStrict($this->app->isLocal());

    Relation::enforceMorphMap(ModelMorphMap::fromModels());

    URL::forceHttps($this->app->isProduction());
}
```

| Default | Guidance |
|---|---|
| `Model::automaticallyEagerLoadRelationships()` | Do not add `with()` only to silence a generic N+1 warning. Add explicit eager loading only when the query needs a constrained relation, predictable SQL shape, or performance tuning. |
| `Model::shouldBeStrict(app()->isLocal())` | Declare model fillable/guarded behavior and avoid relying on undefined attributes. |
| `Relation::enforceMorphMap()` | Use snake_case morph keys such as `invoice`, not class strings such as `App\Models\Invoice`. |
| `DB::prohibitDestructiveCommands(app()->isProduction())` | Treat destructive schema operations as intentional and deployment-sensitive. |
| `URL::forceHttps(app()->isProduction())` | Do not manually prefix generated app URLs with `https://`. |
| `Number::useCurrency(config()->string('app.currency', 'EUR'))` | Use `Number::currency($amount)` for display formatting instead of hardcoded currency symbols. |
| `Number::useLocale(config()->string('app.locale', 'en_US'))` | Let configured locale drive display formatting. |

## Practical Checks

- Before changing model/query behavior, check whether automatic eager loading changes the right fix.
- Before writing morph-related data or assertions, use morph-map keys.
- Before formatting money or numbers, use Laravel `Number` helpers and project config.
- Before destructive database work, verify whether the migration has already run in a shared or production environment.
