<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Smarty\Smarty;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class SmartyEngine implements TemplateEngine
{
    private bool $debug = false;

    private ?Smarty $instance = null;

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
        $this->compilePath = $compilePath;
    }
    
    private function engine(): Smarty
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        $smarty = new Smarty();

        $smarty->debugging = $this->debug;
        
        // diretórios obrigatórios

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        if ($this->compilePath === []) {
            throw new PathException('The compile path has not been set.');
        }

        foreach ($this->viewPaths as $viewPath) {
            $smarty->addTemplateDir($viewPath);
        }
        
        $smarty->setCompileDir($this->compilePath);

        // diretórios opcionais

        if ($this->cachePath !== '') {
            $smarty->caching = true;
            $smarty->cache_lifetime = $this->cacheLifetime;

            $smarty->setCacheDir($this->cachePath);
        }

        if ($this->configPath !== '') {
            $smarty->setConfigDir($this->configPath);
        }


        $this->instance = $smarty;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        $variables = array_merge($this->defaultData, $data);

        return $this->engine()->fetch($template .'.tpl', $variables);
    }
}

