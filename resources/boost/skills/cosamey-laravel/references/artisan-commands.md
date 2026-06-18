# Artisan Commands

## Naming

- Use kebab-case command names.
- Name command classes as action + `Command` when that matches the project.
- Keep command signatures explicit and readable.

## Behavior

- Print output before processing an item when diagnosing long-running work would benefit.
- Show progress for large loops.
- Print a clear summary at the end.
- Return a success or failure code when the command has meaningful failure states.
- Keep business logic in actions or services; commands coordinate input/output and execution.

## Style

- Type command methods and properties.
- Use early returns for invalid input or missing resources.
- Avoid silent long-running commands unless they are intentionally machine-only.
