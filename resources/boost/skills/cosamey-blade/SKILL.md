---
name: cosamey-blade
description: Apply Mey Blade, Tailwind CSS 4, Alpine.js, SVG, and small frontend UI conventions in Mey Laravel projects. Use when creating, editing, reviewing, or refactoring Blade views, Blade components, page sections, inline SVGs, Tailwind classes, Alpine behavior, or small JavaScript/TypeScript used by Laravel UI.
---

# Mey Blade

## Workflow

1. Load `cosamey-laravel` for application conventions.
2. Inspect nearby Blade components and page structure before adding markup.
3. Use references only as needed:
   - `references/blade-components.md` for view/component structure and SVGs.
   - `references/tailwind.md` for Tailwind sizing and class hygiene.
   - `references/alpine-js.md` for Alpine and small JS/TS behavior.

## Rules

- Keep route-facing pages thin and composed from Blade components.
- Extract repeated or visually complex markup into focused components.
- Prefer components over includes for reusable UI.
- Pass data explicitly through props.
- Use icon packages only when they are already installed in the project, such as existing Lucide Blade components; do not install icon packages yourself.
- Prefer Tailwind scale classes before arbitrary values.
- Round noisy design-export measurements.
- Keep Alpine behavior small, local, and UI-focused.
- Do not hide business rules in Blade, Alpine, or JavaScript.

## References

- `references/blade-components.md`
- `references/tailwind.md`
- `references/alpine-js.md`
