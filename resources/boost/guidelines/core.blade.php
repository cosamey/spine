# Mey Project Conventions

This project follows Cosa Mey Laravel conventions. Mey projects are expected to define shared Laravel defaults in the application, even when those defaults are not configured directly by the `spine` package.

- Always activate the `cosamey-laravel` skill when writing, editing, reviewing, or formatting Laravel/PHP code in a Mey project.
- Activate focused Mey skills only when relevant: `cosamey-eloquent`, `cosamey-livewire`, `cosamey-filament`, `cosamey-blade`, and `cosamey-test`.
- Mey project defaults affect models, morph maps, URLs, destructive commands, and number formatting; consult `cosamey-laravel` before writing relevant code.
- After every feature or change, run `composer check` to verify static analysis, formatting, and tests pass.
