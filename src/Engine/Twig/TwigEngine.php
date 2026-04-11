<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Twig;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\ViewPathException;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigEngine implements TemplateEngine
{
    private ?Environment $twig = null;

    private array $viewPaths = [];

    public function addViewPath(string $viewPath): void
    {
        $this->viewPaths[] = $viewPath;
    }

    public function setCachePath(string $cachePath): void
    {
        throw new \LogicException('O cache do Twig não é suportado no momento.');
    }

    private function engine(): Environment
    {
        if ($this->twig !== null) {
            return $this->twig;
        }
        
        if ($this->viewPaths === []) {
            throw new ViewPathException('No view path was added.');
        }

        $loader = new FilesystemLoader($this->viewPaths);

        $twig = new Environment($loader, ['debug' => true]);

        $twig->addExtension(new DebugExtension());

        $this->twig = $twig;

        return $twig;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        return $this->engine()->render($template, $data);
    }
}

