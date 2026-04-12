<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Plates;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use League\Plates\Engine;

class PlatesEngine implements TemplateEngine
{
    private ?Engine $instance = null;

    /** @var array<string,mixed> $defaultData */
    private array $defaultData = [];

    /** @var array<string> $viewPaths */
    private array $viewPaths = [];

    /** @var array<string,string> $namespaceList */
    private array $namespaceList = [];

    /** @param array<string,mixed> $data */
    public function addDefaultData(array $data): void
    {
        $this->defaultData = array_merge($this->defaultData, $data);
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

    /**
     * @param array<string,mixed> $data
     * @throws PathException
     */
    public function render(string $template, array $data = []): string
    {
        // fabrica o motor de template para a lista de namespaces existirem
        $this->engine();

        $template = str_replace('.', '/', $template);
        $variables = array_merge($this->defaultData, $data);

        // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
        foreach ($this->namespaceList as $namespace => $path) {
            if (file_exists($path . '/' . $template . '.tpl') === true) {
                return $this->engine()->render($namespace . '::' . $template, $variables);
            }
        }

        throw new PathException('View not found: ' . $template);
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
}
