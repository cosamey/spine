# Spine Package Conventions

This project uses the `cosamey/spine` Laravel foundation package, which establishes opinionated defaults and coding standards.

- Always activate the `mey-standards` skill when writing, editing, reviewing, or formatting any PHP or Blade files.
- The spine package configures several global defaults that affect how models, URLs, and numbers behave — consult the skill for details before writing relevant code.
- After every feature or change, run `composer format` to format the codebase, then `composer check` to verify tests and static analysis pass.
