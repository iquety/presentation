<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine;

interface TemplateEngine
{
    public function addDefaultData(array $data): void;
    
    public function addViewPath(string $viewPath): void;

    public function enableDebugging(): void;

    public function setCachePath(string $cachePath, int $lifetime): void;

    public function setCompilePath(string $compilePath): void;

    public function setConfigPath(string $configPath): void;

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string;
}
