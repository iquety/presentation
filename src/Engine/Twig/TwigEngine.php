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
    private ?Environment $instance = null;

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


    /** @return Environment */
    public function getEngine(): mixed
    {
        return $this->engine();
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

        $settings = [
            'debug' => true,
        ];

        if ($this->cachePath !== '') {
            $settings['cache'] = $this->cachePath;
        }

        $twig = new Environment($loader, $settings);

        $twig->addExtension(new DebugExtension());

        $this->instance = $twig;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws PathException
     */
    public function render(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template) . '.twig';
        $variables = array_merge($this->defaultData, $data);

        return $this->engine()->render($template, $variables);
    }
}

