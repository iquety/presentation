<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Twig;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigEngine implements TemplateEngine
{
    private ?Environment $instance = null;

    private string $cachePath = '';

    /** @var array<string, mixed> $defaultData */
    private array $defaultData = [];

    /** @var array<string> $viewPaths */
    private array $viewPaths = [];

    /** @param array<string, mixed> $data */
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


    /** @return Environment */
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
        $template = str_replace('.', '/', $template) . '.twig';
        $variables = array_merge($this->defaultData, $data);

        // internamente o cache é modificado para @chmod($key, 0666 & ~umask());
        return $this->engine()->render($template, $variables);

        // todo: padronizar throw new PathException('View not found: ' . $template);
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
}
