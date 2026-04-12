<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Blade;

use eftec\bladeone\BladeOne;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;

class BladeEngine implements TemplateEngine
{
    private ?BladeOne $instance = null;

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

    private function engine(): BladeOne
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        $blade = new BladeOne($this->viewPaths);
        // $blade->setMode(BladeOne::MODE_AUTO);
        $blade->pipeEnable   = true;
        $blade->throwOnError = true;

        if ($this->cachePath !== '') {
            $blade->setPath($this->viewPaths, $this->cachePath);
        }
        
        $this->instance = $blade;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        $variables = array_merge($this->defaultData, $data);
        
        return $this->engine()->run($template, $variables);
    }
}
