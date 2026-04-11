<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine;

interface TemplateEngine
{
    public function addViewPath(string $viewPath): void;

    public function setCachePath(string $cachePath): void;

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string;
}
