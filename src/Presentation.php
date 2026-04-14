<?php

declare(strict_types=1);

namespace Iquety\Presentation;

use Iquety\Presentation\Engine\TemplateEngine;

class Presentation
{
    private string $cachePath = '';

    /** @var array<string, mixed> $defaultData */
    private array $defaultData = [];

    /** @var array<string> $templatePathList */
    private array $templatePathList = [];

    public function __construct(private TemplateEngine $adapter) {}

    /** @param array<string, mixed> $data */
    public function addDefaultData(array $data): void
    {
        $this->defaultData = array_merge($this->defaultData, $data);
    }

    public function addViewPath(string $viewPath): void
    {
        $this->templatePathList[] = $viewPath;
    }

    public function setCachePath(string $cachePath): void
    {
        $this->cachePath = $cachePath;
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): string
    {
        return $this->adapter
            ->bootEngine($this->templatePathList, $this->cachePath)
            ->render($template, $data, $this->defaultData);
    }
}
