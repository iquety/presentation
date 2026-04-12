<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Latte;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Latte\Engine;

class LatteEngine implements TemplateEngine
{
    private ?Engine $instance = null;

    /** @var array<string,mixed> $defaultData */
    private array $defaultData = [];

    private string $cachePath = '';

    /** @var array<string> $viewPaths */
    private array $viewPaths = [];

    /** @param array<string,mixed> $data */
    public function addDefaultData(array $data): void
    {
        $this->defaultData = array_merge($this->defaultData, $data);
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

    /**
     * @param array<string,mixed> $data
     * @throws PathException
     */
    public function render(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template) . '.latte';
        $variables = array_merge($this->defaultData, $data);

        // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
        return $this->engine()->renderToString($template, $variables);

        // todo: padronizar throw new PathException('View not found: ' . $template);
    }

    private function engine(): Engine
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        $latte = new Engine();

        $loader = new MultiFileLoader($this->viewPaths);
        $latte->setLoader($loader);

        if ($this->cachePath !== '') {
            $latte->setCacheDirectory($this->cachePath);
        }

        $this->instance = $latte;

        return $this->instance;
    }
}
