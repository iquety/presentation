<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Plates;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use League\Plates\Engine;

class PlatesEngine implements TemplateEngine
{
    private ?Engine $engine = null;

    /** @var array<string,string> $namespaceList */
    private array $namespaceList = [];

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

        $plates = new Engine();
        $plates->setFileExtension('tpl');

        foreach ($viewPathList as $viewPath) {
            $namespace = strtolower(basename($viewPath));

            $this->namespaceList[$namespace] = $viewPath;

            $plates->addFolder($namespace, $viewPath, true);
        }

        $this->engine = $plates;

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

        $template = str_replace('.', '/', $template);
        $variables = array_merge($defaultData, $data);

        // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
        foreach ($this->namespaceList as $namespace => $path) {
            if (file_exists($path . '/' . $template . '.tpl') === true) {
                return $this->engine->render($namespace . '::' . $template, $variables);
            }
        }

        throw new ViewException(sprintf('Unable to find template "%s.tpl"', $template));
    }
}
