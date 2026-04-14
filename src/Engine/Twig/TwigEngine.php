<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Twig;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\Twig\Tags\CannotTokenParser;
use Iquety\Presentation\Engine\Twig\Tags\CanTokenParser;
use Iquety\Presentation\Engine\ViewException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigEngine implements TemplateEngine
{
    private ?Environment $engine = null;

    /**
     * @param array<string> $viewPathList
     * @throws PathException
     * @return TemplateEngine
     */
    public function bootEngine(array $viewPathList, string $cachePath): TemplateEngine
    {
        if ($viewPathList === []) {
            throw new PathException('No template paths were specified.');
        }

        $loader = new FilesystemLoader($viewPathList);

        $settings = [
            'debug' => true,
        ];

        if ($cachePath !== '') {
            $settings['cache'] = $cachePath;
        }

        $twig = new Environment($loader, $settings);

        $twig->addExtension(new DebugExtension());

        $twig->addTokenParser(new CanTokenParser());
        $twig->addTokenParser(new CannotTokenParser());

        $this->engine = $twig;

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

        $template = str_replace('.', '/', $template) . '.twig';
        $variables = array_merge($defaultData, $data);

        try {
            // internamente o cache é modificado para @chmod($key, 0666 & ~umask());
            return $this->engine->render($template, $variables);
        } catch (LoaderError $exception) {
            $message = sprintf('Unable to find template "%s".', $template);

            throw new ViewException($message, 0, $exception);
        }
    }
}
