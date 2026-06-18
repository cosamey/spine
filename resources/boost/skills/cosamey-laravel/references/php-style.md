# PHP Style

## Typing

- Declare a return type on every method, including `void`.
- Type every parameter and class property.
- Use `?Type`, not `Type|null`.
- Do not add `declare(strict_types=1)`.
- Prefer native types first, then PHPDoc only where native PHP cannot express enough.

## Imports

- Import classes with `use`; do not write fully qualified class names inline.
- Import classes used in PHPDoc instead of writing fully qualified names in annotations.
- Import namespaces when a family of generic return types is repeated, for example `Illuminate\Database\Eloquent\Relations`.

## PHPDoc

Use PHPDoc for:

- Eloquent model `@property`, `@property-read`, and `@mixin` metadata.
- Generic collections, relationships, factories, scopes, and attributes.
- Array shapes with fixed keys.
- Third-party types that static analysis cannot infer.

Avoid PHPDoc for:

- Method descriptions that repeat the method name.
- `@param` or `@return` annotations already fully expressed by native types.
- Obvious local variables.

## Class Structure

- Use constructor property promotion when all promoted properties remain readable.
- Use one trait import per line.
- Do not mark classes `final` or `readonly` by default.
- Prefer dependency injection over service lookups in application services; direct action instantiation is acceptable for dependency-free actions.

## Control Flow

- Put guard clauses and error cases first.
- Keep the happy path last.
- Avoid `else` when an early return is clearer.
- Always use braces for control structures.
- Prefer clear string interpolation over noisy concatenation.
