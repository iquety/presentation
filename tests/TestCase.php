<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class TestCase extends FrameworkTestCase
{
    public function setUp(): void
    {
        $this->cleanUp();
    }

    public function tearDown(): void
    {
        $this->cleanUp();
    }

    public function cleanUp(): void
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

    /**
     * @param array<string> &$listFiles
     * @return array<string>
     */
    protected function listFiles(string $path, array & $listFiles = []): array
    {
        $fileList = scandir($path, SCANDIR_SORT_NONE);

        if ($fileList === false) {
            return [];
        }

        foreach ($fileList as $file) {
            if ($file === '.' || $file === '..' || $file === '.gitkeep') {
                continue;
            }

            $filePath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath) === true) {
                $this->listFiles($filePath, $listFiles);

                continue;
            }

            $listFiles[] = $filePath;
        }

        return $listFiles;
    }

    private function removeFiles(string $path): void
    {
        $fileList = scandir($path, SCANDIR_SORT_NONE);

        if ($fileList === false) {
            return;
        }

        foreach ($fileList as $file) {
            if ($file === '.' || $file === '..' || $file === '.gitkeep') {
                continue;
            }

            $filePath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath) === true) {
                $this->removeFiles($filePath);

                rmdir($filePath);

                continue;
            }

            if (is_file($filePath) === true) {
                unlink($filePath);
            }

            if (is_file($filePath) === true) {
                throw new Exception('Arquivo ' . $filePath . ' não foi removido');
            }
        }
    }
}
