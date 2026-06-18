# Blade Components

## Structure

Recommended view organization:

```text
resources/views/
├── components/
│   ├── art/
│   │   ├── icons/
│   │   └── logo.blade.php
│   ├── domains/
│   │   └── product/
│   │       └── card.blade.php
│   ├── layouts/
│   │   └── app/
│   │       └── index.blade.php
│   ├── sections/
│   │   ├── common/
│   │   │   └── footer.blade.php
│   │   └── home/
│   │       └── hero.blade.php
│   └── ui/
│       └── container.blade.php
├── livewire/
└── pages/
    ├── static/
    │   └── home.blade.php
    └── products/
        └── show.blade.php
```

- `resources/views/pages` contains route-facing pages and should stay thin.
- `components/sections/<page>` contains page sections.
- `components/ui` contains reusable primitives.
- `components/domains` contains domain-specific reusable pieces.
- `components/art` contains logos, icons, and decorative vectors.

## Extraction

- Extract a section when a page has multiple visual sections or the markup becomes hard to scan.
- Extract repeated buttons, inputs, badges, cards, modals, and status displays.
- Keep page templates responsible for order and high-level composition.
- Keep section components responsible for local layout and presentational details.
- Use partials only when the project already uses partials for the same pattern or a component would add no value.

## SVG

- Use icon packages only when they are already installed in the project, such as existing Lucide Blade components.
- Do not install icon packages yourself.
- Do not copy package-provided icons into local SVG components.
- Store custom SVG markup in Blade components.
- Render custom SVG components with component syntax, for example `<x-art.icons.chevron-right class="size-4" />`.
- Use `$attributes->merge([...])` for sizing/color defaults.
- Use `currentColor` for monochrome icons unless fixed fills are required.
- Use `aria-hidden="true"` for decorative SVGs.
- Preserve accessible labels only when the SVG conveys meaning.
- Clean exported SVG markup before committing: remove editor metadata, unnecessary IDs, excessive decimals, hidden layers, and unused masks when safe.
