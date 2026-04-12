<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Blade;

use eftec\bladeone\BladeOne;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;

class BladeEngine implements TemplateEngine
{
    private ?BladeOne $instance = null;

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

    /** @return BladeOne */
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
        $variables = array_merge($this->defaultData, $data);

        // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
        return $this->engine()->run($template, $variables);

        // todo: padronizar throw new PathException('View not found: ' . $template);
    }

    private function engine(): BladeOne
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        $blade = new BladeOne();
        $blade->pipeEnable   = true;
        $blade->throwOnError = true;
        // $blade->setMode(BladeOne::MODE_AUTO);
        $blade->setPath($this->viewPaths, null);

        if ($this->cachePath !== '') {
            $blade->setPath($this->viewPaths, $this->cachePath);
        }

        $this->instance = $blade;

        return $this->instance;
    }
}
