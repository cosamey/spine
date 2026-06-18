---
name: cosamey-livewire
description: Apply Mey Livewire 4 conventions in Mey Laravel projects. Use when creating, editing, reviewing, or refactoring Livewire class components, Livewire views, typed public state, lifecycle hooks, validation attributes, computed properties, form objects, events, browser interaction, and Livewire-backed UI flows.
---

# Mey Livewire

## Workflow

1. Load `cosamey-laravel` for PHP style and action boundaries.
2. Verify the installed Livewire version and existing component patterns.
3. Use class-based Livewire components only.
4. For meaningful user workflows, verify behavior in the browser when possible.

## Rules

- Do not use Volt or functional Livewire components.
- Put component classes under `app/Livewire`.
- Put component views under `resources/views/livewire`.
- Avoid the `Form` suffix for Livewire component classes; reserve it for `Livewire\Form` objects.
- Keep class members ordered as properties, `mount()` when needed, public methods, private methods, computed methods, then `render()`.
- Type public properties that back form inputs.
- Type lifecycle hooks and action methods, including `void`.
- Prefer typed model properties with `#[Locked]` when the component owns a stable model instance.
- Use `#[Computed]` for derived values that are expensive, reused, or conditionally accessed in a request.
- Access computed properties with `$this->property` in PHP and Blade, and clear stale request-cache values with `unset($this->property)` after mutating their source data.
- Do not put computed properties on `Livewire\Form` objects.
- Use `#[Validate([...])]` with array rule syntax on simple static form properties for colocated rules and real-time validation.
- Use `rules()` instead of `#[Validate]` when validation needs `Rule` objects, dynamic values, or complex conditional logic.
- Extract form state to a `Livewire\Form` object when a component has several related form properties, reusable form logic, or validation that makes the component noisy.
- Keep business workflows in actions; Livewire methods coordinate state, validation, action calls, and browser events.
- Keep validation timing intentional. Avoid update-time validation when the UX should validate only on submit.
- Dispatch named events with explicit payloads when other components or browser code consume them.
- Keep Livewire views readable; use `cosamey-blade` for larger view/component structure.

## Example

```php
use App\Actions\CreateInvoice;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class InvoiceForm extends Component
{
    #[Locked]
    public Order $order;

    #[Validate(['string', 'max:1000'])]
    public string $notes = '';

    public function submit(): void
    {
        new CreateInvoice()->execute($this->order, $this->notes);

        $this->dispatch('invoice-created');
    }

    #[Computed]
    public function canSubmit(): bool
    {
        return $this->order->exists;
    }

    public function render(): View
    {
        return view('livewire.invoice-form');
    }
}
```
