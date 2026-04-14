<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Mustache;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Mustache\Engine;
use Mustache\Exception\UnknownTemplateException;
use Mustache\Loader\CascadingLoader;
use Mustache\Loader\FilesystemLoader;

class MustacheEngine implements TemplateEngine
{
    private ?Engine $engine = null;

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

        $loaderList = [];

        foreach ($viewPathList as $viewPath) {
            $loaderList[] = new FilesystemLoader($viewPath, ['extension' => '.ms']);
        }

        $settings = [
            'entity_flags' => ENT_QUOTES,
            'loader'       => new CascadingLoader($loaderList),
        ];

        if ($cachePath !== '') {
            $settings['cache'] = $cachePath;
            $settings['cache_file_mode'] = 0o644;
        }

        $mustache = new Engine($settings);

        $this->engine = $mustache;

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

        $template = str_replace('.', '/', $template) . '.ms';
        $variables = array_merge($defaultData, $data);

        try {
            // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
            return $this->engine->render($template, $variables);
        } catch (UnknownTemplateException $exception) {
            throw new ViewException(sprintf('Unable to find template "%s"', $template), 0, $exception);
        }
    }
}
