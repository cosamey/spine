---
name: cosamey-laravel
description: Apply Cosa Mey Laravel conventions for Laravel 13+, PHP 8.5+, and shared organization architecture. Use for Laravel/PHP implementation, review, refactoring, actions, controllers, routes, form requests, artisan commands, PHP typing/style, Mey project defaults, and as the entrypoint skill in any Mey Laravel project using the spine package.
---

# Mey Laravel

## Workflow

1. Treat Laravel Boost core skills and `laravel-best-practices` as the baseline.
2. Inspect nearby application code before choosing a pattern.
3. Apply Mey rules only where they add package-specific behavior or organization-specific preferences.
4. Load focused Mey skills when the task touches their domain:
   - `cosamey-eloquent` for models, relationships, queries, casts, factories, and migrations.
   - `cosamey-livewire` for Livewire components.
   - `cosamey-filament` for Filament admin resources, pages, forms, tables, actions, and widgets.
   - `cosamey-blade` for Blade, Tailwind, Alpine, SVG, and UI components.
   - `cosamey-test` only when tests are explicitly requested or existing tests are edited.
5. Run `composer check` after completing the task.

## Core Rules

- Keep Mey guidance as an override layer, not a copy of Laravel Boost.
- Every method must declare a return type, including `void`.
- Every parameter and class property must be typed.
- Use short nullable syntax: `?string`, not `string|null`.
- Do not add `declare(strict_types=1)`.
- Import classes with `use` and reference short names in code and PHPDoc.
- Use PHPDoc only where native types are insufficient, such as generics, array shapes, model properties, and mixins.
- Prefer early returns, happy path last, and no unnecessary `else`.
- Use constructor property promotion when it makes the whole constructor clearer.
- Do not add comments that merely restate code.
- Use `config()` outside config files; never call `env()` from application code.

## Application Structure

- Put business workflows in single-purpose action classes under `app/Actions`.
- Name actions with verb + noun: `CreateOrder`, `MarkAsPaid`, `GenerateQrCode`.
- Use one public action method named `execute()`.
- Call actions directly with `new CreateOrder()->execute(...)` unless constructor dependencies require container resolution.
- Controllers are either resource controllers using standard CRUD methods or single invokable controllers.
- Do not add custom methods to resource controllers; extract a dedicated invokable controller instead.
- Name every route.
- Use Form Request classes for non-trivial HTTP validation and authorization.
- Keep artisan commands explicit: typed methods, kebab-case command names, progress output for loops, and a clear summary.

## References

- Read `references/project-defaults.md` when project-level model, URL, number, morph-map, or destructive-command behavior matters.
- Read `references/php-style.md` when PHP typing, PHPDoc, imports, class structure, or control flow matters.
- Read `references/actions-controllers.md` when touching actions, controllers, routes, or form requests.
- Read `references/artisan-commands.md` when creating or editing artisan commands.
