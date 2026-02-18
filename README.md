# WTS Landing Pages

Static landing pages for WTS (Windows That Seal). PHP files are processed into static HTML during build and deployed automatically to GitHub Pages.

## Quick Start

```bash
composer install    # Install dependencies
composer serve      # Preview at localhost:8000
composer build      # Generate static HTML
```

## Quality Tools

```bash
composer lint       # Check code style (PSR-12)
composer lint:fix   # Auto-fix style issues
composer analyse    # Static analysis (PHPStan level 5)
composer test       # Run tests
composer check      # Run all: lint → analyse → test
```

Run `composer check` before committing to catch issues early.

## Development

Edit PHP files in `src/` and preview changes with `composer serve`. The site uses simple PHP includes for header/footer partials.

**Structure:**
- `src/*.php` → Source pages (converted to HTML)
- `src/partials/` → Reusable header/footer
- `src/Helpers/` → Utility classes (DateHelper, etc.)
- `src/assets/` → CSS, images, logos
- `dist/` → Generated static files (git-ignored)
- `tests/` → PHPUnit tests

## Deployment

Pushing to `main` triggers automatic deployment:
1. GitHub Actions runs tests
2. Builds static HTML in `dist/`
3. Deploys to GitHub Pages
4. Live at `standarddoors.github.io/wts-microsite/`

**Custom domain:** Configure `wts.standarddoors.com` in Cloudflare to point to GitHub Pages.

## CI/CD

**On pull request:**
- Lint check (PHP_CodeSniffer)
- Static analysis (PHPStan)
- Unit tests (PHPUnit)
- Build verification

**On push to main:**
- All checks above + deploy to GitHub Pages

**Daily schedule:**
- Rebuild at 7:00 UTC to update time-sensitive banners

## URL Redirects

Old `.php` URLs automatically redirect to `.html` via custom 404 page. Legacy bookmarks continue working:
- `/wts-usa.php` → `/wts-usa.html`
- `/wts-en.php` → `/wts-en.html`

## How It Works

The `build.php` script:
1. Processes each `.php` file in `src/` through PHP
2. Captures output as static `.html`
3. Copies assets and creates 404 redirect page
4. Outputs everything to `dist/`

No PHP execution happens on the server—everything is pre-rendered.

## Requirements

- PHP 8.1+
- Composer
