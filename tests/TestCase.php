<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class TestCase extends FrameworkTestCase
{
    public function tearDown(): void
    {
        $stubsPath = realpath(__DIR__ . '/Stubs');

        $cachePathList = [
            $stubsPath . '/BladeCache',
            $stubsPath . '/LatteCache',
            $stubsPath . '/MustacheCache',
            $stubsPath . '/SmartyCache',
            $stubsPath . '/TwigCache',
        ];

        foreach ($cachePathList as $cachePath) {
            if (is_dir($cachePath) === false) {
                continue;
            }

            $this->removeFiles($cachePath);
        }
    }

    private function removeFiles(string $path): void
    {
        $fileList = scandir($path, SCANDIR_SORT_NONE);

        foreach ($fileList as $file) {
            if ($file === '.' || $file === '..' || $file === '.gitkeep') {
                continue;
            }

            $filePath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath) === true) {
                unlink($filePath);
            }
        }
    }
}
