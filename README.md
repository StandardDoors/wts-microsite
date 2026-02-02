# WTS Landing Pages

Static landing pages for Standard Doors WTS home tabs.

## Project Structure

```
src/
├── pages/          # HTML pages with partial placeholders
├── partials/       # Reusable HTML components (head, footer)
└── assets/         # CSS, JS, and images
dist/               # Generated HTML files (deployed to Netlify)
build.js            # Simple build script
```

## Development

### Build
```bash
npm run build
```

### Local Preview
```bash
npm run serve
```

Then open http://localhost:8080

## Date-Based Content Visibility

Content blocks use `data-show-from` and `data-show-until` attributes for automatic show/hide based on UTC dates:

```html
<div style="display:none" data-show-from="2026-01-05" data-show-until="2026-01-12">
    This content only shows between Jan 5-12, 2026
</div>
```

- All dated content is hidden by default with `style="display:none"`
- JavaScript (using day.js) shows content when current UTC date falls within the specified range
- Use ISO date format: `YYYY-MM-DD`

## Updating Content

1. Edit files in `src/pages/` 
2. Update dates in `data-show-from` / `data-show-until` attributes
3. Run `npm run build`
4. Commit and push to deploy via Netlify

## Netlify Deployment

Netlify automatically builds and deploys when you push to the main branch.

- Build command: `npm run build`
- Publish directory: `dist`
- Redirects from `.php` to `.html` are handled automatically
