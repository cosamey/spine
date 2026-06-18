# Alpine.js and Small JavaScript

## Alpine

- Use Alpine for small, local UI behavior.
- Keep Alpine state close to the markup it controls.
- Avoid large inline expressions.
- Move complex behavior into a JS module or Livewire component.
- Do not put business rules, pricing, authorization, or persistence decisions in Alpine.

## JavaScript and TypeScript

- Follow the existing project Prettier/ESLint config.
- Prefer `const`; use `let` only for reassignment.
- Never use `var`.
- Use `===` and `!==`.
- Use descriptive variable names in multi-line code.
- Use `function` for named functions.
- Use arrow functions for concise callbacks.
- Do not use arrow functions when `this` binding matters.
- Prefer destructuring when it improves clarity.
- Use object method shorthand.
