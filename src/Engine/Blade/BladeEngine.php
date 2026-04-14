<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Blade;

use eftec\bladeone\BladeOne;
use Exception;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;

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

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }
        
        try {
            // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
            return $this->engine()->run($template, $variables);
        } catch (Exception $exception) {
            throw new ViewException(sprintf('Unable to find template "%s.blade.php"', $template), 0, $exception);
        }
    }

    private function engine(): BladeOne
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        $blade = new BladeOne();
        $blade->pipeEnable   = true;
        $blade->throwOnError = true;
        $blade->setPath($this->viewPaths, null);

        if ($this->cachePath !== '') {
            $blade->setPath($this->viewPaths, $this->cachePath);
        }

        $this->instance = $blade;

        return $this->instance;
    }
}
