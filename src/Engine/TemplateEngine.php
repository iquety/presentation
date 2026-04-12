<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine;

interface TemplateEngine
{
    /** @param array<string, mixed> $data */
    public function addDefaultData(array $data): void;

    public function addViewPath(string $viewPath): void;

    public function setCachePath(string $cachePath): void;

    public function getEngine(): mixed;

    /**
     * @param array<string,mixed> $data
     * @throws PathException
     */
    public function render(string $template, array $data = []): string;
}
