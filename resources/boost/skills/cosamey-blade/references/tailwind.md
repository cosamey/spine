# Tailwind CSS

## Sizing

- Prefer Tailwind scale classes: `w-4`, `h-6`, `gap-3`, `rounded-md`, `text-sm`, `leading-6`.
- Use arbitrary values only when the exact visual result requires them.
- Round arbitrary pixel values to whole pixels when visually acceptable.
- Use at most one decimal place when fractional precision is useful.
- Keep related measurements consistent across repeated elements.

## Avoid

```html
<div class="w-[15.2131px] h-[31.9874px] translate-x-[2.381px]">
```

## Prefer

```html
<div class="h-8 w-4 translate-x-0.5">
```

## Review

- Scan changed Blade, JS, TS, Vue, React, and CSS files for noisy arbitrary values.
- Replace excessive precision with scale classes or rounded values.
- Keep class lists readable and grouped in the style already used by the project.
