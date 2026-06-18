---
name: cosamey-test
description: Apply Mey Pest 4 testing conventions in Mey Laravel projects. Use when the user explicitly asks to write tests, when reviewing or refactoring existing tests, or when editing Pest tests, expectations, factories, fakes, and Laravel test setup.
---

# Mey Test

## Activation Rule

Do not write new tests unless the user explicitly asks for them. Use this skill when tests are requested or existing tests are part of the task.

## Rules

- Use Pest v4 conventions.
- Use `test()` instead of `it()`.
- Test closures must declare `void`.
- Follow Arrange / Act / Assert order with blank lines between sections.
- Prefer `expect()` assertions.
- Use `beforeEach()` for shared setup.
- Use Laravel fakes after factory setup unless the test intentionally needs the fake earlier.
- Prefer focused tests that cover the changed behavior.
- Keep generated test data readable with factory states and explicit important values.

## Example

```php
test('records activity on first request', function (): void {
    // Arrange
    $user = User::factory()->create();
    $now = now();

    // Act
    $user->markAsActive($now);

    // Assert
    expect($user->last_active_at)->toEqual($now);
});
```
