<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Plates;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use League\Plates\Engine;

class PlatesEngine implements TemplateEngine
{
    private ?Engine $instance = null;

    private array $defaultData = [];

    private array $viewPaths = [];

    private array $namespaceList = [];

    public function addDefaultData(array $data): void
    {
        $this->defaultData =+ $data;
    }

    public function addViewPath(string $viewPath): void
    {
        $this->viewPaths[] = $viewPath;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function setCachePath(string $cachePath): void
    {
        // o Plates não tem suporte a cache, mas o método é necessário para a interface
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

        $plates = new Engine();
        $plates->setFileExtension('tpl');

        foreach ($this->viewPaths as $viewPath) {
            $namespace = strtolower(basename($viewPath));

            $this->namespaceList[$namespace] = $viewPath;

            $plates->addFolder($namespace, $viewPath, true);
        }

        $this->instance = $plates;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        // fabrica o motor de template para a lista de namespaces existirem
        $this->engine(); 

        $template = str_replace('.', '/', $template);
        $variables = array_merge($this->defaultData, $data);

        foreach ($this->namespaceList as $namespace => $path) {
            if (file_exists($path . '/' . $template . '.tpl') === true) {
                return $this->engine()->render($namespace . '::' . $template, $variables);    
            }
        }

        throw new PathException('View not found: ' . $template);
    }
}
