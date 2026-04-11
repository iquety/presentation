<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Twig;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

// todo: implementar cache e diretório de configuração igual ao Smarty
// ex: https://github.com/smarty-php/smarty/blob/master/demo/templates/index.tpl
class TwigEngine implements TemplateEngine
{
    private bool $debug = false;

    private ?Environment $instance = null;

    private array $viewPaths = [];

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
        throw new \LogicException('O cache do Twig não é suportado no momento.');
    }

    public function setConfigPath(string $configPath): void
    {
        throw new \LogicException('O diretório de configuração do Twig não é suportado no momento.');
    }

    public function setCompilePath(string $compilePath): void
    {
        throw new \LogicException('O cache do Twig não é suportado no momento.');
    }

    private function engine(): Environment
    {
        if ($this->instance !== null) {
            return $this->instance;
        }
        
        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        $loader = new FilesystemLoader($this->viewPaths);

        $twig = new Environment($loader, ['debug' => $this->debug]);

        if ($this->debug === true) {
            $twig->addExtension(new DebugExtension());
        }

        $this->instance = $twig;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws PathException
     */
    public function render(string $template, array $data = []): string
    {
        $variables = array_merge($this->defaultData, $data);

        return $this->engine()->render($template . '.html', $variables);
    }
}

