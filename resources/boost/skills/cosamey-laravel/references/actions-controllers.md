# Actions, Controllers, Routes, and Requests

## Actions

- Put business logic in single-purpose classes under `app/Actions`.
- Name actions as verb + noun: `CreateInvoice`, `MarkAsPaid`, `GenerateQrCode`.
- Use one public method named `execute()`.
- Pass runtime data through `execute()` parameters.
- Use constructor dependencies only for collaborators, not request data.
- Call dependency-free actions directly:

```php
$invoice = new CreateInvoice()->execute($order);
```

- Resolve through the container only when constructor dependencies need it:

```php
$invoice = app(CreateInvoice::class)->execute($order);
```

## Controllers

- Use resource controllers only for the seven standard methods: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.
- For non-CRUD actions, create a single invokable controller with `__invoke()`.
- Keep controllers thin: validate, authorize, call an action, return a response.
- Do not put business workflows in controllers.

## Routes

- Name every route.
- Prefer route names that match Laravel resource conventions, such as `invoices.send`.
- Use implicit route model binding unless the project has a reason not to.
- Keep routes readable and check existing route grouping before adding new groups.

## Form Requests

- Use Form Request classes for non-trivial validation.
- Put authorization in `authorize()` when it belongs to the request boundary.
- Use array validation rule syntax for new code unless the project clearly uses string rules nearby.
- Read validated input with `$request->validated()`, typed request helpers, or explicit DTO/action parameters; do not pass `$request->all()` into actions.
