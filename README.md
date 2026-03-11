# WTS Landing Pages

Static landing pages for WTS (Windows That Seal). PHP source files are pre-rendered to static HTML at build time and served via GitHub Pages. Pages auto-refresh every hour so in-store displays always pick up the latest deploy.

## How it works

`build.php` processes each `src/*.php` file through PHP, captures the output as static HTML, and writes it to `dist/`. No PHP runs on the server. A custom `dist/404.html` redirects legacy `.php` URLs to their `.html` equivalents.

## Quick start

```bash
composer install  # Install dependencies
composer serve    # Preview at localhost:8000
composer build    # Generate static HTML in dist/
```

## Project structure

- `src/*.php` — Source pages
- `src/partials/` — Shared components (header, footer, logo, banners)
- `src/Helpers/` — Utility classes (DateHelper, etc.)
- `src/assets/` — CSS, images, logos
- `dist/` — Generated output (git-ignored)
- `tests/` — PHPUnit tests

## Quality tools

Run `composer check` before committing — it runs the full suite:

```bash
composer lint          # PSR-12 code style check
composer lint:fix      # Auto-fix style issues
composer analyse       # PHPStan static analysis (level 5)
composer test          # Unit tests
composer validate-html # Build + HTML validation (PHPUnit + W3C Nu Checker)
composer check         # All of the above
```

## CI/CD

**On pull request** — lint, static analysis, unit tests, build check, HTML validation.

**On push to `main`** — same as above, then deploy to GitHub Pages. Live at `wts.standarddoors.com`.

**Daily at 2:00 AM EST** — full rebuild and deploy so time-sensitive banners stay current.

## Requirements

- PHP 8.1+ with `intl` extension
- Composer
- Docker (required locally for `composer check` and `composer validate-html` — used for W3C Nu HTML Checker)
