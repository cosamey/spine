---
name: cosamey-eloquent
description: Apply Mey Eloquent and database conventions in Mey Laravel projects. Use when creating, editing, reviewing, or refactoring models, relationships, casts, accessors, mutators, scopes, factories, morph maps, queries, migrations, and schema-related code.
---

# Mey Eloquent

## Workflow

1. Load `cosamey-laravel` first for Mey project defaults and PHP style.
2. Check sibling models and migrations before introducing a pattern.
3. Read `references/models.md` for model, relationship, cast, scope, and factory details.
4. Read `references/migrations.md` for schema changes.

## Core Rules

- Every model must have useful PHPDoc metadata for columns, computed attributes, relationships, and mixins where needed.
- Order model PHPDoc properties as database columns in migration order, then computed attributes, then relationships.
- Keep model class bodies ordered as traits, properties, casts, defaults/package configuration, accessors and mutators, scopes, then relationships.
- Use `casts(): array` with a generic PHPDoc return type.
- Use Laravel attribute objects for accessors/mutators and annotate generics.
- Use `#[Scope]` local scopes with `void` return type and `Builder<$this>` PHPDoc.
- Use observer classes for model lifecycle behavior and register them on the model with `#[ObservedBy(...)]`.
- Import `Illuminate\Database\Eloquent\Relations` as a namespace for relationship return types.
- Annotate `HasFactory` with the concrete factory class.
- Use configured morph map keys such as `invoice`, not model class strings.
- Do not add `with()` only because a generic N+1 rule suggested it; Mey project defaults enable automatic eager loading.
- Follow the application's migration policy; do not assume whether existing migrations should be edited or replaced.
- In migrations, place combined indexes at the end and do not add `down()` methods.

## References

- `references/models.md`
- `references/migrations.md`
