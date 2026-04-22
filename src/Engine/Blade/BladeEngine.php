<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Blade;

use eftec\bladeone\BladeOne;
use Exception;
use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;

class BladeEngine implements TemplateEngine
{
    private ?Blade $engine = null;

    private bool $debugMode = false;

    private bool $reseting = false;

    public function enableDebug(): void
    {
        $this->debugMode = true;
    }

    public function resetAfterBoot(): void
    {
        $this->engine->clearcompile();
        $this->engine->clearControlStack();
        $this->engine->clearMethods();
    }

    /**
     * @param array<string> $viewPathList
     * @return Environment
     * @throws PathException
     * @return TemplateEngine
     */
    public function bootEngine(array $viewPathList, string $cachePath): TemplateEngine
    {
        if ($viewPathList === []) {
            throw new PathException('No template paths were specified.');
        }

        $blade = new Blade();
        $blade->pipeEnable   = true;
        $blade->throwOnError = true;

        $blade->setPath($viewPathList, null);

        $mode = $this->debugMode === true ? Blade::MODE_DEBUG : Blade::MODE_AUTO;
            
        $blade->setMode($mode);

        // compile path
        if ($cachePath !== '') {
            $blade->setPath($viewPathList, $cachePath);
        }

        $this->engine = $blade;

        return $this;
    }

    /**
     * @param array<string,mixed> $data
     * @param array<string,mixed> $defaultData
     * @throws EngineException
     * @throws ViewException
     */
    public function render(string $template, array $data = [], array $defaultData = []): string
    {
        if ($this->engine === null) {
            throw new EngineException('The engine was not booted.');
        }

        $variables = array_merge($defaultData, $data);

        try {
            // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
            return $this->engine->run($template, $variables);
        } catch (Exception $exception) {
            throw new ViewException(sprintf('Unable to find template "%s.blade.php"', $template), 0, $exception);
        }
    }
}
