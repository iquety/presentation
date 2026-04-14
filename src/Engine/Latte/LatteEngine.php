<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Latte;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Latte\Engine;
use Latte\TemplateNotFoundException;

class LatteEngine implements TemplateEngine
{
    private ?Engine $engine = null;

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

        $latte = new Engine();

        $loader = new MultiFileLoader($viewPathList);
        $latte->setLoader($loader);

        if ($cachePath !== '') {
            $latte->setCacheDirectory($cachePath);
        }

        $this->engine = $latte;

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

        $template = str_replace('.', '/', $template) . '.latte';
        $variables = array_merge($defaultData, $data);

        try {
            // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
            return $this->engine->renderToString($template, $variables);
        } catch (TemplateNotFoundException $exception) {
            throw new ViewException(sprintf('Unable to find template "%s"', $template), 0, $exception);
        }
    }
}
