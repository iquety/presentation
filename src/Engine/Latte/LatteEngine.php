<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Latte;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Latte\Engine;
use Latte\Loaders\FileLoader;

class LatteEngine implements TemplateEngine
{
    private ?Engine $instance = null;

    private string $cachePath = '';
    
    private array $defaultData = [];

    private array $viewPaths = [];

    public function addDefaultData(array $data): void
    {
        $this->defaultData =+ $data;
    }

    public function addViewPath(string $viewPath): void
    {
        $this->viewPaths[] = $viewPath;
    }

    public function setCachePath(string $cachePath): void
    {
        $this->cachePath = $cachePath;
    }

    /** @return Engine */
    public function getEngine(): mixed
    {
        return $this->engine();
    }

    private function engine(): Engine
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        $latte = new Engine;

        $loader = new MultiFileLoader($this->viewPaths);
        $latte->setLoader($loader);

        if ($this->cachePath !== '') {
            $latte->setCacheDirectory($this->cachePath);
        }

        $this->instance = $latte;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template) . '.latte';
        $variables = array_merge($this->defaultData, $data);

        return $this->engine()->renderToString($template, $variables);
    }
}
