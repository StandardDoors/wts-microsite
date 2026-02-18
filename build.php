#!/usr/bin/env php
<?php

/**
 * Static Site Builder for WTS Landing Pages
 *
 * Processes PHP files in src/ directory and outputs static HTML to dist/
 */

class SiteBuilder
{
    private string $srcDir;
    private string $distDir;
    private array $builtFiles = [];

    public function __construct(string $srcDir = 'src', string $distDir = 'dist')
    {
        $this->srcDir = realpath($srcDir);
        $this->distDir = $distDir;

        if (!$this->srcDir) {
            throw new Exception("Source directory '{$srcDir}' does not exist");
        }
    }

    public function build(): void
    {
        echo "🔨 Building static site...\n\n";

        $this->cleanDistDirectory();
        $this->createDistDirectory();
        $this->processPhpFiles();
        $this->create404Page();
        $this->copyAssets();
        $this->copyFavicons();

        echo "\n✅ Build complete! Built " . count($this->builtFiles) . " pages.\n";
        echo "📁 Output directory: {$this->distDir}/\n";
    }

    private function cleanDistDirectory(): void
    {
        if (is_dir($this->distDir)) {
            echo "🧹 Cleaning dist directory...\n";
            $this->removeDirectory($this->distDir);
        }
    }

    private function createDistDirectory(): void
    {
        if (!mkdir($this->distDir, 0755, true)) {
            throw new Exception("Failed to create dist directory");
        }
    }

    private function processPhpFiles(): void
    {
        echo "📄 Processing PHP files...\n";

        $phpFiles = glob("{$this->srcDir}/*.php");

        if (empty($phpFiles)) {
            throw new Exception("No PHP files found in src directory");
        }

        foreach ($phpFiles as $file) {
            $this->processPhpFile($file);
        }
    }

    private function processPhpFile(string $file): void
    {
        $filename = basename($file, '.php');
        $outputFile = $filename . '.html';
        $outputPath = "{$this->distDir}/{$outputFile}";

        echo "  • {$filename}.php → {$outputFile}\n";

        // Change to src directory to allow relative includes
        $originalDir = getcwd();
        chdir($this->srcDir);

        // Set PHP_SELF to the output filename for proper meta refresh tags
        $_SERVER['PHP_SELF'] = $outputFile;

        // Capture PHP output
        ob_start();
        try {
            include basename($file);
            $content = ob_get_clean();
        } catch (Exception $e) {
            ob_end_clean();
            chdir($originalDir);
            throw new Exception("Error processing {$file}: " . $e->getMessage());
        }

        chdir($originalDir);

        // Write static HTML
        if (file_put_contents($outputPath, $content) === false) {
            throw new Exception("Failed to write {$outputPath}");
        }

        $this->builtFiles[] = $outputFile;
    }

    private function create404Page(): void
    {
        echo "🔄 Creating 404 redirect page...\n";

        $content = <<<'HTML'
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Page Not Found</title>
    <script>
        // Redirect .php URLs to .html equivalents
        (function() {
            const path = window.location.pathname;
            
            // Check if URL ends with .php
            if (path.endsWith('.php')) {
                // Replace .php with .html and redirect
                const newPath = path.replace(/\.php$/, '.html');
                window.location.replace(newPath);
            }
        })();
    </script>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <p>Redirecting...</p>
</body>
</html>
HTML;

        file_put_contents("{$this->distDir}/404.html", $content);
        echo "  • 404.html created for .php → .html redirects\n";
    }

    private function copyAssets(): void
    {
        $assetsDir = "{$this->srcDir}/assets";

        if (!is_dir($assetsDir)) {
            echo "⚠️  No assets directory found, skipping...\n";
            return;
        }

        echo "📦 Copying assets...\n";

        $this->copyDirectory($assetsDir, "{$this->distDir}/assets");
    }

    private function copyFavicons(): void
    {
        echo "🎨 Copying favicon files...\n";

        $faviconFiles = ['favicon.ico', 'favicon.gif'];

        foreach ($faviconFiles as $favicon) {
            $source = dirname($this->srcDir) . "/{$favicon}";
            if (file_exists($source)) {
                copy($source, "{$this->distDir}/{$favicon}");
                echo "  • {$favicon}\n";
            }
        }
    }

    private function copyDirectory(string $source, string $dest): void
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $items = scandir($source);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || $item === '.DS_Store') {
                continue;
            }

            $sourcePath = "{$source}/{$item}";
            $destPath = "{$dest}/{$item}";

            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
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

    public function getBuiltFiles(): array
    {
        return $this->builtFiles;
    }
}

// Run the builder
try {
    $builder = new SiteBuilder();
    $builder->build();
    exit(0);
} catch (Exception $e) {
    echo "❌ Build failed: " . $e->getMessage() . "\n";
    exit(1);
}
