# Rule: 01-api-incremental-testing.md

---
id: api_incremental_testing
description: "Always test API changes with small datasets before running on full data to prevent mass errors"
---

# API Incremental Testing

When making changes to API interactions, always test incrementally before processing large datasets.

## Core Principle

**Test small, then scale.** Never run modified API code against your entire dataset without validating the changes first.

## Testing Approach

### 1. Single Item Test
```bash
# Test with ONE item first
process_api_data --limit=1

# Verify the result manually
# Check logs, response format, error handling
```

### 2. Small Batch Test
```bash
# Test with a tiny subset (5-10 items)
process_api_data --limit=10

# Validate:
# - No unexpected errors
# - Response format is correct
# - Rate limiting works properly
# - Error handling behaves as expected
```

### 3. Gradual Scale-Up
```bash
# Only after small tests pass
process_api_data --limit=100   # Medium test
process_api_data               # Full dataset
```

## Why This Matters

### ✅ **Prevents Mass Failures:**
- API rate limiting issues discovered early
- Data format changes caught before corrupting large datasets
- Authentication problems identified quickly
- Network timeouts handled before affecting thousands of records

### ✅ **Saves Time:**
- Fix issues on 1 record vs. debugging 10,000 failed records
- Avoid API quota exhaustion from repeated failed requests
- Prevent data corruption requiring full rollback

### ✅ **Reduces Costs:**
- Many APIs charge per request - failed mass operations are expensive
- Prevents hitting rate limits that require waiting periods
- Avoids cleanup work from partial failures

## Implementation Examples

### API Client Changes
```python
# ❌ Don't do this
for item in all_10000_items:
    api_client.process(item)  # Untested changes

# ✅ Do this instead
test_items = all_items[:1]  # Test with 1 item first
for item in test_items:
    result = api_client.process(item)
    print(f"Test result: {result}")  # Verify manually

# After verification, proceed with full dataset
```

### Database Operations
```bash
# ❌ Don't run untested changes on full table
UPDATE users SET new_column = api_fetch(user_id);

# ✅ Test with LIMIT first
UPDATE users SET new_column = api_fetch(user_id) LIMIT 5;
-- Check results manually
-- Then proceed with full update
```

## Red Flags

Stop and test incrementally when you:
- Modified API request format or parameters
- Changed authentication or headers
- Updated error handling logic
- Switched API endpoints or versions
- Added new data processing steps
- Changed rate limiting or retry logic

**Remember: It's always faster to test 1 record properly than to debug 1,000 failed records.**

---

# Rule: 01-be-thorough.md

---
id: be_thorough
description: "Always be thorough and complete in analysis, discovery, and task execution"
---

# Be Thorough

Always be thorough and complete in analysis, discovery, and task execution. Don't skip steps or make assumptions about scope.

## Discovery and analysis

- When asked to analyze a directory, check ALL subdirectories recursively
- Use systematic discovery methods (`find`, `ls -la`, directory traversal) to ensure complete coverage
- Don't assume what's important - examine everything first, then prioritize
- Document what you analyzed vs what you skipped, and why

## Task execution

- Read requirements carefully and completely before starting
- Break down complex tasks into comprehensive checklists
- Verify you've addressed all parts of multi-part requests
- Double-check your work against the original requirements

## When reporting findings

- Clearly state the scope of your analysis ("I analyzed X but did not examine Y")
- Call out any limitations or areas you didn't cover
- If you discover you missed something, acknowledge it explicitly and offer to complete the analysis

## Self-checking

Before declaring a task complete, ask yourself:
- Did I examine all the areas requested?
- Are there obvious places I should have looked but didn't?
- Would someone reviewing my work find glaring omissions?

*This rule exists because thoroughness prevents having to redo work and builds trust through comprehensive analysis.*

---

# Rule: 01-cloud-services-cli-standards.md

---
id: cloud_services_cli_standards
description: "Standards for configuring and using cloud service CLIs and maintaining service integrations"
---

# Cloud Services & CLI Standards

Establish consistent configuration and usage patterns for cloud service CLIs and API integrations.

## ✅ DO

**AWS CLI Configuration**
- Use named profiles for different AWS accounts/environments
- Configure profiles with descriptive names: `personal`, `work-dev`, `work-prod`
- Set default regions appropriate for each profile
- Use AWS SSO when available for better security
- Store profiles in `~/.aws/config` and credentials in `~/.aws/credentials`

**GitHub CLI (gh) Best Practices**
- Authenticate using `gh auth login` with appropriate scopes
- Use SSH keys for repository operations
- Configure default settings for common operations
- Use `gh` for workflow automation instead of web interface when possible
- Set up aliases for frequently used commands

**Docker Configuration**
- Configure Docker daemon settings appropriately for development
- Use BuildKit by default for improved build performance
- Set up multi-platform builds when needed
- Configure resource limits to prevent system impact
- Keep commonly used Docker commands documented and accessible

**Service Integration Principles**
- Use environment-based configuration (development, staging, production)
- Keep service credentials in appropriate secret management (direnv, not hardcoded)
- Document which services require which credentials
- Use service-specific CLI tools when available rather than generic HTTP clients

## ❌ DON'T

**Avoid Configuration Mistakes**
- Don't use default/unnamed profiles for production services
- Don't hardcode credentials in scripts or configuration files
- Don't mix development and production credentials in the same profile
- Don't ignore CLI tool updates that might include security fixes

**Security Anti-patterns**
- Don't store credentials in version control
- Don't use overly broad permissions when specific scopes are available
- Don't skip environment-specific configurations
- Don't share credentials between different environments or projects

## Configuration Examples

**AWS CLI Profile Setup**
```ini
# ~/.aws/config
[profile personal]
region = us-west-2
output = json

[profile work-dev]
region = us-east-1
output = json
sso_start_url = https://company.awsapps.com/start

[profile work-prod]
region = us-east-1
output = json
sso_start_url = https://company.awsapps.com/start
```

**GitHub CLI Configuration**
```bash
# Set up useful aliases
gh alias set prs 'pr list --assignee @me'
gh alias set issues 'issue list --assignee @me'
gh alias set co 'pr checkout'

# Configure default behavior
gh config set git_protocol ssh
gh config set editor vim
```

**Common Docker Commands Reference**
```bash
# Development workflow
docker-compose up -d
docker-compose logs -f
docker system prune  # cleanup

# Multi-platform builds
docker buildx create --use
docker buildx build --platform linux/amd64,linux/arm64
```

## Service-Specific Guidelines

**AWS Services**
- Use least-privilege IAM policies
- Prefer temporary credentials over long-lived access keys
- Use AWS profiles for different environments
- Document which AWS services each project uses

**GitHub Integration**
- Use GitHub CLI for repository operations
- Set up repository templates with consistent structure
- Use GitHub Actions for CI/CD when appropriate
- Keep issue and PR templates updated

**Docker Workflow**
- Use multi-stage builds for production images
- Keep Docker images minimal and secure
- Use .dockerignore files appropriately
- Document container dependencies and networking requirements

## Backup and Recovery

**Credential Backup**
- Keep secure backups of important credentials (LastPass, 1Password, etc.)
- Document credential recovery procedures
- Test credential recovery process periodically
- Keep emergency access methods documented

**Configuration Backup**
- Include CLI configurations in dotfiles management
- Back up service-specific settings and preferences
- Document service integrations and dependencies
- Keep setup procedures documented for new machines

This approach ensures reliable, secure access to cloud services while maintaining good security practices and operational consistency.

---

# Rule: 01-commit-message-standards.md

---
id: commit_message_standards
description: "Use clear, conventional commit messages with emoji prefixes for consistent project history"
---

# Commit Message Standards

Use clear, conventional, and actionable commit messages to maintain a high-quality project history. Keep commit messages short, clear, and imperative.

## Emoji Prefixes

Start each commit with one emoji:

- 🌪 **New value** · new feature or major addition
- 💨 **Enhancement** · improvements to existing features
- 🧰 **DevX** · developer tooling/workflow changes
- 💡 **KTLO** · routine maintenance/operations
- 🚑 **Hotfix** · urgent critical fix
- 🐛 **Defect** · bug fix for live features

## Format Rules

- Start with an emoji, then a short imperative summary
- Use **imperative mood**: e.g., "Add validation", not "Added validation"
- Focus on **what changed**, not why (rationale goes in PR description)
- Keep the subject line **≤50 characters**, capitalized, no trailing period
- Avoid repetition of filenames or context already evident in the diff
- Don't write rationale; keep messages short and practical

## Examples

**Good commit messages:**
- `🐛 Fix null check in webhook handler`
- `🧰 Speed up local dev with turbo cache`
- `💨 Simplify auth middleware branches`
- `🌪 Add CSV export for workspace reports`

**Bad commit messages:**
- `Updated commit message guidelines for clarity` (too verbose)
- `Fixed bug` (not specific)
- `Added new feature that does X Y and Z because we need it for the client` (too long, includes rationale)
- `fix: correct typo in dashboard label`
- `refactor: streamline auth middleware`
- `docs: add README install instructions`


---

# Rule: 01-local-server-error-monitoring.md

---
id: local_server_error_monitoring
description: "Automatically extract, format, and present local development server errors for easy debugging"
---

# Local Server Error Monitoring

When users run local development servers (http-server, Vite, Astro, Netlify dev, etc.), automatically monitor output for errors and present them in a clear, actionable format.

## When to Monitor

Trigger on server output containing:
- HTTP 404/500 errors
- Build/compilation errors
- Asset loading failures
- CORS errors
- Port conflicts
- Module resolution errors

## Error Presentation Format

### Summary First

```
🔍 Server Issues Detected:
- 3 × 404 errors (missing assets)
- 1 × favicon missing
```

### Detailed Breakdown

List each unique error with type, affected resource, frequency, and suggested fix:

```
📁 Missing Assets (404):
  → /assets/style.css
  → /assets/logos/logo.png

  Likely cause: Build output path mismatch
  Check: Does `dist/assets/` contain these files?
```

## Common Error Patterns

### 404 Errors
- Verify file exists in build output
- Check asset path configuration
- Verify build process completed successfully

### Asset Path Issues
Common causes:
1. Build output path configured incorrectly
2. Assets not copied during build
3. Base URL path mismatch

Quick checks: `ls -la dist/assets/` and review build config (vite.config.js, astro.config.mjs, etc.)

### Port Conflicts
Quick fixes:
- Kill process: `lsof -ti:XXXX | xargs kill`
- Use different port: `--port 3001`
- Check for zombie processes: `ps aux | grep node`

### Build Errors
Present with: affected file, line number (if available), and specific fix based on error type.

## ✅ DO

- **Automatically scan** server output for error patterns
- **Group similar errors** to reduce noise
- **Provide actionable suggestions** for each error type
- **Show file paths** relative to project root
- **Count repeated errors** to highlight frequency

## ❌ DON'T

- Don't show raw log output without processing
- Don't present errors without context or suggestions
- Don't ignore patterns that indicate common misconfigurations
- Don't skip verification steps that could clarify root cause

## Server-Specific Patterns

**http-server**: 404 errors, missing files, CORS issues
**Vite/Astro**: Module resolution errors, hot reload failures, asset transformation issues
**Netlify Dev**: Function errors, redirect/rewrite issues, environment variable problems

## Proactive Debugging

After detecting errors, offer to:
1. Check build output — verify files exist where expected
2. Review configuration — check relevant config files
3. Compare paths — match requested paths to actual file structure
4. Suggest fixes — based on common patterns and project type


---

# Rule: 01-php-code-quality.md

---
id: php_code_quality
description: "PHP code quality tooling with PHP_CodeSniffer and PHPStan"
---

# PHP Code Quality

Code quality tooling standards using PHP_CodeSniffer (PHPCS) and PHPStan.

## PHP_CodeSniffer (PHPCS)

### Configuration
Configure via `phpcs.xml` in the project root:

```xml
<?xml version="1.0"?>
<ruleset name="Project Name">
    <description>Coding standards</description>

    <!-- Scan targets -->
    <file>src</file>
    <file>tests</file>
    <file>build.php</file>

    <!-- Exclusions -->
    <exclude-pattern>*/vendor/*</exclude-pattern>
    <exclude-pattern>*/dist/*</exclude-pattern>

    <!-- PSR-12 base standard -->
    <rule ref="PSR12"/>

    <!-- Enforce short array syntax -->
    <rule ref="Generic.Arrays.DisallowLongArraySyntax"/>

    <!-- Line length -->
    <rule ref="Generic.Files.LineLength">
        <properties>
            <property name="lineLimit" value="120"/>
            <property name="absoluteLineLimit" value="0"/>
        </properties>
        <exclude-pattern>*/partials/*</exclude-pattern>
    </rule>

    <!-- Show progress and colors -->
    <arg value="sp"/>
    <arg name="colors"/>
</ruleset>
```

### Key Rules
- **Base standard**: PSR-12
- **Short array syntax**: enforced via `Generic.Arrays.DisallowLongArraySyntax`
- **Line length**: 120 soft limit, no hard limit (`absoluteLineLimit=0`)

### Relaxations for Partials
Partials contain mixed PHP/HTML and need relaxed rules:
- **Exclude from line length checks** — HTML content often exceeds limits
- **Exclude from `SideEffects` rule** — partials produce output by design
- **Exclude from docblock requirements** — template fragments don't need full docblocks

```xml
<rule ref="PSR1.Files.SideEffects">
    <exclude-pattern>*/partials/*</exclude-pattern>
    <exclude-pattern>build.php</exclude-pattern>
</rule>
```

### Relaxed Docblock Rules
Keep docblock rules practical — require structure but don't enforce every tag:

```xml
<rule ref="Squiz.Commenting.FunctionComment">
    <exclude name="Squiz.Commenting.FunctionComment.MissingParamTag"/>
    <exclude name="Squiz.Commenting.FunctionComment.MissingReturn"/>
    <exclude name="Squiz.Commenting.FunctionComment.Missing"/>
    <exclude-pattern>*/partials/*</exclude-pattern>
</rule>

<rule ref="Squiz.Commenting.ClassComment">
    <exclude name="Squiz.Commenting.ClassComment.Missing"/>
</rule>

<rule ref="Squiz.Commenting.FileComment">
    <exclude name="Squiz.Commenting.FileComment.Missing"/>
    <exclude name="Squiz.Commenting.FileComment.MissingPackageTag"/>
</rule>
```

### Commands
```bash
# Check code style
composer lint           # or: vendor/bin/phpcs

# Auto-fix fixable issues
composer lint:fix       # or: vendor/bin/phpcbf

# CI: output as checkstyle for PR annotations
vendor/bin/phpcs --report=checkstyle -q | cs2pr
```

## PHPStan

### Configuration
Configure via `phpstan.neon` in the project root:

```neon
parameters:
    level: 5
    paths:
        - src
        - tests
        - build.php
    excludePaths:
        - vendor
        - dist
    bootstrapFiles:
        - vendor/autoload.php
    reportUnmatchedIgnoredErrors: false
```

### Key Settings
- **Level 5** — good balance of strictness and practicality
- **Bootstrap Composer autoloader** so PHPStan can resolve classes
- **Exclude `vendor/` and `dist/`** from analysis
- **`reportUnmatchedIgnoredErrors: false`** — don't fail when an ignored error no longer occurs

### Ignoring Errors
Use pattern-based ignores for intentional violations:

```neon
ignoreErrors:
    -
        message: '#Function [a-z_]+ has no return type specified#'
        path: src/partials/*
```

### Commands
```bash
# Run analysis
composer analyse        # or: vendor/bin/phpstan analyse

# CI: GitHub-format output for PR annotations
composer analyse -- --error-format=github
```

## Combined Quality Gate

Define a `check` script in `composer.json` that runs everything:

```json
{
    "check": ["@lint", "@analyse", "@test", "@validate-html"]
}
```

Run it locally before pushing:

```bash
composer check
```

## Best Practices

### ✅ DO:
- **Use PSR-12** as the base PHPCS standard
- **Set PHPStan to level 5+** — strict enough to catch real bugs
- **Relax rules for partials/templates** — they have different concerns than application code
- **Run both PHPCS and PHPStan in CI** as separate parallel jobs
- **Use GitHub-native output formats** (`cs2pr`, `--error-format=github`) for PR annotations
- **Keep a combined `check` script** for local pre-push validation

### ❌ DON'T:
- **Set `absoluteLineLimit`** — let developers exceed soft limits for HTML content
- **Require docblocks on every function** — typed code is often self-documenting
- **Ignore PHPStan errors globally** — use path-specific ignores
- **Skip analysis on test files** — tests should follow the same standards as production code


---

# Rule: 01-task-brief-formats.md

---
id: task_brief_formats
description: "Two formats for task briefs: structured multi-disciplinary and casual short-form"
---

# Task Brief Formats

Choose between two formats based on task complexity and audience.

## Type 1: Structured task briefs

For multi-disciplinary teams requiring detailed context and planning.

### Format requirements

Start with a short intro paragraph **without a heading** — this should feel like a GitHub readme: plainspoken, context-rich, and immediately informative. Avoid any fluff.

Use clearly labeled section headings to organize content:

1. **Intro paragraph (no heading):**
   - Summarize where the task originated and current scope
   - Mention specific user or business trigger if relevant
   - State the goal or change at a high level

2. **# Why?**
   - Explain why the task matters
   - Provide motivation: recurring pain points, missed opportunities, or business upside
   - Include relevant trends, stakeholder asks, or time-sensitive needs

3. **# Use cases / real examples**
   - Bullet real or hypothetical examples of usage
   - Include links to related tasks, docs, designs, or recorded context
   - Ground the abstract goal in tangible situations

4. **# Requirements**
   - List specific needs for the solution, organized clearly
   - Include design/dev specs, config options, performance needs
   - Call out constraints or dependencies

5. **# Next step**
   - Clarify the immediate action expected
   - For dev tasks: "make an RFC"
   - For design/product/QA: specific evaluation or mockup tasks

### Style requirements

- Clear, intelligent, respectful of reader's time, occasionally funny
- No verbosity, no justification, don't embellish
- Assume assignee is capable and familiar with internal tools
- Avoid corporate speak or over-formality
- Always write for people outside your immediate discipline

### Additional requirements

- Prefer specific examples and real internal links over hypotheticals
- Include at least one screenshot, Figma link, or Loom if UX/UI related
- Keep section headers simple and functional — don't get poetic

## Type 2: Casual task descriptions

For straightforward tasks requiring quick, informal communication.

### Format requirements

Write 1–2 casual, human-sounding paragraphs. Avoid structured headers like "Goals" or "Next Steps" — just lay out the context and what needs doing naturally, like you'd say it in Slack.

### Style requirements

- Direct, thoughtful, and informal without being sloppy
- Assume assignee is smart and doesn't need hand-holding
- Prioritize clarity and tone over formality
- Should be easy to absorb at a glance

### Example

> The product overview page feels pretty clunky when scanned quickly — too much visual weight on secondary stuff, and the key message isn't landing above the fold. Can you take a pass at tightening it up? Not expecting a full redesign, just enough to make the hierarchy feel more intentional.

---

# Rule: 02-api-response-caching.md

---
id: api_response_caching
description: "Cache API responses to avoid redundant requests during development and testing, reducing costs and improving performance"
---

# API Response Caching

Implement intelligent caching to reduce API costs and improve performance during development and testing.

## Core Principle

**Cache API responses during development** to avoid hitting rate limits, reduce costs, and improve development speed.

## When to Use Caching

### Development and Testing
- **During development** - Avoid repeated API calls while iterating on code
- **When testing** - Use cached responses to test data processing without API overhead
- **For expensive APIs** - Cache responses from costly external services
- **During debugging** - Avoid re-fetching data while debugging processing logic

### Production Considerations
- **Frequent requests** - Cache responses for data that doesn't change often
- **Rate-limited APIs** - Prevent hitting rate limits with intelligent caching
- **Expensive operations** - Cache results of computationally expensive API calls

## Caching Strategies

### File-Based Caching (Simple)
Use for development and small-scale applications:

```python
import json
import os
from pathlib import Path
from datetime import datetime, timedelta

class APICache:
    def __init__(self, cache_dir=".cache/api"):
        self.cache_dir = Path(cache_dir)
        self.cache_dir.mkdir(parents=True, exist_ok=True)

    def get(self, key: str, max_age_hours: int = 24):
        """Get cached response if not expired."""
        cache_file = self.cache_dir / f"{key}.json"

        if not cache_file.exists():
            return None

        with open(cache_file) as f:
            data = json.load(f)

        # Check if cache is expired
        cached_time = datetime.fromisoformat(data['timestamp'])
        if datetime.now() - cached_time > timedelta(hours=max_age_hours):
            cache_file.unlink()  # Remove expired cache
            return None

        return data['response']

    def set(self, key: str, response: dict):
        """Cache API response."""
        cache_file = self.cache_dir / f"{key}.json"

        data = {
            'timestamp': datetime.now().isoformat(),
            'response': response
        }

        with open(cache_file, 'w') as f:
            json.dump(data, f, indent=2)
```

### JavaScript/Node.js Implementation

```javascript
const fs = require('fs');
const path = require('path');

class APICache {
  constructor(cacheDir = '.cache/api') {
    this.cacheDir = cacheDir;
    // Ensure cache directory exists
    if (!fs.existsSync(cacheDir)) {
      fs.mkdirSync(cacheDir, { recursive: true });
    }
  }

  get(key, maxAgeHours = 24) {
    const cacheFile = path.join(this.cacheDir, `${key}.json`);

    if (!fs.existsSync(cacheFile)) {
      return null;
    }

    const data = JSON.parse(fs.readFileSync(cacheFile, 'utf8'));
    const cachedTime = new Date(data.timestamp);
    const now = new Date();

    if (now - cachedTime > maxAgeHours * 60 * 60 * 1000) {
      fs.unlinkSync(cacheFile);
      return null;
    }

    return data.response;
  }

  set(key, response) {
    const cacheFile = path.join(this.cacheDir, `${key}.json`);

    const data = {
      timestamp: new Date().toISOString(),
      response: response,
    };

    fs.writeFileSync(cacheFile, JSON.stringify(data, null, 2));
  }
}
```

## Cache Key Strategies

### URL-Based Keys
```python
def get_cache_key(url: str, params: dict = None) -> str:
    """Generate cache key from URL and parameters."""
    key = url.replace('/', '_').replace('?', '_').replace('&', '_')
    if params:
        param_str = '_'.join(f"{k}_{v}" for k, v in sorted(params.items()))
        key += f"_{param_str}"
    return key
```

### Content-Based Keys  
```python
import hashlib

def get_content_cache_key(content: str) -> str:
    """Generate cache key from content hash."""
    return hashlib.md5(content.encode()).hexdigest()
```

## Cache Usage Pattern

```python
# Initialize cache
cache = APICache()

def fetch_user_data(user_id: int, use_cache: bool = True):
    cache_key = f"user_{user_id}"
    
    # Try cache first
    if use_cache:
        cached_data = cache.get(cache_key, max_age_hours=6)
        if cached_data:
            print(f"Using cached data for user {user_id}")
            return cached_data
    
    # Fetch from API
    print(f"Fetching fresh data for user {user_id}")
    response = api_client.get_user(user_id)
    
    # Cache the response
    if use_cache:
        cache.set(cache_key, response)
    
    return response
```

## Cache Invalidation

### Time-Based Expiration
- Set appropriate expiration times based on data freshness requirements
- Use shorter cache times for frequently changing data
- Use longer cache times for stable reference data

### Manual Invalidation
```python
def invalidate_cache(pattern: str = "*"):
    """Invalidate cache files matching pattern."""
    cache_dir = Path(".cache/api")
    for cache_file in cache_dir.glob(f"{pattern}.json"):
        cache_file.unlink()
        print(f"Invalidated cache: {cache_file.name}")
```

### Environment-Based Invalidation
```bash
# Clear all cache when switching environments
rm -rf .cache/api/

# Clear specific API cache
rm -f .cache/api/notion_*.json
```

## Best Practices

### ✅ DO:
- **Use descriptive cache keys** that include relevant parameters
- **Set appropriate expiration times** based on data freshness needs
- **Add cache debugging** to log cache hits/misses during development
- **Include cache status in logs** to track cache effectiveness
- **Use different cache directories** for different environments
- **Version your cache keys** when API response format changes

### ❌ DON'T:
- Cache sensitive data like authentication tokens or personal information
- Use overly short cache expiration times (defeats the purpose)
- Cache error responses (let them fail fresh each time)
- Ignore cache directory size (implement cleanup for large caches)
- Cache during production without considering data freshness requirements

## Cache Directory Structure

```
.cache/
├── api/
│   ├── notion_pages_123.json
│   ├── notion_blocks_456.json
│   ├── hubspot_contacts.json
│   └── user_data_789.json
├── images/
└── temp/
```

## Environment Integration

### Development Environment
```python
# Enable caching in development
USE_CACHE = os.getenv('NODE_ENV') == 'development'

data = fetch_api_data(params, use_cache=USE_CACHE)
```

### .gitignore Entry
```
# Cache directories
.cache/
*.cache
```

**Remember: API caching is primarily a development tool. Always consider data freshness requirements and cache invalidation strategies for production use.**

---

# Rule: 02-communication-style-direct.md

---
id: communication_style_direct
description: "Clear, efficient communication that prioritizes clarity and accuracy over style or persuasion"
---

# Direct Communication Style

Use a no-fluff tone — direct, technically credible, and minimal on adjectives or enthusiasm.

## Key principles

Avoid hype, "marketing-speak," embellishments, or unnecessary polish. Favor clear, utilitarian language that gets to the point without sounding cold.

Communication should read like something a smart, confident, and grounded person would write — sometimes fun even, but not performative or overly eager.

## Specific guidelines

- Be succinct. Avoid excessive adjectives
- Favor a skeptical, curious stance over absolutes
- Ask questions when unsure; don't assume without data
- Eliminate filler language or exaggerated claims
- Avoid persuasive or salesy tone unless requested
- Use a professional tone that respects the user's time and intelligence
- Default to a professional, neutral tone that works across roles
- Prioritize clarity and accuracy over style or persuasion
- Provide concise answers and practical suggestions
- Use structured output where relevant (bullets, headings, tables)
- Avoid hype or sales language
- Be direct, but not abrupt
- Ask clarifying questions when context is missing
- Avoid unnecessary preamble and keep messages streamlined and to-the-point

## For different contexts

**Email**: Make it sound like a human who types quickly but thoughtfully. Keep efficient, friendly, and lightly conversational. No fluffy sign-offs or over-thanking.

**Slack/Async**: Keep direct, structured, and efficient. Use plainspoken language with technical clarity. Format for scan-ability with headers and bullet points. Tag people with purpose, not ceremony.

---

# Rule: 02-cursor-standards.md

---
id: cursor_standards
description: "Standards for Cursor IDE configuration and rules organization"
---

# Cursor IDE Standards

Configuration standards and best practices for working with Cursor IDE.

## Rules Directory Structure

- Place Cursor rules files in the `.cursor/rules/` directory
- **Do not** use `cursorrules` or other non-standard directory names
- Organize rules by domain or functionality when you have multiple rule files
- Use descriptive filenames that clearly indicate the rule's purpose

## Examples

✅ **Correct directory structure:**

    .cursor/
      rules/
        general.md
        typescript.md
        testing.md

❌ **Avoid these patterns:**

    cursorrules/          # Wrong directory name
    .cursorrules/         # Wrong directory name
    .cursor/cursorrules   # Wrong nested structure

## File Organization

- Keep rules focused and specific to avoid conflicts
- Use clear, descriptive rule names
- Group related rules together in the same file
- Separate language-specific rules from general coding standards

## Best Practices

- Regularly review and update Cursor rules to match project evolution
- Test rules with actual code to ensure they work as expected
- Document any custom rules that team members should be aware of
- Keep rules version controlled with the rest of your project configuration

---

# Rule: 02-daily-development-workflows.md

---
id: daily_development_workflows
description: "Standardized daily workflows for development setup, maintenance, and common operations"
---

# Daily Development Workflows

Establish consistent, efficient workflows for common development tasks and system maintenance.

## ✅ DO

**Morning Setup Routine**
- Check system status: disk space, running services, updates needed
- Update key repositories and configurations (`chezmoi update`, `git pull` on active projects)
- Verify development environment health (language versions, key services running)
- Review any overnight build failures or alerts

**Project Switching Workflow**
- Use `direnv` to automatically load project-specific environment variables
- Verify correct language versions are active (check `.python-version`, `.nvmrc`)
- Pull latest changes and check for dependency updates
- Run health checks (tests, linting) after switching contexts

**Common Development Operations**
- Use consistent shortcuts and aliases for frequent operations
- Maintain a personal command reference for complex operations
- Use `gh` CLI for GitHub operations instead of web interface when possible
- Keep commonly used Docker commands and configurations readily available

**End-of-Day Cleanup**
- Commit and push work in progress with clear WIP markers
- Close unnecessary applications and free up system resources
- Back up any important configuration changes
- Review and clean up temporary files and downloads

## ❌ DON'T

**Avoid Inefficient Patterns**
- Don't manually recreate the same environment setups repeatedly
- Don't skip environment verification when switching projects
- Don't leave uncommitted changes without clear tracking
- Don't accumulate system clutter without regular cleanup

**Bad Habits to Avoid**
- Don't rely on memory for complex command sequences - document them
- Don't skip backing up configurations before making changes
- Don't ignore system health indicators (disk space, memory usage)
- Don't let temporary solutions become permanent without proper implementation

## Quick Reference Commands

**System Status**
```bash
# Disk space and system health
df -h
top -l 1 | head -10
brew doctor  # if using Homebrew
```

**Git Operations**
```bash
# Quick status across projects
find ~/Projects -name .git -type d -exec dirname {} \; | xargs -I {} sh -c 'echo "\n=== {} ==="; git -C {} status --porcelain'

# Common GitHub CLI operations
gh repo list
gh pr status
gh issue list --assignee @me
```

**Development Environment**
```bash
# Verify language versions
python --version && which python
node --version && which node
docker --version && docker ps

# direnv status
direnv status
env | grep -E "(API_KEY|TOKEN)"
```

**Project Templates**
- Maintain templates for new project initialization
- Include standard files: README.md, .gitignore, .envrc template, basic CI/CD
- Keep language-specific templates (Python with poetry, Node with package.json)
- Document project setup steps that can't be automated

## Integration with AI Tools

**AI Rule Management**
- Keep AI rules updated as development practices evolve
- Use rulebook-ai project sync to apply rules to new projects
- Document any project-specific AI rule requirements
- Regular review of AI-generated code against established standards

**Automation Opportunities**
- Script repetitive setup tasks
- Use make files or task runners for common project operations
- Automate environment verification and health checks
- Create shortcuts for complex multi-step operations

This approach creates predictable, efficient workflows that scale across different projects and development contexts.

---

# Rule: 02-php-composer.md

---
id: php_composer
description: "Composer dependency management, script conventions, and CI integration"
---

# Composer Standards

Composer dependency management patterns, script conventions, and CI integration.

## composer.json Structure

### Required Fields
Every project must define:
- `name` — vendor/package format
- `description` — brief project summary
- `type` — typically `project`
- `require.php` — minimum PHP version constraint (currently `^8.5`)

### Dependency Organization
- **Runtime dependencies** in `require` — only what production code needs
- **Dev dependencies** in `require-dev` — testing, linting, analysis tools
- **Typical dev stack**:
  - `phpunit/phpunit` — testing
  - `squizlabs/php_codesniffer` — code style
  - `phpstan/phpstan` — static analysis
  - `guzzlehttp/guzzle` — HTTP client (if needed for tests or build)

```json
{
    "require": {
        "php": "^8.5"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "squizlabs/php_codesniffer": "^3.7",
        "phpstan/phpstan": "^1.10"
    }
}
```

## Composer Scripts

### Script Conventions
Define all project commands as Composer scripts so they are discoverable and consistent:

```json
{
    "scripts": {
        "build": "php build.php",
        "serve": "php -S localhost:8000 -t src",
        "test": ["@build", "vendor/bin/phpunit --exclude-group=html-validation,network"],
        "test:html": "vendor/bin/phpunit --group=html-validation",
        "test:network": "vendor/bin/phpunit --group=network",
        "clean": "rm -rf dist",
        "lint": "phpcs",
        "lint:fix": "phpcbf",
        "analyse": "phpstan analyse",
        "check": ["@lint", "@analyse", "@test", "@validate-html"]
    }
}
```

### Script Patterns
- **Use `@` references** to call other scripts (e.g. `@build`, `@lint`)
- **Chain scripts as arrays** for multi-step commands (e.g. `"check"` runs lint → analyse → test → validate)
- **Use `--` to pass extra args** from the CLI (e.g. `composer analyse -- --error-format=github`)
- **Keep a `check` script** that runs the full quality gate locally

### Makefile Wrapper
Provide a `Makefile` as a convenience layer over Composer scripts with `help` as the default target:

```makefile
.DEFAULT_GOAL := help

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

lint: ## Check code style (PSR-12 via PHP_CodeSniffer)
	composer lint

test: ## Build site and run unit tests
	composer test

check: ## Run all quality checks
	composer check
```

## Lock File

- **Always commit `composer.lock`** for applications and sites
- This ensures reproducible builds across dev, CI, and production
- Run `composer install` (not `composer update`) in CI to respect the lock file

## CI Flags

When installing in CI environments, use:

```bash
composer install --prefer-dist --no-progress
```

- `--prefer-dist` — downloads zip archives instead of cloning (faster)
- `--no-progress` — suppresses download progress output (cleaner CI logs)

## Best Practices

### ✅ DO:
- **Pin major versions** with caret (`^`) in version constraints
- **Keep `composer.lock` committed** for reproducible installs
- **Define all commands as Composer scripts** for discoverability
- **Use `vendor/bin/` prefix** when calling tools in scripts
- **Run `composer install`** in CI, not `composer update`

### ❌ DON'T:
- **Commit the `vendor/` directory** — always install from lock file
- **Use `composer update` in CI** — this ignores the lock file
- **Install dev dependencies in production** — use `--no-dev` flag
- **Use global Composer installs** for project-specific tools


---

# Rule: 02-server-log-analysis-on-demand.md

---
id: server_log_analysis_on_demand
description: "When users share server output, automatically analyze and present errors in actionable format"
---

# Server Log Analysis on Demand

When users share local server output (via command history, pasted logs, or terminal output), automatically extract and analyze errors without being asked.

## Trigger Patterns

Automatically analyze when you see:
- Command output containing server logs
- Server startup messages followed by request logs
- HTTP status codes in terminal output
- Error patterns in development server output

## Analysis Workflow

### 1. Extract Error Information
Scan for: HTTP error codes, error messages, failed requests, missing resources, repeated patterns.

### 2. Group and Categorize
Organize by:
- **Type**: 404s, 500s, build errors, etc.
- **Frequency**: one-time vs. repeated
- **Severity**: critical vs. informational
- **Resource**: files, routes, APIs, etc.

### 3. Present Findings

```
🔍 Server Log Analysis

Issues Detected:
- 2 × Missing assets (404)
- 1 × Missing favicon

Missing Assets:
  → /assets/style.css
  → /assets/logos/logo.png

Root Cause Analysis:
[educated guess based on patterns]

Verification Steps:
1. [specific command to verify hypothesis]
2. [specific file/config to review]
```

## ✅ DO

- **Automatically analyze** shared server logs without being asked
- **Identify patterns** proactively
- **Offer specific next steps** based on findings
- **Ask permission** before running diagnostic commands

## ❌ DON'T

- Don't wait for user to ask for analysis
- Don't present raw logs without interpretation
- Don't offer generic advice without analyzing the specific errors
- Don't run commands without user permission

## Common Scenarios

### Build Output Mismatch
Server requests `/assets/style.css`, build output has `dist/css/style.css` → path mismatch between build output and HTML references.

### Assets Not Copied
Server requests `/assets/logos/logo.png`, assets folder missing from dist → configure build tool to copy static assets.

### Base Path Issues
Paths are correct and files exist but still 404 → rebuild and hard refresh, or check base URL config.

## Post-Analysis Flow

1. Confirm issues found in the logs
2. Propose investigation steps
3. Execute verification commands (with permission)
4. Suggest fixes based on findings

**Always provide analysis proactively when server logs are shared. Users want immediate understanding of issues, not just confirmation that errors exist.**


---

# Rule: 03-avoid-over-scripting.md

---
id: avoid_over_scripting
description: "Don't write scripts for simple tasks that can be done manually - especially for small datasets"
---

# Avoid Over-Scripting

You don't need to automate everything. Sometimes manual inspection and decision-making is faster and more appropriate than writing a script.

## Core Principle

**Script when it saves time, not when it feels sophisticated.** Manual work is often faster for small, one-time tasks.

## When NOT to Write Scripts

### Small File Counts
```bash
# ❌ Don't write a Python script for this
ls project_files/
# Only 5 files? Just look at them manually
cat file1.txt  # Quick manual review
cat file2.txt
```

### One-Time Operations
```bash
# ❌ Don't script this
# "Write a script to check these 3 config files for syntax errors"

# ✅ Just do this
yamllint config1.yaml
yamllint config2.yaml  
yamllint config3.yaml
```

### Simple Decision Trees
```bash
# ❌ Don't script complex logic for
ls uploads/ | wc -l  # 7 files

# ✅ Just manually check each file
file uploads/doc1.pdf  # "PDF document, version 1.4"
file uploads/img2.jpg  # "JPEG image data"
# Make decisions based on what you see
```

## When TO Write Scripts

### High Volume
```bash
# ✅ Script makes sense here
find /logs -name "*.log" | wc -l  # 15,000 files
# Yes, write a script to process these
```

### Repeated Operations
```bash
# ✅ You'll do this weekly
# Write a script for recurring tasks
```

### Complex Logic
```bash
# ✅ Multi-step validation across many files
# Write a script when manual steps would be error-prone
```

## Decision Framework

Ask yourself:

1. **How many items?** 
   - < 10 items → Probably manual
   - > 50 items → Probably script
   - 10-50 items → Depends on complexity

2. **How often will this run?**
   - Once → Probably manual
   - Weekly → Probably script
   - Occasionally → Depends on complexity

3. **How complex is the logic?**
   - Simple check → Manual
   - Multi-step validation → Script
   - Complex conditionals → Script

## Examples

### ✅ **Good Manual Approach:**
```bash
# Task: "Check if these 5 API endpoints are responding"
curl -I https://api1.example.com/health
curl -I https://api2.example.com/health  
curl -I https://api3.example.com/health
curl -I https://api4.example.com/health
curl -I https://api5.example.com/health
```

### ❌ **Over-Scripted:**
```python
#!/usr/bin/env python3
import requests
import concurrent.futures
from typing import List

def check_endpoints(urls: List[str]) -> dict:
    """Check health of multiple endpoints with threading and error handling..."""
    # 30 lines of code for something that takes 30 seconds manually
```

### ✅ **Good Scripted Approach:**
```bash
# Task: "Check if these 100 log files contain errors"
# This many files? Script makes sense.
find /logs -name "*.log" -exec grep -l "ERROR" {} \;
```

## Red Flags for Over-Scripting

Stop and consider manual approach when:
- Writing more than 10 lines for a one-time task
- Spending more time writing the script than doing the task manually
- Adding error handling for edge cases that won't occur in your specific use case
- Creating reusable functions for something you'll never reuse

## Time Investment Guide

**5-minute rule**: If the manual task takes less than 5 minutes and you won't repeat it soon, don't script it.

**Script time vs. execution time**: If writing the script takes longer than doing the task manually × number of times you'll do it, don't script it.

## Remember

- **Efficiency over elegance** - Choose the approach that gets you to the result fastest
- **Manual doesn't mean sloppy** - You can still be systematic and thorough
- **Scripts aren't always better** - They introduce complexity, debugging, and maintenance overhead

**Sometimes `ls` and your eyes are the best tools for the job.**

---

# Rule: 03-git-pager-handling.md

---
id: git_pager_handling
description: "Ensure git and GitHub CLI commands produce full output by disabling pagers"
---

# Git Command Pager Handling

When running git commands or GitHub CLI commands that might produce paginated output, always use pager-disabling options or pipe to cat to ensure full output is captured. Many git commands (like git log, git diff, gh repo view) use pagers by default, which can cause empty or incomplete output in AI interactions.

## Implementation

✅ **DO:**
- Use the `--no-pager` global flag for git commands: `git --no-pager log`
- Pipe output to cat for other commands: `gh repo view repo-name | cat`
- Set `uses_pager: true` in tool calls when commands might invoke a pager
- Be proactive about pager handling for any VCS or CLI tool that might paginate

❌ **DON'T:**
- Run git commands without pager considerations: `git log` (may truncate)
- Assume tools won't paginate output in AI environments
- Ignore pagination settings when using CLI tools through AI interfaces

## Common Commands Requiring Pager Handling

**Git Commands:**
```bash
git --no-pager log
git --no-pager diff
git --no-pager show
git --no-pager blame
```

**GitHub CLI:**
```bash
gh repo view repo-name | cat
gh pr list | cat
gh issue list | cat
```

**Other Tools:**
```bash
less file.txt | cat
man command | cat
```

## Rationale

Pagers are designed for interactive terminal use but can interfere with programmatic access to command output. In AI interactions, incomplete output leads to poor analysis and decision-making.

---

# Rule: 03-local-environment-macos.md

---
id: local_environment_macos
description: "Local development assumes macOS with Homebrew; use platform-appropriate approaches for cloud/CI environments"
---

# Local Environment: macOS

The local development machine runs macOS. Assume macOS conventions, paths, and tooling for any local scripting or development work.

## Local (macOS)

- Homebrew is the package manager — `brew install` is the default for local tooling
- Use macOS-native paths (`/usr/local/`, `/opt/homebrew/`, `~/Library/`, etc.)
- Assume `zsh` as the default shell
- Use `launchd` for local services, not `systemd`
- Prefer macOS-compatible flags for CLI tools (e.g. BSD `sed`, `date`, etc.) or use GNU versions installed via Homebrew when needed

## Remote / Cloud / CI

When working in non-local environments, use platform-appropriate tooling:

- **Docker**: Assume Debian/Alpine base images unless specified; use `apt-get` or `apk`
- **GitHub Actions**: Use Ubuntu runners by default; use `apt-get` for dependencies
- **DigitalOcean / Linux VMs**: Assume Ubuntu/Debian; use `apt-get` or `snap`
- **General**: Don't assume Homebrew or macOS tools are available outside the local machine

## ✅ DO

- Use `brew install` for local tool installation
- Detect the environment when writing cross-platform scripts
- Use `/bin/bash` or `/bin/sh` for portable scripts targeting CI/cloud
- Note BSD vs GNU differences when they matter (e.g. `sed -i ''` on macOS vs `sed -i` on Linux)

## ❌ DON'T

- Don't suggest `apt-get` for local macOS installs
- Don't suggest `brew` in Dockerfiles, CI pipelines, or remote servers
- Don't assume `systemd` locally or `launchd` remotely


---

# Rule: 03-php-github-actions.md

---
id: php_github_actions
description: "GitHub Actions patterns for PHP projects using shivammathur/setup-php"
---

# PHP in GitHub Actions

Standards for setting up PHP in GitHub Actions workflows using `shivammathur/setup-php`.

## Setup Action

Use **[shivammathur/setup-php](https://github.com/shivammathur/setup-php)** (`v2`) to install PHP in all workflows.

### Pin by Commit SHA
Always pin the action by full commit SHA, not by tag:

```yaml
# ✅ Pinned by SHA with version comment
uses: shivammathur/setup-php@d59004228537ca90c8dca680592a08a675bf52b6 # v2

# ❌ Pinned by tag (vulnerable to tag mutation)
uses: shivammathur/setup-php@v2
```

This applies to **all** third-party actions (`actions/checkout`, `actions/cache`, `actions/upload-pages-artifact`, etc.).

### Default Configuration
- **PHP version**: `'8.5'` (latest stable — keep this updated)
- **Tools**: `composer:v2` (always specify Composer v2 explicitly)
- **Extensions**: only add what the project needs (e.g. `intl` for date/locale handling)

## Composite Action Pattern

Extract shared PHP setup into a **reusable composite action** at `.github/actions/setup-php-project/action.yml`:

```yaml
name: Setup PHP Project
description: Install PHP, cache Composer, and install dependencies

inputs:
  php-version:
    description: PHP version to install
    required: false
    default: '8.5'
  php-extensions:
    description: Space-separated list of PHP extensions
    required: false
    default: ''
  php-tools:
    description: Comma-separated list of PHP tools
    required: false
    default: 'composer:v2'
  composer-install-args:
    description: Extra arguments for composer install
    required: false
    default: '--prefer-dist --no-progress'

runs:
  using: composite
  steps:
    - name: Setup PHP
      uses: shivammathur/setup-php@d59004228537ca90c8dca680592a08a675bf52b6 # v2
      with:
        php-version: ${{ inputs.php-version }}
        tools: ${{ inputs.php-tools }}
        extensions: ${{ inputs.php-extensions }}

    - name: Get Composer cache directory
      id: composer-cache
      shell: bash
      run: echo "dir=$(composer config cache-files-dir)" >> "$GITHUB_OUTPUT"

    - name: Cache Composer dependencies
      if: ${{ !env.ACT }}
      uses: actions/cache@0057852bfaa89a56745cba8c7296529d2fc39830 # v4
      with:
        path: ${{ steps.composer-cache.outputs.dir }}
        key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
        restore-keys: |
          ${{ runner.os }}-composer-

    - name: Install dependencies
      shell: bash
      run: composer install ${{ inputs.composer-install-args }}
```

### Using the Composite Action
Reference it from workflow jobs:

```yaml
steps:
  - uses: actions/checkout@34e114876b0b11c390a56381ad16ebd13914f8d5 # v4

  - name: Setup PHP project
    uses: ./.github/actions/setup-php-project

  # Override defaults when needed:
  - name: Setup PHP project (with extensions and extra tools)
    uses: ./.github/actions/setup-php-project
    with:
      php-extensions: intl
      php-tools: 'composer:v2, cs2pr'
```

## Composer Caching

- **Cache the Composer download directory**, not `vendor/` — this caches downloaded packages while still respecting lock file changes
- **Key on `composer.lock` hash** for exact cache hits
- **Include a fallback restore key** (`${{ runner.os }}-composer-`) for partial cache hits
- **Skip caching when running locally with `act`** via `if: ${{ !env.ACT }}`

## CI Tools

### cs2pr
Install `cs2pr` alongside Composer to convert PHPCS checkstyle output into GitHub PR annotations:

```yaml
php-tools: 'composer:v2, cs2pr'
```

Then pipe PHPCS output:

```yaml
- name: Run PHP_CodeSniffer
  run: vendor/bin/phpcs --report=checkstyle -q | cs2pr
```

### PHPStan GitHub Format
Use `--error-format=github` to get inline annotations:

```yaml
- name: Run PHPStan
  run: composer analyse -- --error-format=github
```

## Workflow Patterns

### Separate CI Jobs
Split quality checks into **parallel jobs** for faster feedback:

```yaml
jobs:
  lint:
    name: Lint (PHP_CodeSniffer)
    # ...
  analyse:
    name: Static Analysis (PHPStan)
    # ...
  test:
    name: Tests (PHPUnit)
    # ...
  build-check:
    name: Build Check
    needs: [lint, analyse, test]
    # ...
```

### Reusable CI via workflow_call
Allow the CI workflow to be called from other workflows (e.g. build-deploy):

```yaml
on:
  pull_request:
    branches: [main]
  push:
    branches: ['feature/**', 'fix/**']
  workflow_call:
```

Then invoke it:

```yaml
jobs:
  ci:
    uses: ./.github/workflows/ci.yml
    permissions:
      contents: read
```

### Timezone in CI
When tests depend on date/time, set `TZ` explicitly:

```yaml
env:
  TZ: America/Toronto
```

### Permissions
Always set minimal permissions:

```yaml
permissions:
  contents: read
```

## Best Practices

### ✅ DO:
- **Pin all third-party actions by SHA** with a version comment
- **Use a composite action** to avoid duplicating PHP setup across jobs
- **Cache Composer's download directory**, not `vendor/`
- **Use `cs2pr`** for PHPCS PR annotations
- **Use `--error-format=github`** for PHPStan PR annotations
- **Set `TZ` explicitly** when tests involve dates
- **Use `workflow_call`** to share CI across workflows

### ❌ DON'T:
- **Pin actions by tag** — tags can be force-pushed
- **Duplicate setup steps** across multiple jobs — extract a composite action
- **Cache `vendor/`** directly — this can cause stale dependency issues
- **Skip the `!env.ACT` guard** on cache steps if you use `act` for local testing


---

# Rule: 03-systematic-problem-solving.md

---
id: systematic_problem_solving
description: "Always use systematic, skeptical approach to problem-solving and debugging"
---

# Systematic Problem-Solving Approach

Never jump to conclusions. Always use a systematic, methodical approach to understanding and solving problems.

## Core principles

- Never jump to conclusions
- Always ask clarifying questions before diagnosing problems
- Break problems down into components before recommending solutions
- Validate assumptions before proposing any fix
- Do not start by problem-solving — start by debugging
- Never invent context or guess intent; ask first

## Debugging protocol

Always debug first — do not attempt a solution before the root cause is clear.

1. **Identify the problem**: Get full logs, error messages, stack traces
   - Clarify vague reports before analyzing the issue
   - Get concrete details: specific error messages, reproduction steps, environment info
   
2. **List and verify likely failure points**: Auth, DB, config, network, etc.
   - Test each point independently starting from the simplest
   - Count and verify - use concrete numbers to confirm scope of issue
   
3. **Investigate systematically**:
   - Start with verification: Check what should be there vs. what actually exists
   - Use command-line tools to validate assumptions (e.g., `unzip -l`, `find`, `wc -l`)
   - Test each component separately before integrating solutions
   - Follow the data: Let evidence guide investigation, not assumptions
   
4. **Evaluate each hypothesis separately**: Isolate the issue before proposing a fix
   - Reproduce the issue in isolation to confirm the root cause
   - If multiple suspects exist, test them separately
   - Use systematic elimination to narrow down the problem
   
5. **Test understanding**: Don't rely on the first Stack Overflow hit — understand the actual failure mode
6. **Eliminate systematically**: If multiple potential causes, eliminate them one by one

7. **Recommend solutions carefully**:
   - Propose solutions only after identifying the root cause
   - Address only the identified issue - avoid scope creep
   - Test the fix before considering the issue resolved
   - Verify the solution resolves the original problem completely

## Implementation Guidelines

### ✅ DO:
- Stay systematic - follow logical progression
- Use concrete evidence - numbers, logs, reproducible steps over assumptions
- Test incrementally - verify each step before moving to the next
- Document findings - track what you've tested and learned
- Build minimal reproducible examples

### ❌ DON'T:
- Jump between solutions without systematic investigation
- Make assumptions without verification
- Skip steps in the debugging process
- Run persistent commands (`npm run dev`, `npm start`) in terminal - use one-shot commands (`npm run build`)
- Fix multiple issues simultaneously

## Confidence and verification

- If unsure, state confidence level explicitly
- Suggest verification steps when uncertain
- Cite relevant standards or documentation when recommending tools or techniques
- Be transparent — explicitly note when something is inferred or uncertain


---

# Rule: 04-avoid-regex-parsing.md

---
id: avoid_regex_parsing
description: "Never use regular expressions for parsing structured text formats"
---

# Avoid Regex for Structured Data Parsing

Never use regular expressions for parsing or transforming structured text formats (like Markdown, HTML, JSON, YAML, or code).

Always use well-maintained, officially supported libraries that are purpose-built for the format you're working with.

## Examples

✅ **DO USE:**
- `remark` to modify Markdown documents
- `turndown` (mixmark-io) to convert HTML to Markdown
- YAML parser to handle frontmatter
- JSON.parse() for JSON data
- DOM parser for HTML manipulation

❌ **AVOID:**
- `text.replace(/#+ (.+)/g, ...)` to parse headings
- `htmlString.match(/<h[1-6]>(.*?)<\/h[1-6]>/g)` for DOM parsing
- Regex patterns for extracting YAML frontmatter
- Complex regex for code parsing

## Why This Matters

- **Reliability**: Regex is brittle and error-prone when applied to structured formats
- **Readability**: Parser libraries make your intent clearer to collaborators
- **Maintainability**: Purpose-built libraries handle edge cases and evolution of formats
- **Bug Prevention**: Reduces risk of subtle parsing errors and security vulnerabilities

## When Regex Is Appropriate

Regex should be used for:
- Simple string validation (email format, phone numbers)
- Find-and-replace operations on plain text
- Pattern matching in unstructured text
- Log parsing for specific known patterns

---

# Rule: 04-github-actions-workflow-standards.md

---
id: github_actions_workflow_standards
description: "Security, maintainability, and correctness standards for GitHub Actions workflows"
---

# GitHub Actions Workflow Standards

Write secure, maintainable, and correct GitHub Actions workflows by following these standards.

## Security

### Pin actions by SHA
- Always pin third-party actions to a full commit SHA, not a mutable tag
- Add a version comment for readability: `uses: actions/checkout@<sha> # v4`
- Floating tags (`@v4`, `@main`) can be silently replaced with malicious code

### Restrict permissions
- Always include an explicit `permissions` block at the workflow level
- Use the minimum permissions required (e.g., `contents: read` for CI)
- Never rely on the repository's default token permissions

### Pin Docker images
- Use specific version tags for Docker images, not `:latest`
- `:latest` is non-reproducible and can break without warning

## Maintainability

### Eliminate duplication with composite actions
- When the same setup steps repeat across multiple jobs, extract them into a local composite action (e.g., `.github/actions/setup-project/action.yml`)
- Composite actions accept inputs, making them flexible enough for slight variations (e.g., extra extensions, dev vs. prod installs)
- This reduces workflow files to business logic only

### Cache keys must reference committed files
- Cache keys like `hashFiles('**/lockfile')` only work if the lockfile is committed
- If a lockfile is gitignored, use the manifest file instead (e.g., `composer.json`, `package.json`)
- A cache key that always resolves to the same hash is effectively no cache at all

### Composer scripts must use vendor/bin/
- Always reference tools via `vendor/bin/<tool>` in `composer.json` scripts
- Bare command names (e.g., `phpunit`) depend on PATH, which varies between local, CI, and container environments
- This applies equally to `phpcs`, `phpstan`, `phpunit`, and any other Composer-installed binary

## act compatibility

- Use `if: ${{ !env.ACT }}` to skip steps that require GitHub infrastructure (artifact uploads, Pages deployment, PR comments, Docker-in-Docker)
- See the `local-ci-act` directive for full `act` setup and usage standards

## ✅ DO

- Pin all action versions by SHA with version comments
- Add explicit `permissions` block to every workflow
- Pin Docker image tags to specific versions
- Extract repeated steps into composite actions
- Verify cache keys reference committed files

## ❌ DON'T

- Don't use floating tags (`@v4`, `@latest`) for actions or Docker images
- Don't rely on default repository permissions
- Don't duplicate setup steps across multiple jobs
- Don't use bare command names in CI scripts when a full path is available
- Don't assume lockfiles exist in the repo without checking `.gitignore`


---

# Rule: 04-php-standards.md

---
id: php_standards
description: "Core PHP development standards and project conventions"
---

# PHP Standards Overview

Core PHP development standards for consistent, maintainable code across projects.

## PHP Version

- **Minimum version**: PHP 8.5 (`"php": "^8.5"` in `composer.json`)
- **CI default**: PHP 8.5 (latest stable)
- **Always target the latest stable PHP release** in CI workflows and bump the minimum version accordingly
- **Use modern PHP features**: typed properties, named arguments, union types, enums, fibers, `readonly`, `match` expressions
- **Prefer `DateTimeImmutable`** over `DateTime` for safer date handling

## Coding Standards

### Style
- **Follow PSR-12** as the base coding standard
- **Enforce short array syntax** (`[]` not `array()`)
- **Line length**: 120 characters soft limit, no hard limit
- **Use strict types** where practical

### Autoloading
- **Follow PSR-4** for class autoloading
- **Namespace convention**: top-level namespace maps to `src/`
- **Dev namespace** maps to `tests/` via `autoload-dev`

```json
{
    "autoload": {
        "psr-4": {
            "YourNamespace\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "YourNamespace\\Tests\\": "tests/"
        }
    }
}
```

### Class and File Organization
- One class per file
- File name matches class name
- Helpers and utilities go in a `Helpers/` subdirectory under `src/`
- Partials (template fragments with side effects) go in `partials/` and are excluded from strict analysis rules

### Error Handling
- **Use typed exceptions** (`InvalidArgumentException`, domain-specific exceptions)
- **Include contextual messages** in exception strings
- **Use try/catch with specific exception types**, not bare `catch (\Exception $e)`

```php
if ($date === false) {
    throw new InvalidArgumentException("Invalid date format: {$dateString}. Expected YYYY-MM-DD.");
}
```

## Project Structure

Standard PHP project layout:

```
project/
├── src/                  # Application source code
│   ├── Helpers/          # Utility classes
│   └── partials/         # Template partials (HTML output)
├── tests/                # PHPUnit test files
├── dist/                 # Build output (generated, gitignored)
├── vendor/               # Composer dependencies (gitignored)
├── build.php             # Build script (if applicable)
├── composer.json         # Dependency and script definitions
├── composer.lock         # Locked dependency versions (committed)
├── phpcs.xml             # PHP_CodeSniffer configuration
├── phpstan.neon          # PHPStan configuration
├── phpunit.xml           # PHPUnit configuration
└── Makefile              # Developer convenience commands
```

## Quick Reference

### Essential Commands
```bash
# Install dependencies
composer install

# Run all quality checks
composer check

# Individual checks
composer lint          # Code style (PHPCS)
composer analyse       # Static analysis (PHPStan)
composer test          # Unit tests (PHPUnit)

# Fix code style automatically
composer lint:fix
```

## Related Rules

- **Composer**: See `php-composer.md` for dependency management and script patterns
- **GitHub Actions**: See `php-github-actions.md` for CI/CD PHP setup
- **Testing**: See `php-testing.md` for PHPUnit standards
- **Code Quality**: See `php-code-quality.md` for linting and static analysis


---

# Rule: 04-prefer-ssh-remotes.md

---
id: prefer_ssh_remotes
description: "Always use SSH instead of HTTPS for Git remotes and CLI tool authentication"
---

# Prefer SSH Remotes

Always configure Git remotes and CLI tools to use SSH instead of HTTPS for authentication.

## Core Principle

**SSH keys over password prompts.** SSH provides better security and eliminates repeated authentication prompts.

## Git Remotes

### ✅ **Preferred SSH Format:**
```bash
# When adding remotes
git remote add origin git@github.com:username/repository.git

# When cloning
git clone git@github.com:username/repository.git

# GitHub CLI setup
gh auth login  # Choose SSH when prompted
```

### ❌ **Avoid HTTPS Format:**
```bash
# Avoid these formats
git remote add origin https://github.com/username/repository.git
git clone https://github.com/username/repository.git
```

## Converting Existing HTTPS Remotes

### Check Current Remote
```bash
git remote -v
# origin  https://github.com/username/repo.git (fetch)
# origin  https://github.com/username/repo.git (push)
```

### Convert to SSH
```bash
git remote set-url origin git@github.com:username/repo.git

# Verify the change
git remote -v
# origin  git@github.com:username/repo.git (fetch)
# origin  git@github.com:username/repo.git (push)
```

## SSH Key Setup

### Generate SSH Key (if needed)
```bash
# Generate new SSH key
ssh-keygen -t ed25519 -C "your.email@example.com"

# Start SSH agent
eval "$(ssh-agent -s)"

# Add key to agent
ssh-add ~/.ssh/id_ed25519
```

### Add to GitHub/GitLab/Bitbucket
```bash
# Copy public key to clipboard (macOS)
pbcopy < ~/.ssh/id_ed25519.pub

# Then add to your Git provider's SSH keys settings
```

### Test SSH Connection
```bash
# Test GitHub connection
ssh -T git@github.com

# Expected response:
# Hi username! You've successfully authenticated...
```

## CLI Tools Configuration

### GitHub CLI
```bash
# Login with SSH preference
gh auth login
# Choose: "GitHub.com"
# Choose: "SSH"
# Choose: "Upload your SSH public key"
```

### Other Git Providers
```bash
# GitLab
git clone git@gitlab.com:username/project.git

# Bitbucket  
git clone git@bitbucket.org:username/project.git

# Custom Git servers
git clone git@your-server.com:username/project.git
```

## Why SSH is Better

### ✅ **Security Benefits:**
- **No password transmission** - Keys are never sent over the network
- **Strong cryptographic authentication** - Much harder to compromise than passwords
- **Key-based access control** - Easy to revoke specific keys without affecting others

### ✅ **Convenience Benefits:**
- **No repeated login prompts** - Authentication happens automatically
- **Works with 2FA** - No need to generate tokens for command line access
- **Better for automation** - Scripts can run without interactive authentication

### ✅ **Performance Benefits:**
- **Faster connection setup** - No OAuth/token exchange overhead
- **Persistent connections** - SSH can reuse connections for multiple operations

## Common Issues and Solutions

### SSH Agent Not Running
```bash
# Start SSH agent
eval "$(ssh-agent -s)"

# Add key
ssh-add ~/.ssh/id_ed25519

# Make persistent (add to ~/.zshrc or ~/.bashrc)
echo 'eval "$(ssh-agent -s)"' >> ~/.zshrc
echo 'ssh-add ~/.ssh/id_ed25519' >> ~/.zshrc
```

### Permission Denied
```bash
# Check if key is added to agent
ssh-add -l

# If not listed, add it
ssh-add ~/.ssh/id_ed25519

# Verify key is uploaded to Git provider
```

### Wrong Key Used
```bash
# Specify key explicitly
ssh -i ~/.ssh/specific_key git@github.com

# Or configure in ~/.ssh/config
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519
```

## Team Guidelines

### For New Projects
- Always clone with SSH
- Add SSH setup to project README
- Include SSH key generation in onboarding docs

### For Existing Projects
- Convert HTTPS remotes to SSH during next setup
- Document the conversion process for team members
- Update deployment scripts to use SSH

**Remember: SSH setup takes 5 minutes once, but saves authentication headaches forever.**

---

# Rule: 04-tone-neutral.md

---
id: tone_neutral
description: "Maintain a neutral, matter-of-fact tone without excessive positivity or enthusiasm"
---

# Neutral Tone

Use a neutral, matter-of-fact tone. Be friendly but concise. Avoid excessive positivity, filler, or enthusiasm. Prioritize clarity and precision.

## Core principle

Respond with professional neutrality. Don't inject artificial enthusiasm or overly positive language.

## Avoid

❌ "Great question!"  
❌ "I'd be happy to help!"  
❌ "Excellent work on..."  
❌ "Absolutely!"  
❌ "Perfect!"  
❌ "Amazing!"  
❌ "Sounds good!"

## Instead

✅ State facts directly  
✅ Answer the question  
✅ Acknowledge without embellishment  
✅ Use "yes" or "correct" instead of "absolutely" or "perfect"

## Examples

**Instead of**: "Great question! I'd be happy to help you with that. This is actually a really interesting problem!"

**Use**: "Here's how to solve that:"

---

**Instead of**: "Absolutely! That's a perfect approach. Great thinking!"

**Use**: "That approach works."

---

**Instead of**: "Thanks so much for the clarification! That's super helpful!"

**Use**: "Understood. Here's the updated solution:"


---

# Rule: 05-code-formatting-guidelines.md

---
id: code_formatting_guidelines
description: "Specific formatting preferences for code blocks, headings, and document structure"
---

# Code Formatting Guidelines

Use consistent formatting for code blocks, headings, and document structure.

## Code block formatting

- Use 4-space indentation for code blocks instead of triple backticks
- Single backticks are fine for inline code
- Maintain consistent indentation throughout code examples

## Code organization

- Define constant logic at the top of functions/modules, avoiding split logic between different control structures
- Keep related logic together rather than scattered across multiple conditional blocks
- Initialize variables and constants before using them in control flow

## Document structure

- Don't use '...' or '---' to divide sections unnecessarily
- Rely on proper heading structure for organization
- Don't bold headings (e.g., avoid `## **Heading**`)
- Use clean heading hierarchy without additional formatting

## Examples

✅ **Good code formatting:**

    function example() {
        const data = fetchData();
        return processData(data);
    }

✅ **Good logic organization:**

    function processUserData(users, filters) {
        // Define constants at the top
        const MAX_RESULTS = 100;
        const DEFAULT_SORT = 'name';
        const VALID_STATUSES = ['active', 'pending', 'inactive'];
        
        // Apply logic consistently
        const filteredUsers = users
            .filter(user => VALID_STATUSES.includes(user.status))
            .sort((a, b) => a[DEFAULT_SORT].localeCompare(b[DEFAULT_SORT]))
            .slice(0, MAX_RESULTS);
        
        return filteredUsers;
    }

❌ **Avoid scattered logic:**

    function processUserData(users, filters) {
        let results = [];
        for (let user of users) {
            const MAX_RESULTS = 100; // Don't define constants in loops
            if (user.status === 'active' || user.status === 'pending') {
                results.push(user);
            } else if (user.status === 'inactive') { // Split logic
                results.push(user);
            }
            if (results.length >= MAX_RESULTS) break;
        }
        return results;
    }

❌ **Avoid triple backticks for multi-line code**

✅ **Good heading structure:**

    # Main Heading
    ## Sub Heading
    ### Details

❌ **Avoid bolded headings:**

    ## **Sub Heading**


---

# Rule: 05-list-formatting.md

---
id: list_formatting
description: "Use consistent, scannable list formatting with proper structure and minimal nesting"
---

# List Formatting Standards

Use consistent, scannable formatting for lists to improve readability and structure.

## Basic list guidelines

- Use **single-level lists only** — no sub-bullets or excessive nesting
- Avoid starting a section directly with a list — include a brief intro paragraph first
- Use lists only when helpful — prefer paragraphs over stacked lists when appropriate

## Titled list items

When list items have titles:

- The title should be on the same line as the bullet
- Follow the title with a colon
- Format as: `- **Title:** Description or details`

## Examples

✅ **Good formatting:**
- **Define data contract:** Use the Data Attributes API to enumerate available fields

❌ **Bad formatting:**
- **Define data contract**  
Use the Data Attributes API to enumerate available fields

## Task list formatting

For action items:
- Format as: `**Action or Focus:** Clarifying detail`
- Keep items as human-readable task lines
- Avoid nested action items

## Structural guidelines

- Use proper heading hierarchy (`#`, `##`, `###`) for organization
- Follow every heading with a full sentence or paragraph before any list
- Prefer `#` for main section headings to improve scannability in flat editors
- Use **sentence case** for all section headings and subheadings

---

# Rule: 05-php-testing.md

---
id: php_testing
description: "PHPUnit testing standards, configuration, and test organization"
---

# PHP Testing Standards

PHPUnit testing standards, configuration patterns, and test organization.

## Framework

Use **PHPUnit 10+** as the testing framework.

## PHPUnit Configuration

Configure via `phpunit.xml` in the project root:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         failOnRisky="true"
         failOnWarning="true">
    <testsuites>
        <testsuite name="Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory suffix=".php">src</directory>
        </include>
        <exclude>
            <directory>src/partials</directory>
        </exclude>
    </source>
</phpunit>
```

### Key Settings
- **`failOnRisky="true"`** — fail on tests that don't assert anything or have other risky behavior
- **`failOnWarning="true"`** — fail on PHPUnit warnings (deprecations, etc.)
- **`bootstrap="vendor/autoload.php"`** — use Composer autoloader
- **Exclude partials** from code coverage (they are template fragments, not testable units)

## Test Groups

Use PHPUnit `@group` annotations to categorize tests that should run separately:

```php
/**
 * @group html-validation
 */
class HtmlValidationTest extends TestCase { }

/**
 * @group network
 */
class UrlValidationTest extends TestCase { }
```

### Running Groups Selectively
```bash
# Run all tests except slow/external groups
vendor/bin/phpunit --exclude-group=html-validation,network

# Run only HTML validation tests
vendor/bin/phpunit --group=html-validation

# Run only network-dependent tests
vendor/bin/phpunit --group=network
```

Wire these into Composer scripts:

```json
{
    "test": ["@build", "vendor/bin/phpunit --exclude-group=html-validation,network"],
    "test:html": "vendor/bin/phpunit --group=html-validation",
    "test:network": "vendor/bin/phpunit --group=network"
}
```

## Test Organization

### Directory Structure
```
tests/
├── BuildTest.php              # Build process tests
├── DateHelperTest.php         # Unit tests for helpers
├── HtmlValidationTest.php     # HTML output validation (grouped)
└── UrlValidationTest.php      # External URL checks (grouped)
```

### Naming Conventions
- Test files: `{ClassName}Test.php`
- Test methods: `test{DescriptiveBehavior}` — e.g. `testParseDateWithInvalidFormatThrowsException`
- Use camelCase for method names (PSR convention)

## Writing Tests

### Test Structure
```php
namespace YourNamespace\Tests;

use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    private DateHelper $dateHelper;

    protected function setUp(): void
    {
        $this->dateHelper = new DateHelper();
    }

    public function testGetCurrentDateReturnsDateTimeImmutable(): void
    {
        $date = $this->dateHelper->getCurrentDate();

        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
    }

    public function testInvalidTimezoneThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid timezone: NotARealTimezone');

        new DateHelper('NotARealTimezone');
    }
}
```

### Patterns
- **Use `setUp()` / `tearDown()`** for common test scaffolding
- **Test both happy path and error cases**
- **Use `expectException()` and `expectExceptionMessage()`** for exception testing
- **Clean up side effects** in `tearDown()` (temp files, directories, etc.)
- **Use type declarations** on test properties and return types (`: void`)

### Testing Build Processes
When testing code that produces filesystem output:
- Use a **separate output directory** (e.g. `dist-test`) to avoid interfering with real builds
- **Clean up** in both `setUp()` and `tearDown()`
- **Include the source file** via `require_once` and instantiate with test parameters

```php
private function buildSite(): void
{
    require_once __DIR__ . '/../build.php';
    $builder = new \SiteBuilder('src', $this->testDistDir);
    $builder->build();
}
```

## Best Practices

### ✅ DO:
- **Use `failOnRisky` and `failOnWarning`** in phpunit.xml
- **Group slow/external tests** so the default `composer test` is fast
- **Use descriptive test method names** that explain the scenario
- **Clean up filesystem side effects** in tearDown
- **Type-hint everything** — properties, parameters, return types
- **Test exception messages**, not just exception types

### ❌ DON'T:
- **Hit the network in default test runs** — isolate network tests in a group
- **Leave test artifacts** (temp dirs, files) after test runs
- **Write tests without assertions** — PHPUnit will flag these as risky
- **Couple tests to each other** — each test should be independently runnable


---

# Rule: 05-validate-sources.md

---
id: validate_sources
description: "Always validate tool features, config formats, or APIs against authoritative and current sources before use"
---

# Validate Sources

When referencing features, configuration files, or APIs from tools (e.g. Cursor, Warp, Copilot, ChatGPT), always **validate against at least one authoritative source** to confirm they are current and supported.

## Preferred verification sources

In order of reliability:

1. **Official documentation or product website** (e.g. `docs.cursor.com`, `docs.warp.dev`, `docs.github.com/copilot`)
2. **Release notes or changelogs** on official channels (e.g. GitHub Releases, product blog posts)
3. **Official community forums or Slack/Discord announcements** maintained by the vendor
4. **Actively maintained GitHub repositories** from the tool maintainers

## When uncertain

If you cannot verify against authoritative sources:

- Explicitly mark the information as **uncertain or possibly deprecated**
- Suggest checking the above sources directly instead of asserting as fact
- Prefer pointing to the most likely replacement feature (if identifiable) with a note that it requires confirmation

## Rationale

Many AI toolchains evolve quickly and deprecate features (e.g. `.cursorrules`). This prevents reliance on outdated or unsupported practices.

---

# Rule: 06-database-evolution-principle.md

---
id: database_evolution_principle
description: "Prefer extending existing databases with new columns over creating separate databases for each feature"
---

# Database Evolution Principle

When working with CSV-type databases, prefer extending existing databases with new columns rather than creating separate databases for everything.

## Core Principle

**Evolve, don't multiply.** Enhance existing data structures non-destructively rather than fragmenting data across multiple databases.

## Preferred Approach: Column Extension

### ✅ **Extend Existing Database:**
```csv
# Original users.csv
user_id,name,email,created_at
1,John Doe,john@example.com,2024-01-01
2,Jane Smith,jane@example.com,2024-01-02

# After adding subscription feature
user_id,name,email,created_at,subscription_tier,subscription_start
1,John Doe,john@example.com,2024-01-01,premium,2024-01-15
2,Jane Smith,jane@example.com,2024-01-02,basic,2024-01-10
```

### ❌ **Avoid Database Fragmentation:**
```csv
# users.csv
user_id,name,email,created_at
1,John Doe,john@example.com,2024-01-01

# subscriptions.csv (separate database)
user_id,subscription_tier,subscription_start  
1,premium,2024-01-15

# user_preferences.csv (another separate database)
user_id,theme,notifications
1,dark,enabled

# user_analytics.csv (yet another database)
user_id,last_login,page_views
1,2024-01-20,45
```

## When Column Extension Makes Sense

### ✅ **Good Candidates for Column Addition:**
- **Related user attributes** (preferences, settings, status)
- **Simple feature flags** (is_verified, is_premium)
- **Timestamp tracking** (last_updated, last_login)
- **Calculated fields** (total_orders, lifetime_value)
- **Status fields** (subscription_status, account_status)

### ✅ **Benefits:**
- **Single source of truth** - All user data in one place
- **Simpler queries** - No complex joins across multiple files
- **Easier maintenance** - One schema to manage
- **Better performance** - No cross-database lookups for basic operations
- **Clearer relationships** - Data naturally belongs together

## When to Create Separate Databases

### ✅ **Valid Reasons for Separation:**

#### High Volume/Performance Issues
```csv
# If user events table becomes massive (millions of rows)
users.csv          # User profiles (hundreds/thousands of rows)
user_events.csv    # Event tracking (millions of rows)
```

#### Fundamentally Different Entities
```csv
users.csv          # User entities
products.csv       # Product catalog
orders.csv         # Transaction records
```

#### Security/Access Separation
```csv
users.csv          # Public user data
user_secrets.csv   # API keys, encrypted data (restricted access)
```

#### Complex Relationships (Many-to-Many)
```csv
users.csv          # One user
user_skills.csv    # Many skills per user
skills.csv         # Skill definitions
```

## Implementation Guidelines

### Non-Destructive Column Addition
```python
# ✅ Safe column addition
def add_subscription_columns(csv_file):
    # Add columns with sensible defaults
    df['subscription_tier'] = df.get('subscription_tier', 'free')
    df['subscription_start'] = df.get('subscription_start', None)
    return df
```

### Gradual Migration
```python
# ✅ Migrate data gradually
def migrate_subscription_data():
    users_df = pd.read_csv('users.csv')
    subscriptions_df = pd.read_csv('subscriptions.csv')  # Old separate file
    
    # Merge subscription data into users
    merged = users_df.merge(subscriptions_df, on='user_id', how='left')
    merged.to_csv('users.csv', index=False)
    
    # Archive old file instead of deleting
    subscriptions_df.to_csv('archived/subscriptions_backup.csv', index=False)
```

### Handle Missing Data Gracefully
```python
# ✅ Robust handling of new columns
def get_user_subscription(user_id):
    user = users_df[users_df['user_id'] == user_id].iloc[0]
    
    # Handle cases where new columns might not exist for all rows
    tier = user.get('subscription_tier', 'free')
    start_date = user.get('subscription_start', None)
    
    return tier, start_date
```

## Decision Framework

Ask yourself before creating a new database:

1. **Is this data fundamentally about the same entity?**
   - User preferences → Add to users.csv
   - Product reviews → Separate reviews.csv

2. **Will queries commonly need both datasets?**
   - User profile + subscription status → Same database
   - User profile + detailed analytics → Consider separation

3. **Is performance becoming an issue?**
   - < 100K rows → Probably keep together
   - > 1M rows → Consider separation

4. **Do these fields have different update patterns?**
   - Profile updated occasionally, login timestamps updated frequently → Consider separation

## Common Patterns

### ✅ **User Profile Evolution:**
```csv
# Start simple
user_id,name,email

# Add authentication
user_id,name,email,password_hash,created_at

# Add preferences  
user_id,name,email,password_hash,created_at,theme,language,notifications

# Add subscription
user_id,name,email,password_hash,created_at,theme,language,notifications,subscription_tier,billing_cycle
```

### ✅ **Product Catalog Evolution:**
```csv
# Start with basics
product_id,name,price,description

# Add inventory tracking
product_id,name,price,description,stock_count,last_restocked

# Add categorization
product_id,name,price,description,stock_count,last_restocked,category,tags,featured
```

## Remember

- **Start simple** - Begin with broader schemas and refactor when necessary
- **Monitor performance** - Separate when file size/query performance becomes problematic  
- **Preserve relationships** - Keep related data together when it makes logical sense
- **Non-destructive changes** - Always add columns, rarely remove them

**A well-evolved single database is usually better than a constellation of tiny, fragmented databases.**

---

# Rule: 06-no-code-in-briefs.md

---
id: no_code_in_briefs
description: "Prevents inclusion of code examples in planning or brief-type documents"
---

# No Code in Briefs

When generating **briefs, task drafts, project overviews, or planning docs**, do not include code examples or implementation details.

## What to avoid

- Code examples, snippets, or implementation details
- Specific syntax or technical implementations
- Opinionated solutions before exploration begins

## What to include instead

- Keep content conceptual, requirements-level, or descriptive only
- Express technical details in plain language, not code
- Focus on the problem and requirements rather than solutions

## Rationale

The purpose is to avoid opinionated solutions before proper exploration and analysis begins. Planning documents should focus on understanding the problem space and requirements, not prescribing specific technical implementations.

---

# Rule: 07-development-cleanup-guidelines.md

---
id: development_cleanup_guidelines
description: "Remove temporary files and development artifacts that shouldn't persist in codebases before committing"
---

# Development File Cleanup Guidelines

Remove temporary files and development artifacts that shouldn't persist in the codebase, especially before committing to version control.

## Files to Clean Up

### Temporary Files
- `*.tmp`, `*.temp`, `temp-*`
- `*.bak`, `*.backup`, `*.orig`
- `.cache`, `node_modules/.cache`
- OS-specific temporary files (`.DS_Store`, `Thumbs.db`)

### Development Artifacts
- `console.log()`, `console.debug()` statements
- `debugger;` statements  
- Large blocks of commented-out code
- Test files created during development
- Debugging print statements (`print()`, `echo`, etc.)

### Build and Cache Artifacts
- `dist/` directory before new builds (unless ignored)
- `build/` output directories
- `.cache/` directories
- Compiled files that should be regenerated

### Project-Specific Artifacts
- Unused images in asset directories (`src/assets/images/uploads/`)
- Deprecated config files (e.g., `.cursorrules`)
- Temporary data files
- Screenshot files from debugging
- Generated files that should not be committed

## When to Clean Up

### Before Committing
- **Always remove debug code** and temporary files
- Review diff for unintended inclusions
- Clean up console/debug statements
- Remove temporary test code

### After Development Sessions  
- Clean temporary artifacts created during development
- Remove downloaded test files or samples
- Clear debugging output files

### Before Deployment
- Ensure no development files are included in build
- Remove test-specific configurations
- Clean up any hardcoded debugging values

### During Code Review
- Check for overlooked debug statements
- Identify temporary files that slipped through
- Remove experimental code that didn't make it to final solution

### Proactive Cleanup Offers
**After completing tasks or reaching milestones**, proactively offer to clean up temporary files:
- Ask "Should I clean up the temporary files from this work?"
- Be specific about what would be removed
- **Never automatically delete files** - always ask permission
- Provide clear file/folder names and locations
- Explain why files are no longer needed

**Example cleanup offers:**
- "I can remove the `/temp/analysis-data/` folder and the 3 `test-output.json` files"
- "The download cache in `/temp/` is no longer needed. Clean it up?"
- "We have 15 test files from the processing. Keep them or remove?"
- "This analysis created several temp folders in Downloads. Should I clean them up?"

## Cleanup Commands

### Quick Cleanup
```bash
# Remove common temp files
find . -name "*.tmp" -delete
find . -name "*.temp" -delete
find . -name "temp-*" -delete

# Remove backup files
find . -name "*.bak" -delete
find . -name "*.orig" -delete

# Remove OS-specific files
find . -name ".DS_Store" -delete
find . -name "Thumbs.db" -delete
```

### Git-Based Cleanup
```bash
# See untracked files before cleaning
git status --ignored

# Clean untracked files (be careful!)
git clean -n  # dry run first
git clean -fd # remove untracked files and directories

# Remove files from git but keep locally
git rm --cached filename
```

### Code Pattern Cleanup
```bash
# Find console.log statements
grep -r "console\.log" src/ --include="*.js" --include="*.ts"

# Find debugger statements  
grep -r "debugger" src/ --include="*.js" --include="*.ts"

# Find print statements in Python
grep -r "print(" . --include="*.py"
```

### Project-Specific Cleanup
```bash
# Remove dist/build before rebuild
rm -rf dist/ build/

# Clean npm/node artifacts
npm run clean  # if available
rm -rf node_modules/.cache/
```

## Best Practices

### ✅ DO:
- **Review diffs carefully** before committing to catch overlooked artifacts
- **Use .gitignore** to prevent temporary files from being tracked
- **Set up pre-commit hooks** to automatically clean common artifacts  
- **Document cleanup commands** in project README if project-specific
- **Use build scripts** that clean before building
- **Remove debug code** as you finish each development session

### ❌ DON'T:
- Commit console.log or debug statements to production code
- Leave large commented-out code blocks - remove or document why they're kept
- Include temporary test files in commits
- Skip reviewing the diff - easy to miss temporary files
- Use `git clean -fd` without understanding what it will remove
- Leave hardcoded debugging values in configuration files

## Integration with Workflow

### Pre-commit Checklist
1. **Search for debug statements**: `grep -r "console\.log\|debugger\|print(" src/`
2. **Check for temp files**: `find . -name "*.tmp" -o -name "*.temp"`
3. **Review git status**: `git status` - look for unintended additions
4. **Clean if needed**: Remove identified artifacts
5. **Final review**: Check diff one more time before commit

### Automated Cleanup
Consider setting up:
- Pre-commit hooks that run cleanup commands
- Build scripts that clean before building
- Linting rules that catch debug statements
- `.gitignore` patterns for common temporary files

**Remember: A clean codebase is easier to maintain, debug, and deploy. Make cleanup part of your regular development workflow.**

---

# Rule: 07-pr-summary-standards.md

---
id: pr_summary_standards
description: "Structured pull request summaries with emoji titles and issue-first sourcing approach"
---

# Pull Request Summary Standards

Write short, goal-based PR summaries with emoji titles that help reviewers understand context and changes. Never repeat the diff content.

## Title Format

PR titles must start with an emoji prefix:

- 🌪 **New value** · new feature or major addition
- 💨 **Enhancement** · improvements to existing features
- 🧰 **DevX** · developer tooling/workflow changes
- 💡 **KTLO** · routine maintenance/operations
- 🚑 **Hotfix** · urgent critical fix
- 🐛 **Defect** · bug fix for live features

**Example title:**
`💨 Speed up CI by caching pnpm store`

## Content Guidelines

### No Screenshots, Links, or Feature Flags
- Keep summaries text-only for clarity
- Avoid external dependencies in PR descriptions
- Focus on the essential information

### Sourcing Rule for AI
- **If an issue exists**: Read the issue first, then scan code changes, then write the summary
- **If no issue**: Infer intent from the code changes

## Required Structure

### Top Line (Issue Reference)
- If closing an issue: `closes #123`
- If related only: `relates to #123`

### Three-Section Format

**Goal:** Why this PR exists (1–2 sentences)
**Scope:** High-level description of what changed (not the diff details)  
**How to test:** Steps reviewers can follow, with expected result

## Example Summary

```
closes #123

**Goal:** Reduce CI time to under 8 minutes by caching dependencies.
**Scope:** Add `actions/cache` keyed to lockfile; no app logic changes.
**How to test:** Push branch, confirm cache restore in install step, total job < 8 minutes.
```

## What NOT to Include

- **Don't repeat the diff** - reviewers can see file changes
- **Don't include rationale** - that goes in the issue or commit messages
- **Don't list every file changed** - focus on the high-level impact
- **Don't include implementation details** - stick to the "what" and "why"

## AI Assistant Guidelines

When helping write PR summaries:

1. **Always check for linked issues first** - read the full issue context
2. **Scan the code diff** - understand the technical changes
3. **Focus on business impact** - why does this matter to the team/product?
4. **Keep it scannable** - busy reviewers need quick context
5. **Make testing actionable** - provide specific steps, not vague suggestions

---

# Rule: 08-directory-organization-philosophy.md

---
id: directory_organization_philosophy
description: "Philosophy for organizing directories, configs, and avoiding tool pollution in home directory"
---

# Directory Organization Philosophy

Maintain clean separation between different types of files and avoid tools that pollute the home directory with unnecessary initialization files.

## ✅ DO

**Directory Structure Principles**
- Separate configuration files, data files, and applications
- Use dedicated directories for different purposes: `~/Projects/`, `~/Unito/`, etc.
- Keep the home directory clean of application-specific files when possible
- Use `~/.config/` for configuration files that support XDG Base Directory specification

**Project Organization**
- Use logical directory hierarchies: `~/Projects/personal/`, `~/Unito/work-projects/`
- Group related projects together by context (work, personal, experiments)
- Keep project templates and standards consistent across similar projects
- Document project structure and conventions in README files

**Configuration Management**
- Store configurations in version-controlled dotfiles
- Use tools that support clean directory structures (chezmoi, not random dotfile dumps)
- Separate machine-specific configs from universal configs
- Keep backup configurations for critical tools

## ❌ DON'T

**Avoid Directory Pollution**
- Don't use tools that create unnecessary files in home directory (like `warp init` in home)
- Don't let package managers scatter config files everywhere without organization
- Don't mix personal and work projects in the same directory structure
- Don't create deep nested hierarchies that make navigation difficult

**Anti-patterns to Avoid**
- Don't use tools that force their directory structure on you
- Don't accept default locations if they create clutter
- Don't ignore where tools put their files - be intentional about organization
- Don't let tools create initialization files in places you don't want them

## Integration Points

**With Dotfiles Management**
- Use chezmoi for version-controlled config files
- Keep dotfiles repository clean and well-organized
- Document which configs are managed where

**With Development Tools**
- Configure tools to use appropriate directories (`~/.config/` when supported)
- Use project-local configurations when possible
- Avoid global installations that affect all projects unnecessarily

**With Package Managers**
- Use virtual environments and project-local dependencies
- Keep global installations minimal and purposeful
- Document global dependencies and their purposes

This approach maintains a clean, navigable system while supporting efficient development workflows.

---

# Rule: 08-project-templates-standards.md

---
id: project_templates_standards
description: "Standards for creating project templates and maintaining consistent development practices across projects"
---

# Project Templates & Standards

Establish consistent project initialization and development standards to ensure quality and maintainability across all projects.

## ✅ DO

**Project Template Components**
- Create template repositories for common project types (Python, Node.js, Astro, WordPress)
- Include essential files in templates: README.md, .gitignore, .envrc template, LICENSE
- Add basic CI/CD configuration appropriate for the project type
- Include development environment setup instructions
- Provide code quality tools configuration (linting, formatting, testing)

**README.md Standards**
- Include clear project description and purpose
- Document installation and setup requirements
- Provide usage examples and common commands
- Include contribution guidelines and development setup
- Document environment variables and configuration requirements

**Code Quality Standards**
- Set up automatic code formatting (black, prettier, etc.)
- Configure linting rules appropriate for the language/framework
- Include basic test structure and examples
- Set up pre-commit hooks where beneficial
- Document coding conventions and style guidelines

**Project Structure Conventions**
- Use consistent directory structures within project types
- Separate source code, tests, documentation, and configuration
- Include scripts directory for common development tasks
- Use standard locations for configuration files
- Document any deviations from standard structure

## ❌ DON'T

**Template Maintenance Mistakes**
- Don't let templates become outdated with old dependencies or practices
- Don't include sensitive information or credentials in templates
- Don't create overly complex templates that obscure simple projects
- Don't skip documentation of template choices and conventions

**Project Setup Anti-patterns**
- Don't skip project setup automation when it would save repeated work
- Don't ignore established conventions without good reason
- Don't mix different architectural patterns within the same project type
- Don't leave placeholder content in production-ready projects

## Template Categories

**Backend Projects (Python/Node.js/etc.)**
- Virtual environment setup instructions
- Database configuration examples
- API documentation structure
- Environment variable templates
- Docker configuration for development and production

**Frontend Projects (React/Astro/etc.)**
- Build tool configuration
- Asset management setup
- Component organization structure
- Testing framework integration
- Deployment pipeline configuration

**WordPress Projects**
- Theme/plugin development structure
- Local development environment (Docker/Local)
- Build tools for assets (Webpack/Vite)
- WordPress coding standards configuration
- Deployment and staging procedures

**Full-Stack Projects**
- Monorepo or multi-repo organization
- Shared configuration management
- Development environment orchestration
- Cross-service communication patterns
- Integrated testing strategies

## Integration with AI Rules

**Rulebook-AI Integration**
- Include .rulebook-ai configuration in project templates
- Document which AI rule packs are recommended for each project type
- Provide project-specific AI rules when needed
- Keep AI rules consistent with project conventions

**Code Generation Standards**
- Establish patterns for AI-generated code review
- Document when AI assistance is appropriate vs. manual coding
- Include AI-generated code attribution in comments when helpful
- Maintain consistency between AI-generated and manually written code

## Quality Assurance

**Template Testing**
- Regularly test project templates on clean environments
- Verify all dependencies install correctly
- Test that basic project functions work out of the box
- Update templates when dependencies or practices change

**Documentation Maintenance**
- Keep setup instructions current with actual requirements
- Test documentation steps on different operating systems when relevant
- Update examples and screenshots when interfaces change
- Gather feedback from developers using the templates

**Version Management**
- Tag template versions to allow rollback if needed
- Document major changes and migration paths
- Keep changelog of template updates
- Coordinate template updates with team practices

## Automation Opportunities

**Project Initialization**
- Create scripts to generate projects from templates
- Automate initial git setup and remote configuration
- Set up development environment with single command when possible
- Generate project-specific documentation from templates

**Maintenance Scripts**
- Create scripts to update existing projects with template changes
- Automate dependency updates across similar projects
- Generate reports on project compliance with standards
- Batch update common configuration changes

This approach ensures consistent, high-quality project initialization while reducing repetitive setup work and maintaining standards across all development efforts.

---

# Rule: 09-documentation-first-research.md

---
id: documentation_first_research
description: Always prioritize comprehensive official documentation research before attempting custom implementations
---

# Documentation-First Research Protocol

## Core Principle

**ALWAYS research official documentation thoroughly before attempting any custom implementation, workaround, or third-party solution.**

## Research Protocol

### 1. Official Documentation Deep Dive
✅ **DO:**
- Search the complete official documentation, not just getting started guides
- Look specifically for:
  - Built-in features that solve your exact use case
  - Template systems, themes, or layout options
  - Configuration options and frontmatter
  - Advanced features and reference sections
- Check examples, tutorials, and advanced guides
- Look for "reference" sections which often contain comprehensive feature lists

❌ **DON'T:**
- Skip to Stack Overflow or tutorials without exhausting official docs
- Assume a feature doesn't exist because it's not in the getting started guide
- Start with custom implementations before confirming no built-in solution exists

### 2. Documentation Search Strategy
✅ **Search for these patterns:**
- Templates, themes, layouts
- Configuration, frontmatter, options
- Built-in components or features
- Override, customize, extend
- Advanced features, reference guides

### 3. Framework-Specific Research
✅ **For any framework/tool:**
- Check the complete API reference
- Look for plugin/extension ecosystems
- Search for "recipes" or "guides" sections
- Check GitHub discussions, issues, and examples
- Review changelog for recent feature additions

## Implementation Rule

**Only after exhaustive documentation research should you consider:**
1. Custom implementations
2. Third-party libraries
3. Workarounds or hacks
4. Overriding core functionality

## Real-World Example

❌ **What happened:** Weeks spent creating custom landing page overrides for Astro Starlight
✅ **What should have happened:** Found the built-in `splash` template in the frontmatter reference documentation in 10 minutes

## Verification Questions

Before any custom implementation, ask:
1. Have I read the complete official documentation?
2. Have I checked the API reference section?
3. Have I searched for built-in templates, themes, or layouts?
4. Have I looked at the configuration/frontmatter options?
5. Does this framework have a feature that already solves this exact problem?

## Time Investment Rule

Spend at least 30 minutes in official documentation before considering custom solutions. Complex frameworks may require 1-2 hours of documentation research.

---

# Rule: 09-repository-creation-standards.md

---
id: repository_creation_standards
description: "Standardize repository creation using GitHub CLI with secure defaults"
---

# Repository Creation Standards

When creating or managing repositories, use consistent tooling and secure defaults to ensure proper repository management practices.

## Implementation

### Repository Creation
✅ **DO:**
- Use GitHub CLI (`gh` command) for repository operations instead of web interfaces
- Default to private repositories unless explicitly told otherwise
- Use `gh repo create repo-name --private` as the standard creation command
- Only use `--public` when specifically requested by the user

❌ **DON'T:**
- Create repositories through web interfaces when CLI is available
- Default to public repositories without explicit user request
- Use inconsistent tooling across repository operations

### Command Standards

**Repository Creation:**
```bash
# Default (secure)
gh repo create project-name --private

# Only when explicitly requested
gh repo create project-name --public

# With additional options
gh repo create project-name --private --description "Project description" --clone
```

**Repository Management:**
```bash
# Consistent use of gh CLI for other operations
gh repo view
gh repo clone owner/repo-name
gh repo fork owner/repo-name
```

## Rationale

- **Security First**: Private by default reduces risk of accidental data exposure
- **Tool Consistency**: Using GitHub CLI provides consistent, scriptable repository management
- **Audit Trail**: CLI operations are easier to track and reproduce than web interface actions
- **Automation Ready**: CLI-based operations integrate better with scripts and workflows

---

# Rule: 10-dotfiles-management-chezmoi.md

---
id: dotfiles_management_chezmoi
description: "Effective dotfiles management using chezmoi with automated workflows and proper file organization"
---

# Dotfiles Management with chezmoi

Use chezmoi for version-controlled, automated dotfiles management with clear separation between managed configs and dynamic content.

## ✅ DO

**File Management Strategy**
- Use `chezmoi managed` to verify what's tracked before making changes
- Use `chezmoi re-add` (no arguments) to update all modified managed files at once
- Use `chezmoi re-add <file>` for specific files when you know exactly what changed
- Use `chezmoi status` to see what's been modified before re-adding
- Keep `.gitignore` comprehensive to prevent unwanted file tracking

**Content Organization**
- Separate configuration files (managed by chezmoi) from dynamic/generated content
- Use templates only when files need variable substitution across machines
- Store machine-specific configs in separate files (e.g., `.gitconfig-work`)
- Document the purpose of each managed file in README.md

**Repository Hygiene**
- Include comprehensive `.gitignore` for macOS (`.DS_Store`, `._*`, etc.)
- Use `encrypted_*DS_Store*` patterns to catch encrypted unwanted files
- Set up autocommit and autopush for seamless updates
- Use descriptive commit messages that explain what changed

**Backup and Recovery**
- Keep backup copies of critical configs before major changes
- Use `chezmoi archive` for full backups before system changes
- Document recovery procedures in README.md
- Test dotfiles installation on clean systems periodically

## ❌ DON'T

**Avoid These Mistakes**
- Don't track dynamic content like logs, caches, or generated files
- Don't use chezmoi for secrets management - use direnv instead
- Don't commit `.DS_Store` or other OS-generated files
- Don't edit files directly in `~/.local/share/chezmoi/` - use `chezmoi edit`

**Management Anti-patterns**
- Don't add entire directories without understanding what's in them
- Don't use encryption for non-sensitive files that need frequent access
- Don't ignore the `.gitignore` - add patterns proactively
- Don't skip `--dry-run` testing for complex changes

## Common Workflows

**Daily Updates**
```bash
# Check what's changed
chezmoi status

# Update all modified files
chezmoi re-add

# Or update specific files
chezmoi re-add ~/.zshrc ~/.gitconfig
```

**Adding New Files**
```bash
# Add a new config file
chezmoi add ~/.newconfig

# Add with encryption (only if needed)
chezmoi add ~/.sensitive-config --encrypt
```

**Preventing Unwanted Files**
```bash
# Check what would be added before doing it
find ~/config-dir -name ".*" | head -10

# Use .gitignore patterns like:
# .DS_Store
# *.log
# *.cache
# encrypted_*DS_Store*
```

**Cross-Machine Sync**
```bash
# On new machine
chezmoi init --apply username/dotfiles

# Regular updates
chezmoi update
```

## Integration with Other Tools

**With direnv (for secrets)**
- Don't manage `.envrc` files with chezmoi (they're project-specific)
- Don't manage `~/.config/secrets/` with chezmoi (contains sensitive data)
- Do document the relationship in README.md

**With package managers**
- Track package manager config files (`.npmrc`, etc.)
- Don't track package installation lists unless they're minimal
- Use separate setup scripts for package installation

**Repository Structure**
```
dotfiles-repo/
├── .gitignore                    # Comprehensive exclusions
├── README.md                     # Usage and recovery docs
├── dot_zshrc                     # Shell configuration
├── dot_gitconfig                 # Git configuration
├── private_dot_ssh/              # SSH configs and keys
└── .chezmoiignore               # chezmoi-specific exclusions
```

This approach maintains clean, version-controlled dotfiles while avoiding common pitfalls and keeping sensitive data properly separated.

---

# Rule: 10-system-maintenance-cleanup.md

---
id: system_maintenance_cleanup
description: "Systematic approach to identifying, evaluating, and cleaning up deprecated system configurations and folders"
---

# System Maintenance and Cleanup

Regularly audit and clean up deprecated configurations, unused folders, and outdated system setups to maintain a clean development environment.

## ✅ DO

**Regular Audit Process**
- Review hidden folders in home directory periodically (`ls -la ~ | grep "^\."`)
- Check for duplicate functionality (e.g., multiple secrets management approaches)
- Investigate unfamiliar folders before deletion - they may serve active purposes
- Compare file modification dates to identify potentially deprecated content
- Look for references in configs before removing directories

**Safe Cleanup Methodology**
- Always create backups before removing configurations: `cp -r ~/.old-config ~/.old-config.backup`
- Use `find` and `grep` to search for references before deletion
- Test functionality after removal to ensure nothing breaks
- Document cleanup decisions for future reference
- Remove empty directories after migrating contents: `rmdir ~/.empty-folder`

**Configuration Migration**
- Consolidate similar functionality into single, well-organized locations
- Update all references when moving configurations
- Use consistent patterns (e.g., all secrets in `~/.config/secrets/`)
- Maintain clear documentation of new organization
- Test all dependent systems after migration

**Version Control Integration**
- Update dotfiles repository to reflect cleanup changes
- Remove old entries from tracking (e.g., `chezmoi forget ~/.deprecated-config`)
- Update README files to document new organization
- Commit cleanup changes with clear messages

## ❌ DON'T

**Avoid These Cleanup Mistakes**
- Don't remove folders without understanding their purpose
- Don't assume folders are deprecated based on age alone - check if they're actively used
- Don't skip searching for references in scripts, configs, and documentation
- Don't delete unique configurations that don't have clear replacements

**Migration Anti-patterns**
- Don't leave broken references after moving configurations
- Don't create multiple competing systems for the same function
- Don't ignore error messages after cleanup - they indicate missed references
- Don't rush cleanup - systematic investigation prevents mistakes

## Investigation Workflow

**Identify Potential Cleanup Candidates**
```bash
# Find hidden folders/files modified more than 90 days ago
find ~ -maxdepth 1 -name ".*" -type d -mtime +90

# Look for duplicate functionality
ls -la ~/.config/ | grep -i secret
ls -la ~ | grep -i secret
```

**Evaluate Before Removal**
```bash
# Check for references in common config locations
grep -r "deprecated-folder" ~/.zshrc ~/.bashrc ~/.config/ ~/.*rc

# Look for recent file access
find ~/.deprecated-folder -type f -atime -30  # accessed in last 30 days
```

**Safe Removal Process**
```bash
# 1. Create backup
cp -r ~/.deprecated-config ~/.deprecated-config.$(date +%Y%m%d)

# 2. Remove from version control if tracked
chezmoi forget ~/.deprecated-config

# 3. Remove actual folder
rm -rf ~/.deprecated-config

# 4. Test dependent systems
```

## Common Cleanup Scenarios

**Secrets Management Consolidation**
- Migrate from multiple secrets locations to single `~/.config/secrets/` structure
- Update all environment variable references to new paths
- Remove old secrets directories only after confirming migration success
- Update documentation to reflect new organization

**Dotfiles Organization**
- Remove files from dotfiles tracking that are now managed elsewhere
- Consolidate similar configuration files
- Clean up `.gitignore` patterns after removing tracked files
- Update repository documentation

**Tool Migration**
- Remove old tool configurations after migrating to new tools
- Update shell aliases and PATH modifications
- Clean up old scripts and shortcuts
- Document tool migration decisions

This systematic approach prevents accidental deletion of important configurations while maintaining a clean, organized development environment.

---

# Rule: 11-env-var-management.md

---
id: env_var_management
description: "Standard patterns for loading environment variables with direnv and .env fallback"
---

# Environment Variable Management

Support two automatic methods for loading environment variables in every project: direnv (recommended) and `.env` file fallback. No manual `source` or `export` commands needed.

## File Responsibilities

```
.env.example          — Template. Committed to git. Lists all required vars.
.env                  — Local values. Gitignored. Never committed.
.envrc                — direnv config. Committed. Loads .env then secrets.
Makefile              — Loads .env as fallback if direnv isn't active.
```

## Precedence (highest wins)

1. Shell environment (already exported vars)
2. direnv / secret manager overrides
3. `.env` file values

## ✅ DO

**direnv Setup (Primary)**
- Use `dotenv_if_exists` in `.envrc` to load `.env` when present
- Override with secrets from a secrets manager (e.g. `export-secret`) when available
- Require only `direnv allow` for first-time setup

**`.env` Fallback (Secondary)**
- Provide `.env.example` with all required vars and comments
- Conditionally load `.env` in Makefile only when env vars aren't already set
- Ensure all workflows function with just a `.env` file for contributors without direnv

**Adding a New Variable**
- Add to `.env.example` with an empty value and a comment
- Add the `export` line to `.envrc`
- Add a `ifndef` warning block in Makefile if it depends on it
- Document it in the project README

**Naming Conventions**
- Terraform variables: `TF_VAR_<variable_name>` — prefix uppercase, variable name lowercase (e.g. `TF_VAR_cloudflare_api_token`)
- Provider/tool credentials: Use the name the tool expects natively (e.g. `CLOUDFLARE_API_TOKEN`, `AWS_ACCESS_KEY_ID`)
- Project-specific: `SCREAMING_SNAKE_CASE` (e.g. `R2_ENDPOINT`)

## ❌ DON'T

- Don't commit `.env` — it's gitignored
- Don't pass secrets as CLI arguments — use env vars that tools read natively (`TF_VAR_*`, `AWS_*`), not `-var` or `-backend-config` flags
- Don't let `.env.example` drift from `.envrc` — every variable should appear in both
- Don't require direnv — all workflows must work with just `.env`


---

# Rule: 12-implementation-standards.md

---
id: implementation_standards
description: "Use libraries and methods with active support, prioritizing maintainability and ecosystem standards"
---

# Implementation Standards

Solutions should prioritize maintainability, readability, and alignment with ecosystem standards.

## Documentation Research Priority

**BEFORE ANY IMPLEMENTATION: Research official documentation thoroughly.**

- Always check complete official documentation before attempting custom solutions
- Look specifically for built-in features, templates, configuration options
- Search API reference sections and advanced guides, not just getting started docs
- Verify that no built-in functionality already solves the problem
- Only consider custom implementations after exhaustive documentation research

## Library and tool selection

- Use libraries and methods with active support and documentation
- Don't copy solutions that rely on obscure hacks or unmaintained packages
- Prefer standard libraries over regex-based hacks
- Recommend only actively maintained and safe libraries (check commit activity and issues)
- Avoid abandoned or deprecated packages

## Language-specific guidelines

**JavaScript/TypeScript**: Prefer `URL`, `path`, or platform-native utilities for routing and string parsing over brittle regular expressions.

## Best practice enforcement

- Validate that recommended tools or methods follow current best practices
- Ensure compatibility with the project's existing environment and configurations
- If a standard or config file exists (e.g., `.phpcs.xml.dist`, `phpstan.neon`, `settings.json`), defer to those
- Avoid re-defining behavior that is already handled by CLI tools or linters
- Prefer configurations that are command-line-friendly and visible in version control

## Shell command reliability

**Keep shell commands simple and testable**: Avoid complex chained commands that are difficult to debug and prone to failure.

✅ **Reliable approaches:**
- Use separate commands instead of complex chains with multiple `&&` operators
- Test commands individually before chaining them
- Place complex variable substitutions in separate variables first
- Use basic ASCII characters for formatting rather than Unicode/emojis in critical operations

❌ **Avoid:**
- Chaining more than 2-3 commands with `&&` when complexity increases
- Mixing complex variable substitution with heavy formatting in single commands
- Relying on Unicode characters in automated scripts

## New project initialization

- Start from first principles — don't inherit assumptions from other stacks
- Research and validate all tool or architecture recommendations
- Build one component at a time; verify functionality before moving on
- Ensure each module is independently testable and documented
- Include a minimal, accurate README with setup, usage, and testing instructions


---

# Rule: 13-inline-comments-guidelines.md

---
id: inline_comments_guidelines
description: "Guidelines for writing clear, concise inline comments that enhance code readability by explaining reasoning and context"
---

# Inline Comments Guidelines

Write clear, concise inline comments that enhance code readability by explaining the "why" and "reasoning" behind decisions, not restating what the code obviously does.

## Core Principle

**Comments should enhance code readability** by explaining reasoning behind business logic, architectural decisions, and providing context for future maintainers.

## When to Comment

### ✅ DO Comment:
- **Explain reasoning** behind business logic or architectural decisions
- **Document complex algorithms** or non-obvious implementations
- **Clarify edge cases** or workarounds for external limitations
- **Provide context** for future maintainers about historical decisions
- **Document assumptions** that might not be obvious
- **Explain "magic numbers"** or configuration values
- **Describe performance optimizations** and their trade-offs

### ❌ DON'T Comment:
- **Obvious code** that clearly describes what it does
- **Simple variable assignments** or basic operations
- **Self-documenting code** where the intent is clear from naming
- **Every single line** - over-commenting reduces readability

## Formatting Requirements

### General Comments
- Use **sentence case** (capitalize first word)
- Include **proper punctuation** (periods, commas, etc.)
- Keep comments **brief and focused**
- Place comments **above** the relevant code line(s)
- Use consistent comment style for the language

### Language-Specific Formats
```php
// PHP: Use double slashes for single-line comments.
/* PHP: Use block comments for multi-line explanations. */
```

```javascript
// JavaScript: Use double slashes for single-line comments.
/* JavaScript: Use block comments for multi-line explanations. */
```

```python
# Python: Use hash for single-line comments.
"""Python: Use triple quotes for docstrings and multi-line comments."""
```

## Comment Examples

### ✅ Good Comments

```php
// Validate user permissions before processing sensitive data.
if (!current_user_can('manage_options')) {
    return new WP_Error('insufficient_permissions');
}

// Cache expensive API response for 5 minutes to reduce load.
$cached_data = get_transient('api_response_' . $user_id);

// Workaround for legacy browser compatibility with CSS Grid.
if (!$this->supports_modern_css()) {
    $this->use_fallback_layout();
}

// Rate limit API calls to avoid hitting external service limits.
if ($this->get_request_count() > self::MAX_REQUESTS_PER_MINUTE) {
    sleep(60);
}
```

```javascript
// Debounce search input to avoid excessive API calls during typing.
const debouncedSearch = debounce(handleSearch, 300);

// Handle legacy browser compatibility for CSS Grid.
if (!CSS.supports('display', 'grid')) {
  fallbackToFlexbox();
}

// Batch DOM updates to prevent layout thrashing.
requestAnimationFrame(() => {
  elements.forEach(el => el.classList.add('active'));
});
```

```python
# Cache API responses to avoid redundant network calls during development.
cached_response = cache.get(api_key, max_age_hours=6)

# Convert timestamps to UTC to ensure consistent timezone handling.
utc_timestamp = local_time.astimezone(timezone.utc)

# Use binary search for O(log n) lookup performance on large datasets.
index = bisect.bisect_left(sorted_data, target_value)
```

### ❌ Poor Comments

```php
// Set the variable to true
$is_enabled = true;

// Loop through the array
foreach ($items as $item) {
    // Do something with the item
    process_item($item);
}

// Get the user ID
$user_id = get_current_user_id();
```

```javascript
// Get the element
const element = document.getElementById('myElement');

// Add click event listener
element.addEventListener('click', handleClick);

// Return the result
return result;
```

```python
# Increment counter
counter += 1

# Print the value
print(value)

# Create empty list
items = []
```

## TODO Comments

### Format
All TODO comments must follow this exact format:

```
// TODO: [clear, actionable description]
```

### Examples

```php
// TODO: Implement proper error handling for failed API calls.
// TODO: Add unit tests for this authentication function.
// TODO: Remove debug logging before production deployment.
// TODO: Optimize database queries for better performance.
```

```javascript
// TODO: Add loading states for better user experience.
// TODO: Extract this logic into a reusable custom hook.
// TODO: Implement proper error boundaries for component failures.
// TODO: Add accessibility attributes for screen readers.
```

```python
# TODO: Implement retry logic for failed API requests.
# TODO: Add type hints for better code documentation.
# TODO: Optimize algorithm for better time complexity.
# TODO: Add validation for edge cases.
```

### TODO Guidelines
- Write **clear, actionable descriptions**
- Use **sentence case** with proper punctuation
- Focus on **what needs to be done**, not how
- Place TODO comments **immediately before** the relevant code
- **Remove TODOs** when the work is completed
- **Track TODOs** in issue trackers for larger tasks

## Complex Logic Documentation

### Algorithm Explanations
```python
# Implement Floyd's cycle detection algorithm to find duplicates.
# Uses two pointers moving at different speeds to detect cycles in O(n) time.
slow = nums[0]
fast = nums[0]

# Phase 1: Detect if cycle exists
do:
    slow = nums[slow]
    fast = nums[nums[fast]]
while slow != fast:
```

### Business Logic Context
```javascript
// Apply promotional discount only for first-time customers
// during the holiday season (Nov 1 - Jan 15) to increase conversion.
const isEligibleForDiscount = (
  customer.isFirstTime &&
  isHolidaySeason(currentDate) &&
  !customer.hasUsedPromotion
);
```

### Performance Optimizations
```php
// Pre-load user permissions to avoid N+1 query problem.
// Single query fetches all permissions instead of individual lookups.
$user_permissions = $this->get_bulk_user_permissions($user_ids);
```

## Best Practices

### ✅ DO:
- **Focus on the "why"** rather than the "what"
- **Explain business rules** and their context
- **Document assumptions** that might not be obvious
- **Use proper grammar** and punctuation
- **Keep comments current** - update them when code changes
- **Write for future maintainers** including yourself in 6 months

### ❌ DON'T:
- **State the obvious** - good code is self-documenting
- **Write novels** - keep comments concise and focused
- **Leave outdated comments** - they mislead more than they help
- **Comment bad code** - refactor it instead
- **Use comments to disable code** - use version control instead

## Comment Maintenance

### During Code Reviews
- **Check comment accuracy** - do they still match the code?
- **Identify missing context** - where would comments help future maintainers?
- **Remove obvious comments** - they add noise without value
- **Ensure TODO format** - consistent formatting helps tracking

### During Refactoring
- **Update relevant comments** when changing code behavior
- **Remove outdated comments** that no longer apply
- **Add comments for new complexity** introduced during refactoring

**Remember: Good comments explain WHY the code exists and WHY decisions were made, not WHAT the code is doing.**

---

# Rule: 14-local-ci-act.md

---
id: local_ci_act
description: "Standards for running GitHub Actions locally with act to catch CI failures before pushing"
---

# Local CI Testing with act

Use [`act`](https://github.com/nektos/act) to run GitHub Actions workflows locally in Docker containers. Catch CI failures before pushing and avoid burning Actions minutes.

## Prerequisites

- Docker Desktop (or compatible runtime like Colima) running
- `act` installed: `brew install act`

## Project Setup

### `.actrc` (commit this)

Create `.actrc` in the project root with default flags:

```
-P ubuntu-latest=ghcr.io/catthehacker/ubuntu:act-24.04
--container-architecture linux/amd64
```

- Use `catthehacker/ubuntu:act-*` images — purpose-built for act, much smaller than official runner images
- `--container-architecture linux/amd64` is required on Apple Silicon Macs
- Available tags: `act-22.04`, `act-24.04`. Use `full-*` variants only if `act-*` is missing tools your workflow needs

### Makefile Target (recommended)

Wrap `act` invocations so the team has a single command:

```makefile
test-ci:
	@command -v act >/dev/null 2>&1 || { echo "act not installed. Run: brew install act"; exit 1; }
	act pull_request -W .github/workflows/ci.yml \
		--secret TOKEN_A="$$TOKEN_A" \
		--secret TOKEN_B="$$TOKEN_B"
```

The `$$` escaping passes shell variables through Make's parser. Secrets come from direnv-loaded env vars — never from committed files.

## ✅ DO

**Secrets Handling**
- Pass secrets from env vars via `--secret` flags — never store in committed files
- Use `--secret` for `${{ secrets.X }}` references; use `--env` for `${{ env.X }}` references
- Add `.env`, `.secrets` to `.gitignore`
- For `GITHUB_TOKEN`, pass manually: `--secret GITHUB_TOKEN="$(gh auth token)"`

**Workflow Compatibility**
- Pin actions by SHA, not tag: `uses: actions/checkout@<sha> # v4.3.1`
- Use `if: ${{ !env.ACT }}` to skip steps that won't work locally (artifact uploads, PR comments, caching)
- Keep secrets generic — map repo-specific secret names to generic env vars in the workflow's `env:` block

**Running Workflows**
- Use `act <event> -n` for dry runs before real runs
- Use `act <event> -W .github/workflows/<file>.yml` to target specific workflows
- Use `act <event> -j <job>` to run a single job
- Use `act workflow_dispatch --input action=plan` to pass dispatch inputs

## ❌ DON'T

- Don't use `ubuntu-latest=ubuntu:24.04` images — missing runner tooling
- Don't use deprecated `nektos/act-environments-ubuntu:18.04` images
- Don't expect `actions/cache`, service containers, or `workflow_run` events to work
- Don't store secrets in `.actrc` or any committed file

## Troubleshooting

- **Image not found / slow first run**: First pull is ~1.2GB, one-time cost. Ensure Docker is running.
- **exec format error (Apple Silicon)**: Add `--container-architecture linux/amd64` to `.actrc`
- **Fails in act but works on GitHub**: Check for GitHub API dependencies, unsupported features, or missing tools in `act-*` images
- **Permission denied on scripts**: Add `chmod +x` before running or use `bash script.sh`
- **Secrets not available**: Confirm `--secret` (not `--env`) for `${{ secrets.X }}` references
- **Auth errors**: Run `direnv allow` to reload env vars

## Debugging

```bash
act pull_request -v          # verbose output
act pull_request -v -v       # very verbose (Docker commands)
act pull_request --reuse     # keep containers after failure for inspection
docker exec -it <id> bash    # shell into container
```


---

# Rule: 15-package-management-standards.md

---
id: package_management_standards
description: "Standards for managing packages across different language ecosystems and keeping environments clean"
---

# Package Management Standards

Maintain clean, reproducible environments across different programming languages and package managers while avoiding dependency conflicts.

## ✅ DO

**Language Managers**
- Use version managers for each language: `pyenv` (Python), `nvm` (Node.js), `rbenv` (Ruby)
- Install language versions as needed per project, not globally
- Document required language versions in project files (`.python-version`, `.nvmrc`)
- Keep system package managers (Homebrew) separate from language package managers

**Virtual Environments**
- Use virtual environments for all Python projects (`venv`, `virtualenv`, or `poetry`)
- Use project-local `node_modules` for Node.js projects
- Isolate dependencies per project to avoid conflicts
- Document environment setup steps in project README files

**Dependency Management**
- Pin dependency versions in lock files (`requirements.txt`, `package-lock.json`, `composer.lock`)
- Separate development dependencies from production dependencies
- Keep dependency lists minimal - only include what's actually needed
- Regularly audit and update dependencies for security

**Homebrew Essentials**
- Use Homebrew for system tools and utilities, not programming language runtimes
- Keep a `Brewfile` for essential system packages
- Document purpose of each Homebrew package
- Regular cleanup with `brew cleanup` and `brew autoremove`

## ❌ DON'T

**Avoid Global Pollution**
- Don't install language packages globally unless absolutely necessary
- Don't mix system packages with language-specific packages
- Don't let package managers install to system directories without explicit permission
- Don't ignore lock files or commit history of dependency changes

**Common Mistakes**
- Don't use `sudo` for language package installations
- Don't install different versions of the same package globally
- Don't skip virtual environments "for quick scripts"
- Don't commit `node_modules/` or other dependency directories to version control

**Version Management**
- Don't rely on system-installed language versions for development
- Don't skip documenting version requirements
- Don't assume other developers have the same versions installed
- Don't mix global and local package installations carelessly

## Package Manager Best Practices

**Python (pip/pipenv/poetry)**
```bash
# Use virtual environments
python -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt

# Or use poetry
poetry install
poetry shell
```

**Node.js (npm/yarn)**
```bash
# Use exact versions
nvm use  # reads from .nvmrc
npm install --exact
npm ci   # for production/CI builds
```

**Homebrew (system tools)**
```bash
# Essential tools only
brew install git gh direnv age
brew bundle  # from Brewfile
brew cleanup
```

## Environment Reproducibility

**Documentation Requirements**
- Document all required versions in project files
- Include setup instructions in README.md
- List any global dependencies that must be installed
- Provide troubleshooting steps for common environment issues

**CI/CD Integration**
- Use same package manager commands in CI as locally
- Pin versions in CI environments
- Cache dependencies appropriately
- Test with clean environments regularly

This approach ensures consistent, reproducible development environments while keeping system and project dependencies properly isolated.

---

# Rule: 16-secrets-management-direnv.md

---
id: secrets_management_direnv
description: "Modern secrets management using direnv with organized ~/.config/secrets/ structure"
---

# Secrets Management with direnv

Use direnv for project-specific, context-aware secrets management with organized file structure in `~/.config/secrets/`.

## File Organization

```
~/.config/secrets/
├── personal.env           # Personal API keys (Dropbox, Trakt.tv, etc.)
├── unito.env              # Work-related API keys (OpenAI, Airtable, etc.)
└── credentials/           # JSON/binary credential files (service accounts)
    └── *.json
```

## ✅ DO

**Environment File Format**
- Use `KEY=value` format without `export` statements
- No quotes needed unless value contains spaces or special chars
- Group related secrets with comments
- One environment variable per line

**Project Setup**
- Create `.envrc` files in project directories to load secrets using `~/.config/scripts/export-secret`
- Secrets are NOT sourced directly - use the `export-secret` helper to selectively export variables
- Use `~/.config/secrets/personal.env` for personal projects
- Use `~/.config/secrets/unito.env` for work projects

**Security Practices**
- Never commit `.envrc` files to version control (add to `.gitignore`)
- Use full paths for credential files: `GOOGLE_APPLICATION_CREDENTIALS="/Users/username/.config/secrets/credentials/service-account.json"`
- Keep backup copies of secrets files with `.backup` extension
- Store JSON credentials in `credentials/` subfolder

**direnv Workflow**
- Run `direnv allow` after creating/editing `.envrc` files
- Use `direnv reload` after modifying secrets files
- Check `direnv status` to verify what's loaded
- Use `env | grep -E "(API_KEY|TOKEN|CREDENTIALS)"` to verify secrets are loaded

## ❌ DON'T

**Anti-patterns**
- Don't use global shell exports in `.zshrc` or `.bashrc` for project secrets
- Don't mix personal and work secrets in the same file
- Don't use tilde (`~`) paths in environment variables - use full paths
- Don't commit secrets to version control in any form
- Don't use `source` or `source_env` to load entire secrets files - use `export-secret` for selective loading

**Avoid These Legacy Approaches**
- Don't use `.env` files in project roots (security risk)
- Don't use encrypted dotfile managers for frequently-changing secrets
- Don't source secrets globally in shell startup files
- Don't hardcode secrets in scripts or configuration files
- Don't source entire secrets files - only export what's needed for the project

## Integration Examples

**Using export-secret Helper**

The `~/.config/scripts/export-secret` utility selectively exports variables from secrets files:

```bash
# Usage: export-secret VARIABLE_NAME [secrets_file]
# If secrets_file is omitted, defaults to ~/.config/secrets/unito.env
```

**Personal Project `.envrc`**
```bash
# Load specific secrets from personal.env
eval "$(~/.config/scripts/export-secret TRAKT_CLIENT_ID ~/.config/secrets/personal.env)"
eval "$(~/.config/scripts/export-secret TRAKT_CLIENT_SECRET ~/.config/secrets/personal.env)"
eval "$(~/.config/scripts/export-secret TRAKT_ACCESS_TOKEN ~/.config/secrets/personal.env)"
eval "$(~/.config/scripts/export-secret TRAKT_REFRESH_TOKEN ~/.config/secrets/personal.env)"

# Project-specific settings
export PROJECT_NAME=my-project
```

**Work Project `.envrc`**
```bash
# Load work secrets (defaults to unito.env)
eval "$(~/.config/scripts/export-secret OPENAI_API_KEY)"
eval "$(~/.config/scripts/export-secret AIRTABLE_API_KEY)"

# Or specify file explicitly
eval "$(~/.config/scripts/export-secret DATABASE_URL ~/.config/secrets/unito.env)"
```

**Mixed/Sandbox Project `.envrc`**
```bash
# Load secrets from both personal and work files
eval "$(~/.config/scripts/export-secret DROPBOX_TOKEN ~/.config/secrets/personal.env)"
eval "$(~/.config/scripts/export-secret OPENAI_API_KEY ~/.config/secrets/unito.env)"

# Project settings
export ENVIRONMENT=development
```

## Troubleshooting

**Secrets not loading**
1. Check if you're in a directory with `.envrc` file
2. Run `direnv allow` to approve the `.envrc` file
3. Verify file paths are absolute (no tilde expansion issues)
4. Use `direnv status` to check loading status

**Path resolution issues**
- Use absolute paths for all file references
- Avoid shell expansion (use `/Users/username/` not `~/`)
- Check file permissions are readable

This approach provides project-isolation while maintaining organized, secure secrets management.

---

# Rule: 17-secrets-storage-standards.md

---
id: secrets_storage_standards
description: "Standard hierarchy and naming conventions for organizing secrets across projects and environments"
---

# Secrets Storage Standards

Define a consistent structure for storing and organizing credentials, API keys, tokens, and other secrets. These conventions are backend-agnostic — they apply whether using `pass`, AWS Secrets Manager, 1Password, Vault, or any other system.

## Path Hierarchy

Organize secrets using a shallow, predictable structure:

```
<context>/<service>/<credential-type>
```

- **Context**: Top-level grouping by business domain or environment
- **Service**: The external service or internal system
- **Credential type**: The specific secret

Keep depth to 2–3 levels. Deeper nesting creates friction without adding clarity.

## Standard Credential Type Names

Use these consistently across all services:

- `api-key` — general-purpose API key
- `access-token` — OAuth or bearer token
- `refresh-token` — OAuth refresh token
- `client-id` — OAuth client identifier
- `client-secret` — OAuth client secret
- `access-key-id` — AWS-style access key identifier
- `secret-access-key` — AWS-style secret key
- `personal-token` — personal access token (e.g. GitHub PAT)
- `password` — account password
- `webhook-secret` — webhook signing secret
- `signing-key` — cryptographic signing key
- `connection-string` — database or service connection string
- `service-account` — service account credential (often a JSON file path)

If a service has only one credential, the type alone is sufficient (e.g. `work/openai/api-key`).

## Context Groupings

- `work/` — credentials for work projects and internal services
- `personal/` — personal accounts and side projects
- `infrastructure/` — cloud providers, CI/CD, hosting, DNS
- `shared/` — team-shared credentials (use sparingly)

## ✅ DO

**Naming**
- Use **lowercase** throughout
- Use **hyphens** to separate words (not underscores or camelCase)
- Use **explicit credential types** from the standard list above
- Make names self-documenting — someone unfamiliar with the project should understand the purpose

**Organization**
- One secret per entry — don't combine multiple values in a single entry
- Use the standard credential type names before inventing new ones
- Preserve the `context/service/credential-type` structure regardless of backend

**Usage in Scripts**
- Export secrets via env vars: `export OPENAI_API_KEY=$(pass show work/openai/api-key)`
- Use `pass show <context>/<service>/` to list all secrets for a service

## ❌ DON'T

- Don't store secrets in version control — not in code, `.env` files, or config files
- Don't nest deeper than 3 levels
- Don't duplicate secrets across contexts — pick one location and reference it
- Don't call everything `key` — use the specific credential type name
- Don't skip documentation for non-obvious secrets

## Migration

When migrating between backends:
- Preserve the `context/service/credential-type` path structure
- Map paths directly to the new system
- Update retrieval commands in scripts and `.envrc` files
- Verify all references resolve after migration


---

# Rule: 18-simple-solutions-principle.md

---
id: simple_solutions_principle
description: "Prefer simple, direct solutions over complex workarounds"
---

# Simple Solutions Principle

Prefer simple, direct solutions over complex workarounds. When you find yourself adding layers of abstraction, parameters, or conditional logic to solve a problem, step back and ask: "Is there a simpler way?"

## Anti-Patterns to Avoid

### ❌ Redundant Parameters
Don't add parameters to track what the system already knows:
```yaml
# BAD: Adding unnecessary parameters
inputs:
  isAutomated: true  # System already knows this
  triggerSource: "workflow"  # Context provides this
```

### ❌ Complex Conditionals
Don't create complex logic when simpler approaches exist:
```yaml
# BAD: Complex conditionals
if: always() && github.event_name == 'workflow_dispatch' && inputs.isAutomated != true
```

### ❌ Workarounds Instead of Proper Solutions
Don't use CLI calls when proper APIs exist:
```yaml
# BAD: Using CLI workarounds
run: gh workflow run other-workflow.yml -f param1=value1
```

### ❌ Multiple Ways to Do the Same Thing
Don't support multiple approaches for identical functionality:
```yaml
# BAD: Duplicate trigger types
on:
  workflow_dispatch: # Complex inputs
  workflow_call:     # Nearly identical inputs
```

## Better Approaches

### ✅ Use Built-in Context
```yaml
# GOOD: Use what the system provides
if: always() && github.event_name == 'workflow_dispatch'
```

### ✅ Use Proper Architecture
```yaml
# GOOD: Use workflow_call for automated calls
jobs:
  backup:
    uses: ./.github/workflows/wpengine-api-backup.yml
    with:
      installName: ${{ inputs.installName }}
    secrets: inherit
```

### ✅ Single Responsibility
```yaml
# GOOD: One workflow, one purpose
on:
  workflow_dispatch:
    inputs:
      installName:
        type: string
        required: true
```

## Decision Framework

Before adding complexity, ask:

1. **Does the system already provide this information?**
   - Check system context variables
   - Review platform documentation
   - Look for built-in capabilities

2. **Is there a simpler architectural approach?**
   - Can we use proper APIs instead of CLI?
   - Can we restructure to avoid the complexity?
   - Is there a more direct solution?

3. **Will this make maintenance harder?**
   - More parameters = more places for bugs
   - Complex conditionals = harder to debug
   - Multiple code paths = more testing needed

4. **What's the real requirement?**
   - Step back from the immediate problem
   - What are we actually trying to achieve?
   - Is there a different way to meet the requirement?

## Remember

- **The system is smarter than you think** - leverage built-in capabilities
- **Simple is maintainable** - fewer moving parts = fewer bugs
- **Architecture matters** - use the right tool for the job
- **When in doubt, ask** - "Is there a simpler way to do this?"

The best code is the code you don't have to write.

---

# Rule: 19-systematic-code-crafting.md

---
id: systematic_code_crafting
description: "Build solutions incrementally with verification at every step, ensuring each component works before moving to the next"
---

# Systematic Code Crafting

Build solutions incrementally with verification at every step, ensuring each component works before moving to the next.

## Core Methodology

### Build Incrementally
- **One component at a time**: Implement and test each piece before moving to the next
- **Verify functionality**: Test each increment independently before integration
- **Start simple**: Begin with the most basic working version, then enhance
- **Fail fast**: Identify issues early through constant verification

### Verification at Every Step
- **Test after each change**: Run the code/script after every significant modification
- **Validate assumptions**: Use concrete tests to confirm each component works as expected
- **Check integration points**: Verify that new components work with existing ones
- **Measure progress**: Use concrete metrics to confirm functionality

## Development Workflow

### 1. Plan the Approach
- Break down the solution into discrete, testable components
- Identify the smallest working unit to build first
- Map out dependencies and integration points
- Define success criteria for each component

### 2. Implement the Foundation
- Build and test the basic structure
- Ensure the foundation works independently
- Document any assumptions or constraints discovered
- Verify core functionality before adding features

### 3. Add Features Incrementally
- **One feature at a time** with testing
- Verify each feature works before proceeding
- Test integration with existing components
- Maintain working state throughout development

### 4. Integrate Carefully
- Connect components one by one
- Test each integration point thoroughly
- Validate that new features work with existing code
- Resolve conflicts as they arise

### 5. Validate Completeness
- Ensure all requirements are met
- Test end-to-end functionality
- Verify performance and edge cases
- Document the complete solution

## Implementation Guidelines

### When Building New Features
- **Start with a minimal working prototype**
- Add complexity one layer at a time
- Test each layer before adding the next
- Use concrete examples to validate functionality

### When Refactoring Existing Code
- **Make one change at a time**
- Test after each modification
- Ensure the change doesn't break existing functionality
- Verify the refactored code works as expected

### When Integrating Systems
- **Test each system independently first**
- Create a simple integration test
- Add complexity gradually
- Validate the complete integration works

## Key Behaviors

### ✅ DO:
- Stay systematic - follow logical progression
- Build incrementally - one working piece at a time
- Verify constantly - test after every significant change
- Use concrete evidence - test results and metrics over assumptions
- Document progress - keep track of what's been implemented and tested
- Fail fast - identify issues early through constant verification

### ❌ DON'T:
- Jump between solutions without completing current work
- Add multiple features simultaneously without testing
- Skip verification steps to "save time"
- Build large components without incremental testing
- Assume integration will work without testing
- Continue building on broken foundations

## Success Indicators

- Each increment works independently
- Integration points are tested and verified
- The complete solution meets all requirements
- Performance and edge cases are validated
- Code is maintainable and well-documented
- Development process is reproducible and systematic

## Example Workflow

1. **Implement core data structure** → test independently
2. **Add basic functionality** → verify it works with core structure  
3. **Add error handling** → test error scenarios
4. **Integrate with external systems** → test integration points
5. **Add advanced features** → verify they work with existing code
6. **Optimize and finalize** → test complete solution

**Remember: Each step should result in a working system, even if incomplete. Never build on broken foundations.**

---

# Rule: 20-systematic-file-operations.md

---
id: systematic_file_operations
description: "Always follow systematic validation approach for bulk file operations, especially potentially destructive ones"
---

# Systematic File Operations with Validation

When performing bulk file operations (especially potentially destructive ones like deleting files), always follow a systematic validation approach to minimize data loss risk.

## Core principles

### Test small first
- **Always** start with a single file or small subset before bulk operations
- Validate the process works correctly on the test case
- Only proceed to bulk operations after successful validation

### Non-interactive operations
- Use appropriate flags to avoid interactive prompts in automated scripts
- Handle edge cases programmatically rather than requiring user input
- Examples: `-o` (overwrite), `-j` (junk paths), `-q` (quiet) for unzip operations

### Verification before deletion
- Never delete source files until extraction/operation is completely verified
- Check file counts, checksums, or other validation metrics
- Provide clear success/failure indicators

### Graceful error handling
- Check for common issues: disk space, permissions, file corruption
- Provide meaningful error messages
- Fail safely - prefer keeping original files over risky deletions

## Implementation pattern

    #!/bin/bash
    # Template for systematic file operations
    
    # 1. Pre-flight checks
    check_disk_space
    check_permissions
    validate_input_files
    
    # 2. Test with single file
    process_single_file_test()
    
    # 3. If test succeeds, process all files
    for file in files; do
        if process_file "$file"; then
            verify_operation "$file"
            if verification_passed; then
                cleanup_source_file "$file"
            else
                log_warning "Verification failed for $file"
            fi
        else
            log_error "Processing failed for $file"
        fi
    done
    
    # 4. Final verification and summary
    generate_operation_report

## Error recovery

- Keep detailed logs of all operations
- Provide rollback mechanisms where possible
- Never assume operations succeeded without verification

## Common use cases

- Bulk file extraction/compression
- File format conversions
- Data migrations
- Batch file processing

## Why this matters

This systematic approach ensures reliable, predictable file operations while minimizing the risk of data loss through premature deletion or incomplete processing.

---

# Rule: 21-tooling-parity.md

---
id: tooling_parity
description: "Ensure consistent behavior between command-line environment, CI/CD, and IDE"
---

# Tooling Parity Between IDE and CLI

Ensure consistent behavior between the **command-line environment**, **CI/CD**, and the **IDE** by aligning all tooling on a single source of configuration and minimal duplication.

## Core Requirements

### 🔁 Use Shared Config

- Use one **shared config file** for any tool:
  - e.g. `phpstan.neon`, `.eslintrc.json`, `.prettierrc`, `.stylelint.config.js`
- Both the CLI and IDE **must read from this file** — no overrides, no forks

### 🧩 Recommend Extensions That Respect Config

- Recommend IDE extensions (e.g., via `.vscode/extensions.json`) that:
  - Automatically use the project's config file
  - Require minimal or no additional setup
- Example: `swordev.phpstan` extension respects `phpstan.neon` directly

### ⚙️ Minimal IDE Settings

- If needed, IDE settings (e.g., in `.vscode/settings.json`) must only enable the extension:

  ```json
  {
    "phpstan.enabled": true
  }
  ```

- Avoid duplicating rules, paths, or settings in the IDE config

## What NOT to Do

- Do **not** define separate config for the same tool in `.vscode` or global settings
- Do **not** rely on manual extension-specific settings to replicate CLI behavior
- Do **not** use tools that behave inconsistently between CLI and editor

## Goal

All contributors should see the **same output and errors**, whether they:

- Run a command like `npm run lint` or `composer analyse`
- Save a file in the IDE
- Push code through CI

No drift. No duplication. One source of truth.

## Example: WordPress + PHPStan

- CLI: `phpstan analyse` reads from `phpstan.neon`
- IDE: `swordev.phpstan` reads the same file
- IDE config:

  ```json
  {
    "phpstan.enabled": true
  }
  ```

→ Both environments behave identically. No extra maintenance required.

## Troubleshooting

If results differ between CLI and IDE, check:
- Is the IDE using the same config file?
- Are there conflicting settings in `.vscode/settings.json`?
- Is the extension outdated or not respecting the shared config?

→ If the tool or extension can't respect the shared config, consider a different one.