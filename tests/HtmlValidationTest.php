<?php

/**
 * HTML Validation Tests
 *
 * Validates the structure and correctness of generated HTML in dist/.
 * Requires a prior build (composer build) to produce output.
 *
 * @package WTS
 * @subpackage Tests
 */

namespace WTS\Tests;

use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for validating generated HTML output.
 *
 * Run after building the site:
 *   composer build && composer test -- --group=html-validation
 *
 * @group html-validation
 */
class HtmlValidationTest extends TestCase
{
    private const DIST_DIR = 'dist';

    /**
     * Provide all .html files in dist/ as test cases.
     *
     * Returns a placeholder entry if dist/ doesn't exist so PHPUnit doesn't
     * error on an empty data provider (it evaluates providers before group filters).
     *
     * @return array<string, array{string}>
     */
    public static function htmlFileProvider(): array
    {
        $distDir = self::DIST_DIR;

        if (!is_dir($distDir)) {
            return ['no-build' => ['']];
        }

        $files = glob("{$distDir}/*.html") ?: [];

        if (empty($files)) {
            return ['no-files' => ['']];
        }

        $result = [];

        foreach ($files as $file) {
            $result[basename($file)] = [$file];
        }

        return $result;
    }

    /**
     * Verify dist/ exists before running file-level tests.
     */
    public function testDistDirectoryExists(): void
    {
        if (!is_dir(self::DIST_DIR)) {
            $this->markTestSkipped('dist/ does not exist. Run "composer build" first.');
        }

        $this->assertDirectoryExists(self::DIST_DIR);
    }

    /**
     * Verify at least one HTML file was generated.
     */
    public function testHtmlFilesWereGenerated(): void
    {
        if (!is_dir(self::DIST_DIR)) {
            $this->markTestSkipped('dist/ does not exist. Run "composer build" first.');
        }

        $files = glob(self::DIST_DIR . '/*.html') ?: [];
        $this->assertNotEmpty($files, 'No HTML files found in dist/. Did the build run?');
    }

    /**
     * Each file must parse without fatal errors.
     *
     * @dataProvider htmlFileProvider
     */
    public function testHtmlIsParseable(string $file): void
    {
        $content = $this->readFile($file);

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        // Libxml reports many false positives for HTML5 elements; only fail on fatals.
        $fatals = array_filter($errors, fn($e) => $e->level === LIBXML_ERR_FATAL);

        $this->assertEmpty(
            $fatals,
            sprintf(
                "%s has fatal parse errors:\n%s",
                basename($file),
                implode("\n", array_map(fn($e) => "  Line {$e->line}: " . trim($e->message), $fatals))
            )
        );
    }

    /**
     * Meta refresh URL must not be wrapped in single quotes.
     *
     * The URL='...' pattern causes browsers to treat the closing quote as part
     * of the URL, producing 404s like https://example.com/page.html'.
     *
     * @dataProvider htmlFileProvider
     */
    public function testMetaRefreshUrlIsNotQuoted(string $file): void
    {
        $content = $this->readFile($file);

        $this->assertDoesNotMatchRegularExpression(
            '/content="[^"]*;URL=\'/',
            $content,
            basename($file) . ': meta refresh URL must not be wrapped in single quotes'
        );
    }

    /**
     * Each file must declare a DOCTYPE.
     *
     * @dataProvider htmlFileProvider
     */
    public function testHtmlHasDoctype(string $file): void
    {
        $content = $this->readFile($file);

        $this->assertMatchesRegularExpression(
            '/^<!DOCTYPE\s+html>/i',
            ltrim($content),
            basename($file) . ' must begin with <!DOCTYPE html>'
        );
    }

    /**
     * Each file must declare a character encoding.
     *
     * @dataProvider htmlFileProvider
     */
    public function testHtmlHasCharset(string $file): void
    {
        $content = $this->readFile($file);

        $this->assertMatchesRegularExpression(
            '/<meta\s[^>]*charset\s*=/i',
            $content,
            basename($file) . ' must declare a charset'
        );
    }

    /**
     * Internal links must resolve to existing files in dist/.
     *
     * @dataProvider htmlFileProvider
     */
    public function testInternalLinksResolve(string $file): void
    {
        $content = $this->readFile($file);
        $distDir = self::DIST_DIR;

        preg_match_all('/href="([^"#?]+)"/', $content, $matches);

        foreach ($matches[1] as $href) {
            // Skip external URLs and asset paths
            if (preg_match('#^https?://#', $href) || str_starts_with($href, 'assets/')) {
                continue;
            }

            $target = $distDir . '/' . ltrim($href, '/');

            $this->assertFileExists(
                $target,
                basename($file) . ": internal link '{$href}' does not resolve to an existing file"
            );
        }
    }

    /**
     * Read file contents, skipping the test if dist/ hasn't been built.
     */
    private function readFile(string $file): string
    {
        if ($file === '' || !file_exists($file)) {
            $this->markTestSkipped('dist/ not built. Run "composer build" first.');
        }

        $content = file_get_contents($file);
        $this->assertNotFalse($content, "Could not read {$file}");
        return $content;
    }
}
