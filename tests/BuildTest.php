<?php

namespace WTS\Tests;

use PHPUnit\Framework\TestCase;

class BuildTest extends TestCase
{
    private string $testDistDir = 'dist-test';

    protected function setUp(): void
    {
        // Clean test dist directory before each test
        if (is_dir($this->testDistDir)) {
            $this->removeDirectory($this->testDistDir);
        }
    }

    protected function tearDown(): void
    {
        // Clean up after tests
        if (is_dir($this->testDistDir)) {
            $this->removeDirectory($this->testDistDir);
        }
    }

    public function testBuildCreatesDistDirectory(): void
    {
        $this->buildSite();

        $this->assertDirectoryExists($this->testDistDir);
    }

    public function testBuildGeneratesHtmlFiles(): void
    {
        $this->buildSite();

        $htmlFiles = glob("{$this->testDistDir}/*.html");

        $this->assertNotEmpty($htmlFiles, 'No HTML files generated');
        $this->assertGreaterThan(0, count($htmlFiles), 'Expected at least one HTML file');
    }

    public function testBuildCopiesAssets(): void
    {
        $this->buildSite();

        $assetsDir = "{$this->testDistDir}/assets";
        $this->assertDirectoryExists($assetsDir, 'Assets directory not copied');
    }

    public function testGeneratedFilesContainExpectedContent(): void
    {
        $this->buildSite();

        $enFile = "{$this->testDistDir}/wts-en.html";

        if (file_exists($enFile)) {
            $content = file_get_contents($enFile);

            // Verify that PHP has been processed and not left as raw code
            $this->assertStringNotContainsString('<?php', $content, 'PHP tags found in output');
            $this->assertStringContainsString('Standard', $content, 'Expected content not found');
        }
    }

    public function testAllSourceFilesAreProcessed(): void
    {
        $this->buildSite();

        $sourceFiles = glob('src/*.php');
        $outputFiles = glob("{$this->testDistDir}/*.html");

        // Exclude generated files that don't correspond to source pages
        $generatedPages = array_filter(
            $outputFiles,
            fn($f) => basename($f) !== '404.html'
        );

        $this->assertCount(
            count($sourceFiles),
            $generatedPages,
            'Number of output pages does not match source files'
        );
    }

    private function buildSite(): void
    {
        // Include and run the builder with test directory
        require_once __DIR__ . '/../build.php';

        $builder = new \SiteBuilder('src', $this->testDistDir);
        $builder->build();
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = "{$dir}/{$item}";

            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}
