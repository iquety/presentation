<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Mustache;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Mustache\Engine;
use Mustache\Loader\CascadingLoader;
use Mustache\Loader\FilesystemLoader;

class MustacheEngine implements TemplateEngine
{
    private bool $debug = false;

    private ?Engine $instance = null;

    private array $viewPaths = [];

    private string $cachePath = '';

    private int $cacheLifetime = 120;

    private string $compilePath = '';

    private string $configPath = '';

    private array $defaultData = [];

    public function addDefaultData(array $data): void
    {
        $this->defaultData =+ $data;
    }

    public function addViewPath(string $viewPath): void
    {
        $this->viewPaths[] = $viewPath;
    }

    public function enableDebugging(): void
    {
        $this->debug = true;
    }

    public function setCachePath(string $cachePath, int $lifetime): void
    {
        $this->cachePath = $cachePath;
    }

    public function setConfigPath(string $configPath): void
    {
        $this->configPath = $configPath;
    }

    public function setCompilePath(string $compilePath): void
    {
        throw new \LogicException('xxx');
    }
    
    private function engine(): Engine
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        // diretórios obrigatórios

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        if ($this->compilePath === []) {
            throw new PathException('The compile path has not been set.');
        }

        $loaderList = [];

        foreach ($this->viewPaths as $viewPath) {
            $loaderList[] = new FilesystemLoader($viewPath, ['extension' => '.ms']);
        }

        $mustache = new Engine([
            'loader' =>  new CascadingLoader($loaderList),
        ]);

        // diretórios opcionais

        // if ($this->cachePath !== '') {
        //     $smarty->caching = true;
        //     $smarty->cache_lifetime = $this->cacheLifetime;

        //     $smarty->setCacheDir($this->cachePath);
        // }

        // if ($this->configPath !== '') {
        //     $smarty->setConfigDir($this->configPath);
        // }


        $this->instance = $mustache;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        $variables = array_merge($this->defaultData, $data);

        return $this->engine()->render($template .'.ms', $variables);
    }
}

