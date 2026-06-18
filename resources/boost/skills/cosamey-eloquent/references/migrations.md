# Migrations

## Default Behavior

- Follow the application's migration policy and nearby migration history.
- Do not assume whether an existing migration should be edited or replaced unless the task or project context makes that clear.
- Keep schema changes and data migrations separate unless the existing project clearly combines them for a reason.

## Structure

- Use typed anonymous migration classes.
- Prefer `constrained()` for conventional foreign keys.
- Add indexes in the migration where the query need is introduced.
- Place combined indexes at the end of the migration after column definitions and table configuration.
- Mirror database defaults in model defaults when application behavior depends on them.

## Rollbacks

- Do not include `down()` methods.
- Use forward migrations to change schema after a migration has been applied.

## Mey Project Considerations

- Production blocks destructive commands through `DB::prohibitDestructiveCommands(app()->isProduction())`.
- Treat column/table drops as deployment-sensitive.
- Use morph-map keys in seeded morph data, not class strings.
