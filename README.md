# WTS Landing Pages

Static landing pages for WTS (Windows That Seal). PHP files are processed into static HTML during build and deployed automatically to GitHub Pages.

## Quick Start

```bash
composer install    # Install dependencies
composer serve      # Preview at localhost:8000
composer build      # Generate static HTML
composer test       # Run tests
```

## Development

Edit PHP files in `src/` and preview changes with `composer serve`. The site uses simple PHP includes for header/footer partials.

**Structure:**
- `src/*.php` → Source pages (converted to HTML)
- `src/partials/` → Reusable header/footer
- `src/assets/` → CSS, images, logos
- `dist/` → Generated static files (git-ignored)

## Deployment

Pushing to `main` triggers automatic deployment:
1. GitHub Actions builds the site
2. Generates static HTML in `dist/`
3. Deploys to GitHub Pages
4. Live at `standarddoors.github.io/wts-microsite/`

**Custom domain:** Configure `wts.standarddoors.com` in Cloudflare to point to GitHub Pages.

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
