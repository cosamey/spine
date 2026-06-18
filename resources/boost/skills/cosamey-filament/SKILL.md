---
name: cosamey-filament
description: Apply Mey Filament 5 conventions in Mey Laravel projects. Use when creating, editing, reviewing, or refactoring Filament resources, pages, forms, tables, actions, widgets, relation managers, typed closures, role-aware admin behavior, and Filament-backed workflows.
---

# Mey Filament

## Workflow

1. Load `cosamey-laravel` for PHP style and action boundaries.
2. Inspect existing Filament panel, resource, page, and action namespaces before adding new code.
3. Verify Filament API details with project code or current docs for the installed version.
4. For meaningful admin workflows, verify in the browser with a representative seeded role or record.

## Rules

- Preserve existing panel boundaries, namespaces, authorization checks, and query scopes.
- Type closure parameters to the exact record/model expected by the component.
- Keep Filament page/resource methods readable; extract repeated or complex business behavior into actions.
- Use Filament actions for UI interaction and Mey action classes for domain work.
- Keep table row actions visible and predictable; use icon/tooltip state for compact controls.
- Keep forms explicit about defaults, validation, dehydration, and visibility when state affects saved data.
- Do not change role-aware queries or workflow gates just to simplify UI code.
- Prefer small reusable helpers/components only when markup or configuration is repeated or visually complex.
- Reconcile values across list, detail, edit, generated document, and notification surfaces when a workflow depends on totals or statuses.

## Review Checklist

- Closure model types match the resource record.
- Actions authorize or rely on an established policy/resource gate.
- Hidden/disabled fields do not accidentally persist stale state.
- Table queries preserve tenant, role, status, and ownership filters.
- Labels and enum meanings match domain language exactly.
