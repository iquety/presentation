<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine;

interface TemplateEngine
{
    public function enableDebug(): void;

    /**
     * @param array<string> $viewPathList
     * @return Environment
     * @throws PathException
     * @return TemplateEngine
     */
    public function bootEngine(array $viewPathList, string $cachePath): TemplateEngine;

    /**
     * @param array<string,mixed> $data
     * @param array<string,mixed> $defaultData
     * @throws EngineException
     * @throws ViewException
     */
    public function render(string $template, array $data = [], array $defaultData = []): string;
}
