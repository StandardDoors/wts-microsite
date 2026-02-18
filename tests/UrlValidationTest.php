<?php

/**
 * URL Validation Tests
 *
 * Verifies that external URLs used in the site resolve correctly.
 *
 * @package WTS
 * @subpackage Tests
 */

namespace WTS\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Test cases for validating external URLs.
 *
 * These tests verify that redirect destinations and external links resolve.
 * Tests are marked with @group network to allow skipping in offline environments.
 *
 * @group network
 */
class UrlValidationTest extends TestCase
{
    private Client $client;

    /**
     * URLs that should be validated.
     * These are extracted from logo.php and other partials.
     */
    private const URLS_TO_VALIDATE = [
        'https://standarddoors.com/',
        'https://standarddoors.com/fr/',
        'https://usa.standarddoors.com/',
    ];

    protected function setUp(): void
    {
        $this->client = new Client([
            'timeout' => 10,
            'allow_redirects' => true,
            'verify' => true,
            'http_errors' => false, // Don't throw on 4xx/5xx, we'll check manually
        ]);
    }

    /**
     * Test that all configured URLs return valid HTTP responses.
     *
     * @dataProvider urlProvider
     */
    public function testUrlReturnsValidResponse(string $url): void
    {
        try {
            $response = $this->client->request('HEAD', $url);
            $statusCode = $response->getStatusCode();

            // Accept 2xx and 3xx status codes as valid
            $this->assertGreaterThanOrEqual(
                200,
                $statusCode,
                "URL {$url} returned status code {$statusCode}"
            );
            $this->assertLessThan(
                400,
                $statusCode,
                "URL {$url} returned error status code {$statusCode}"
            );
        } catch (ConnectException $e) {
            $this->markTestSkipped(
                "Could not connect to {$url}. Network may be unavailable: " . $e->getMessage()
            );
        } catch (RequestException $e) {
            $this->fail("Request to {$url} failed: " . $e->getMessage());
        }
    }

    /**
     * Provide URLs for validation testing.
     *
     * @return array<string, array{string}>
     */
    public static function urlProvider(): array
    {
        $urls = [];
        foreach (self::URLS_TO_VALIDATE as $url) {
            // Use URL as key for better test output
            $key = str_replace(['https://', 'http://', '/'], ['', '', '_'], $url);
            $urls[$key] = [$url];
        }
        return $urls;
    }

    /**
     * Test that the main standarddoors.com site is accessible.
     */
    public function testMainSiteIsAccessible(): void
    {
        try {
            $response = $this->client->request('GET', 'https://standarddoors.com/', [
                'headers' => [
                    'User-Agent' => 'WTS-Landing-Pages-Test/1.0',
                ],
            ]);

            $this->assertEquals(200, $response->getStatusCode());

            // Verify we got HTML content
            $contentType = $response->getHeaderLine('Content-Type');
            $this->assertStringContainsString('text/html', $contentType);
        } catch (ConnectException $e) {
            $this->markTestSkipped('Network unavailable: ' . $e->getMessage());
        }
    }

    /**
     * Test that URLs in generated HTML files are valid.
     *
     * This test builds the site first and then extracts URLs from the output.
     */
    public function testGeneratedHtmlContainsValidUrls(): void
    {
        $distDir = 'dist';

        // Skip if dist doesn't exist (run composer build first)
        if (!is_dir($distDir)) {
            $this->markTestSkipped('dist/ directory does not exist. Run "composer build" first.');
        }

        $htmlFiles = glob("{$distDir}/*.html");

        if (empty($htmlFiles)) {
            $this->markTestSkipped('No HTML files in dist/');
        }

        $urlsFound = [];

        foreach ($htmlFiles as $file) {
            $content = file_get_contents($file);

            // Extract href attributes
            preg_match_all('/href=["\']([^"\']+)["\']/', $content, $matches);

            foreach ($matches[1] as $url) {
                // Only check external URLs
                if (preg_match('#^https?://#', $url)) {
                    $urlsFound[$url] = true;
                }
            }
        }

        $this->assertNotEmpty($urlsFound, 'No external URLs found in generated HTML');

        // Validate each unique URL
        foreach (array_keys($urlsFound) as $url) {
            try {
                $response = $this->client->request('HEAD', $url);
                $statusCode = $response->getStatusCode();

                $this->assertLessThan(
                    400,
                    $statusCode,
                    "URL {$url} from generated HTML returned error status {$statusCode}"
                );
            } catch (ConnectException $e) {
                // Network issues shouldn't fail the build, just skip
                continue;
            }
        }
    }

    /**
     * Test URL format validation without network.
     *
     * This test runs even without network access.
     */
    public function testUrlsHaveValidFormat(): void
    {
        foreach (self::URLS_TO_VALIDATE as $url) {
            // Verify URL is well-formed
            $this->assertNotFalse(
                filter_var($url, FILTER_VALIDATE_URL),
                "URL {$url} is not a valid URL format"
            );

            // Verify HTTPS is used
            $this->assertStringStartsWith(
                'https://',
                $url,
                "URL {$url} should use HTTPS"
            );
        }
    }
}
